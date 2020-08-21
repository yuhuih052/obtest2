<?php
namespace app\index\controller;

use app\index\controller\IndexBase;
//引入Hook类
use think\Hook;

class Ep extends IndexBase{

    public function ep_shop(){
        Hook::listen('CheckAuth',$params);
        $data = $this->logicEp->ep_show();
        $data2 = $this->logicEp->sys_argm();
        $data3 = $this->logicEp->ep_sell_list();
        $data4 = $this->logicEp->ep_buy_list();
        $this->assign('list1',$data);
        $this->assign('list2',$data2);
        $this->assign('list3',$data3);
        $this->assign('list4',$data4);
        return $this->fetch('ep/ep_shop');
    }
    //挂卖
    public function sys_sellEp(){
        Hook::listen('CheckAuth',$params);
        $this->jump($this->logicEp->sys_sellEp($this->param));
    }
    //撤销挂卖
    public function withSellEP(){
        Hook::listen('CheckAuth',$params);
        $this->jump($this->logicEp->withSellEP());
    }
    //挂买
    public function sys_buyEp(){
        Hook::listen('CheckAuth',$params);
        $this->jump($this->logicEp->sys_buyEp($this->param));
    }
    //撤销挂买
    public function withBuyEP(){
        Hook::listen('CheckAuth',$params);
        $this->jump($this->logicEp->withBuyEP($this->param));
    }
    //挂买未交易列表
    public function withBuy_list(){
        Hook::listen('CheckAuth',$params);
        $data = $this->logicEp->withbuy_list();

        $this->assign('list',$data);
        return $this->fetch('withbuy_list');
    }
    //交易列表，挂卖
    public function sell_list(){
        Hook::listen('CheckAuth',$params);
        $data = $this->logicEp->sell_list();
        $this->assign('list',$data);
        return $this->fetch('sell_list');
    }
    //交易列表，挂买
    public function buy_list(){
        Hook::listen('CheckAuth',$params);
        $data = $this->logicEp->buy_list();
        $this->assign('list',$data);
        return $this->fetch('buy_list');
    }
    //部分购买
    public function buy_ali(){

        Hook::listen('CheckAuth',$params);
        $data = $this->param;
        if($data['buy_alist'] == null){
            $this->error('请输入数值');
        }
        if($data['buy_alist'] > $data['buy_all']){
            $this->error('请输入不大于剩余挂买的值');
        }
        if($data['buy_alist'] == $data['buy_all']){
            $this->buy_all($this->param);
        }
        $this->jump($this->logicEp->buy_ali($data));
    }
    //全部购买
    public function buy_all(){
        Hook::listen('CheckAuth',$params);
        $this->jump($this->logicEp->buy_all($this->param));
    }
    //部分出售
    public function sell_ali(){

        Hook::listen('CheckAuth',$params);
        $data = $this->param;
        if($data['sell_alist'] == null){
            $this->error('请输入数值');
        }
        if($data['sell_alist'] > $data['sell_all']){
            $this->error('请输入不大于剩余挂买的值');
        }
        if($data['sell_alist'] == $data['sell_all']){
            $this->sell_all($data);
        }
        $this->jump($this->logicEp->sell_ali($data));
    }
    //全部出售
    public function sell_all(){
        Hook::listen('CheckAuth',$params);
        $this->jump($this->logicEp->sell_all($this->param));
    }
    //上传截图
    public function upload(){
        Hook::listen('CheckAuth',$params);
        // 获取表单上传文件
        $id = $this->param['id'];
        $file = request()->file('image');
        // 移动到框架应用根目录/public/uploads/ 目录下
        $info = $file->validate(['ext'=>'jpg,png,gif'])->move(ROOT_PATH . 'public' . DS . 'uploads'. DS);
        if($info){
          $this->jump($this->logicEp->upload($info,$id));
        }else{
            // 上传失败获取错误信息
            echo $file->getError();
        }
    }
    //确认收款
    public function comfirm_money(){
        Hook::listen('CheckAuth',$params);
        $this->jump($this->logicEp->comfirm_money($this->param));
    }
    //取消交易
    public function cancel_deal(){
        Hook::listen('CheckAuth',$params);
        $this->jump($this->logicEp->cancel_deal($this->param));
    }
    //仲裁
    public function arb(){
        Hook::listen('CheckAuth',$params);
        $this->jump($this->logicEp->arb($this->param));
    }
    //重新上传截图
    public function re_upload(){
        Hook::listen('CheckAuth',$params);
        // 获取表单上传文件
        $id = $this->param['id'];
        $file = request()->file('image');
        // 移动到框架应用根目录/public/uploads/ 目录下
        $info = $file->validate(['ext'=>'jpg,png,gif'])->move(ROOT_PATH . 'public' . DS . 'uploads'. DS);
        if($info){
            $this->jump($this->logicEp->upload($info,$id));
        }else{
            // 上传失败获取错误信息
            echo $file->getError();
        }
    }
    //匹配订单，买入
    public function ep_buy_in(){
        Hook::listen('CheckAuth',$params);
        $data = $this->logicEp->ep_buy_in();
        $this->assign('list',$data);
        return $this->fetch('ep_buy_in');
    }
    //匹配订单，卖出
    public function ep_sell_out(){
        Hook::listen('CheckAuth',$params);
        $data = $this->logicEp->ep_sell_out();
        $this->assign('list',$data);
        return $this->fetch('ep_sell_out');
    }
    //挂卖记录
    public function ep_sell_record(){
        Hook::listen('CheckAuth',$params);
        $data = $this->logicEp->ep_sell_record();
        $this->assign('list',$data);
        return $this->fetch('ep_sell_record');
    }
    //挂买记录
    public function ep_buy_record(){
        Hook::listen('CheckAuth',$params);
        $data = $this->logicEp->ep_buy_record();
        $this->assign('list',$data);
        return $this->fetch('ep_buy_record');
    }
    //具体挂买挂卖详情
    public function ep_record_detail(){
        Hook::listen('CheckAuth',$params);
        $data = $this->logicEp->ep_record_detail($this->param);
        $this->assign('list',$data);
        return $this->fetch('ep_record_detail');
    }
}