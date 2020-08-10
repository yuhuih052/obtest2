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
 * 账单流水
 */
class Money extends AdminBase
{
    
    /**
     * 账单流水列表
     */
    public function moneyList($where = [], $field = true, $order = 'id desc', $paginate = 20)
    {
         
        $data = $this->modelBill->getList($where,$field,$order,$paginate);
        //dump($data);
        return $data;
    }
    /**
     * 账单搜索
     */
    public function moneySearch($data)
    {
         //dump($data );die;
        if( $data['username'] == Null && $data['type_search'] == Null && $data['date'] == Null){ //000

            return 0;

        }else if(!empty($data['type_search']) && $data['username'] == Null && $data['date'] == Null){ //010
            //dd($data['type_search']);
            return  $this->modelBill->whereNotNull($data['type_search'])->order('create_time','desc')->select();
        }else if(!empty($data['username']) && $data['type_search'] == Null && $data['date'] == Null){ //100

           return $this->modelBill->where('user_name',$data['username'])->order('create_time','desc')->select();

        }else if(!empty($data['date']) && $data['username'] == Null && $data['type_search'] == Null){ //001

           return $this->modelBill->whereTime('create_time',$data['date'])->order('create_time','desc')->select();

        }else if(empty($data['username']) && !empty($data['type_search']) && !empty($data['date'])){ //011
            return $this->modelBill->whereTime('create_time',$data['date'])
                                    ->whereNotNull($data['type_search'])
                                    ->order('create_time','desc')
                                    ->select();
        }else if(!empty($data['username']) && empty($data['type_search']) && !empty($data['date'])) { //101
            return $this->modelBill->where('user_name', $data['username'])
                                    ->whereTime('create_time', $data['date'])
                                    ->order('create_time','desc')
                                    ->select();
        }else if(!empty($data['username']) && !empty($data['type_search']) && empty($data['date'])) { //110
            return $this->modelBill->where('user_name', $data['username'])
                                    ->whereNotNull($data['type_search'])
                                    ->order('create_time','desc')
                                    ->select();
        }else if(!empty($data['username']) && !empty($data['type_search']) && !empty($data['date'])) { //110
            return $this->modelBill->where('user_name', $data['username'])
                                    ->whereNotNull($data['type_search'])
                                    ->whereTime('create_time',$data['date'])
                                    ->order('create_time','desc')
                                    ->select();
        }
    }
    
}
