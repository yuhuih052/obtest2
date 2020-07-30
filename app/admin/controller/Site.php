<?php 
namespace app\admin\controller;

/**
 * 
 */
class Site extends AdminBase
{
	public function indexList(){
		//dump($this->param);die;
		$data = $this->logicSite->indexList();
		//dump($data);die;
		$this->assign('list',$data);
		return $this->fetch('site');
	}

	public function siteSys(){
		//dump($this->param);die;
		$this->jump($this->logicSite->siteSys($this->param));

	}
	
}