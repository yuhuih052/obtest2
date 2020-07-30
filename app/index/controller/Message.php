<?php
namespace app\index\controller;

/**
 * 
 */
class Message extends IndexBase
{
	
	public function witer(){
		return view();
	}

	public function send(){
		$data = $this->param;
		$validate = new \app\index\validate\Message;

        if (!$validate->check($data)) {
            return $validate->getError();
        }
        $this->jump($this->logicMessage->send($data));

	}

	public function sendMessage(){
		$userid = session('user_id2');
		$data = $this->logicMessage->sendMessage($userid);
		$this->assign('list',$data);
		return $this->fetch('sendmessage');
	}
	public function receiveMessage(){
		$userid = session('user_id2');
		$data = $this->logicMessage->receiveMessage($userid);
		$this->assign('list',$data);
		return $this->fetch('receivemessage');
	}
	public function response(){
		$this->jump($this->logicMessage->response($this->param));
	}
}