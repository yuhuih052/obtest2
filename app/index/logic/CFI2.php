<?php
namespace app\index\logic;

/**
 * 
 */
class CFI2 extends IndexBase
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

		if($data['cfi_amount']+$member['CFI'] > $member_rank['CFI_split']){
			return [RESULT_SUCCESS,'当前会员等级最多只能持有'.$member_rank['CFI_split']];
		}
		$pay = $this->price()->cfi_price * $data['cfi_amount'];
		if($pay > $member['dianzibi'] ){
			return [RESULT_SUCCESS,'电子币余额不足'];
		}
		//距离涨价的剩余交易量
		$rise_capacity = 15000 - $this->price()->deal;
		//距离涨价的剩余交易量足够时
		if($rise_capacity >= $data['cfi_amount']){
			//更新个人账户信息
			$this->modelMember->where('id',$id)->dec('dianzibi',$pay)->inc('CFI',$data['cfi_amount'])->update();
			//记录账单流水
			$this->bill($id,$member['username'],'dianzibi_all',$pay);
			//成交量刚好达到涨价时
			$cfi_total = $this->price()->cfi_total - $data['cfi_amount'];
			if($rise_capacity == $data['cfi_amount']){
				//涨价达到拆分条件
				if($this->price()->cfi_price +0.1 == 4){
					$this->splitCfi();
				}else{
				//没达到拆分条件
				$cfi_price = $this->price()->cfi_price +0.1;
				$deal = 0;
				$this->priceUd($cfi_price,$deal,$cfi_total);
				}
				//成交量没达到涨价
			}else{
				$cfi_price = $this->price()->cfi_price;
				$deal = $this->price()->deal + $data['cfi_amount'];
				$this->priceUd($cfi_price,$deal,$cfi_total);
			}
			return [RESULT_SUCCESS,'购买成功'];
		}else{//距离涨价的剩余交易量不足时，先成交一部分，涨价后再成交剩余部分
			
			//应该以当前价格交易的数量的支付价格
			$now_pay = $rise_capacity * $this->price()->cfi_price; 			 //需要支付
			//刷新价格表
			$cfi_price = $this->price()->cfi_price+0.1;
			$deal = 0;
			$cfi_total = $this->price()->cfi_total - $rise_capacity;
			$this->priceUd($cfi_price,$deal,$cfi_total);
			//升级后还能购买量
			$over_pay = $pay - $now_pay;
			$after_amount = floor($over_pay / $this->price()->cfi_price);
			if($after_amount >=15000){
				if($this->price()->cfi_price + 0.1 <4){
					
				}
				$cfi_price = $this->price()->cfi_price+0.1;
				$deal = 0;
				$cfi_total = $this->price()->cfi_total - $rise_capacity;
				$this->priceUd($cfi_price,$deal,$cfi_total);

			}else{

				$cfi_price = $this->price()->cfi_price;
				$deal = $after_amount;
				$cfi_total = $this->price()->cfi_total - $after_amount;
				$this->priceUd($cfi_price,$deal,$cfi_total);
				$after_pay = $this->price()->cfi_price *$after_amount;
				$this->modelMember->where('id',$id)->inc('CFI',$rise_capacity+$after_amount)
															->dec('dianzibi',$now_pay+$after_pay)->update();
				$this->bill($id,$member['username'],'dianzibi_all',$now_pay+$after_pay);
				return [RESULT_SUCCESS,'购买成功'];
			}


			$cfi_price_ke = $this->price()->cfi_price + 0.1;
			//购买没达到拆分
			if($cfi_price_ke < 4){
				$cfi_price = $this->price()->cfi_price+0.1;
				$deal = 0;
				$cfi_total = $this->price()->cfi_total - $rise_capacity;
				$this->priceUd($cfi_price,$deal,$cfi_total);
				
				//剩余支付所能购买的cfi数量
				$over_pay = $pay - $now_pay;
				$after_amount = floor($over_pay / $this->price()->cfi_price);
					if($after_amount < 15000){//这时候不可能出现拆分
						$after_pay = $after_amount * $this->price()->cfi_price;
						$this->modelMember->where('id',$id)->inc('CFI',$rise_capacity+$after_amount)
															->dec('dianzibi',$now_pay+$after_pay)->update();
						$this->bill($id,$member['username'],'dianzibi_all',$now_pay+$after_pay);

						$cfi_price = $this->price()->cfi_price;
						$deal = $this->price()->deal + $after_amount;
						$cfi_total = $this->price()->cfi_total - $rise_capacity;
						$this->priceUd($cfi_price,$deal,$cfi_total);
						return [RESULT_SUCCESS,'购买成功'];
					}elseif($after_amount <= 30000 && $after_amount >=15000){//有可能出现拆分
						$now_new_price = $this->price()->cfi_price + 0.1;
						//如果第二轮购买没达到拆分
						if($now_new_price < 4){
							$after_pay = $this->price()->cfi_price *15000;
							$cfi_price = $now_new_price;
							$deal = 0;
							$cfi_total = $this->price()->cfi_total - 15000;
							$this->priceUd($cfi_price,$deal,$cfi_total);
							
							//购买第二轮15000后剩余的购买力
							$over_tt_pay = $over_pay - $tt_pay;
							$last_amount = floor($over_tt_pay / $now_new_price);
							$tt_pay = $last_amount * $this->price()->cfi_price;
							$this->modelMember->where('id',$id)->inc('CFI',$rise_capacity+15000+$last_amount)
															->dec('dianzibi',$now_pay+$after_pay+$tt_pay)->update();
							$this->bill($id,$member['username'],'dianzibi_all',$now_pay+$after_pay+$tt_pay);
							return [RESULT_SUCCESS,'购买成功'];
						}else{//如果购买第二轮15000后达到拆分条件
							$cfi_price = $now_new_price;
							$deal = 0;
							$cfi_total = $this->price()->cfi_total - 15000;
							$this->priceUd($cfi_price,$deal,$cfi_total);
							
							$this->splitCfi();
							$userCfi = $this->info()->CFI;
							if($member_rank['CFI_split'] == $userCfi){
								$this->modelMember->where('id',$id)->dec('dianzibi',$now_pay+15000*3.9)->update();
								$this->bill($id,$member['username'],'dianzibi_all',$now_pay+15000*3.9);
								return [RESULT_SUCCESS,'购买成功'];
							}
							//剩余需求量
							$tt_amount = $after_amount - 15000;
							//账户剩余所能购买量
							$user_tt_amount = $member_rank['CFI_split'] - $userCfi;
							
							$tt_pay = 15000*3.9;
							 //购买第二轮15000后剩余的购买力
							$over_tt_pay = $over_pay - $tt_pay;
							//剩余购买力能购买的数量
							$tt_pay_amount = floor($over_tt_pay / 2);
							//01
							if($tt_amount >= $user_tt_amount){//需求量大于账户剩余购买量
								//目前还需要支付
								$in_now_pay = $user_tt_amount * 2;
								//购买力足够
								if($over_tt_pay >= $in_now_pay){ 
									$this->modelMember->where('id',$id)->inc('CFI',$rise_capacity+15000+$user_tt_amount)
																		->dec('dianzibi',$now_pay+15000*3.9+$in_now_pay)
																		->update();
									$this->bill($id,$member['username'],'dianzibi_all',$now_pay+15000*3.9+$in_now_pay);
									$cfi_price = $this->price()->cfi_price;
									$deal = $this->price()->deal + $user_tt_amount;
									$cfi_total = $this->price()->cfi_total - $user_tt_amount;
									$this->priceUd($cfi_price,$deal,$cfi_total);
									return [RESULT_SUCCESS,'购买成功'];
								}else{//购买力不够时
									$a = $tt_pay_amount * 2;

									$this->modelMember->where('id',$id)->inc('CFI',$rise_capacity+15000+$tt_pay_amount)
																		->dec('dianzibi',$now_pay+15000*3.9+$a)
																		->update();
									$this->bill($id,$member['username'],'dianzibi_all',$now_pay+15000*3.9+$a);
									$cfi_price = $this->price()->cfi_price;
									$deal = $this->price()->deal + $tt_pay_amount;
									$cfi_total = $this->price()->cfi_total - $tt_pay_amount;
									$this->priceUd($cfi_price,$deal,$cfi_total);
									return [RESULT_SUCCESS,'购买成功'];
								}


							}else{//需求量小于账户剩余购买量
								$in_now_pay = $tt_amount *2;

								if($over_tt_pay >= $in_now_pay){ 
									$this->modelMember->where('id',$id)->inc('CFI',$rise_capacity+15000+$tt_amount)
																		->dec('dianzibi',$now_pay+15000*3.9+$in_now_pay)
																		->update();
									$this->bill($id,$member['username'],'dianzibi_all',$now_pay+15000*3.9+$in_now_pay);
									$cfi_price = $this->price()->cfi_price;
									$deal = $this->price()->deal + $tt_amount;
									$cfi_total = $this->price()->cfi_total - $tt_amount;
									$this->priceUd($cfi_price,$deal,$cfi_total);
									return [RESULT_SUCCESS,'购买成功'];
								}else{
									$a = $tt_pay_amount * 2;

									$this->modelMember->where('id',$id)->inc('CFI',$rise_capacity+15000+$$tt_pay_amount)
																		->dec('dianzibi',$now_pay+15000*3.9+$a)
																		->update();
									$this->bill($id,$member['username'],'dianzibi_all',$now_pay+15000*3.9+$a);
									$cfi_price = $this->price()->cfi_price;
									$deal = $this->price()->deal + $tt_pay_amount;
									$cfi_total = $this->price()->cfi_total - $tt_pay_amount;
									$this->priceUd($cfi_price,$deal,$cfi_total);
									return [RESULT_SUCCESS,'购买成功'];
								}

							}
							//01
						}


					}else{//买完第一轮后还需要购买数量超过30000
						$now_new_price = $this->price()->cfi_price + 0.1;
						if($now_new_price < 4 ){
							//如果第二轮购买没达到拆分
							$after_pay = 15000 * $this->price()->cfi_price;
							$cfi_price = $now_new_price;
							$deal = 0;
							$cfi_total = $this->price()->cfi_total - 15000;
							$this->priceUd($cfi_price,$deal,$cfi_total);
							//
							$tt_amount = $after_amount - 15000;
							$tt_pay = $tt_amount * $this->price()->cfi_price;
							//购买第二轮15000后剩余的购买力
							$over_tt_pay = $over_pay - $tt_pay;
							$last_amount = floor($over_tt_pay / $now_new_price);
							$now_new_price2 = $this->price()->cfi_price + 0.1;
							if($now_new_price2 < 4){
								if($last_amount > 15000){//买第二轮15000后购买力还够15000时
									$tt_pay = 15000 * $this->price()->cfi_price;
									$cfi_price = $now_new_price2;
									$deal = 0;
									$cfi_total = $this->price()->cfi_total - 15000;
									$this->priceUd($cfi_price,$deal,$cfi_total);
									$l_last_amount = $last_amount -15000;
									//剩余购买力能购买的个数
									$l_last_pay = $pay -$now_pay-$after_pay-$tt_pay;

									$l_last_amounts = floor($l_last_pay / $this->price()->cfi_price);
									//最后付款
									$l_last_amounts_pay = $l_last_amounts * $this->price()->cfi_price;
									$this->modelMember->where('id',$id)->inc('CFI',$rise_capacity+30000+$l_last_amounts)
															->dec('dianzibi',$now_pay+$after_pay+$tt_pay+$l_last_amounts_pay)->update();
									$this->bill($id,$member['username'],'dianzibi_all',$now_pay+$after_pay+$tt_pay);
									return [RESULT_SUCCESS,'购买成功'];
								}else{//买第二轮15000后购买力，不够15000时
									
									$cfi_price = $this->price()->cfi_price;
									$deal = $last_amount;
									$cfi_total = $this->price()->cfi_total - $last_amount;
									$this->priceUd($cfi_price,$deal,$cfi_total);
									
									$l_last_amounts_pay = $last_amounts * $this->price()->cfi_price;
									$this->modelMember->where('id',$id)->inc('CFI',$rise_capacity+15000+$last_amounts)
															->dec('dianzibi',$now_pay+$after_pay+$tt_pay+$l_last_amounts_pay)->update();
									$this->bill($id,$member['username'],'dianzibi_all',$now_pay+$after_pay+$tt_pay);
									return [RESULT_SUCCESS,'购买成功'];
								}

								}else{
									
								}
							}
							
					}
				

			}else{ //购买后达到拆分

			}

		}	

	}
	
	//添加流水账单
	public function bill($id,$name,$type,$number){
		$bill = [
			'user_id' => $id,
			'user_name' => $name,
			$type => "-".$number,
		];
		$this->modelBill->setInfo($bill);
	}
	//cfi交易记录
	public function cfiRecord($id,$name,$type,$number){
		$re = [
			'user_id' => $id,
			'user_name' =>$username,
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