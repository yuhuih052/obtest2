<?php
namespace app\index\logic;

use app\index\logic\IndexBase;

class Ep extends IndexBase{
    public function ep_show(){
        return $this->modelMember->where('id',session('user_id2'))->find();
    }
    public function sys_argm(){
        return $this->modelSiteArgm->where('id',1)->find();
    }
    public function ep_buy_list(){
        return $this->modelEp->where('type',1)
                                ->where('status',1)
                                ->select();
    }
    public function ep_sell_list(){
        return $this->modelEp->where('type',2)
                            ->where('status',1)
                            ->select();
    }
    //挂卖
    public function sys_sellEp($data){

        $member = $this->modelMember->where('id',session('user_id2'))->find();

        $member_record = $this->modelEp->where('member_id',session('user_id2'))
                                        ->where('type','=',2)
                                        ->where('status','=',1)
                                        ->find();

        if(!$member_record==null){
            return [RESULT_SUCCESS,'当前还有挂卖订单'];
        }
        $member_rank = $this->logicUser->checkMember_rank($member->member_rank);

        $sell_ep = $member_rank['baodanbi_co'] * 0.1;
        $re = [
            'member_id' => session('user_id2'),
            'num'   => $sell_ep,
            'num_a'   => $sell_ep,
            'create_time' =>time(),
            'type' => 2,
            'money_a' =>$sell_ep * $this->sys_argm()->ep_pro,
            'money'=>$sell_ep * $this->sys_argm()->ep_pro,
            'flag' => 0,
        ];
        $result_id = $this->modelEp->setInfo($re);
        $r = $this->sellEp($result_id);

        switch($r){
            case 1 :
                return [RESULT_SUCCESS,'排队成功'];break;
            case 2 :
                return [RESULT_SUCCESS,'匹配到订单,已进行交易'];break;
            case 3 :
                return [RESULT_SUCCESS,'匹配到订单'];break;
        }


    }


    public function sell_list(){
        return $this->modelEp->where('seller|buyer','=',session('user_id2'))
                                ->where('status',1)
                                ->select();
    }
    public function buy_list(){
        return $this->modelEp->where('seller|buyer','=',session('user_id2'))
                                ->where('status',1)
                                ->select();
    }
    public function sys_buyEp($data){
        $member = $this->modelMember->where('id',session('user_id2'))->find();

        $member_record = $this->modelEp->where('member_id',session('user_id2'))
                                        ->where('type','=',1)
                                        ->where('status','=',1)
                                        ->find();

        if(!$member_record==null){
            return [RESULT_SUCCESS,'当前还有挂买订单'];
        }

        $re = [
            'member_id' => session('user_id2'),
            'num'   => $data['amount'],
            'num_a'   => $data['amount'],
            'create_time' =>time(),
            'type' => 1,
            'money_a' =>$data['amount'] * $this->sys_argm()->ep_pro,
            'money'=>$data['amount'] * $this->sys_argm()->ep_pro,
            'flag' => 0,
        ];
        $result_id = $this->modelEp->setInfo($re);
        //$r = $this->buyEp($result_id);

        switch(1){
            case 1 :
                return [RESULT_SUCCESS,'排队成功'];break;
            case 2 :
                return [RESULT_SUCCESS,'匹配到订单,已进行交易'];break;
            case 3 :
                return [RESULT_SUCCESS,'匹配到订单'];break;
        }
    }


}