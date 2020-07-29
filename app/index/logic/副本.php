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

namespace app\common\logic;

/**
 * 用户逻辑
 */
class User extends LogicBase
{

    /**
     * 获取用户信息
     */
    public function index($id)
    {
        
        $data = $this->modelMember->where('id',$id)->find()->toArray();
        //dump($data);die;
        //$this->assign('data',$data);
        return $data;
        
    }

    //获取推荐人
    public function recommonderList(){
        $member = session('member_info');
         
        $data = $this->modelMember->where('center_id',$member[0]['id'])->select()->toArray();
        //dump($data);die;
        return $data;
        
    }

    /**
     * 个人信息编辑
     */
    public function edit($data = [])
    {

        $validate_result = $this->validateMember->scene('edit')->check($data);

        if (!$validate_result) {

            return [RESULT_ERROR, $this->validateMember->getError()];
        }

        if(IS_POST){
            //$param = $this->param;
            //dump($data);die;
            $data = [
                'member_number' => $data['member_number'],
                'username'=>$data['realname'],
                'mobile'  =>$data['mobile'],
                'bankcard'=>$data['bankcard'],
                'bankname'=>$data['bankname'],
                
                'nickname'=>$data['nickname'],
                'mibaowenti'=>$data['mibaowenti'],
                'mibaodaan'=>$data['mibaodaan'],
                //'password_confirm'=>$data['password_confirm'],
            ];

            $data1 = $this->modelMember->where('member_number','=',$data['member_number'])->select()->toArray();

            $id = $data1[0]['id'];
            //dump($id);die;
            $result = $this->modelMember->where('id','=',$id)->update($data);
            //dump($result);die;
            $url = url('user/index',['id'=>$id]);
            //dump($data);die;
            return $result ? [RESULT_SUCCESS, '修改成功', $url] : [RESULT_ERROR, $this->modelMember->getError()];
        }
    }

