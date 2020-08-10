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
 * 会员逻辑
 */
use app\admin\logic\ActivateMember;

class Member extends AdminBase
{
    
    /**
     * 获取会员信息
     */
    public function getMemberInfo($where = [], $field = true)
    {
        
        $info = $this->modelMember->getInfo($where, $field);
        
        $info['leader_nickname'] = $this->modelMember->getValue(['id' => $info['leader_id']], 'nickname');
        
        return $info;
    }
    
    /**
     * 获取会员列表
     */
    public function getMemberList($where = [], $field = 'm.*,b.nickname as leader_nickname', $order = '', $paginate = DB_LIST_ROWS)
    {
        
        $this->modelMember->alias('m');
        
        $join = [
                    [SYS_DB_PREFIX . 'member b', 'm.leader_id = b.id', 'LEFT'],
                ];
        
        $where['m.' . DATA_STATUS_NAME] = ['neq', DATA_DELETE];
        
        $this->modelMember->join = $join;
        
        return $this->modelMember->getList($where, $field, $order, $paginate);
    }
    
    /**
     * 导出会员列表
     */
    public function exportMemberList($where = [], $field = 'm.*,b.nickname as leader_nickname', $order = '')
    {
        
        $list = $this->getMemberList($where, $field, $order, false);
        
        $titles = "昵称,用户名,邮箱,手机,注册时间,上级";
        $keys   = "nickname,username,email,mobile,create_time,leader_nickname";
        
        action_log('导出', '导出会员列表');
        
        export_excel($titles, $keys, $list, '会员列表');
    }
    
    /**
     * 获取会员列表搜索条件
     */
    public function getWhere($data = [])
    {
        
        $where = [];
        
        !empty($data['search_data']) && $where['m.nickname|m.username|m.email|m.mobile'] = ['like', '%'.$data['search_data'].'%'];
        
        if (!is_administrator()) {
            
            $member = session('member_info');
            
            if ($member['is_share_member']) {
                
                $ids = $this->getInheritMemberIds(MEMBER_ID);
                
                $ids[] = MEMBER_ID;
                
                $where['m.leader_id'] = ['in', $ids];
                
            } else {
                
                $where['m.leader_id'] = MEMBER_ID;
            }
        }
        
        return $where;
    }
    
    /**
     * 获取存在继承关系的会员ids
     */
    public function getInheritMemberIds($id = 0, $data = [])
    {
        
        $member_id = $this->modelMember->getValue(['id' => $id, 'is_share_member' => DATA_NORMAL], 'leader_id');
        
        if (empty($member_id)) {
            
            return $data;
        } else {
            
            $data[] = $member_id;
            
            return $this->getInheritMemberIds($member_id, $data);
        }
    }
    
    /**
     * 获取会员的所有下级会员
     */
    public function getSubMemberIds($id = 0, $data = [])
    {
        
        $member_list = $this->modelMember->getList(['leader_id' => $id], 'id', 'id asc', false);
        
        foreach ($member_list as $v)
        {
            
            if (!empty($v['id'])) {
                
                $data[] = $v['id'];
            
                $data = array_unique(array_merge($data, $this->getSubMemberIds($v['id'], $data)));
            }
            
            continue;
        }
            
        return $data;
    }
    
    /**
     * 会员添加到用户组
     */
    public function addToGroup($data = [])
    {
        
        $url = url('memberList');
        
        if (SYS_ADMINISTRATOR_ID == $data['id']) {
            
            return [RESULT_ERROR, '天神不能授权哦~', $url];
        }
        
        $where = ['member_id' => ['in', $data['id']]];
        
        $this->modelAuthGroupAccess->deleteInfo($where, true);
        
        if (empty($data['group_id'])) {
            
            return [RESULT_SUCCESS, '会员授权成功', $url];
        }
        
        $add_data = [];
        
        foreach ($data['group_id'] as $group_id) {
            
            $add_data[] = ['member_id' => $data['id'], 'group_id' => $group_id];
        }
        
        if ($this->modelAuthGroupAccess->setList($add_data)) {
            
            action_log('授权', '会员授权，id：' . $data['id']);
        
            $this->logicAuthGroup->updateSubAuthByMember($data['id']);
            
            return [RESULT_SUCCESS, '会员授权成功', $url];
        } else {
            
            return [RESULT_ERROR, $this->modelAuthGroupAccess->getError()];
        }
    }
    
