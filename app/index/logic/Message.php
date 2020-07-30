<?php
namespace app\index\logic;

/**
 * 
 */
class Message extends IndexBase
{
	
	//$member = session('member_info');
	public function send($data){
		$member = session('member_info2');
		$receive_user = $this->modelMember->where('username',$data['username'])->select();
		$me = [
			'user_id' => $member[0]['id'],
			'username' => $member[0]['username'],
			'receive_id' => $receive_user[0]['id'],
			'receive_name' => $receive_user[0]['username'],
			'message' => $data['message'],
			'message_time' => date('Y-m-d h:i:s', time()),
		];

		$result = $this->modelMessage->setInfo($me);
		$url = url('message/sendmessage');
		return $result ? [RESULT_SUCCESS, '留言成功',$url] : [RESULT_ERROR, $this->modelMessage->getError()];
	}

	public function sendMessage($id){
		return $this->modelMessage->where('user_id',$id)->select();
	}
	public function receiveMessage($id){
		return $this->modelMessage->where('receive_id',$id)->select();
	}

	public function response($data){
		$res = [
			'response' => $data['response'],
			'response_time' => date('Y-m-d h:i:s', time()),
		];
		$result = $this->modelMessage->where('id',$data['id'])->update($res);
		return $result ? [RESULT_SUCCESS, '回复成功'] : [RESULT_ERROR, $this->modelMessage->getError()];
	}
}