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

namespace app\admin\controller;

/**
 * 会员控制器
 */
class Member extends AdminBase
{

    /**
     * 会员授权
     */
    public function memberAuth()
    {
        
        IS_POST && $this->jump($this->logicMember->addToGroup($this->param));
        
        // 所有的权限组
        $group_list = $this->logicAuthGroup->getAuthGroupList(['member_id' => MEMBER_ID]);
        
        // 会员当前权限组
        $member_group_list = $this->logicAuthGroupAccess->getMemberGroupInfo($this->param['id']);

        // 选择权限组
        $list = $this->logicAuthGroup->selectAuthGroupList($group_list, $member_group_list);
        
        $this->assign('list', $list);
        
        $this->assign('id', $this->param['id']);
        
        return $this->fetch('member_auth');
    }
    
    /**
     * 会员列表
     */
    public function memberList()
    {
        
        $where = $this->logicMember->getWhere($this->param);
        //dump($where);die;
        $this->assign('list', $this->logicMember->getMemberList($where));
        
        return $this->fetch('member_list');
    }
    
    /**
     * 会员导出
     */
    public function exportMemberList()
    {
        
        $where = $this->logicMember->getWhere($this->param);
        
        $this->logicMember->exportMemberList($where);
    }
    
    /**
     * 会员添加
     */
    public function memberAdd()
    {
        
        IS_POST && $this->jump($this->logicMember->memberAdd($this->param));
        
        return $this->fetch('member_add');
    }
    
    /**
     * 会员编辑
     */
    public function memberEdit()
    {
        
        IS_POST && $this->jump($this->logicMember->memberEdit($this->param));
        
        $info = $this->logicMember->getMemberInfo(['id' => $this->param['id']]);
        
        $this->assign('info', $info);
        
        return $this->fetch('member_edit');
    }
    //会员激活
    public function memberActivate(){
        $info = $this->logicMember->getMemberInfo(['id' => $this->param['id']]);
        
        $this->assign('info', $info);
        
        return $this->fetch('member_activate');
    }
    public function acSave(){
        $this->jump($this->logicMember->acSave($this->param));
    }
    
    /**
     * 会员删除
     */
    public function memberDel($id = 0)
    {
        
        return $this->jump($this->logicMember->memberDel(['id' => $id]));
    }
    
    /**
     * 修改密码
     */
    public function editPassword()
    {
        
        IS_POST && $this->jump($this->logicMember->editPassword($this->param));
        
        $info = $this->logicMember->getMemberInfo(['id' => MEMBER_ID]);
        
        $this->assign('info', $info);
        
        return $this->fetch('edit_password');
    }
    //申请报单中心
    public function request_is_center(){
        $list = $this->logicMember->request_is_centerList($this->param);
        //dump($list);die;
        $this->assign('list', $list);
        
        return $this->fetch('request_is_center_list');
    }
    //同意申请
    public function requestAgree(){
        $this->jump($this->logicMember->requestAgree($this->param));
    }
    //拒绝申请
    public function requestDisagree(){
        $this->jump($this->logicMember->requestDisagree($this->param));
    }
    //搜索用户
    public function searchUser(){
        
        $data = $this->logicMember->searchUser($this->param);
        $this->assign('list',$data);
        return $this->fetch('search_list');

        
    }
}
