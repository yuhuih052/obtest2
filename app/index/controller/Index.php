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

namespace app\index\controller;

/**
 * 前端首页控制器
 */
class Index extends IndexBase
{
    
    // 首页
    public function index($cid = 0)
    {
        
        $where = [];
        
        !empty((int)$cid) && $where['a.category_id'] = $cid;
        
        $this->assign('article_list', $this->logicArticle->getArticleList($where, 'a.*,m.nickname,c.name as category_name', 'a.create_time desc'));
        
        $this->assign('category_list', $this->logicArticle->getArticleCategoryList([], true, 'create_time asc', false));
        
        return $this->fetch('index');
    }
    
    // 详情
    public function details($id = 0)
    {
        
        $where = [];
        
        !empty((int)$id) && $where['a.id'] = $id;
        
        $data = $this->logicArticle->getArticleInfo($where);
        
        $this->assign('article_info', $data);
        
        $this->assign('category_list', $this->logicArticle->getArticleCategoryList([], true, 'create_time asc', false));
        
        return $this->fetch('details');
    }

    public function home1(){
        $data = $this->logicIndex->indexnew();
        //dd($data[0]['content']);
        //echo '<script language="JavaScript">;alert($data[0]["content"]);location.href="home.html";</script>;';
        $this->assign('list',$data[0]['content']);
        return $this->fetch('home1');
    }
    public function home(){
        return view();
    }
}
