<?php
namespace app\admin\logic;


class EpDeal extends AdminBase
{
    public function epRecord($where = [], $field = true,$order = 'id desc',$paginate = 20)
    {
        $data = $this->modelEpRecord->getList($where, $field, $order,$paginate);
        //dump($data);die;
        return $data;
    }
    public function searchDate($date){
        //dd($date);
        return $this->modelEpDeal->whereTime('create_time',$date['date'])->select();
    }
    public function overtime($where = [], $field = true,$order = 'id desc',$paginate = 20)
    {
        $siteArgm = $this->modelSiteArgm->where('id',1)->find();
        $data = $this->modelEpRecord->where('create_time','<',time()-$siteArgm->overtime)
                                        ->where('flag','=',1)
                                        ->order('create_time','desc')
                                        ->select();
        //dump($data);die;
        return $data;
    }
    //取消超时订单
    public function cancel_overtime($data){
        $deal_record = $this->modelEpRecord->where('id',$data['id'])->find();

        $ep_record = $this->modelEp->where('id',$deal_record->ep_id)->find();
        if($deal_record->ep_amount == $ep_record->num_a){
            $this->modelEp->where('id',$deal_record->ep_id)
                ->data([
                    'num'=>$ep_record->num+$deal_record->ep_amount,
                    'deal_status'=>1,
                ])->update();
            $this->modelEpRecord->where('id',$data['id'])->update(['flag'=>6,'shuoming'=>'超时未付款，后台取消订单']);
        }else{
            $this->modelEp->where('id',$deal_record->ep_id)
                ->data([
                    'num'=>$ep_record->num+$deal_record->ep_amount,
                    'deal_status'=>2,
                    'shuoming'=>'超时未付款，后台取消订单',
                ])->update();
            $this->modelEpRecord->where('id',$data['id'])->update(['flag'=>6,'shuoming'=>'超时未付款，后台取消订单']);
        }
        return [RESULT_SUCCESS,'操作成功'];
    }
    public function overtime_comfirm($where = [], $field = true,$order = 'id desc',$paginate = 20)
    {
        $siteArgm = $this->modelSiteArgm->where('id',1)->find();
        $data = $this->modelEpRecord->where('fukuan_time','<',time()-$siteArgm->overtime)
            ->where('flag','=',2)
            ->order('create_time','desc')
            ->select();
        //dump($data);die;
        return $data;
    }
    //后台确认收款
    public function comfirm($data){
        $ep_record = $this->modelEpRecord->where('id',$data['id'])->find();
        //交易成功，给买家账户增加EP币
        $this->modelMember->where('id',$ep_record->buyer_id)->inc('bonus',$ep_record->ep_amount)->update();
        //如果是一笔全部交易
        if($ep_record->deal_status == 2){
            $this->modelEp->where('id',$ep_record->ep_id)
                                ->data([
                                    'statuss'=>2,
                                    'deal_status'=>3,
                                    'shuoming'=>'卖家超时未确认，后台确认收款',
                                ])->pdate();
            $this->modelEpRecord->where('id',$data['id'])->data(['flag'=>3,'shuoming'=>'超时未付款，后台取消订单'])->update();
        }
        $this->modelEpRecord->where('id',$data['id'])->data(['flag'=>3,'shuoming'=>'超时未付款，后台取消订单'])->update();
        return [RESULT_SUCCESS,'交易成功'];
    }
    //申请仲裁列表
    public function seller_arb($where = [], $field = true,$order = 'id desc',$paginate = 20)
    {
        $data = $this->modelEpRecord->where('complaint','=',1)
            ->where('flag','=',5)
            ->order('update_time','desc')
            ->select();
        //dump($data);die;
        return $data;
    }
    //仲裁，完成交易
    public function agreen_arb($data){
        $ep_record = $this->modelEpRecord->where('id',$data['id'])->find();
        //交易成功，给买家账户增加EP币
        $this->modelMember->where('id',$ep_record->buyer_id)->inc('bonus',$ep_record->ep_amount)->update();
        //如果是一笔全部交易
        if($ep_record->deal_status == 2){
            $this->modelEp->where('id',$ep_record->ep_id)
                ->data([
                    'statuss'=>2,
                    'deal_status'=>3,
                    'shuoming'=>'卖家发起仲裁，拒绝退币，完成交易',
                ])->pdate();
            $this->modelEpRecord->where('id',$data['id'])->data(['complaint'=>3,'shuoming'=>'卖家发起仲裁，拒绝退币，完成交易'])->update();
        }
        $this->modelEpRecord->where('id',$data['id'])->data(['complaint'=>3,'shuoming'=>'卖家发起仲裁，拒绝退币，完成交易'])->update();
        return [RESULT_SUCCESS,'交易成功'];
    }
    //仲裁，取消交易
    public function disagreen_arb($data){
        $deal_record = $this->modelEpRecord->where('id',$data['id'])->find();

        $ep_record = $this->modelEp->where('id',$deal_record->ep_id)->find();
        if($deal_record->ep_amount == $ep_record->num_a){
            $this->modelEp->where('id',$deal_record->ep_id)
                ->data([
                    'num'=>$ep_record->num+$deal_record->ep_amount,
                    'deal_status'=>1,
                ])->update();
            $this->modelEpRecord->where('id',$data['id'])->update(['complaint'=>2,'shuoming'=>'卖家发起仲裁，取消交易']);
        }else{
            $this->modelEp->where('id',$deal_record->ep_id)
                ->data([
                    'num'=>$ep_record->num+$deal_record->ep_amount,
                    'deal_status'=>2,
                ])->update();
            $this->modelEpRecord->where('id',$data['id'])->update(['complaint'=>2,'shuoming'=>'卖家发起仲裁，取消交易']);
        }
        return [RESULT_SUCCESS,'操作成功'];
    }

}