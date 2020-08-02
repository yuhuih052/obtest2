<?php
namespace app\index\controller;

/**
 * 
 */
class CFI extends IndexBase
{
	
	public function price_buy(){
		$data = $this->logicCfi->price();
		$data2 = $this->logicCfi->shop();
		$this->assign('list',$data);
		$this->assign('list2',$data2);
		return $this->fetch('cfi_shop');
	}
	//与系统购买cfi
	public function sys_buy(){
		$this->jump($this->logicCfi->sys_buy($this->param));
	}
}