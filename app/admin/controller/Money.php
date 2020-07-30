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
 * 账单流水控制器
 */
class Money extends AdminBase
{
    
    /**
     * 流水列表
     */
    public function index()
    {
        //dump($this->param);die;
        $list = $this->logicMoney->moneyList();
        //dump($list);die;
        $this->assign('list', $list);
        
        return $this->fetch('money_list');
    }
    
    public function search(){
        $data = $this->param;
        $list = $this->logicMoney->moneySearch($data);
        //dd($list);
        $this->assign('list', $list);

        return $this->fetch('money_search');
    }    
    
}