<?php
namespace app\admin\logic;

/**
 * 
 */
class ActivateMember extends AdminBase
{
	
	//统计业绩
    public function addYeji($member_path,$member_id,$bdb_del){
    		//左右业绩统计
            //增加总业绩
            //获取接点人id
            $hello_id = explode(',', $member_path);
            array_shift($hello_id);
            array_pop($hello_id);
            //往上接点所有人增加总业绩
            for($iii = 0; $iii < count($hello_id); $iii++){
                $tc = $this->modelMember->where('id',$hello_id[$iii])->value('totalChild');
                $this->modelMember->where('id',$hello_id[$iii])->update(['totalChild'=>$tc + $bdb_del]);
            }
            //给左右区增加业绩
            array_push($hello_id, $member_id);
            
            for ($a = count($hello_id)-1;$a>0;$a--) {
                //dd($hello_id[$a]);
                 //$father = $this->modelMember->where('Father_id',$value)->select();
                 $treeplace = $this->modelMember->where('id',$hello_id[$a])->value('treeplace');
                 //dd($treeplace);
                 if($treeplace == 0){
                    $this->modelMember->where('id',$hello_id[$a-1])->setInc('yejil_Total',$bdb_del);
                    $this->modelMember->where('id',$hello_id[$a-1])->setInc('LiftChild',$bdb_del);
                 }else{
                    $this->modelMember->where('id',$hello_id[$a-1])->setInc('yejir_Total',$bdb_del);
                    $this->modelMember->where('id',$hello_id[$a-1])->setInc('RightChild',$bdb_del);
                 }   
            }
        /***左右区业绩统计，结束**/
            //进行对碰结算
            array_pop($hello_id);
            foreach (array_reverse($hello_id) as $key => $value) {
                //产生对碰的接点人信息
                $dp_info = $this->modelMember->where('id',$value)->select();

                if($dp_info[0]['LiftChild'] && $dp_info[0]['RightChild']){

                    $a = min($dp_info[0]['LiftChild'],$dp_info[0]['RightChild']);
                    $this->modelMember->where('id',$value)->setDec('LiftChild',$a);
                    $this->modelMember->where('id',$value)->setDec('RightChild',$a);
                   
                   //获取对碰奖的接点人等级信息,发放对碰奖
                  $dp_info_rank =  $this->checkMember_rank($dp_info[0]['member_rank']);
                  //应获奖额
                  $dp_info_rank_bonus = $dp_info_rank['baodanbi_co'] * $dp_info_rank['duipeng_co'];

                    $bonus_dp =[
                        'bonus' => $dp_info[0]['all_bonus'] + $dp_info_rank_bonus,
                        'all_bonus' => $dp_info[0]['all_bonus'] + $dp_info_rank_bonus,
                    ];
                    //发放对碰奖，更新数据库
                    //判断奖金是否达到日上限
                    $bonus_day = $this->checkBonus_day($value);
                    //奖金日上限值与当日奖金值的差值
                    $D_value = $dp_info_rank['bonus_day'] - $bonus_day;
                    //dd($D_value);
                    //没有达到日上限
                    if($D_value > $dp_info_rank_bonus){
                        
                        $this->modelMember->where('id',$value)->update($bonus_dp);
                        //产生对碰奖，账单流水记录,记录接点人
                    $bill1 = [
                        'user_id' => $dp_info[0]['id'],
                        'user_name' => $dp_info[0]['username'],
                        'duipeng'   => "+".$dp_info_rank_bonus,
                    ];
                    $this->modelBill->setInfo($bill1);

                    //获奖记录保存，记录对碰层接点人
                    $bonus_detail1 = [
                        'user_id' => $dp_info[0]['id'],
                        'user_name' => $dp_info[0]['username'],
                        'bonus_amount' => $dp_info_rank_bonus,
                        'bonus_type'    => '对碰奖',
                        'bonus_time'    => date('Y-m-d h:i:s', time()),
                    ];
                    $this->modelBonusDetail->setInfo($bonus_detail1);

                        //未达到日上限，但接近上限值，剩余上限值不够
                    }elseif ($D_value > 0 && $D_value < $dp_info_rank_bonus ) {
                        $bonus_dp1 =[
                        'bonus' => $dp_info[0]['all_bonus'] + $D_value,
                        'all_bonus' => $dp_info[0]['all_bonus'] + $D_value,
                    ];
                         //产生对碰奖，账单流水记录,记录接点人
                    $bill2 = [
                        'user_id' => $dp_info[0]['id'],
                        'user_name' => $dp_info[0]['username'],
                        'duipeng'   => "+".$D_value,
                    ];
                    $this->modelBill->setInfo($bill2);

                    //获奖记录保存，记录对碰层接点人
                    $bonus_detail2 = [
                        'user_id' => $dp_info[0]['id'],
                        'user_name' => $dp_info[0]['username'],
                        'bonus_amount' => $D_value,
                        'bonus_type'    => '对碰奖',
                        'bonus_time'    => date('Y-m-d h:i:s', time()),
                    ];
                    $this->modelBonusDetail->setInfo($bonus_detail2);

                        $this->modelMember->where('id',$value)->update($bonus_dp1);
                    }else{//已到达获奖日上限
                        $bonus_dp2 =[
                        'bonus' => $dp_info[0]['all_bonus'] + 0,
                        'all_bonus' => $dp_info[0]['all_bonus'] + 0,
                    ];
                        //产生对碰奖，账单流水记录,记录接点人
                    $bill2 = [
                        'user_id' => $dp_info[0]['id'],
                        'user_name' => $dp_info[0]['username'],
                        'duipeng'   => "+0,已达到奖金日封顶",
                    ];
                    $this->modelBill->setInfo($bill2);

                    //获奖记录保存，记录对碰层接点人
                    $bonus_detail2 = [
                        'user_id' => $dp_info[0]['id'],
                        'user_name' => $dp_info[0]['username'],
                        'bonus_amount' => 0,
                        'bonus_type'    => '对碰奖',
                        'bonus_time'    => date('Y-m-d h:i:s', time()),
                        'bonus_des' => "应获".$dp_info_rank_bonus."，但已达到奖金日封顶",
                    ];
                    $this->modelBonusDetail->setInfo($bonus_detail2);

                        $this->modelMember->where('id',$value)->update($bonus_dp2);
                    }

                    //产生对碰后，接点人往上级发管理奖
                    $dp_p_id_path = explode(',', $dp_info[0]['p_path_id']);
                    array_shift($dp_p_id_path);
                    array_pop($dp_p_id_path);
                    array_reverse($dp_p_id_path);
                    $first_father = $dp_p_id_path[0];
                    //dd($dp_p_id_path);
                    //判断最近接点人的信息
                    $first_father_info = $this->modelMember->where('id',$dp_p_id_path[0])->select();
                    $first_father_rank = $this->checkMember_rank($first_father_info[0]['member_rank']);
                    //查询当日奖金
                    $first_father_bonus_day = $this->checkBonus_day($dp_p_id_path[0]);
                    //发放管理奖
                    //获奖额度
                    $first_father_rank_bonus = $first_father_rank['baodanbi_co'] * $first_father_rank['guanli_co'];
                    //日封顶与当日获奖差值
                    $D_value1 = $first_father_rank['bonus_day'] - $first_father_rank_bonus;
                    
                    if($D_value1 > $first_father_rank_bonus){
                        $first_father_guanli_bonus = [
                        'bonus' => $first_father_info['bonus'] + $first_father_rank_bonus,
                        'all_bonus' => $first_father_info[0]['all_bonus'] + $first_father_rank_bonus,
                    ];

                    //管理奖，账单流水记录,记录对碰层最近接点人
                    $bill1 = [
                        'user_id' => $first_father_info[0]['id'],
                        'user_name' => $first_father_info[0]['username'],
                        'guanli'   => "+".$first_father_rank_bonus,
                    ];
                    $this->modelBill->setInfo($bill1);

                    //获奖记录保存，记录接点人
                    $bonus_detail1 = [
                        'user_id' => $first_father_info[0]['id'],
                        'user_name' => $first_father_info[0]['username'],
                        'bonus_amount' => $first_father_rank_bonus,
                        'bonus_type'    => '管理奖',
                        'bonus_time'    => date('Y-m-d h:i:s', time()),
                    ];
                    $this->modelBonusDetail->setInfo($bonus_detail1);

                    $this->modelMember->where('id',$dp_p_id_path[0])->update($first_father_guanli_bonus);
                    }elseif($D_value1 >0 &&$D_value1< $first_father_rank_bonus){
                        $first_father_guanli_bonus = [
                        'bonus' => $first_father_info['bonus'] + $D_value1,
                        'all_bonus' => $first_father_info[0]['all_bonus'] + $D_value1,
                    ];

                    //管理奖，账单流水记录,记录对碰层最近接点人
                    $bill1 = [
                        'user_id' => $first_father_info[0]['id'],
                        'user_name' => $first_father_info[0]['username'],
                        'guanli'   => "+".$D_value1,
                    ];
                    $this->modelBill->setInfo($bill1);

                    //获奖记录保存，记录接点人
                    $bonus_detail1 = [
                        'user_id' => $first_father_info[0]['id'],
                        'user_name' => $first_father_info[0]['username'],
                        'bonus_amount' => $D_value1,
                        'bonus_type'    => '管理奖',
                        'bonus_time'    => date('Y-m-d h:i:s', time()),
                    ];
                    $this->modelBonusDetail->setInfo($bonus_detail1);

                    $this->modelMember->where('id',$dp_p_id_path[0])->update($first_father_guanli_bonus);
                }else{
                    $first_father_guanli_bonus = [
                        'bonus' => $first_father_info['bonus'] + $D_value1,
                        'all_bonus' => $first_father_info[0]['all_bonus'] + $D_value1,
                    ];

                    //管理奖，账单流水记录,记录对碰层最近接点人
                    $bill1 = [
                        'user_id' => $first_father_info[0]['id'],
                        'user_name' => $first_father_info[0]['username'],
                        'guanli'   => "+0,已达到奖金日封顶",
                    ];
                    $this->modelBill->setInfo($bill1);

                    //获奖记录保存，记录接点人
                    $bonus_detail1 = [
                        'user_id' => $first_father_info[0]['id'],
                        'user_name' => $first_father_info[0]['username'],
                        'bonus_amount' => 0,
                        'bonus_type'    => '管理奖',
                        'bonus_time'    => date('Y-m-d h:i:s', time()),
                        'bonus_des' =>"应获".$dp_info_rank_bonus."，但已达到奖金日封顶",
                    ];
                    $this->modelBonusDetail->setInfo($bonus_detail1);

                    $this->modelMember->where('id',$dp_p_id_path[0])->update($first_father_guanli_bonus);
                }

                    
                    //dd($dp_p_id_path);
                    for($i = 1; count($dp_p_id_path)>$i && $i<=2; $i++){
                        $f_father_info = $this->modelMember->where('id',$dp_p_id_path[$i])->select();
                        
                        //dd($f_father_info);
                        if($f_father_info[0]['member_rank']>1){

                            $f_father_rank = $this->checkMember_rank($f_father_info[0]['member_rank']);
                            $bonus_day = $this->checkBonus_day($dp_p_id_path[$i]);
                            //应获奖金
                            $f_father_rank_bonus = $f_father_rank['baodanbi_co'] * $f_father_rank['guanli_co'];
                            //差值
                            $D_value2 = $f_father_rank['bonus_day'] - $bonus_day;
                           
                            if($D_value2 > $f_father_rank_bonus){
                                 $f_father_guanli_bonus = [
                            'bonus' => $f_father_info[0]['bonus']+$f_father_rank_bonus,
                            'all_bonus' => $f_father_info[0]['all_bonus'] + $f_father_rank_bonus,
                            ];
                            //管理奖，账单流水记录,记录对碰层上面三级内的接点人
                        $bill1 = [
                            'user_id' => $f_father_info[0]['id'],
                            'user_name' => $f_father_info[0]['username'],
                            'guanli'   => "+".$f_father_rank_bonus,
                        ];
                        $this->modelBill->setInfo($bill1);

                        //获奖记录保存，记录三代内的接点人
                        $bonus_detail1 = [
                            'user_id' => $f_father_info[0]['id'],
                            'user_name' => $f_father_info[0]['username'],
                            'bonus_amount' => $f_father_rank_bonus,
                            'bonus_type'    => '管理奖',
                            'bonus_time'    => date('Y-m-d h:i:s', time()),
                        ];
                        $this->modelBonusDetail->setInfo($bonus_detail1);

                             $this->modelMember->where('id',$dp_p_id_path[$i])->update($f_father_guanli_bonus);

                            }elseif($D_value2 >0 && $D_value2 < $f_father_rank_bonus){

                                $f_father_guanli_bonus = [
                            'bonus' => $f_father_info[0]['bonus']+$D_value2,
                            'all_bonus' => $f_father_info[0]['all_bonus'] + $D_value2,
                            ];
                            //管理奖，账单流水记录,记录对碰层上面三级内的接点人
                        $bill1 = [
                            'user_id' => $f_father_info[0]['id'],
                            'user_name' => $f_father_info[0]['username'],
                            'guanli'   => "+".$D_value2,
                        ];
                        $this->modelBill->setInfo($bill1);

                        //获奖记录保存，记录三代内的接点人
                        $bonus_detail1 = [
                            'user_id' => $f_father_info[0]['id'],
                            'user_name' => $f_father_info[0]['username'],
                            'bonus_amount' => $D_value2,
                            'bonus_type'    => '管理奖',
                            'bonus_time'    => date('Y-m-d h:i:s', time()),
                        ];
                        $this->modelBonusDetail->setInfo($bonus_detail1);

                             $this->modelMember->where('id',$dp_p_id_path[$i])->update($f_father_guanli_bonus);

                            }else{
                                $f_father_guanli_bonus = [
                            'bonus' => $f_father_info[0]['bonus']+0,
                            'all_bonus' => $f_father_info[0]['all_bonus'] + 0,
                            ];
                            //管理奖，账单流水记录,记录对碰层上面三级内的接点人
                        $bill1 = [
                            'user_id' => $f_father_info[0]['id'],
                            'user_name' => $f_father_info[0]['username'],
                            'guanli'   => "+0,已达到奖金日封顶",
                        ];
                        $this->modelBill->setInfo($bill1);

                        //获奖记录保存，记录三代内的接点人
                        $bonus_detail1 = [
                            'user_id' => $f_father_info[0]['id'],
                            'user_name' => $f_father_info[0]['username'],
                            'bonus_amount' => $D_value2,
                            'bonus_type'    => '管理奖',
                            'bonus_time'    => date('Y-m-d h:i:s', time()),
                            'bonus_des'     =>"应获".$f_father_rank_bonus."，但已达到奖金日封顶",
                        ];
                        $this->modelBonusDetail->setInfo($bonus_detail1);

                             $this->modelMember->where('id',$dp_p_id_path[$i])->update($f_father_guanli_bonus);
                            }
                             
                        }

                    }

                }

            }
            //dd('1');
        //return [RESULT_SUCCESS,'操作成功'];
    }
    //增加账单流水和奖金记录
    public function bill_bonus($id,$name,$number,$type){
        //见点奖，账单流水记录,记录接点人
                    $bill_ct6 = [
                        'user_id' => $id,
                        'user_name' => $name,
                        'jiandian'   => "+".$number,
                    ];

                    $this->modelBill->setInfo($bill_ct6);

                    //获奖记录保存，记录接点人
                    $bonus_detail_ct6 = [
                        'user_id' => $id,
                        'user_name' => $name,
                        'bonus_amount' => $number,
                        'bonus_type'    => $type,
                        'bonus_time'    => date('Y-m-d h:i:s', time()),
                    ];
                    //dd($bonus_detail_ct6);
                    $this->modelBonusDetail->setInfo($bonus_detail_ct6);
    }

