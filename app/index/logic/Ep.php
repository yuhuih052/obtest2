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
                                ->where('statuss',1)
                                ->where('num','>',0)
                                ->select();
    }
    public function ep_sell_list(){
        return $this->modelEp->where('type',2)
                            ->where('statuss',1)
                            ->where('num','>',0)
                            ->select();
    }
    //添加流水账单
    public function bill($id,$name,$number,$shuoming){
        $bill = [
            'user_id' => $id,
            'user_name' => $name,
            'bonus' => $number,
            'shuoming' =>$shuoming,
        ];
        $this->modelBill->setInfo($bill);
    }
    //挂卖
    public function sys_sellEp($data){

        $member = $this->modelMember->where('id',session('user_id2'))->find();
        $member_record = $this->modelEp->where('member_id',session('user_id2'))
                                        ->where('type','=',2)
                                        ->where('statuss','=',1)
                                        ->find();
        $mem_eprecord = $this->modelEpRecord->where('seller_id',session('user_id2'))
                                            ->where('flag','in','1,2')
                                            ->find();

        if(!$member_record==null && !$mem_eprecord == null){
            return [RESULT_ERROR,'当前还有未完成订单'];
        }
        $member_rank = $this->logicUser->checkMember_rank($member->member_rank);

        $sell_ep = $member_rank['baodanbi_co'] * 0.1;
        if($member->bonus < $sell_ep){
            return [RESULT_ERROR,'现金币不足'];
        }
        $this->modelMember->where('id',$member->id)->dec('bouns',$sell_ep)->update();
        $this->bill($member->id,$member->username,'-'.$sell_ep,'挂卖现金币');
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

        switch(1){
            case 1 :
                return [RESULT_SUCCESS,'挂卖订单发布成功'];break;
            case 2 :
                return [RESULT_SUCCESS,'匹配到订单,已进行交易'];break;
            case 3 :
                return [RESULT_SUCCESS,'匹配到订单'];break;
        }


    }
    //撤销挂卖
    public function withSellEP(){
        $sellinfo = $this->modelEp->where('member_id',session('user_id2'))
                                    ->where('type',2)
                                    ->where('num','>',0)
                                    ->where('statuss',1)
                                    ->find();
        if($sellinfo == Null){
            return [RESULT_ERROR,'当前暂无挂卖中的订单，请检查交易列表订单完成情况'];
        }
        $this->modelMember->where('id',$sellinfo->member_id)->inc('bouns',$sellinfo->num)->update();
        $member = $this->modelMember->where('id',$sellinfo->member_id)->find();
        $this->bill($sellinfo->member_id,$member->username,'+'.$sellinfo->num,'撤销挂卖现金币');
        $this->modelEp->where('id',$sellinfo->id)->data(['num'=>0,'statuss'=>3])->update();
        return [RESULT_SUCCESS,'操作成功'];
    }
    //交易列表
    public function sell_list(){
        return $this->modelEpRecord->where('seller_id','=',session('user_id2'))
                                ->where('flag','in','1,2,5')
                                ->where('complaint','in','0,1')
                                ->select();
    }
    //交易列表
    public function buy_list(){
        return $this->modelEpRecord->where('buyer_id','=',session('user_id2'))
                                ->where('flag','in','1,2,5')
                                ->where('complaint','in','0,1')
                                ->select();
    }
    //挂买
    public function sys_buyEp($data){
        $member = $this->modelMember->where('id',session('user_id2'))->find();

        $member_record = $this->modelEpRecord->where('buyer_id',session('user_id2'))
                                        ->where('flag','=',1)
                                        ->find();

        if(!$member_record==null){
            $url = url('ep/buy_list');
            return [RESULT_SUCCESS,'当前还有订单未未付款',$url];
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
                return [RESULT_SUCCESS,'挂买成功'];break;
            case 2 :
                return [RESULT_SUCCESS,'匹配到订单,已进行交易'];break;
            case 3 :
                return [RESULT_SUCCESS,'匹配到订单'];break;
        }
    }
    //挂买未交易列表
    public function withBuy_list(){
        return $this->modelEp->where('member_id',session('user_id2'))
                        ->where('num','>',0)
                        ->where('type','=',1)
                        ->select();
    }
    //撤销挂买
    public function withBuyEP($data){

        $this->modelEp->where('id',$data['id'])->data(['statuss'=>3])->update();
        return [RESULT_SUCCESS,'操作成功'];
    }
    //部分购买
    public function buy_ali($data){
        $member_record = $this->modelEpRecord->where('buyer_id',session('user_id2'))
            ->where('flag','=',1)
            ->find();

        if(!$member_record==null){
            $url = url('ep/buy_list');
            return [RESULT_SUCCESS,'当前还有订单未未付款',$url];
        }
        $sell_info = $this->modelEp->where('id',$data['id'])->find();
        $re = [
            'ep_id'=>$data['id'],
            'buyer_id' => session('user_id2'),
            'seller_id'=>$sell_info->member_id,
            'ep_amount'=>$data['buy_alist'],
            'ep_pro'=>$data['ep_pro'],
            'ep_money'=>$data['buy_alist'] * $data['ep_pro'],
            'ac_time'=>time(),
            'flag'=>1,
            'deal_status'=>1,
        ];

        $result = $this->modelEpRecord->setInfo($re);
        $this->modelEp->where('id',$data['id'])
                        ->dec('num',$data['buy_alist'])
                        ->data(['deal_status'=>2])
                        ->update();
        $url = url('ep/buy_list');
        return $result>0? [RESULT_SUCCESS,'生成订单，请及时付款',$url] :[RESULT_ERROR,$this->modelEpRecord->getError()];
    }
    //全部购买
    public function buy_all($data){
        $member_record = $this->modelEpRecord->where('buyer_id',session('user_id2'))
            ->where('flag','=',1)
            ->find();

        if(!$member_record==null){
            $url = url('ep/buy_list');
            return [RESULT_SUCCESS,'当前还有订单未未付款',$url];
        }
        $sell_info = $this->modelEp->where('id',$data['id'])->find();
        $re = [
            'ep_id'=>$data['id'],
            'buyer_id' => session('user_id2'),
            'seller_id'=>$sell_info->member_id,
            'ep_amount'=>$data['buy_alist'],
            'ep_pro'=>$data['ep_pro'],
            'ep_money'=>$data['buy_alist'] * $data['ep_pro'],
            'ac_time'=>time(),
            'flag'=>1,
            'deal_status'=>2,
        ];

        $result = $this->modelEpRecord->setInfo($re);
        $this->modelEp->where('id',$data['id'])
            ->dec('num',$data['buy_all'])
            ->data(['deal_status'=>3])
            ->update();
        $url = url('ep/buy_list');
        return $result>0? [RESULT_SUCCESS,'购买成功，请及时付款',$url] :[RESULT_ERROR,$this->modelEpRecord->getError()];
    }
    //部分出售
    public function sell_ali($data){
        $buy_info = $this->modelEp->where('id',$data['id'])->find();
        $re = [
            'ep_id'=>$data['id'],
            'buyer_id' =>$buy_info->member_id,
            'seller_id'=> session('user_id2'),
            'ep_amount'=>$data['sell_alist'],
            'ep_pro'=>$data['ep_pro'],
            'ep_money'=>$data['sell_alist'] * $data['ep_pro'],
            'ac_time'=>time(),
            'flag'=>1,
            'deal_status'=>1,
        ];

        $result = $this->modelEpRecord->setInfo($re);
        $this->modelMember->where('id',$re['seller_id'])
            ->dec('bonus',$data['sell_alist'])
            ->update();

        $this->modelEp->where('id',$data['id'])
            ->dec('num',$data['sell_alist'])
            ->data(['deal_status'=>2])
            ->update();
        $url = url('ep/sell_list');
        return $result>0? [RESULT_SUCCESS,'出售成功，正在跳转交易列表',$url] :[RESULT_ERROR,$this->modelEpRecord->getError()];
    }
    //全部出售
    public function sell_all($data){
        $buy_info = $this->modelEp->where('id',$data['id'])->find();
        $re = [
            'ep_id'=>$data['id'],
            'buyer_id' =>$buy_info->member_id,
            'seller_id'=> session('user_id2'),
            'ep_amount'=>$data['sell_all'],
            'ep_pro'=>$data['ep_pro'],
            'ep_money'=>$data['sell_all'] * $data['ep_pro'],
            'ac_time'=>time(),
            'flag'=>1,
            'deal_status'=>2,
        ];

        $result = $this->modelEpRecord->setInfo($re);
        $this->modelEp->where('id',$data['id'])
            ->dec('num',$data['sell_all'])
            ->data(['deal_status'=>3])
            ->update();
        $this->modelMember->where('id',$re['seller_id'])->dec('bonus',$re['ep_amount'])->update();
        $url = url('ep/sell_list');
        return $result>0? [RESULT_SUCCESS,'出售成功，正在跳转交易列表',$url] :[RESULT_ERROR,$this->modelEpRecord->getError()];
    }
    public function upload($info,$id){

        $filePath =  'uploads'. DS .$info->getSaveName();
        $getInfo = $info->getInfo();
        //获取图片的原名称
        $name = $getInfo['name'];
        //整理数据,写入数据库
        $data = [
            'screenshot' => $filePath,
            'fukuan_time' => time(),
            'flag'=>2,
        ];

        $result = $this->modelEpRecord->where('id',$id)->update($data);
        return $result>0? [RESULT_SUCCESS,'上传成功']:[RESULT_ERROR,$this->modelEpRecord->getError()];
    }
    //重新上传截图
    public function re_upload($info,$id){

        $filePath = 'uploads'. DS .$info->getSaveName();
        $getInfo = $info->getInfo();
        //获取图片的原名称
        $name = $getInfo['name'];
        //整理数据,写入数据库
        $data = [
            'screenshot' => $filePath,
            're_fukuan_time' => time(),
        ];

        $result = $this->modelEpRecord->where('id',$id)->update($data);
        return $result>0? [RESULT_SUCCESS,'上传成功']:[RESULT_ERROR,$this->modelEpRecord->getError()];
    }
    //确认收款
    public function comfirm_money($data){
        $ep_record = $this->modelEpRecord->where('id',$data['id'])->find();
            //交易成功，给买家账户增加EP币
            $this->modelMember->where('id',$ep_record->buyer_id)->inc('bonus',$ep_record->ep_amount)->update();
            $member = $this->modelMember->where('id',$ep_record->buyer_id)->find();
            $this->bill($member->id,$member->username,'+'.$ep_record->ep_amount,'购买现金币');
            //如果是一笔全部交易
            if($ep_record->deal_status == 2){
                $this->modelEp->where('id',$ep_record->ep_id)->data(['statuss'=>2,'deal_status'=>3])->pdate();
                $this->modelEpRecord->where('id',$data['id'])->data(['flag'=>3])->update();
            }
        $this->modelEpRecord->where('id',$data['id'])->data(['flag'=>3])->update();
            return [RESULT_SUCCESS,'交易成功'];
    }
    //订单取消
    public function cancel_deal($data){
        $deal_record = $this->modelEpRecord->where('id',$data['id'])->find();

        $ep_record = $this->modelEp->where('id',$deal_record->ep_id)->find();
        if($deal_record->ep_amount == $ep_record->num_a){
            $this->modelEp->where('id',$deal_record->ep_id)
                ->data([
                    'num'=>$ep_record->num+$deal_record->ep_amount,
                    'deal_status'=>1,
                ])->update();
            $this->modelEpRecord->where('id',$data['id'])->update(['flag'=>6,'shuoming'=>'买家取消订单']);
        }else{
            $this->modelEp->where('id',$deal_record->ep_id)
                ->data([
                    'num'=>$ep_record->num+$deal_record->ep_amount,
                    'deal_status'=>2,
                ])->update();
            $this->modelEpRecord->where('id',$data['id'])->update(['flag'=>6,'shuoming'=>'买家取消订单']);
        }
        return [RESULT_SUCCESS,'操作成功'];
    }
    //发起仲裁
    public function arb($data){
        $this->modelEpRecord->where('id',$data['id'])->update(['flag'=>5,'complaint'=>1,'update_time'=>time()]);
        return [RESULT_SUCCESS,'申请仲裁成功'];
    }
    //匹配订单记录，买入
    public function ep_buy_in(){
        return $this->modelEpRecord->where('buyer_id',session('user_id2'))->select();
    }
    //匹配订单记录，卖出
    public function ep_sell_out(){
        return $this->modelEpRecord->where('seller_id',session('user_id2'))->select();
    }
    //挂卖记录
    public function ep_sell_record(){
        return $this->modelEp->where('member_id',session('user_id2'))
                                ->where('type',2)
                                ->select();
    }
    //挂卖记录
    public function ep_buy_record(){
        return $this->modelEp->where('member_id',session('user_id2'))
            ->where('type',1)
            ->select();
    }
    //记录详情
    public function ep_record_detail($data){

        return $this->modelEpRecord->where('ep_id',$data['id'])->select();
    }
}