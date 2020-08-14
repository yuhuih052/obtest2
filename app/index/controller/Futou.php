<?php
namespace app\index\controller;

/**
 * 
 */
use think\Hook;

class Futou extends IndexBase
{
	
	public function list(){
		Hook::listen('CheckAuth',$params);
		$data = $this->logicFutou->list();
		$this->assign('list',$data);
		return $this->fetch('futou_list');
	}
	public function centername($id){
		Hook::listen('CheckAuth',$params);
		$this->jump($this->logicFutou->centername($id));
	}
	public function futou(){
		Hook::listen('CheckAuth',$params);
		$this->jump($this->logicFutou->futou());
	}

	public function getInto($id){
		Hook::listen('CheckAuth',$params);
		$this->jump($this->logicFutou->getinto($id));
	}
	public function returnInto(){
		Hook::listen('CheckAuth',$params);
		$this->jump($this->logicFutou->returnInto());
	}
	public function gather(){
		Hook::listen('CheckAuth',$params);
		$this->jump($this->logicFutou->gather());
	}
	public function gatherOne(){
		Hook::listen('CheckAuth',$params);
		$this->jump($this->logicFutou->gatherOne($this->param));
	}
}