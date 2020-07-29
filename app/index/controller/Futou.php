<?php
namespace app\index\controller;

/**
 * 
 */
class Futou extends IndexBase
{
	
	public function list(){
		$data = $this->logicFutou->list();
		$this->assign('list',$data);
		return $this->fetch('futou_list');
	}
	public function centername($id){
		$this->jump($this->logicFutou->centername($id));
	}
	public function futou(){
		$this->jump($this->logicFutou->futou());
	}

	public function getInto($id){
		dd($id);
	}
}