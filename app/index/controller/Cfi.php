<?php
namespace app\index\controller;

/**
 * 
 */
use think\Hook;
class CFI extends IndexBase
{
	
	public function price_buy(){
        Hook::listen('CheckAuth',$params);
		$data = $this->logicCfi->price();
		$data2 = $this->logicCfi->shop();
		$data3 = $this->logicCfi->info(session('user_id2'));
        $data4 = $this->logicCfi->shop_buy();
        $data5 = $this->logicCfi->shop_sell();
		$this->assign('list',$data);
		$this->assign('list2',$data2);
		$this->assign('list3',$data3);
        $this->assign('list4',$data4);
        $this->assign('list5',$data5);
		return $this->fetch('cfi_shop');
	}
	//与系统购买cfi
	public function sys_buy(){
        Hook::listen('CheckAuth',$params);
		$this->jump($this->logicCfi->sys_buy($this->param));
	}
    public function sys_sell(){
        Hook::listen('CheckAuth',$params);
        $this->jump($this->logicCfi->sys_sell($this->param));
    }
    //撤销挂买卖界面
    public function withBuyS(){
        Hook::listen('CheckAuth',$params);
	    $data = $this->logicCfi->withBuyS();
	    $this->assign('list',$data);
	    return $this->fetch('cfi_buy');
    }
    //撤销挂买卖界面
    public function withSellS(){
        Hook::listen('CheckAuth',$params);
        $data = $this->logicCfi->withSellS();
        $this->assign('list',$data);
        return $this->fetch('cfi_sell');
    }
    //撤销挂买
    public function withdralbuy(){
        Hook::listen('CheckAuth',$params);
	    $this->jump($this->logicCfi->withdralbuy($this->param));
    }
    public function withdralsell(){
        Hook::listen('CheckAuth',$params);
        $this->jump($this->logicCfi->withdralsell($this->param));
    }
    public function buyRecord(){
        Hook::listen('CheckAuth',$params);
	    $data = $this->logicCfi->buyRecord();
	    $this->assign('list',$data);
        $this->assign('data',1);
	    return $this->fetch('deal_record');
    }

    public function sellRecord(){
        Hook::listen('CheckAuth',$params);
        $data = $this->logicCfi->sellRecord();
        $this->assign('list',$data);
        $this->assign('data',2);
        return $this->fetch('deal_record');
    }


}