    //根据会员等级查询不同奖励值
    public function checkMember_rank($mmm){

        switch ($mmm) {
            case 1:
            $checkData = [
                'CF' => 50,
                'baodanbi_co' => 100,
                'tuijian_co' => 0.05,
                'duipeng_co' =>0.05,
                'jiandian_co' =>0.005,
                'guanli_co' =>0.02,
                'bonus_day' => 100,
                'CFI_split' => 400,
            ];
                break;
            case 2:
                $checkData = [
                'CF' => 260,
                'baodanbi_co' => 500,
                'tuijian_co' => 0.06,
                'duipeng_co' =>0.06,
                'jiandian_co' =>0.005,
                'guanli_co' =>0.02,
                'bonus_day' => 500,
                'CFI_split' => 2000,
            ];
                break;
            case 3:
                $checkData = [
                'CF' => 540,
                'baodanbi_co' => 1000,
                'tuijian_co' => 0.07,
                'duipeng_co' =>0.07,
                'jiandian_co' =>0.005,
                'guanli_co' =>0.03,
                'bonus_day' => 1000,
                'CFI_split' => 4000,
            ];
                break;
            case 4:
                $checkData = [
                'CF' => 1120,
                'baodanbi_co' => 2000,
                'tuijian_co' => 0.08,
                'duipeng_co' =>0.08,
                'jiandian_co' =>0.005,
                'guanli_co' =>0.04,
                'bonus_day' => 2000,
                'CFI_split' => 8000,
            ];
                break;
            case 5:
                $checkData = [
                'CF' => 2900,
                'baodanbi_co' => 5000,
                'tuijian_co' => 0.10,
                'duipeng_co' =>0.10,
                'jiandian_co' =>0.005,
                'guanli_co' =>0.05,
                'bonus_day' => 5000,
                'CFI_split' => 20000,
            ];
                break;
            case 6:
                 $checkData = [
                'CF' => 6000,
                'baodanbi_co' => 10000,
                'tuijian_co' => 0.12,
                'duipeng_co' =>0.12,
                'jiandian_co' =>0.005,
                'guanli_co' =>0.06,
                'bonus_day' => 10000,
                'CFI_split' => 40000,
            ];
                break;
            default:
                $checkData = Null;
                break;
        }
        return $checkData;
    }

    //查询今天奖金是否达到日上限
    public function checkBonus_day($id){
        return $this->modelBonusDetail->whereTime('create_time','d')
                                ->where('user_id',$id)
                                ->where('bonus_type','in','管理奖,对碰奖,见点奖')
                                ->sum('bonus_amount');
    }
}