<?php
namespace app\admin\controller;


/**
 * 
 */
class Cfi extends AdminBase
{
/**********************************************/
	 public function sellCfiList(){
		
         return view('sellCfiList');
	 }
	 public function sellCfiList2(){

	 return $this->logicCfi->sellCfiList($this->param);
    
	 }
	 public function buyCfiList(){

         return view('buyCfiList');

	 }
	 public function buyCfiList2(){

	 return $this->logicCfi->buyCfiList($this->param);

	 }

	 //查看交易详情
	 public function dealDetail2(){

	 	$this->assign('id',$this->param['id']);
	 	return $this->fetch('dealDetail2');
	 }
	 //检查是否有详细交易信息
	 public function dealDetail3(){

	 	return $this->logicCfi->dealDetail3($this->param);
	 }
	 //获取详细交易信息
    public function dealDetail4(){

	     return $this->logicCfi->dealDetail4($this->param);
    }
/******************************************/
	public function buyCfi(){
		$data = $this->logicCfi->buyCfi($this->param);
		$this->assign('list',$data);
		return $this->fetch('buycfi');
	}
	public function sellCfi(){
		$data = $this->logicCfi->sellCfi($this->param);
		$this->assign('list',$data);
		return $this->fetch('sellcfi');
	}
	public function dealDetail(){
		$data = $this->logicCfi->dealDetail($this->param);
		$this->assign('list',$data);
		return $this->fetch('dealDetail');
	}
}