    /**
     * 会员添加
     */
    public function memberAdd($data = [])
    {
        
        $validate_result = $this->validateMember->scene('add')->check($data);
        
        if (!$validate_result) {
            
            return [RESULT_ERROR, $this->validateMember->getError()];
        }
        
        $url = url('memberList');
        
        $data['nickname']  = $data['username'];
        $data['leader_id'] = MEMBER_ID;
        $data['is_inside'] = DATA_NORMAL;
        
        $data['password'] = data_md5_key($data['password']);
        
        $result = $this->modelMember->setInfo($data);
        
        $result && action_log('新增', '新增会员，username：' . $data['username']);
        
        return $result ? [RESULT_SUCCESS, '会员添加成功', $url] : [RESULT_ERROR, $this->modelMember->getError()];
    }
    
    /**
     * 会员编辑
     */
    public function memberEdit($data = [])
    {
        
        $validate_result = $this->validateMember->scene('edit')->check($data);
        
        if (!$validate_result) {
            
            return [RESULT_ERROR, $this->validateMember->getError()];
        }
        
        $url = url('memberList');
        
        $result = $this->modelMember->setInfo($data);
        
        $result && action_log('编辑', '编辑会员，id：' . $data['id']);
        
        return $result>0 ? [RESULT_SUCCESS, '会员编辑成功', $url] : [RESULT_ERROR, $this->modelMember->getError()];
    }

