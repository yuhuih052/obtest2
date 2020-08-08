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
 * 留言管理
 */
class Messages extends AdminBase
{
    /**
     * 列表
     */
    public function messageList($where = [], $field = true, $order = '', $paginate = 25)
    {
         $where=[
             'receive_id'=>0,
         ];
        $data = $this->modelMessage->getList($where, $field, $order, $paginate);
        //dump($data);
        return $data;
    }

    public function responseEdit($data){
        //dump($data);die;
        $re = ['response' => $data['response'],
            'response_time' => date('Y-m-d h:i:s', time()),
        ];
        // /dump($re);die;
        $result = $this->modelMessage->where('id',$data['id'])->update($re);

        return $result ? [RESULT_SUCCESS, '回复成功'] : [RESULT_ERROR, $this->modelMember->getError()]; 
    }
    public function recordList($where = [], $field = true, $order = '', $paginate = 25)
    {
//         $where=[
//             ['receive_id','=',0],
//         ];
        $data = $this->modelMessage->getList($where, $field, $order, $paginate);
        //dump($data);
        return $data;
    }
    
}
