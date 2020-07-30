<?php
namespace app\index\logic;

/**
 * 
 */
class Futou extends IndexBase
{
	public function list(){
		$id = session('user_id2');
		return $this->modelMember->where('master_id',$id)->select()->toArray();
	}
	public function centername($id){
		return $this->modelMember->where('id',$id)->value('username');
	}	

	public function futou(){
		$master = session('member_info2');
		$leader = $this->modelMember->where('id',$master[0]['id'])->find();
	        
	        $params = $this->logicTree->getPlaceInfoTwo($leader);
	        //dd($params);
	        //最后一位会员编号
            $last_number = $this->modelMember->where('is_inside','>=',0)->limit(1)->order("id desc")->value('member_number');
            //dd($last_number);
            $new_number = preg_replace("/[a-zA-Z]/u",'',$last_number)+1;
		$sl = [
			'member_number' => "ID".$new_number,
                'Re_name' =>$master[0]['Re_name'],
                'Re_id'     =>$master[0]['Re_id'],
                'Re_level'  =>$master[0]['Re_level'],
                'Re_path'   =>$master[0]['Re_path'],
                're_path_id' =>$master[0]['re_path_id'],
                'father_name' =>$params['father_name'],
                'Father_id' =>$params['father_id'],
                'p_level' =>$params['p_level'],
                'p_path' =>$params['p_path'],
                'p_path_id' => $params['p_path_id'],
                'treeplace' => $params['treeplace'],
                'username'=>$master[0]['username']."_".$new_number,
                'center_id' =>$master[0]['center_id'],
                'center_name' => $this->centername($master[0]['center_id']),
                'mobile'  =>$master[0]['mobile'],
                'email'	=>$master[0]['email'],
                'bankcard'=>$master[0]['bankcard'],
                'bankname'=>$master[0]['bankname'],
                'password'=>$master[0]['password'],
                'nickname'=>$master[0]['nickname'],
                'mibaowenti'=>$master[0]['mibaowenti'],
                'mibaodaan'=>$master[0]['mibaodaan'],
                'member_rank'=>$master[0]['member_rank'],
                'u_pai'	=> $params['u_pai'],
                'master_id' => $master[0]['id'],
                'status' => $master[0]['status'],
		];

        //被激活者的等级信息
        $check_dis = $this->logicUser->checkMember_rank($master[0]['member_rank']);  
        //激活者的信息
        $p_person = $this->modelMember->where('id',$master[0]['id'])->select(); 
        //判断报单币是否足够激活该星级会员
        
        if($p_person[0]['wallet'] >= $check_dis['baodanbi_co']){
    		$result = $this->modelMember->setInfo($sl);
            //dd($result);
    		$this->modelMember->where('id',$master[0]['id'])->setInc('u_num',1);
/*****************奖励发放****/
            //激活者的等级信息
            $check = $this->logicUser->checkMember_rank($master[0]['member_rank']);

            //获取被激活用户的信息
            $p = $this->modelMember->where('username',$sl['username'])->select();
            
            //直推人信息
            $p_zhi = $this->modelMember->where('id',$p[0]['Re_id'])->select();
            if(count($p_zhi) == 0){
                $p_zhi = $this->modelMember->where('id',$master[0]['id'])->select();
                
            }
          
            //直推人等级信息
            $check_p_zhi = $this->logicUser->checkMember_rank($p_zhi[0]['member_rank']);

            //配送电子币
                $this->modelMember->where('username',$sl['username'])->setInc('dianzibi',$check_dis['CF']);
                //扣除报单中心的报单币（也是激活者）
                $re = [
                    'wallet' => $p_person[0]['wallet'] - $check_dis['baodanbi_co'],
                ];
                $res = $this->modelMember->where('id','=',$p_person[0]['id'])->update($re);

                //直推人数
                $this->modelMember->where('id',$p_zhi[0]['id'])->setInc('zhitui_all',1);

                //更新直推人的奖励
                $re_zhi = [
                    'bonus' => $p_zhi[0]['bonus'] + $check_p_zhi['baodanbi_co'] * $check_p_zhi['tuijian_co'],
                    'all_bonus' =>  $p_zhi[0]['all_bonus'] + $check_p_zhi['baodanbi_co'] * $check_p_zhi['tuijian_co'],
                ];
                
                //直推人获奖更新
                $this->modelMember->where('id',$p_zhi[0]['id'])->update($re_zhi);

                //账单流水记录,记录直推人
                $bill1 = [
                    'user_id' => $p_zhi[0]['id'],
                    'user_name' => $p_zhi[0]['username'],
                    //'activate'  => "-".$check_dis['baodanbi_co'],
                    'tuijian'   => "+".$check_p_zhi['baodanbi_co'] * $check_p_zhi['tuijian_co'],
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
                    'bonus_amount' => $check_p_zhi['baodanbi_co'] * $check_p_zhi['tuijian_co'],
                    'bonus_type'    => '直推奖',
                    'bonus_time'    => date('Y-m-d h:i:s', time()),
                ];
                $this->modelBonusDetail->setInfo($bonus_detail1);

                //增加业绩
                $this->logicUser->addYeji($p[0]['p_path_id'],$p[0]['id'],$check_dis['baodanbi_co']);
                //见点奖发放
                $this->logicUser->jiandianjiang($p[0]['p_path_id']);


/************奖励发放结束****/
    //获取被激活者的推荐人的绝对路径
                $hello = explode(',',$p[0]['Re_path']);
                array_shift($hello);
                array_pop($hello);
                //增加团队总人数
                for($i = 0; $i < count($hello); $i++){
                    $team_all = $this->modelMember->where('username',$hello[$i])->value('team_all');
                    $this->modelMember->where('username',$hello[$i])->update(['team_all'=> $team_all + 1]);
                }

		$url = url('futou/list');
		return $result ? [RESULT_SUCCESS, '复投成功。',$url] : [RESULT_ERROR, $this->modelMember->getError()];
        }else{
            return [RESULT_ERROR,'报单币不足'];
        }
		
	        
	}

