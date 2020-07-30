<?php
namespace app\index\logic;

/**
 * 
 */
class CFI extends IndexBase
{
	
	public function price(){
		return $this->modelPrice->where('id',1)->select();
	}
}