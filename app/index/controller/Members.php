<?php
namespace app\index\controller;

/**
 * 前端首页控制器
 */
class Members extends IndexBase
{
	 public function tree()
    {

        $this->assign($this->logicMembers->teamTree($this->request->param('root_id', '')));

        return $this->fetch('tree');
    }

    //树形图
    public function teamTree(){
        $root_id = session('user_id2');
        $this->assign($this->logicMembers->teamTree2($this->request->param('root_id', '')));
        return $this->fetch('team_tree');
    }
    // 获取下级会员
    public function getChildrenMembers(){

        return $this->jump($this->logicMembers->getChildrenMembers($this->request->param('pid', '0', 'intval')));
    }


}
