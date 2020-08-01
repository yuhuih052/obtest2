<?php
namespace app\index\logic;

/**
 * 
 */
class CFI extends IndexBase
{
	public function info(){

		return $this->modelMember->where('id',session('user_id2'))->find();
	}
	
	public function price(){
		return $this->modelPrice->where('id',1)->find();
	}

	public function sys_buy($data){
		
		$id = $this->info()->id;
		$member = $this->modelMember->where('id',$id)->find();
		
		//获取会员等级信息
		$member_rank = $this->logicUser->checkMember_rank($member['member_rank']);

		$pay = $data['cfi_amount'];
		if($pay > $member['dianzibi'] ){
			return [RESULT_SUCCESS,'电子币余额不足'];
		}
		$this->modelMember->where('id',$id)->dec('dianzibi',$pay)->update();
		$buyer = $this->modelShop->where('user_id',$id)->find();

		if(empty($buyer)){
			$re =[
				'user_id' => $id,
				'dianzibi' => $pay,
				'create_time' => time(),
			];
			//$this->modelShop->setInfo($re);
			//$this->bill($id,$member['username'],'dianzibi_all',$pay,'挂买CFI');
		}else{
			$re =[
				'dianzibi' => $buyer->dianzibi + $pay,
				'update_time' => time(),
			];
			//$this->modelShop->where('user_id',$id)->update($re);
			//$this->bill($id,$member['username'],'dianzibi_all',$pay,'挂买CFI');
		}
		$this->transaction($id,1);
		return [RESULT_SUCCESS,'挂买成功'];

	}

	public function transaction($id,$status){
		$now_price = $this->price()->cfi_price;
		$buyer_list = $this->modelShop->where('dianzibi','>',0)->select();
		$seller_list = $this->modelShop->where('sell','>',0)->select();
		//当没有用户挂卖时，从系统账户购买
		if(count($seller_list) == 0){
			$buyer = $this->modelShop->where('user_id',$id)->find();
			
		}else{
			foreach ($seller_list as $key => $v) {
			dd($v);
			}
		}
		
		

	}
	
	//添加流水账单
	public function bill($id,$name,$type,$number,$shuoming){
		$bill = [
			'user_id' => $id,
			'user_name' => $name,
			$type => "-".$number,
			'shuoming' =>$shuoming,
		];
		$this->modelBill->setInfo($bill);
	}
	//cfi交易记录
	public function cfiRecord($id,$name,$type,$number){
		$re = [
			'user_id' => $id,
			'user_name' =>$name,
			$type => $number,
		];
		$this->modelCfiRecord->setInfo($re);
	}
	//价格表的更新
	public function priceUd($price,$number,$total){
		$r = [
			'cfi_price' => $price,
			'deal' =>$number,
			'cfi_total' => $total,
			'update_time' => time(),
		];
		$this->modelPrice->where('id',1)->update($r);
	}
	//拆分股票
	public function splitCfi(){
		$a = $this->price()->cfi_price / 2;
		$pre = [
				'cfi_price' =>2,
				'deal' => 0,
				'cfi_total' => $this->price()->cfi_total *$a,
			];
		$this->modelPrice->where('id',1)->update($pre);
		$seller = $this->modelShop->where('sell','>',0)->select();
		foreach ($seller as $key => $va) {
			if($va->status == 1){
				$this->modelShop->where('user_id',$va->user_id)->update(['sell'=>0,'update_time'=>time()]);
				$this->modelMember->where('id',$va->user_id)->dec('CFI',$va->sell)->update();
			}
		}

		$user_split = $this->modelMember->where('CFI','>',0)
										->where('status',1)
										->select();
		foreach ($user_split as $key => $v) {
			//用户拆分封顶
			$v_info_rank = $this->logicUser->checkMember_rank($v->member_rank);
			if($v->CFI *$a <= $v_info_rank['CFI_split']){
				$this->modelMember->where('id',$v->id)->inc('CFI',$v->CFI)->update();
			}else{
				$this->modelMember->where('id',$v->id)->update(['CFI'=>$v_info_rank['CFI_split']]);
				$this->modelPrice->where('id',1)->inc('cfi_total',$v->CFI *$a - $v_info_rank['CFI_split']);
			}
			
		}
		//遍历持有cFi用户结束
				
	}
	//拆分股票结束
	

}