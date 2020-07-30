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
 * 友情链接控制器
 */
class Bonus extends AdminBase
{
    
    /**
     * 奖金列表
     */
    public function bonusList()
    {
        $data = $this->logicBonus->bonusList();
        $this->assign('list', $data);
        //dump($data);die;
        return $this->fetch('bonus_cashier');
    }
    
    
    /**
     * 奖金明细
     */
    public function statistics()
    {
        
        $info = $this->logicBonus->statistics();
        
        $this->assign('list', $info);
        
        return $this->fetch('bonus_statistics');
    }
    //刷新出纳信息
    public function record(){

       $result = $this->logicBonus->record();
       if($result){
        $this->success('刷新成功');
       }

    }

    public function search(){
        $data = $this->param;

        $info = $this->logicBonus->search($data);

        $this->assign('list', $info);

        return $this->fetch('bonus_search');
    }

    public function searchCashier(){
        $date = $this->param;
        if(!$date == Null){
        $data = $this->logicBonus->searchDate($date);
        $this->assign('list', $data);
        //dump($data);die;
        return $this->fetch('searchDate');
        }
    }
}