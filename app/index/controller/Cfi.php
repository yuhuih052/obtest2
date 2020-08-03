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
		$data3 = $this->logicCfi->info(session('user_id2'));
		$this->assign('list',$data);
		$this->assign('list2',$data2);
		$this->assign('list3',$data3);
		return $this->fetch('cfi_shop');
	}
	//与系统购买cfi
	public function sys_buy(){
		$this->jump($this->logicCfi->sys_buy($this->param));
	}
    public function sys_sell(){
        $this->jump($this->logicCfi->sys_sell($this->param));
    }
    //撤销挂买
    public function withdralbuy(){
	    $this->jump($this->logicCfi->withdralbuy($this->param));
    }
    public function withdralsell(){
        $this->jump($this->logicCfi->withdralsell($this->param));
    }
    public function buyRecord(){
	    $data = $this->logicCfi->buyRecord();
	    $this->assign('list',$data);
	    return $this->fetch('deal_record');
    }

    public function sellRecord(){
        $data = $this->logicCfi->sellRecord();
        $this->assign('list',$data);
        return $this->fetch('deal_record');
    }
}