<?php
// +---------------------------------------------------------------------+
// | OneBase    | [ WE CAN DO IT JUST THINK ]                            |
// +---------------------------------------------------------------------+
// | Licensed   | http://www.apache.org/licenses/LICENSE-2.0 )           |
// +---------------------------------------------------------------------+
// | Author     | Bigotry <3162875@qq.com>                               |
// +---------------------------------------------------------------------+
// | Repository | https://gitee.com/Bigotry/OneBase                      |
// +---------------------------------------------------------------------+

namespace app\admin\controller;

/**
 * 充值申请控制器
 */
class Recharge extends AdminBase
{
    
    /**
     * 充值列表
     */
    public function index()
    {
        //dump($this->param);die;
        $list = $this->logicRecharge->rechargeList($this->param);
        //dump($list);die;
        $this->assign('list', $list);
        
        return $this->fetch('recharge_index');
    }
    //充值记录
    public function rechargeRecord(){
        $list = $this->logicRecharge->rechargeRecord($this->param);
        //dump($list);die;
        $this->assign('list', $list);
        
        return $this->fetch('recharge_record');
    }
    
    /**
     * 同意充值
     */
    public function rechargeAgree()
    {
        //dump($this->param);die;
       $this->jump($this->logicRecharge->rechargeAgree($this->param));
        
        //return $this->fetch('bonus_cashier');
    }
    
    
    /**
     * 删除
     */
    public function rechargeDel()
    {
        
        $this->jump($this->logicRecharge->rechargeDel($this->param));
    }
}