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
 * 留言信息控制器
 */
class Messages extends AdminBase
{
    
    /**
     * 留言列表
     */
    public function index()
    {
        //dump($this->param);die;
        $list = $this->logicMessages->MessageList($this->param);
        //dump($list);die;
        $this->assign('list', $list);
        
        return $this->fetch('messages_list');
    }

    public function responseEdit(){
        //dump($this->param);die;
        $data = $this->param;
        $this->logicMessages->responseEdit($data);

        $list = $this->logicMessages->MessageList($this->param);
        //dump($list);die;
        $this->assign('list', $list);
        
        return $this->fetch('messages_list');
    }
    
}