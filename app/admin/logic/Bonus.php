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
        
        return $data;
    }
    
    /**
     * 获取奖金列表
     */
    public function statistics($where = [], $field = true, $order = 'id desc', $paginate = 20)
    {
        
       $bonus_list = $this->modelBonusDetail->getList($where, $field, $order,$paginate);
        
        return $bonus_list;
    }
    
    public function record(){
        $baodanbi_all = $this->modelBill->whereTime('create_time','d' )->sum('baodanbi_all');
        
        $totalbonus = $this->modelBonusDetail->whereTime('create_time','d')->sum('bonus_amount');
        
        $baodanbi_all = $baodanbi_all == NULL ? 0 : $baodanbi_all;
        $totalbonus = $totalbonus == NULL ? 0 : $totalbonus;
        
        if(!$baodanbi_all == 0){
        $pp =  $totalbonus/$baodanbi_all;
        }else{
            $pp = 0;
        }
        //dump($data);die;
        $datarecord = [
            'month_day' => date('Y-m-d H:i:s', time()),
            'entry' => $baodanbi_all,
            'out_account' => $totalbonus,
            'pp' => $pp,
        ];
        //dump($data['sum']);die;
        $result = $this->modelBonus->setInfo($datarecord);
        return 1;
        
    }

    public function search($data){
       return  $this->modelBonusDetail->where('user_name',$data['username'])->select();
    }

    public function searchDate($date){
        //dd($date);
        return $this->modelBonus->whereTime('create_time',$date['date'])->select();
    }
}