     //
    public function getInto($id){
        $member = $this->modelMember->where('id',$id)->select()->toArray();
        $mb = $this->modelMember->where('id',$id)->find();
        //dd($member);
        if ($member[0]['status'] == 2) 
                {
                    session('member_info_forbid', $member);
                    return [RESULT_ERROR, '账号已被锁定，请联系客服。'];
                }
        $auth = ['member_id' => $member[0]['id'], TIME_UT_NAME => TIME_NOW];
        session('member_info2', $member);
        session('mb',$mb);
        session('user_id2',$member[0]['id']);
        session('member_auth2', $auth);
        session('member_auth_sign2', data_auth_sign($auth));
        return [RESULT_SUCCESS, '登录成功', url('index/home1')];
    }
    public function returnInto(){
        $mem = session('member_info2');
        $member = $this->modelMember->where('id',$mem[0]['master_id'])->select();
        $mb = $this->modelMember->where('id',$mem[0]['master_id'])->find();
        if ($member[0]['status'] == 2) 
                {
                    session('member_info_forbid', $member);
                    return [RESULT_ERROR, '账号已被锁定，请联系客服。'];
                }
        $auth = ['member_id' => $member[0]['id'], TIME_UT_NAME => TIME_NOW];
        session('member_info2', $member);
        session('mb',$mb);
        session('user_id2',$member[0]['id']);
        session('member_auth2', $auth);
        session('member_auth_sign2', data_auth_sign($auth));
        return [RESULT_SUCCESS, '登录成功', url('index/home1')];
    }
    public function gather(){
        $member_info2 = session('member_info2');
        $sl_baodanbi = $this->modelMember->where('master_id',$member_info2[0]['id'])
                                        ->where('status','=',1)
                                        ->sum('wallet');

        $sl_bonus = $this->modelMember->where('master_id',$member_info2[0]['id'])
                                        ->where('status','=',1)
                                        ->sum('bonus');
        $this->modelMember->where('id',$member_info2[0]['id'])->inc('wallet',$sl_baodanbi)->inc('bonus')->update();
        
        $sl = $this->modelMember->where('master_id',$member_info2[0]['id'])
                                ->where('status','=',1)
                                ->where('wallet >0 OR bonus >0')
                                ->select();
        //dd($sl);
        foreach ($sl as $key => $v) {
            
            $this->bill($v['id'],$v['username'],"-".$v['wallet'],"-".$v['bonus']);
        }

        $this->modelMember->where('master_id',$member_info2[0]['id'])
                            ->where('status','=',1)
                                ->update(['wallet'=>0,'bonus'=>0]);
        $this->bill($v['id'],$v['username'],"+".$sl_baodanbi,"+".$sl_bonus);
        return [RESULT_SUCCESS,'收取成功'];

    }
    //增加账单流水和奖金记录
    public function bill($id,$name,$number,$number2){
                //账单流水记录,记录接点人
                    $bill_ct6 = [
                        'user_id' => $id,
                        'user_name' => $name,
                        'wallet'   => $number,
                        'bonus' =>$number2
                    ];

                    $this->modelBill->setInfo($bill_ct6);
    }


}