    public function acSave($data){
       
        $uu_status = $this->modelMember->where('username',$data['username'])->value('status');
        if($uu_status == 0 && $data['status'] ==1){
            //获取被激活用户的信息
        $p = $this->modelMember->where('username',$data['username'])->select();
        
        //直推人信息
        $p_zhi = $this->modelMember->where('id',$p[0]['Re_id'])->select();
        //dd($p_zhi);
        //被激活者的等级信息
        $check_dis = $this->logicActivateMember->checkMember_rank($p[0]['member_rank']);
        
        //直推人等级信息
        $check_p_zhi = $this->logicActivateMember->checkMember_rank($p_zhi[0]['member_rank']);
        //dd($check_dis);
        //配送电子币
        $this->modelMember->where('id',$p[0]['id'])->setInc('dianzibi',$check_dis['CF']);
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
        $this->logicActivateMember->addYeji($p[0]['p_path_id'],$p[0]['id'],$check_dis['baodanbi_co']);

        //见点奖发放
        //获取被激活者推荐人的绝对路径id
        $p_id = explode(',', $p[0]['p_path_id']);
        array_shift($p_id);
        array_pop($p_id);
        $ct = 0;
        for($last_id = count($p_id)-1; $last_id>=0 && $last_id>$last_id-25;$last_id--){
            
            $ct++;
            $p_id_info = $this->modelMember->where('id',$p_id[$last_id])->select();
            $p_id_rank = $this->logicActivateMember->checkMember_rank($p_id_info[0]['member_rank']);
            //dd($p_id_rank);
            //应获奖
            $p_id_rank_bonus =  $p_id_rank['baodanbi_co'] * $p_id_rank['jiandian_co'];
            //dd($p_id_rank_bonus);
            //当日累计奖金
            $bonus_day = $this->logicActivateMember->checkBonus_day($p_id[$last_id]);
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
            //增加账单流水和奖金记录
            $this->logicActivateMember->bill_bonus($p_id_info[0]['id'],$p_id_info[0]['username'],$p_id_rank_bonus,$type ='见点奖');
            }

        }else if ($ct<9) {
            if($p_id_info[0]['member_rank'] > 1){
                $ct9 = [
                'bonus' => $p_id_info[0]['bonus'] + $p_id_rank_bonus,
                'all_bonus' => $p_id_info[0]['all_bonus'] + $p_id_rank_bonus,
                ];
                if($D_value3 > $p_id_rank_bonus){
            $this->modelMember->where('id',$p_id[$last_id])->update($ct9);
            //增加账单流水和奖金记录
            $this->logicActivateMember->bill_bonus($p_id_info[0]['id'],$p_id_info[0]['username'],$p_id_rank_bonus,$type ='见点奖');
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
            //增加账单流水和奖金记录
            $this->logicActivateMember->bill_bonus($p_id_info[0]['id'],$p_id_info[0]['username'],$p_id_rank_bonus,$type ='见点奖');
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
            //增加账单流水和奖金记录
            $this->logicActivateMember->bill_bonus($p_id_info[0]['id'],$p_id_info[0]['username'],$p_id_rank_bonus,$type ='见点奖');
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
            //增加账单流水和奖金记录
            $this->logicActivateMember->bill_bonus($p_id_info[0]['id'],$p_id_info[0]['username'],$p_id_rank_bonus,$type ='见点奖');
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
            $this->logicActivateMember->bill_bonus($p_id_info[0]['id'],$p_id_info[0]['username'],$p_id_rank_bonus,$type ='见点奖');
                }
            }
            continue;
        }
      /**************见点奖发放结束************/    
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
        $url = url('memberList');
            $dio = [
                'status' =>$data['status'],
                'activate_time'=> date('Y-m-d h:i:s', time()),
                'ac_time'=> time(),
            ];
            $result = $this->modelMember->where('username',$data['username'])->update($dio);
            return $result ? [RESULT_SUCCESS, '会员激活成功', $url] : [RESULT_ERROR, $this->modelMember->getError()];
        }else{
            $url = url('memberList');
            $result = $this->modelMember->where('username',$data['username'])->update(['status'=>$data['status']]);
            return $result ? [RESULT_SUCCESS, '会员编辑成功', $url] : [RESULT_ERROR, $this->modelMember->getError()];
        }

    }
    
    /**
     * 修改密码
     */
    public function editPassword($data = [])
    {
        
        $validate_result = $this->validateMember->scene('password')->check($data);
        
        if (!$validate_result) {
            
            return [RESULT_ERROR, $this->validateMember->getError()];
        }
        
        $member = $this->getMemberInfo(['id' => $data['id']]);
        
        if (data_md5_key($data['old_password']) != $member['password']) {
            
            return [RESULT_ERROR, '旧密码输入不正确'];
        }
        
        $data['id'] = MEMBER_ID;
        
        $url = url('index/index');
        
		$data['password'] = data_md5_key($data['password']);
		
        $result = $this->modelMember->setInfo($data);
        
        $result && action_log('编辑', '会员密码修改，id：' . $data['id']);
        
        return $result ? [RESULT_SUCCESS, '密码修改成功', $url] : [RESULT_ERROR, $this->modelMember->getError()];
    }
    
    /**
     * 设置会员信息
     */
    public function setMemberValue($where = [], $field = '', $value = '')
    {
        
        return $this->modelMember->setFieldValue($where, $field, $value);
    }
    
    /**
     * 会员删除
     */
    public function memberDel($where = [])
    {
        
        $url = url('memberList');
        
        if (SYS_ADMINISTRATOR_ID == $where['id'] || MEMBER_ID == $where['id']) {
            
            return [RESULT_ERROR, '天神和自己不能删除哦~', $url];
        }
        
        $result = $this->modelMember->deleteInfo($where);
                
        $result && action_log('删除', '删除会员，where：' . http_build_query($where));
        
        return $result ? [RESULT_SUCCESS, '会员删除成功', $url] : [RESULT_ERROR, $this->modelMember->getError(), $url];
    }

    //报单中心
    public function request_is_centerList($where = [], $field = true, $order = '', $paginate = 20)
    {
        $data = $this->modelMember->where('re_is_center','>',0)->select();
        //dump($data);
        return $data;
    }
    public function requestAgree($data){
       
        $url = url('memberList');
        $result = $this->modelMember->where('id',$data['id'])->update(['is_center' => $data['re_is_center'],'re_is_center'=> 0]);
        return [RESULT_SUCCESS,'已同意',$url];
    }
    public function requestDisagree($data){
        $url = url('memberList');
        $result = $this->modelMember->where('id',$data['id'])->update(['re_is_center' => 0]);
        return [RESULT_SUCCESS,'已拒绝',$url];
    }

    //会员搜索
    public function searchUser($data){
        
        if(empty($data['select']) ){
            return Null;
        }else {
            $re = $data['select'];
            switch ($re) {
                case 1:
                    return $this->modelMember->where('username',$data['search'])->select();
                
                case 2:
                    return $this->modelMember->where('Re_name',$data['search'])->select();
                    
                case 3:
                    $id = $this->modelMember->where('username',$data['search'])->value('id');
                    return $this->modelMember->where('center_id',$id)->select();
                case 4:
                    return $this->modelMember->where('baodan','<>',0)->select();
                case 5:
                    return $this->modelMember->where('create_time', 'between time', [$data['date1'], $data['date2']])->select();
                case 6:
                    $date1 = $this->modelMember->where('member_number',$data['Id1'])->value('create_time');
                    $date2 = $this->modelMember->where('member_number',$data['Id2'])->value('create_time');

                    return $result = $this->modelMember->where('create_time', 'between time', [$date1, $date2])->select();

                default:
                    return Null;
                    break;
            }

        }
    }
/**会员查询结束*/

    

}
