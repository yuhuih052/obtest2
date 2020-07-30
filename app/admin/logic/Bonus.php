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
 * 奖金明细
 */
class Bonus extends AdminBase
{
    
    /**
     * 奖金信息
     */
    public function bonusList($where = [], $field = true,$order = 'id desc',$paginate = 20)
    {
        
        $data = $this->modelBonus->getList($where, $field, $order,$paginate);
        //dump($data);die;
        return $data;
    }
    
    /**
     * 获取奖金列表
     */
    public function statistics($where = [], $field = true, $order = 'id desc', $paginate = 20)
    {
        
       $bonus_list = $this->modelBonusDetail->getList($where, $field, $order,$paginate);
        //dump($bonus_list);die;
        
        return $bonus_list;
    }
    
    public function record(){
        $info = $this->modelMember->whereTime('ac_time','d' )->select()->toArray();
        //dump(count($info));die;
        $data['tatol'] = count($info) * 1000;
        $tobonus = $this->modelBonusDetail->whereTime('create_time','d')->sum('bonus_amount');
        $data['sum'] = $tobonus == NULL ? 0 : $tobonus;
        $data['pp'] = $data['tatol'] == 0 ? 0 : $tobonus / $data['tatol'];
        //dump($data);die;
        $datarecord = [
            'month_day' => date('Y-m-d H:i:s', time()),
            'entry' => $data['tatol'],
            'out_account' => $data['sum'],
            'pp' => $data['pp'],
        ];
        //dump($data['sum']);die;
        $result = $this->modelBonus->setInfo($datarecord);
        //dd($result);
        //$result = 1;
        
        return $result;
    }

    public function search($data){
       return  $this->modelBonusDetail->where('user_name',$data['username'])->select();
    }

    public function searchDate($date){
        //dd($date);
        return $this->modelBonus->whereTime('create_time',$date['date'])->select();
    }
}
