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
		$data2 = $this->logicSite->price_table();
		//dump($data);die;
		$this->assign('list',$data);
		$this->assign('list2',$data2);
		return $this->fetch('site');
	}

	public function siteSys(){
		//dump($this->param);die;
		$this->jump($this->logicSite->siteSys($this->param));

	}

	public function cfi_deal(){
		$this->jump($this->logicSite->cfi_deal($this->param));
	}
	
}