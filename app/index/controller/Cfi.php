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
}