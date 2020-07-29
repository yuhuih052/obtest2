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
class Withdrawl extends AdminBase
{
    
    /**
     * 提现列表
     */
    public function index()
    {
        //dump($this->param);die;
        $list = $this->logicWithdrawl->withdrawlList($this->param);
        //dump($list);die;
        $this->assign('list', $list);
        
        return $this->fetch('withdrawl_list');
    }
    
    /**
     * 同意提现
     */
    public function withdrawlAgree()
    {
        //dump($this->param);die;
       $this->jump($this->logicWithdrawl->withdrawlAgree($this->param));
        
        return $this->fetch('bonus_cashier');
    }
    /**
     * 拒绝提现
     */
    public function withdrawlAgreenot()
    {
        //dump($this->param);die;
       $this->jump($this->logicWithdrawl->withdrawlAgreenot($this->param));
        
        return $this->fetch('bonus_cashier');
    }
    
    //提现记录
    public function record(){
        
        $data = $this->logicWithdrawl->withdrawlrecordlList();
        //dump($this->param);die;
        $this->assign('list',$data);
        //dump($data);die;
        return $this->fetch('withdrawl_record');
    }
    
}