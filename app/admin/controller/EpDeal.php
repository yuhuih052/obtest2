<?php
namespace app\admin\controller;

class EpDeal extends AdminBase
{
    public function epRecord(){
        $info =$this->logicEpDeal->epRecord();
        $this->assign('list',$info);
        return $this->fetch('record');
    }
    public function searchData(){
        $date = $this->param;
        if(!$date == Null){
            $data = $this->logicEpDeal->searchDate($date);
            $this->assign('list', $data);
            //dump($data);die;
            return $this->fetch('searchDate');
        }
    }
    //超时未打款
    public function overtime(){
        $info =$this->logicEpDeal->overtime();
        $this->assign('list',$info);
        return $this->fetch('overtime');
    }
    //取消订单，超时未打款
    public function cancel_overtime(){
        $this->jump($this->logicEpDeal->cancel_overtime($this->param));
    }
    //超时未确认收款
    public function overtime_comfirm(){
        $info =$this->logicEpDeal->overtime_comfirm();
        $this->assign('list',$info);
        return $this->fetch('overtime_comfirm');
    }
    //后台确认收款
    public function comfirm(){
        $this->jump($this->logicEpDeal->comfirm($this->param));
    }
    //仲裁列表
    public function seller_arb(){
        $info =$this->logicEpDeal->seller_arb();
        $this->assign('list',$info);
        return $this->fetch('arblist');
    }
    //仲裁，完成交易
    public function agreen_arb(){
        $this->jump($this->logicEpDeal->agreen_arb($this->param));
    }
    //仲裁，取消交易
    public function disagreen_arb(){
        $this->jump($this->logicEpDeal->disagreen_arb($this->param));
    }
}