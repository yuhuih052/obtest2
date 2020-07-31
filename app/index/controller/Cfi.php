<?php
namespace app\index\controller;

/**
 * 
 */
class CFI extends IndexBase
{
	
	public function price_buy(){
		$data = $this->logicCfi->price();
		$this->assign('list',$data);
		return $this->fetch('cfi_shop');
	}
	//与系统购买cfi
	public function sys_buy(){
		$this->jump($this->logicCfi->sys_buy($this->param));
	}
}