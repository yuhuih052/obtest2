<?php
namespace app\index\controller;

use think\Hook;
/**
 * 
 */
class Message extends IndexBase
{
	
	public function witer(){
		Hook::listen('CheckAuth',$params);
	    if($this->param['w'] == 0){
	        return view('witer0');
        }else{
		    return view();
        }
	}

	public function send(){
		Hook::listen('CheckAuth',$params);
		$data = $this->param;
		$validate = new \app\index\validate\Message;

        if (!$validate->check($data)) {
            return $validate->getError();
        }
        $this->jump($this->logicMessage->send($data));

	}

	public function sendMessage(){
		Hook::listen('CheckAuth',$params);
		$userid = session('user_id2');
		$data = $this->logicMessage->sendMessage($userid);
		$this->assign('list',$data);
		return $this->fetch('sendmessage');
	}
	public function receiveMessage(){
		Hook::listen('CheckAuth',$params);
		$userid = session('user_id2');
		$data = $this->logicMessage->receiveMessage($userid);
		$this->assign('list',$data);
		return $this->fetch('receivemessage');
	}
	public function response(){
		Hook::listen('CheckAuth',$params);
		$this->jump($this->logicMessage->response($this->param));
	}
}