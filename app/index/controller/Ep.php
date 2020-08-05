<?php
namespace app\index\controller;

use app\index\controller\IndexBase;

class Ep extends IndexBase{
    public function ep_shop(){
        $data = $this->logicEp->ep_show();
        $data2 = $this->logicEp->sys_argm();
        $data5 = $this->logicEp->ep_sell_list();
        $this->assign('list1',$data);
        $this->assign('list2',$data2);
        $this->assign('list5',$data5);
        return $this->fetch('ep/ep_shop');
    }

    public function sys_sellEp(){
        $this->jump($this->logicEp->sys_sellEp($this->param));
    }
    public function sys_buyEp(){
        $this->jump($this->logicEp->sys_buyEp($this->param));
    }
    public function sell_list(){
        $data = $this->logicEp->sell_list();
        $this->assign('list',$data);
        return $this->fetch('ep/sell_list');
    }
    public function buy_list(){
        $data = $this->logicEp->buy_list();
        $this->assign('list',$data);
        return $this->fetch('ep/buy_list');
    }
    public function buy_ali(){
        dd($this->param);
    }
}