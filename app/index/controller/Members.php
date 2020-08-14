<?php
namespace app\index\controller;

use think\Hook;
/**
 * 前端首页控制器
 */

class Members extends IndexBase
{
	 public function tree()
    {
        Hook::listen('CheckAuth',$params);
        $this->assign($this->logicMembers->teamTree($this->request->param('root_id', '')));

        return $this->fetch('tree');
    }

    //树形图
    public function teamTree(){
        Hook::listen('CheckAuth',$params);
        $root_id = session('user_id2');
        $this->assign($this->logicMembers->teamTree2($this->request->param('root_id', '')));
        return $this->fetch('team_tree');
    }
    // 获取下级会员
    public function getChildrenMembers(){
        Hook::listen('CheckAuth',$params);
        return $this->jump($this->logicMembers->getChildrenMembers($this->request->param('pid', '0', 'intval')));
    }


}
