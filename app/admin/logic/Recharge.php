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

namespace app\admin\logic;

/**
 * 充值逻辑
 */
class Recharge extends AdminBase
{
    
    /**
     * 获取充值申请列表
     */
    public function rechargeList($where = [], $field = true, $order = '', $paginate = 0)
    {
         
        $data = $this->modelRecharge->where('result','=',0)->select();
        //dump($data);
        return $data;
    }
    /**
     * 获取充值申请记录
     */
    public function rechargeRecord($where = [], $field = true, $order = '', $paginate = 0)
    {
         
        $data = $this->modelRecharge->where('create_time','> time','2016-1-1')->select()->toArray();
        //dump($data);
        return $data;
    }
    
    /**
     * 同意充值
     */
    public function rechargeAgree($data = [])
    {
        //dump($data);die;
        if($data['charge'] == 0){
            $url = url('index');
            return [RESULT_SUCCESS, '用户未申请充值', $url];
        }else{
        $url = url('index');
        $useid = $this->modelRecharge->where('id','=',$data['id'])->value('user_id');
        $user = $this->modelMember->where('id','=',$useid)->select()->toArray();
        $userwallet = $user[0]['wallet'];
        //dump($data);die;
        $data1 = [
            'wallet' => $userwallet + $data['charge'],
            'request_chongzhi' => 0,
        ];
        //dump($data1);die;
        $result = $this->modelMember->where('id','=',$useid)->update($data1);

        //修改申请充值状态
        $recharge_result = $this->modelRecharge->where('id',$data['id'])
                                                ->where('request',$data['charge'])
                                                ->update(['result' => 1]);

        $usernnn = $this->modelMember->where('id',$data['id'])->value('username');
       //账单流水记录
        $bill6 = [
            'user_id' => $data['id'],
            'user_name' => $usernnn,
            'recharge'  => '+'.$data['charge'],
        ];
        $this->modelBill->setInfo($bill6);
        
        return $result ? [RESULT_SUCCESS, '操作成功', $url] : [RESULT_ERROR, $this->modelBill->getError()];
        }
    }
    //删除
    public function rechargeDel($data){
        $useid = $this->modelRecharge->where('id',$data['id'])->value('user_id');
        $result = $this->modelMember->where('id',$useid)->update(['request_chongzhi' => 0]);
        //修改申请充值状态
        $recharge_result = $this->modelRecharge->where('id',$data['id'])
                                                ->where('request',$data['charge'])
                                                ->update(['result' => 2]);
        return $result ? [RESULT_SUCCESS, '操作成功'] : [RESULT_ERROR, $this->modelRecharge->getError()];
    }

}
