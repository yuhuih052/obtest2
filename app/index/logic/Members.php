<?php
namespace app\index\logic;

/**
 * 
 */
class Members extends IndexBase
{
	
	
    public function teamTree($root_id){
    	$uid = session('user_id2');
        $member = $this->modelMember->find($uid);
        //$member = session('member_info');
        $icon_has = '/module/index/images/center.gif';
        $icon_no = '/module/index/images/center.gif';

        $root_id = $root_id ? $root_id : $member->username;
        if ($root_id == $member->username) {
            $member2 = $member;
        }else{
            $member2 = $this->modelMember->where('username', '=', $root_id)->find();
            if (!$member2) {
                return ['root' => $member, 'data' => json_encode([])];
                // return [RESULT_ERROR, '没有该会员！'];
            }
            if (strpos($member2->re_path_id, ','.$member->id.',') === false) {
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
        $like = $member2->re_path_id ? $member2->re_path_id.$member2->id.',' : ','.$member2->id.',';
        //dd($root);
        return [
            'root' => $member2,
            'data' => json_encode([$root]),
        ];
    }

    public function getChildrenMembers($pid){
        $member = $this->modelMember->find(2);
        $icon_has = '/module/index/images/center.gif';
        $icon_no = '/module/index/images/center.gif';

        $member2 = $this->modelMember->where('id', $pid)->find();
        if (!$member2) {
            return [RESULT_ERROR, '没有该会员！'];
        }
        if (strpos($member2->re_path_id, ','.$member->id.',') === false) {
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
            if ($vo->zhitui_all) {
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
        $name .= '<span class="ztree-username">' . $member->username . '</span>';
//         $name .= '<span class="ztree-realname">[' . $member->mobile . ']</span>';
        // $name .= '<span class="ztree-status"> . $status[$member->getData('status')] . ']</span>';
        // $name .= '<span class="ztree-status">[' . $member->team . ']</span>';
        // $name .= '<span class="ztree-rank">' . $member->rank_name_html . '</span>';
        // $name .= '<span class="ztree-paidan">[总钱包:' . $member->b1 . ']</span></span>';
        
        //$name .= '<span class="ztree-team">[总计:' . (floatval($member->shichang_u)) . ']</span></span>';
        //$name .= '<span class="ztree-team">[会员累计投资:' . (floatval($member->leiji_u)) . ']</span></span>';
        //$name .= '<span class="ztree-team">[伞下累计投资:' . (floatval($member->sanxia_leiji_u)) . ']</span></span>';
        return $name;
    }




    ///////////////////////////////////////////////////////////////////////////////////////////////
    public function teamTree2($root_id){
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
                 'username' => $vo->username,
                // 'realname' => $vo->realname,
                // 'rank' => $vo->rank,
                // 'paidan' => $vo->b0,
                'name' => $this->getZtreeName2($vo),
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

    public function getChildrenMembers2($pid){
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

    private function getZtreeName2($member)
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

}