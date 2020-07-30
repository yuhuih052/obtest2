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
 * 提现
 */
class Withdrawl extends AdminBase
{
    
    /**
     * 获取提现申请列表
     */
    public function withdrawlList($where = [], $field = true, $order = '', $paginate = DB_LIST_ROWS)
    {
         
        //$data = $this->modelMember->getList($where, $field, $order, $paginate);
        //dump($data);
        $data = $this->modelMember->where('re_withdrawl','>',0)->select();
        //dump($data);die;
        return $data;
    }
    
    /**
     * 同意提现
     */
    public function withdrawlAgree($data = [])
    {
        //dump($data);die;
        
        $url = url('index');
        $user = $this->modelMember->where('id','=',$data['id'])->select()->toArray();
        $server = $this->modelSiteArgm->where('id',1)->select();
        //dump($server[0]['withdrawl_server'] *100);die;
        $ser = $server[0]['withdrawl_server'] /100;
        $userwithdrawl = $user[0]['re_withdrawl'];
        //dump($user);die;

        $data2 = [
            'user_id' => $data['id'],
            'user_name' => $user[0]['username'],
            'amount' => $data['withdrawl'],
            'server' => $server[0]['withdrawl_server'].'%',
            'time' =>  date('Y-m-d h:i:s', time()),
            'daozhang' =>$data['withdrawl'] - $data['withdrawl'] * $ser,
            'shuoming' => '提现成功',
        ];
         $data1 = [
            'with_re_time' => NULL,
            're_withdrawl' => 0,
            'daozhang'     => $user[0]['daozhang'] + $data2['daozhang'],
        ];
        
        $result1 = $this->modelMember->where('id','=',$data['id'])->update($data1);
        $result2 = $this->modelWithdrawl->setInfo($data2);
        $usernnn = $this->modelMember->where('id','=',$data['id'])->value('username');
        //账单流水记录
        $bill9 = [
            'user_id' => $data['id'],
            'user_name' => $usernnn,
            'withdrawl'  => '-'.$data['withdrawl'],
        ];
        $this->modelBill->setInfo($bill9);
        
        return $result1&&$result2 ? [RESULT_SUCCESS, '操作成功', $url] : [RESULT_ERROR, $this->modelBlogroll->getError()];
        
    }
    /**
     * 拒绝提现
     */
    public function withdrawlAgreenot($data = [])
    {
        //dump($data);die;
        if($data['withdrawl'] == 0){
            $url = url('index');
            return [RESULT_SUCCESS, '用户未申请提现', $url];
        }else{
        $url = url('index');
        $user = $this->modelMember->where('id','=',$data['id'])->select()->toArray();
        $userwithdrawl = $user[0]['re_withdrawl'];
        //dump($user);die;
        $data1 = [
            'with_re_time' => NULL,
            're_withdrawl' => 0,
            'bonus'  => $user[0]['bonus'] + $data['withdrawl'],
        ];
        $data2 = [
            'user_id' => $data['id'],
            'user_name' => $user[0]['username'],
            'amount' => $data['withdrawl'],
            'server' => '本次不扣手续费',
            'time' =>  date('Y-m-d h:i:s', time()),
            'daozhang' =>0,
            'shuoming' => '提现申请被拒绝，奖金币已退回原账户',
        ];
        
        $result1 = $this->modelMember->where('id','=',$data['id'])->update($data1);
        $result2 = $this->modelWithdrawl->setInfo($data2);
        
        return $result1&&$result2 ? [RESULT_SUCCESS, '操作成功', $url] : [RESULT_ERROR, $this->modelBlogroll->getError()];
        }
    }

    //提现记录
    public function withdrawlrecordlList($where = [], $field = true, $order = 'id desc', $paginate = DB_LIST_ROWS)
    {
         
        $list = $this->modelWithdrawl->getList($where, $field, $order, $paginate);
        //dump($list);
        return $list;
    }

}
