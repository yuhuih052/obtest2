<?php
namespace app\index\controller;

use app\index\controller\IndexBase;

class Ep extends IndexBase{
    public function ep_shop(){
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
        $this->jump($this->logicEp->sys_sellEp($this->param));
    }
    //撤销挂卖
    public function withSellEP(){
        $this->jump($this->logicEp->withSellEP());
    }
    //挂买
    public function sys_buyEp(){
        $this->jump($this->logicEp->sys_buyEp($this->param));
    }
    //撤销挂买
    public function withBuyEP(){
        $this->jump($this->logicEp->withBuyEP($this->param));
    }
    //挂买未交易列表
    public function withBuy_list(){
        $data = $this->logicEp->withbuy_list();

        $this->assign('list',$data);
        return $this->fetch('withbuy_list');
    }
    //交易列表，挂卖
    public function sell_list(){
        $data = $this->logicEp->sell_list();
        $this->assign('list',$data);
        return $this->fetch('ep/sell_list');
    }
    //交易列表，挂买
    public function buy_list(){
        $data = $this->logicEp->buy_list();
        $this->assign('list',$data);
        return $this->fetch('ep/buy_list');
    }
    //部分购买
    public function buy_ali(){
        $data = $this->param;
        if($data['buy_alist'] == null){
            $this->error('请输入数值');
        }
        if($data['buy_alist'] > $data['buy_all']){
            $this->error('请输入不大于剩余挂买的值');
        }
        if($data['buy_alist'] == $data['buy_all']){
            $this->buy_all($data);
        }
        $this->jump($this->logicEp->buy_ali($data));
    }
    //全部购买
    public function buy_all(){
        $this->jump($this->logicEp->buy_all($this->param));
    }
    //部分出售
    public function sell_ali(){
        $data = $this->param;
        if($data['sell_alist'] == null){
            $this->error('请输入数值');
        }
        if($data['sell_alist'] > $data['sell_all']){
            $this->error('请输入不大于剩余挂买的值');
        }
        if($data['sell_alist'] == $data['sell_all']){
            $this->buy_all($data);
        }
        $this->jump($this->logicEp->sell_ali($data));
    }
    //全部出售
    public function sell_all(){
        $this->jump($this->logicEp->sell_all($this->param));
    }
    //上传截图
    public function upload(){
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
        $this->jump($this->logicEp->comfirm_money($this->param));
    }
    //取消交易
    public function cancel_deal(){
        $this->jump($this->logicEp->cancel_deal($this->param));
    }
}