    /**
     * 推荐人激活会员信息
     */
    public function memberEdit($data = [])
    {
        $user = session('member_info');
        
        $username = $user[0]['username'];
        
        $status = $this->modelMember->where('id',$data['id'])->value('status');
        //获取被激活用户的信息
        $p = $this->modelMember->where('id',$data['id'])->select();
        //激活者的信息
        $p_person = $this->modelMember->where('id',$user[0]['id'])->select();
        //直推人信息
        $p_zhi = $this->modelMember->where('id',$p[0]['Re_id'])->select();
        //dd($p_person);
        $url = url('User/userRecommonder',['username'=> $username]);

        if(!$status){
        
        //被激活者的等级信息
        $check_dis = $this->checkMember_rank($p[0]['member_rank']);
        //激活者的等级信息
        $check = $this->checkMember_rank($p_person[0]['member_rank']);
        //直推人等级信息
        $check_p_zhi = $this->checkMember_rank($p_zhi[0]['member_rank']);
            //判断报单币是否足够激活该星级会员
            if($p_person[0]['wallet'] >= $check_dis['baodanbi_co']){
                //配送电子币
                $this->modelMember->where('id',$data['id'])->setInc('dianzibi',$check_dis['CF']);
                //扣除报单中心的报单币（也是激活者）
                $re = [
                    'wallet' => $p_person[0]['wallet'] - $check_dis['baodanbi_co'],
                ];
                $res = $this->modelMember->where('id','=',$user[0]['id'])->update($re);

                //直推人数
                $this->modelMember->where('id',$p_zhi[0]['id'])->setInc('zhitui_all',1);
                
                //更新直推人的奖励
                $re_zhi = [
                    'bonus' => $p_zhi[0]['bonus'] + $check_dis['baodanbi_co'] * $check['tuijian_co'],
                    'all_bonus' =>  $p_zhi[0]['all_bonus'] + $check_dis['baodanbi_co'] * $check['tuijian_co'],
                ];
                
                //直推人获奖更新
                $this->modelMember->where('id',$p_zhi[0]['id'])->update($re_zhi);

                //账单流水记录,记录直推人
                $bill1 = [
                    'user_id' => $p_zhi[0]['id'],
                    'user_name' => $p_zhi[0]['username'],
                    //'activate'  => "-".$check_dis['baodanbi_co'],
                    'tuijian'   => "+".$check_dis['baodanbi_co'] * $check['tuijian_co'],
                ];
                $this->modelBill->setInfo($bill1);

                //报单中心扣除报单币记录
                $bill12 = [
                    'user_id' => $p_person[0]['id'],
                    'user_name' => $p_person[0]['username'],
                    'activate' => "-".$check_dis['baodanbi_co'],
                    'baodanbi_all'=>$check_dis['baodanbi_co'],
                ];
                $this->modelBill->setInfo($bill12);

                //获奖记录保存，记录直推人
                $bonus_detail1 = [
                    'user_id' => $p_zhi[0]['id'],
                    'user_name' => $p_zhi[0]['username'],
                    'bonus_amount' => $check_dis['baodanbi_co'] * $check_p_zhi['tuijian_co'],
                    'bonus_type'    => '直推奖',
                    'bonus_time'    => date('Y-m-d h:i:s', time()),
                ];
                $this->modelBonusDetail->setInfo($bonus_detail1);
                    //增加业绩
                    $this->addYeji($member[0]['p_path_id'],$member[0]['id']);

                //见点奖发放
                //获取被激活者推荐人的绝对路径id
                $p_id = explode(',', $p[0]['p_path_id']);
                array_shift($p_id);
                array_pop($p_id);
                $ct = 0;
                for($last_id = count($p_id)-1; $last_id>=0 && $last_id>$last_id-25;$last_id--){
                    
                    $ct++;
                    $p_id_info = $this->modelMember->where('id',$p_id[$last_id])->select();
                    $p_id_rank = $this->checkMember_rank($p_id_info[0]['member_rank']);
                    //应获奖
                    $p_id_rank_bonus =  $p_id_rank['baodanbi_co'] * $p_id_rank['jiandian_co'];
                    //当日累计奖金
                    $bonus_day = $this->checkBonus_day($p_id[$last_id]);
                    //差
                    $D_value3 = $p_id_rank['bonus_day'] - $bonus_day;
                    if($ct<6){
                        //发放见点奖
                        $ct6 = [
                        'bonus' => $p_id_info[0]['bonus'] + $p_id_rank_bonus,
                        'all_bonus' => $p_id_info[0]['all_bonus'] + $p_id_rank_bonus,
                        ];
                    if($D_value3 > $p_id_rank_bonus){
                    $this->modelMember->where('id',$p_id[$last_id])->update($ct6);
                    //见点奖，账单流水记录,记录接点人
                    $bill_ct6 = [
                        'user_id' => $p_id_info[0]['id'],
                        'user_name' => $p_id_info[0]['username'],
                        'jiandian'   => "+".$p_id_rank_bonus,
                    ];

                    $this->modelBill->setInfo($bill_ct6);

                    //获奖记录保存，记录接点人
                    $bonus_detail_ct6 = [
                        'user_id' => $p_id_info[0]['id'],
                        'user_name' => $p_id_info[0]['username'],
                        'bonus_amount' => $p_id_rank_bonus,
                        'bonus_type'    => '见点奖',
                        'bonus_time'    => date('Y-m-d h:i:s', time()),
                    ];
                    //dd($bonus_detail_ct6);
                    $this->modelBonusDetail->setInfo($bonus_detail_ct6);
                    }

                }else if ($ct<9) {
                    if($p_id_info[0]['member_rank'] > 1){
                        $ct9 = [
                        'bonus' => $p_id_info[0]['bonus'] + $p_id_rank_bonus,
                        'all_bonus' => $p_id_info[0]['all_bonus'] + $p_id_rank_bonus,
                        ];
                        if($D_value3 > $p_id_rank_bonus){
                    $this->modelMember->where('id',$p_id[$last_id])->update($ct9);
                    //见点奖，账单流水记录,记录接点人
                    $bill_ct9 = [
                        'user_id' => $p_id_info[0]['id'],
                        'user_name' => $p_id_info[0]['username'],
                        'jiandian'   => "+".$p_id_rank_bonus,
                    ];
                    $this->modelBill->setInfo($bill_ct9);

                    //获奖记录保存，记录接点人
                    $bonus_detail_ct9 = [
                        'user_id' => $p_id_info[0]['id'],
                        'user_name' => $p_id_info[0]['username'],
                        'bonus_amount' => $p_id_rank_bonus,
                        'bonus_type'    => '见点奖',
                        'bonus_time'    => date('Y-m-d h:i:s', time()),
                    ];
                    $this->modelBonusDetail->setInfo($bonus_detail_ct9);
                        }
                    }
                    continue;

                }else if ($ct<14) {
                    if($p_id_info[0]['member_rank'] > 2){
                        if($D_value3 > $p_id_rank_bonus){
                        $ct14 = [
                        'bonus' => $p_id_info[0]['bonus'] + $p_id_rank_bonus,
                        'all_bonus' => $p_id_info[0]['all_bonus'] + $p_id_rank_bonus,
                        ];
                    $this->modelMember->where('id',$p_id[$last_id])->update($ct14);
                    //见点奖，账单流水记录,记录接点人
                    $bill_ct14 = [
                        'user_id' => $p_id_info[0]['id'],
                        'user_name' => $p_id_info[0]['username'],
                        'jiandian'   => "+".$p_id_rank_bonus,
                    ];
                    $this->modelBill->setInfo($bill_ct14);

                    //获奖记录保存，记录接点人
                    $bonus_detail_ct14 = [
                        'user_id' => $p_id_info[0]['id'],
                        'user_name' => $p_id_info[0]['username'],
                        'bonus_amount' => $p_id_rank_bonus,
                        'bonus_type'    => '见点奖',
                        'bonus_time'    => date('Y-m-d h:i:s', time()),
                    ];
                    $this->modelBonusDetail->setInfo($bonus_detail_ct14);
                        }
                    }
                    continue;

                }else if ($ct<16) {
                    if($p_id_info[0]['member_rank'] > 3){
                        if($D_value3 > $p_id_rank_bonus){
                        $ct16 = [
                        'bonus' => $p_id_info[0]['bonus'] + $p_id_rank_bonus,
                        'all_bonus' => $p_id_info[0]['all_bonus'] + $p_id_rank_bonus,
                        ];
                    $this->modelMember->where('id',$p_id[$last_id])->update($ct16);
                    //见点奖，账单流水记录,记录接点人
                    $bill_ct16 = [
                        'user_id' => $p_id_info[0]['id'],
                        'user_name' => $p_id_info[0]['username'],
                        'jiandian'   => "+".$p_id_rank_bonus,
                    ];
                    $this->modelBill->setInfo($bill_ct16);

                    //获奖记录保存，记录接点人
                    $bonus_detail_ct6 = [
                        'user_id' => $p_id_info[0]['id'],
                        'user_name' => $p_id_info[0]['username'],
                        'bonus_amount' => $p_id_rank_bonus,
                        'bonus_type'    => '见点奖',
                        'bonus_time'    => date('Y-m-d h:i:s', time()),
                    ];
                    $this->modelBonusDetail->setInfo($bonus_detail_ct16);
                        }
                    }
                    continue;
                    
                }elseif ($ct<21) {
                    if($p_id_info[0]['member_rank'] > 4){
                        if($D_value3 > $p_id_rank_bonus){
                        $ct21 = [
                        'bonus' => $p_id_info[0]['bonus'] + $p_id_rank_bonus,
                        'all_bonus' => $p_id_info[0]['all_bonus'] + $p_id_rank_bonus,
                        ];
                    $this->modelMember->where('id',$p_id[$last_id])->update($ct21);
                    //见点奖，账单流水记录,记录接点人
                    $bill_ct21 = [
                        'user_id' => $p_id_info[0]['id'],
                        'user_name' => $p_id_info[0]['username'],
                        'jiandian'   => "+".$p_id_rank_bonus,
                    ];
                    $this->modelBill->setInfo($bill_ct21);

                    //获奖记录保存，记录接点人
                    $bonus_detail_ct21 = [
                        'user_id' => $p_id_info[0]['id'],
                        'user_name' => $p_id_info[0]['username'],
                        'bonus_amount' => $p_id_rank_bonus,
                        'bonus_type'    => '见点奖',
                        'bonus_time'    => date('Y-m-d h:i:s', time()),
                    ];
                    $this->modelBonusDetail->setInfo($bonus_detail_ct21);
                        }
                    }
                    continue;
                }elseif ($ct<26) {
                    if($p_id_info[0]['member_rank'] > 5){
                        if($D_value3 > $p_id_rank_bonus){
                        $ct26 = [
                        'bonus' => $p_id_info[0]['bonus'] + $p_id_rank_bonus,
                        'all_bonus' => $p_id_info[0]['all_bonus'] + $p_id_rank_bonus,
                        ];
                    $this->modelMember->where('id',$p_id[$last_id])->update($ct26);
                    //增加账单流水和奖金记录
                    $this->bill_bonus($p_id_info[0]['id'],$p_id_info[0]['username'],$p_id_rank_bonus,$type ='见点奖');
                        }
                    }
                    continue;
                }
/**************************见点奖发放结束****/

                //获取被激活者的推荐人的绝对路径
                $hello = explode(',',$p[0]['Re_path']);
                array_shift($hello);
                array_pop($hello);
                //增加团队总人数
                for($i = 0; $i < count($hello); $i++){
                    $team_all = $this->modelMember->where('username',$hello[$i])->value('team_all');
                    $this->modelMember->where('username',$hello[$i])->update(['team_all'=> $team_all + 1]);
                }
                    }
        
            //激活时间记录
            $this->modelMember->where('id',$p[0]['id'])
                                ->update([
                                    'activate_time'=> date('Y-m-d h:i:s', time()),'ac_time'=> time(),
                                ]);
            
            $result = $this->modelMember->setInfo($data);    
            
            return $result&&$res ? [RESULT_SUCCESS, '会员激活成功。',$url] : [RESULT_ERROR, $this->modelMember->getError()];
            }
        $url = url('User/userRecommonder',['username'=> $username]);
        return [RESULT_ERROR,'报单币余额不足'.$check['baodanbi_co'].'，无法激活会员。',$url];

        }
        return [RESULT_ERROR,'当前会员已激活，不可操作。',$url];
    }
    //查询今天奖金是否达到日上限
    public function checkBonus_day($id){
        return $this->modelBonusDetail->whereTime('create_time','d')
                                ->where('user_id',$id)
                                ->where('bonus_type','in','管理奖,对碰奖,见点奖')
                                ->sum('bonus_amount');
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
    //会员原点位升级
    public function upgrade($data){
        $id = session('user_id');
        $member = $this->modelMember->where('id',$id)->select()->toArray();
        //等级信息
        $old_v = $this->checkMember_rank($member[0]['member_rank']);
        $new_v = $this->checkMember_rank($data['v']);
        //应扣除报单币数量
        $bdb_del = $new_v['baodanbi_co'] - $old_v['baodanbi_co'];
        //补偿电子币
        $dzb_add = $new_v['CF'] - $old_v['CF'];
        //dd($dzb_add);
        if($member[0]['wallet'] >= $bdb_del){
            //扣除报单币和补偿电子币
            $this->modelMember->where('id',$id)->dec('wallet',$bdb_del)
                                                ->inc('dianzibi',$dzb_add)
                                                ->inc('member_rank',$data['v']-$member[0]['member_rank'])
                                                ->update();
            //报单中心扣除报单币记录
                $bill12 = [
                    'user_id' => $id,
                    'user_name' => $member[0]['username'],
                    'activate' => "-".$bdb_del,
                    'baodanbi_all'=>$bdb_del,
                ];
                $this->modelBill->setInfo($bill12);
                //dd('1');
             $this->addYeji($member[0]['p_path_id'],$member[0]['id']);
        /****对碰奖结算，结束******/

        return  [RESULT_SUCCESS,'升级成功'];
        }
        return [RESULT_ERROR,'报单币不足'];  
    }

    //统计业绩
    public function addYeji($member_path,$member_id){
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

    //删除会员
   public function memberDel($where = [])
    {
        //dump($where);die;
        $user = session('member_info');
        $username = $user[0]['username'];
        $url = url('User/userRecommonder',['username'=> $username]);
        //dump($where['id']);die;
        $userstatus = $this->modelMember->where('id',$where['id'])->value('status');
        //dump($userstatus);die;
        if(!$userstatus){
        $result = $this->modelMember->where('id',$where['id'])->delete();
                
        //$result && action_log('删除', '删除会员，where：' . http_build_query($where));
        return $result ? [RESULT_SUCCESS, '会员删除成功'] : [RESULT_ERROR, $this->modelMember->getError(), $url];
        }

        return [RESULT_ERROR,'会员已激活，删除失败', $url];

    }

    public function chongzhi(){
        return $this->modelSiteArgm->where('id',1)->select()->toArray();
        // /dump($data);die;
      
    }
    //充值申请
    public function request_chongzhi1($data){
        $userid = session('user_id');
        $usern = session('member_info');

        $data = (int)$data;
         $sys_argm = $this->modelSiteArgm->where('id',1)->select()->toArray();
         //dump($sys_argm[0]['recharge_min']);die;
         if($data < $sys_argm[0]['recharge_min'] || $data > $sys_argm[0]['recharge_max']){
            return [RESULT_ERROR,'充值额度不在区间内'];
         }else if($data % $sys_argm[0]['recharge_mult']){
            return [RESULT_ERROR,'充值倍率不符合'];
         }

         $rq = $this->modelMember->where('id','=',$userid)->value('request_chongzhi');
         $tatol = $rq + $data;
         $ttime = [   
            'request_chongzhi' => $tatol,
            'request_time'      => date('Y-m-d h:i:s', time()),
         ];
        $result = $this->modelMember->where('id','=',$userid)->update($ttime);
        $re2 = [
            'user_id' => $userid,
            'user_name' => $usern[0]['username'],
            'request' => $data,
            'request_time' => date('Y-m-d h:i:s', time()),
            'result'    => 0,
         ];
        $result2 = $this->modelRecharge->setInfo($re2);
        
        //$url = url('User/index',['id'=>$userid]);
        //dump($result);die;
        return $result&&$result2 ? [RESULT_SUCCESS, '充值申请成功'] : [RESULT_ERROR, $this->modelMember->getError()];
    }
    //转账
    public function transfer($data){
        
        //dump($data);die;
        $center = $this->modelMember->where('username','=',$data['inputname'])->value('is_center');
        if($center == 1){
        $userid = session('user_id');
        $outwallet = $this->modelMember->where('id','=',$userid)->value('wallet');
        if($outwallet < $data['money']){
            $url = url('user/zhuanzhang');
            return [RESULT_ERROR,'账户余额不足。'];
        }
        $outname = $this->modelMember->where('id','=',$userid)->value('username');
        
        // /$tatol = $outwallet - $data['money'];
         $this->modelMember->where('id','=',$userid)->update(['wallet' => $outwallet - $data['money']]);
         //账单流水记录
        $bill7 = [
            'user_id' => $userid,
            'user_name' => $outname,
            'transfer'  => '-'.$data['money'],
        ];
        $this->modelBill->setInfo($bill7);

        $inputwallet = $this->modelMember->where('username','=',$data['inputname'])->value('wallet');
        //$tatol1 = $inputwallet + $data['money'];
         $this->modelMember->where('username','=',$data['inputname'])->update(['wallet' => $inputwallet + $data['money']]);
         $i8 = $this->modelMember->where('username','=',$data['inputname'])->value('id');
         //账单流水记录
        $bill8 = [
            'user_id' => $i8,
            'user_name' => $data['inputname'],
            'transfer'  => '+'.$data['money'],
        ];
        $this->modelBill->setInfo($bill8);

        //dump($outwallet);die;
        $record = [
            'userid' => $userid,
            'out_name' => $outname,
            'input_name' => $data['inputname'],
            'money' => $data['money'],
            'time'      => date('Y-m-d h:i:s', time()),
        ];
        $this->modelOutinput->setInfo($record);
        $map1 = ['out_name'=>$outname,];
        $map2 = ['input_name'=>$outname,];
        $data = $this->modelOutinput->where($map1)
                                        ->whereOr($map2)
                                        ->select()
                                        ->toArray();
        //array_push($data, ['userid'=>$userid]);
        //dump($data);die;
        return $data;
        }

        return $data = 0;

    }
    public function transferRecord($username){
       
        $map1 = ['out_name'=>$username,];
        $map2 = ['input_name'=>$username,];
        $data = $this->modelOutinput->where($map1)
                                        ->whereOr($map2)
                                        ->select()
                                        ->toArray();
        //array_push($data, ['userid'=>$userid]);
        //dump($data);die;
        return $data;

    }
    //提现申请提示
    public function withDD(){
        $data = $this->modelSiteArgm->where('id',1)->select()->toArray();
        return $data;
    }
    //提现申请
    public function withDrawl($data){
        $userid = session('user_id');
         //dump($data['money']);die;
         $user = $this->modelMember->where('id','=',$userid)->select()->toArray();

         $withDrawl = (int)$data['money'];
         $sys_argm = $this->modelSiteArgm->where('id',1)->select()->toArray();
         // /dump($sys_argm);die;
         if($withDrawl < $sys_argm[0]['withdrawl_min'] || $withDrawl > $sys_argm[0]['withdrawl_max']){
            return [RESULT_ERROR,'充值额度不在区间内'];
         }else if($withDrawl % $sys_argm[0]['withdrawl_mult']){
            return [RESULT_ERROR,'充值倍率不符合'];
         }

         if($data['money'] <= $user[0]['bonus']){
         $tatol = $user[0]['re_withdrawl'] + $data['money'];
         $bonus = $user[0]['bonus'] - $data['money'];
         $ttime = [   
            're_withdrawl' => $tatol,
            'bonus'         => $bonus,
            'with_re_time'      => date('Y-m-d h:i:s', time()),
         ];
        $result = $this->modelMember->where('id','=',$userid)->update($ttime);
        //$url = url('User/index',['id'=>$userid]);
        //dump($result);die;
        return $result ? [RESULT_SUCCESS, '提现申请成功'] : [RESULT_ERROR, $this->modelMember->getError()];
        }else{

        $url = url('user/withDrawl');
        return [RESULT_ERROR,'奖金币余额不足，请检查账户余额。',$url];

        }
    }

    //提现记录
    public function record($id){
        $data = $this->modelWithdrawl->where('user_id',$id)->select()->toArray();
        return $data;
    }

    //留言
    public function message($data){
        $user = session('member_info');
        //dump($user[0]['username']);die;
        $mes = [
            'user_id' => $data['id'],
            'username' => $user[0]['username'],
            'message'   => $data['message'],
            'message_time' => date('Y-m-d h:i:s', time()),
        ];
        $result = $this->modelMessage->setInfo($mes);
        return $result ? [RESULT_SUCCESS, '留言成功'] : [RESULT_ERROR, $this->modelMessage->getError()];
    }
    //留言记录
    public function messageRecord($id){
        $record = $this->modelMessage->where('user_id',$id)->select()->toArray();
        //dump($record);
        return $record;
    }
    //双轨图
     public function twoPathway($top_id,$level_number){
        $where = [
            ['is_inside', '=', 0]
        ];
        if ($top_id) {
            $where[] = ['id', '=', $top_id];
            $top = $this->modelMember->where('id', '=', $top_id)->order('id asc')->find();
        }else{
            $top = session('mb');
            $level_number = 4;
        }
        
        if (!$top) {
            return '<div class="no-data">暂无数据！</div>';
        }

        return $this->logicTree->getTwoPathwayHtml($top->id, $level_number);
    }
    //双轨图查询
    public function twofind($data){
        return $this->modelMember->where('username',$data['username'])->find();
    }
    
    public function findId($data){
        $user = session('member_info');
        return $this->modelMember->where('father_name',$user[0]['username'])
                                    ->where('treeplace',$data['place'])
                                    ->value('id');
    }
    public function placeFind($data){
        return $this->modelMember->where('father_name',$data['username'])
                                    ->where('treeplace',$data['place'])
                                    ->value('id');
    }
    //推荐图
    public function tuijiantu($root_id){
        $root = $this->modelMember->where('id',$root_id)->select();
        //dump($root);die;
        if(!$root){
            return '<div class="no-data">没有数据</div>';
        }
        return $this->logicTuijiantu->Zhituitu($root_id);
    }
///////////////////////////////////////////////////////////////////////////    
    //树形图!!未使用

    public function teamTree($root_id){
        $member = session('mb');
        //dump($member);die;
        $icon_has = '/module/index/images/center.gif';
        $icon_no = '/module/index/images/center.gif';

        $root_id = $root_id ? $root_id : $member->username;
        if ($root_id == $member->username) {
            $member2 = $member;
        }else{
            $member2 = $this->modelMember->where([['username', '=', $root_id]])->find();
            if (!$member2) {
                return ['root' => $member, 'data' => json_encode([])];
                // return [RESULT_ERROR, '没有该会员！'];
            }
            if (strpos($member2->re_path, ','.$member->id.',') === false) {
                return ['root' => $member, 'data' => json_encode([])];
                // return [RESULT_ERROR, '没有权限查看你的上级！'];
            }
        }
        $status = ['正常', '未激活', '封号'];
        $root = [
            'id' => $member2->id,
            // 'status' => $member2->getData('status'),
            // 'username' => $member2->username,
            // 'realname' => $member2->realname,
            // 'rank' => $member2->rank,
            // 'paidan' => $member2->b0,
            'name' => $this->getZtreeName($member2),
            'open' => 'true',
            'icon' => $icon_no,
        ];
        if ($member2->zhitui_all) {
            $root['children'] = [];
            $root['icon'] = $icon_has;
        }
        foreach ($this->modelMember->where('Re_id', $member2->id)->select() as $vo) {
            $data = [
                'id' => $vo->id,
                // 'status' => $vo->getData('status'),
                // 'username' => $vo->username,
                // 'realname' => $vo->realname,
                // 'rank' => $vo->rank,
                // 'paidan' => $vo->b0,
                'name' => $this->getZtreeName($vo),
                'open' => 'false',
                'icon' => $icon_no,
            ];
            if ($vo->zhitui_all) {
                $data['children'] = [];
                $data['icon'] = $icon_has;
            }
            $root['children'][] = $data;
        }
        //dump($root);die;
        $like = $member2->Re_path ? $member2->Re_path.$member2->username.',' : ','.$member2->username.',';

        return [
            'root' => $member2,
            'data' => json_encode([$root]),
        ];
    }

    public function getChildrenMembers($pid){
        $member = session('mb');
        $icon_has = '/module/index/images/center.gif';
        $icon_no = '/module/index/images/center.gif';

        $member2 = $this->modelMember->where('id', $pid)->find();
        if (!$member2) {
            return [RESULT_ERROR, '没有该会员！'];
        }
        if (strpos($member2->re_path, ','.$member->username.',') === false) {
            return [RESULT_ERROR, '没有权限查看你的上级！'];
        }
        $re = [];
        $status = ['正常', '未激活', '封号'];
        foreach ($this->modelMember->where('re_id', $member2->id)->select() as $vo) {
            $data = [
                'id' => $vo->id,
                // 'status' => $vo->getData('status'),
                // 'username' => $vo->username,
                // 'realname' => $vo->realname,
                // 'rank' => $vo->rank,
                // 'paidan' => $vo->b0,
                'name' => $this->getZtreeName($vo),
                'open' => 'false',
                'icon' => $icon_no,
            ];
            if ($vo->zhitui_all && $member2->re_level - $member->re_level <= config_parse('show_dai_max') - 2) {
                $data['children'] = [];
                $data['icon'] = $icon_has;
            }
            $re[] = $data;
        }
        return [RESULT_SUCCESS, json_encode($re)];
    }

    private function getZtreeName($member)
    {
        $status = ['正常', '未激活', '封号'];
        $forbid_class = '';
        if ($member->getData('status') != 0) {
            $forbid_class = ' class="forbid"';
        }
        $name = '<span'.$forbid_class.'>';
        $name .= '<span class="ztree-username">' . $member->nickname . '</span>';
        $name .= '<span class="ztree-realname">(' . $member->username . ')</span>';
        // $name .= '<span class="ztree-status">[' . $status[$member->getData('status')] . ']</span>';
        // $name .= '<span class="ztree-status">[' . $member->team . ']</span>';
        // $name .= '<span class="ztree-rank">【' . $member->rank . '】</span>';
        $name .= '<span class="ztree-paidan">[团队人数:' . $member->team_all . ']</span></span>';
        //dd($name);
        return $name;
    }


//////////////////////////////////////////////////////////////////////////////
/// ！！使用中
public function re_tree($id){
    
    $data = $this->modelMember->where('id','>=',$id)->where('re_path_id','like','%'.$id.'%')->select()->toArray();
    $user = $this->modelMember->where('id',$id)->select()->toArray();
    //dd($data);
    $data = array_merge($user, $data);
    //dd($data);
    return $data;
    }

    public function find_re_tree_id($user){
        return $this->modelMember->where('username',$user['username'])->find();
       
    }
    public function find_all_number($id){
        return count($this->modelMember->where('id','>=',$id)->where('re_path_id','like','%'.$id.'%')->select());
    }
/*********************************************************************************/
//  使用中
     //会员体系
    public function system($id)
    {
        $html = $this->modelMember->systemTree($id);
        //dd($html);
        return $html;
    }

    public function queryOne($username)
    {

        $member = session('member_info');
        $ch = $this->modelMember->where('username', '=', $username['username'])->find();
        //dd($ch);
        if ($ch) {
            if ($ch['p_level'] <= $member[0]['p_level']) {
                return $member[0]['id'];
            } else {
                $brr = explode(',', '0' . $ch['p_path_id'] . ',0');
                if (in_array($member[0]['id'], $brr)) {
                    return $ch['id'];
                } else {
                    return 400;
                }
                return $ch['id'];
            }
        } else {
            return 0;
        }

    }

    public function findPlid($id,$p){
        return $this->modelMember->where('Father_id',$id)
                                    ->where('treeplace',$p)->value('id');
    }

/*********************************************************************************************************/
//未使用，无法调用raw()
public function teamTree3($root_id)
    {
        $member = session('member_info');
        $icon_has = './module/index/images/tree/center.gif';
        $icon_no ='./module/index/images/tree/center.gif';

        $root_id = $root_id ? $root_id : $member[0]['username'];
        if ($root_id == $member[0]['username']) {
            $member2 = $member;
        } else {
            $member2 = $this->modelMember->getInfo([['username', '=', $root_id]]);
            if (!$member2) {
                return ['root' => $member, 'data' => json_encode([])];
                // return [RESULT_ERROR, '没有该会员！'];
            }
            if (strpos($member2->re_path_id, ',' . $member->id . ',') === false) {
                return ['root' => $member, 'data' => json_encode([])];
                // return [RESULT_ERROR, '没有权限查看你的上级！'];
            }
        }
        $status = ['正常', '未激活', '封号'];
        $root = [
            'id' => $member2[0]['id'],
            // 'status' => $member2->getData('status'),
            // 'username' => $member2->username,
            // 'realname' => $member2->realname,
            // 'rank' => $member2->rank,
            // 'paidan' => $member2->b0,
            'name' => $this->getZtreeName3($member2),
            'open' => 'true',
            'icon' => $icon_no,
        ];
        if ($member2[0]['zhitui_all']) {
            $root['children'] = [];
            $root['icon'] = $icon_has;
        }
        foreach ($this->modelMember->where('Re_id', $member2[0]['id'])->select() as $vo) {
            //dd($vo);
            $data = [
                'id' => $vo->id,
                // 'status' => $vo->getData('status'),
                // 'username' => $vo->username,
                // 'realname' => $vo->realname,
                // 'rank' => $vo->rank,
                // 'paidan' => $vo->b0,
                'name' => $this->getZtreeName3($vo),
                'open' => 'false',
                'icon' => $icon_no,
            ];
            if ($vo->zhitui_all) {
                $data['children'] = [];
                $data['icon'] = $icon_has;
            }
            $root['children'][] = $data;
        }
        $like = $member2[0]['re_path_id'] ? $member2[0]['re_path_id'] . $member2[0]['id'] . ',' : ',' . $member2[0]['id'] . ',';

        return [
            'root' => $member2,
            'data' => json_encode([$root]),
        ];
    }

    //当前会员下的直接推荐会员
    public function directRela3($param)
    {
        $member = session('member_info');

        if (empty($param['username'])) {
            $where = [
                ['Re_id', '=', $member['id']]
            ];
        } else {
            $where = [
                ['Re_id', '=', $member['id']],
                ['username', '=', $param['username']]
            ];
        }


        return $this->modelMember
            ->where($where)
            ->order('create_time desc')
            ->paginate(DB_LIST_ROWS, false, ['query' => request()->param()]);

    }

    public function getChildrenMembers3($pid)
    {

        $member = session('member_info');
        $icon_has = __STATIC__ . '/module/index/images/tree/center.gif';
        $icon_no = __STATIC__ . '/module/index/images/tree/center.gif';

        $member2 = $this->modelMember->where('id', $pid)->find();
        if (!$member2) {
            return [RESULT_ERROR, '没有该会员！'];
        }
        if (strpos($member2->re_path_id, ',' . $member->id . ',') === false) {
            return [RESULT_ERROR, '没有权限查看你的上级！'];
        }
        $re = [];
        $status = ['正常', '未激活', '封号'];
        foreach ($this->modelMember->where('leader_id', $member2->id)->select() as $vo) {
        dd($vo);
            $data = [
                'id' => $vo->id,
                // 'status' => $vo->getData('status'),
                // 'username' => $vo->username,
                // 'realname' => $vo->realname,
                // 'rank' => $vo->rank,
                // 'paidan' => $vo->b0,
                'name' => $this->getZtreeName3($vo),
                'open' => 'false',
                'icon' => $icon_no,
            ];
            if ($vo->re_nums) {
                $data['children'] = [];
                $data['icon'] = $icon_has;
            }
            $re[] = $data;
        }
        return [RESULT_SUCCESS, json_encode($re)];
    }

    private function getZtreeName3($member)
    {
        
        $status = ['正常', '未激活', '封号'];
        $forbid_class = '';
        // if ($member->getData('status') != 0) {
        //     $forbid_class = ' class="forbid"';
        // }
         //dd($member);
        $name = '<span' . $forbid_class . '>';
        $name .= '<span class="ztree-username">12</span>';
        $name .= '<span class="ztree-realname">()</span>';
        // $name .= '<span class="ztree-status">[' . $status[$member->getData('status')] . ']</span>';
//        $name .= '<span class="ztree-status">[' . $member->team . ']</span>';
//        // $name .= '<span class="ztree-rank">【' . $member->rank . '】</span>';
//        $name .= '<span class="ztree-paidan">[业绩:' . $member->b6 . ']</span>';
//        $name .= '<span class="ztree-team">[团队:' . $member->b11 . ']</span>';
        return $name;
    }
///////////////////////////////////////////////////////////////////////////////////
    //货币转换操作
    public function zhuanhuan1($data){
        $user = session('user_id');
        $userinfo = $this->modelMember->where('id',$user)->select()->toArray();
        if($data['zhuanhuan_type'] == 1){
            if($userinfo[0]['bonus'] >= $data['zhuanhuan']){
                $update = [
                    'bonus' => $userinfo[0]['bonus'] - $data['zhuanhuan'],
                    'wallet' => $userinfo[0]['wallet'] + $data['zhuanhuan'],
                ];
           $up = $this->modelMember->where('id',$userinfo[0]['id'])->update($update);

            //记录流水账单
                $bill = [
                    'user_id' => $userinfo[0]['id'],
                    'user_name' => $userinfo[0]['username'],
                    'bonus' => "-".$data['zhuanhuan'],
                    'wallet' => "+".$data['zhuanhuan'],
                ];
                //dd($bill1['wallet']);
                $this->modelBill->setInfo($bill);
           return $up ? [RESULT_SUCCESS,'现金币转报单币成功'] : [RESULT_ERROR, $this->modelMember->getError()];
           }
           return [RESULT_ERROR,'账户现金币余额不足。'];
        }elseif ($data['zhuanhuan_type'] == 2) {
            if($userinfo[0]['bonus'] >= $data['zhuanhuan']){
                $update = [
                    'bonus' => $userinfo[0]['bonus'] - $data['zhuanhuan'],
                    'baoguanjin' => $userinfo[0]['baoguanjin'] + $data['zhuanhuan'],
                ];
           $up = $this->modelMember->where('id',$userinfo[0]['id'])->update($update);
           //记录流水账单
                $bill = [
                    'user_id' => $userinfo[0]['id'],
                    'user_name' => $userinfo[0]['username'],
                    'bonus' => "-".$data['zhuanhuan'],
                    'baoguanjin' => "+".$data['zhuanhuan'],
                ];
                //dd($bill1['wallet']);
                $this->modelBill->setInfo($bill);
           return $up ? [RESULT_SUCCESS,'现金币转保管金成功'] : [RESULT_ERROR, $this->modelMember->getError()];
           }
           return [RESULT_ERROR,'账户现金币余额不足。'];
        }else{
            if($userinfo[0]['baoguanjin'] >= $data['zhuanhuan']){
                $update = [
                    'baoguanjin' => $userinfo[0]['baoguanjin'] - $data['zhuanhuan'],
                    'wallet' => $userinfo[0]['wallet'] + $data['zhuanhuan'],
                ];
           $up = $this->modelMember->where('id',$userinfo[0]['id'])->update($update);
           //记录流水账单
                $bill = [
                    'user_id' => $userinfo[0]['id'],
                    'user_name' => $userinfo[0]['username'],
                    'baoguanjin' => "-".$data['zhuanhuan'],
                    'wallet' => "+".$data['zhuanhuan'],
                ];
                //dd($bill1['wallet']);
                $this->modelBill->setInfo($bill);
           return $up ? [RESULT_SUCCESS,'保管金转报单币成功'] : [RESULT_ERROR, $this->modelMember->getError()];
           }
           return [RESULT_ERROR,'账户保管金余额不足。'];
        }

    }

    //货币明细
    public function billDetail(){
        $id = session('user_id');
        return $this->modelBill->where('user_id',$id)
                            ->where('wallet|bonus|baoguanjin','not null')
                            ->select();
    }
    //申请报单中心
    public function request_is_center($data){

        $member = session('member_info');
        
        $result = $this->modelMember->where('id',$member[0]['id'])->update(['re_is_center' => $data['re_is_center']]);
        return $result ? [RESULT_SUCCESS,'申请成功'] : [RESULT_ERROR, $this->modelMember->getError()];
    }

}
