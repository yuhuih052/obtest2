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
	public function shop(){

		return $this->modelShop->where('user_id',$this->info()->id)->find();
	}
	//挂买CFI
	public function sys_buy($data){
		
		$id = $this->info()->id;
		$member = $this->modelMember->where('id',$id)->find();
		
		//获取会员等级信息
		$member_rank = $this->logicUser->checkMember_rank($member['member_rank']);
		$data['cfi_amount'] = $data['cfi_amount'] == Null ? 0:$data['cfi_amount'];
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
			$this->modelShop->setInfo($re);
			$this->bill($id,$member['username'],'dianzibi_all',$pay,'挂买CFI');
		}else{
			$re =[
				'dianzibi' => $buyer->dianzibi + $pay,
				'update_time' => time(),
			];
			$this->modelShop->where('user_id',$id)->update($re);
			$this->bill($id,$member['username'],'dianzibi_all',$pay,'挂买CFI');
		}
		$re = $this->transaction($id,1,$member_rank);
		
		switch($re){
			case 1 ;
			return [RESULT_SUCCESS,'交易成功'];break;
			case 2 ;
			return [RESULT_SUCCESS,'交易成功,当前价格已刷新，请留意交易价格,如继续购买请点击继续'];break;
		}
		

	}
	//购买逻辑
	public function transaction($id,$status,$member_rank){
		
		$now_price = $this->price()->cfi_price;
		$cfi_total = $this->price()->cfi_total;
		$buyer_list = $this->modelShop->where('dianzibi','>',0)->select();
		$seller_list = $this->modelShop->where('sell','>',0)->select(); 
		$buyer = $this->modelShop->where('user_id',$id)->find();
		//当前账户挂买需求能购买的个数
		$amount = floor($buyer->dianzibi / $now_price);
		//判断账户目前能购买的最大数
		$b = $member_rank['CFI_split'] - $this->info()->CFI;
		//距离涨价的个数
		$rise_ca = $this->price()->default_deal - $this->price()->deal;
		//当没有用户挂卖且系统账户有剩余，从系统账户购买
		if(count($seller_list) == 0 && $cfi_total >0){
			
			if($rise_ca >= $amount){
				if($cfi_total >$amount){
					if($b >= $amount){
						$now_pay = $amount * $this->price()->cfi_price;
						$this->modelShop->where('user_id',$id)->dec('dianzibi',$now_pay)->update();
						$this->modelMember->where('id',$id)->inc('CFI',$amount)->update();
						$this->priceUd($now_price,$this->price()->deal+$amount,$this->price()->cfi_total - $amount);
						return 1;
					}
					$pp = $b * $now_price;
					$this->modelShop->where('user_id',$id)->dec('dianzibi',$pp)->update();
					$this->modelMember->where('id',$id)->inc('CFI',$b)->update();
					$this->priceUd($now_price,$this->price()->deal+$b,$this->price()->cfi_total - $b);
					return 1;
				}else{
					if($b >= $cfi_total){
						$this->modelShop->where('user_id',$id)->dec('daiznibi',$now_price * $cfi_total)->update();
						$this->modelMember->where('id',$id)->inc('CFI',$cfi_total)->update();
						$this->priceUd($now_price,$this->price()->deal+$cfi_total,$this->price()->cfi_total - $cfi_total);
						return 1;
					}
					$pp = $b * $now_price;
					$this->modelShop->where('user_id',$id)->dec('dianzibi',$pp)->update();
					$this->modelMember->where('id',$id)->inc('CFI',$b)->update();
					$this->priceUd($now_price,$this->price()->deal+$b,$this->price()->cfi_total-$b);
					return 1;
				}
			}else{
				$after_buy = $amount - $rise_ca;
				//如果账户接近封顶数
				if($b < $rise_ca){
					$pp = $b * $now_price;
					$this->modelShop->where('user_id',$id)->dec('dianzibi',$pp)->update();
					$this->modelMember->where('id',$id)->inc('CFI',$b)->update();
					$this->priceUd($now_price,$this->price()->deal+$b,$this->price()->cfi_total-$b);
					return 1;
				}
				//先结算未涨价部分
				$this->modelShop->where('user_id',$id)->dec('daiznibi',$now_price * $rise_ca)->update();
				$this->modelMember->where('id',$id)->inc('CFI',$rise_ca)->update();
				
				//刷新购买者账户信息
				$buyer = $this->modelShop->where('user_id',$id)->find();
				//
				
				//结算后，价格上涨0.1；
				$this->priceUd($now_price +0.1,$this->price()->deal+$rise_ca,$this->price()->cfi_total - $rise_ca);
				//判断涨价后是否达到拆分条件
				if($this->price()->cfi_price >=4){
					$this->splitCfi();
					return 2;
				}else{//没达到拆分条件
					//账户当前能购买的数量
					$after_amount = floor($buyer->dianzibi / $this->price()->cfi_price);
					//距离涨价的个数
					$rise_ca = $this->price()->default_deal - $this->price()->deal;
					$now_price = $this->price()->cfi_price;
					$cfi_total = $this->price()->cfi_total;
					//再次交易
					if($cfi_total >$after_amount){
						//如果账户接近封顶数
						if($b < $after_amount){
							$pp = $b * $now_price;
							$this->modelShop->where('user_id',$id)->dec('dianzibi',$pp)->update();
							$this->modelMember->where('id',$id)->inc('CFI',$b)->update();
							$this->priceUd($now_price,$this->price()->deal+$b,$this->price()->cfi_total-$b);
							return 1;
						}
						$this->modelShop->where('user_id',$id)->update(['dianzibi'=>0]);
						$this->modelMember->where('id',$id)->inc('CFI',$after_amount)->update();
						$this->priceUd($now_price,$this->price()->deal+$after_amount,$this->price()->cfi_total-$after_amount);
						return 2;
					}else{
						//如果账户接近封顶数
						if($b < $cfi_total){
							$pp = $b * $now_price;
							$this->modelShop->where('user_id',$id)->dec('dianzibi',$pp)->update();
							$this->modelMember->where('id',$id)->inc('CFI',$b)->update();
							$this->priceUd($now_price,$this->price()->deal+$b,$this->price()->cfi_total-$b);
							return 1;
						}
							$this->modelShop->where('user_id',$id)->dec('daiznibi',$now_price * $cfi_total)->update();
							$this->modelMember->where('id',$id)->inc('CFI',$cfi_total)->update();
							$this->priceUd($now_price,$this->price()->deal+$cfi_total,$this->price()->cfi_total-$cfi_total);
						return 2;
					}

				}
			}

			//交易市场有人挂卖时
		}elseif(!count($seller_list) == 0){//有人挂卖时
			foreach ($seller_list as $key => $v) {
				
				if($amount >= $v->sell){//购买需求大于当前挂卖数量
					if($rise_ca > $v->sell && $rise_ca  > $amount){//涨价空间足够完成这笔交易时 A1
						if($b >= $v->sell){//账户能购买的数量足够时
							
							$this->modelMember->where('id',$v->user_id)->inc('bonus',$v->sell * $now_price*0.54)
																		->inc('wallet',$v->sell * $now_price*0.27,)
																		->inc('baoguanjin',$v->sell * $now_price*0.09,)
																		->update();
							$this->modelMember->where('id',$id)->inc('CFI',$v->sell)->update();
							$this->modelShop->where('user_id',$v->user_id)->dec('sell',$v->sell)->update();
							$this->modelShop->where('user_id',$id)->dec('dianzibi',$v->sell * $now_price)->update();
							$this->priceUd($now_price,$this->price()->deal+$v->sell,$this->price()->cfi_total);
							return 1;
						}else{//账户能购买的数量不够时
																	
							$this->modelMember->where('id',$v->user_id)->inc('bonus',$b * $now_price*0.54)
																	->inc('wallet',$b * $now_price*0.27,)
																	->inc('baoguanjin',$b * $now_price*0.09,)
																	->update();
							$this->modelMember->where('id',$id)->inc('CFI',$b)->update();
							$this->modelShop->where('user_id',$v->user_id)->dec('sell',$b)->update();
							$this->modelShop->where('user_id',$id)->dec('dianzibi',$b * $now_price)->update();
							$this->priceUd($now_price,$this->price()->deal+$b,$this->price()->cfi_total);
							return 1;
						}
						
					}else{//涨价空间不够完成这笔交易时 A1
						
						//首先完成未涨价部分的交易
						if($b >= $rise_ca){//账户能购买的数量足够时
							$now_pay = $rise_ca *$now_price;
							$this->modelShop->where('user_id',$id)->dec('dianzibi',$now_pay)->update();
							$this->modelMember->where('id',$id)->inc('CFI',$rise_ca)->update();
							$this->modelShop->where('user_id',$v->user_id)->dec('sell',$rise_ca)->update();
							
							$this->modelMember->where('id',$v->user_id)->inc('bonus',$now_pay * 0.54)
																		->inc('wallet',$now_pay * 0.27,)
																		->inc('baoguanjin',$now_pay *0.09,)
																		->update();
							$this->priceUd($now_price+0.1,$this->price()->deal+$cfi_total,$this->price()->cfi_total);
							$v = $this->modelShop->where('user_id',$v->user_id)->find();
						}else{//账户能购买的数量不够时
							$now_pay = $b *$now_price;
							$this->modelShop->where('user_id',$id)->dec('dianzibi',$now_pay)->update();
							$this->modelMember->where('id',$id)->inc('CFI',$b)->update();
							$this->modelShop->where('user_id',$v->user_id)->dec('sell',$b)->update();
							$this->modelMember->where('id',$v->user_id)->inc('bonus',$now_pay * 0.54)
																	->inc('wallet',$now_pay * 0.27,)
																	->inc('baoguanjin',$now_pay *0.09,)
																	->update();
							$this->priceUd($now_price+0.1,$this->price()->deal+$b,$this->price()->cfi_total);
							return 1;
						}
							//进行涨价后进行交易
							if($this->price()->cfi_price >= 4){
								$this->splitCfi();
								return 2;
							}
						//当前账户挂买电子币剩余
						$buyer_dianzibi = $buyer->dianzibi - $now_pay;
						$after_amount = floor($buyer_dianzibi / $this->price()->cfi_price);
						//涨价后距离下一次涨价的个数
						$rise_ca = $this->price()->default_deal - $this->price()->deal;
						//目前账户所能购买最大数额
						$b = $member_rank['CFI_split'] - $this->info()->CFI;
						$now_price = $this->price()->cfi_price;
						$v = $this->modelShop->where('user_id',$v->user_id)->find();
						if($after_amount < $rise_ca || $v->sell <$rise_ca){//剩余交易大于一次涨价的交易量
							if($after_amount >= $v->sell){//购买需求大于当前卖家
								if($b >= $v->sell){
									$now_pay = $v->sell * $now_price;
									$this->modelShop->where('user_id',$id)->dec('dianzibi',$now_pay)->update();
									$this->modelMember->where('id',$id)->inc('CFI',$v->sell)->update();
									$this->modelShop->where('user_id',$v->user_id)->dec('sell',$v->sell)->update();
									$this->modelMember->where('id',$v->user_id)->inc('bonus',$now_pay * 0.54)
																			->inc('wallet',$now_pay * 0.27,)
																			->inc('baoguanjin',$now_pay *0.09,)
																			->update();
									$this->priceUd($now_price,$this->price()->deal+$v_sell,$this->price()->cfi_total);
									continue;
								}
									$now_pay = $b *$now_price;
									$this->modelShop->where('user_id',$id)->dec('dianzibi',$now_pay)->update();
									$this->modelMember->where('id',$id)->inc('CFI',$b)->update();
									$this->modelShop->where('user_id',$v->user_id)->dec('sell',$b)->update();
									$this->modelMember->where('id',$v->user_id)->inc('bonus',$now_pay * 0.54)
																				->inc('wallet',$now_pay * 0.27,)
																				->inc('baoguanjin',$now_pay *0.09,)
																				->update();
									$this->priceUd($now_price,$this->price()->deal+$b,$this->price()->cfi_total);
									return 2;
							}else{//购买需求小于当前卖家
								if($b >= $amount){
									$now_pay = $amount * $now_price;
									$this->modelShop->where('user_id',$id)->dec('dianzibi',$now_pay)->update();
									$this->modelMember->where('id',$id)->inc('CFI',$amount)->update();
									$this->modelShop->where('user_id',$v->user_id)->dec('sell',$amount)->update();
									$this->modelMember->where('id',$v->user_id)->inc('bonus',$now_pay * 0.54)
																				->inc('wallet',$now_pay * 0.27,)
																				->inc('baoguanjin',$now_pay *0.09,)
																				->update();
									$this->priceUd($now_price,$this->price()->deal+$amount,$this->price()->cfi_total);
									return 2;
								}else{
									$now_pay = $b *$now_price;
									$this->modelShop->where('user_id',$id)->dec('dianzibi',$now_pay)->update();
									$this->modelMember->where('id',$id)->inc('CFI',$b)->update();
									$this->modelShop->where('user_id',$v->user_id)->dec('sell',$b)->update();
									$this->modelMember->where('id',$v->user_id)->inc('bonus',$now_pay * 0.54)
																				->inc('wallet',$now_pay * 0.27,)
																				->inc('baoguanjin',$now_pay *0.09,)
																				->update();
									$this->priceUd($now_price,$this->price()->deal+$b,$this->price()->cfi_total);
									return 2;
								}
							}
						}else{
							if($b >= $rise_ca){
								$now_pay = $rise_ca * $now_price;
								$this->modelShop->where('user_id',$id)->dec('dianzibi',$now_pay)->update();
								$this->modelMember->where('id',$id)->inc('CFI',$rise_ca)->update();
								$this->modelShop->where('user_id',$v->user_id)->dec('sell',$rise_ca)->update();
								$this->modelMember->where('id',$v->user_id)->inc('bonus',$now_pay * 0.54)
																			->inc('wallet',$now_pay * 0.27,)
																			->inc('baoguanjin',$now_pay *0.09,)
																			->update();
								$this->priceUd($now_price+0.1,$this->price()->deal+$rise_ca,$this->price()->cfi_total);
								$v = $this->modelShop->where('user_id',$v->user_id)->find();
							}else{
								$now_pay = $b *$now_price;
								$this->modelShop->where('user_id',$id)->dec('dianzibi',$now_pay)->update();
								$this->modelMember->where('id',$id)->inc('CFI',$b)->update();
								$this->modelShop->where('user_id',$v->user_id)->dec('sell',$b)->update();
								$this->modelMember->where('id',$v->user_id)->inc('bonus',$now_pay * 0.54)
																			->inc('wallet',$now_pay * 0.27,)
																			->inc('baoguanjin',$now_pay *0.09,)
																			->update();
								$this->priceUd($now_price,$this->price()->deal+$b,$this->price()->cfi_total);
								return 2;
							}
							//继续购买剩余需求
							//当前账户挂买电子币剩余
							$buyer_dianzibi = $buyer->dianzibi - $now_pay;
							$after_amount = floor($buyer_dianzibi / $this->price()->cfi_price);
							//涨价后距离下一次涨价的个数
							$rise_ca = $this->price()->default_deal - $this->price()->deal;
							//目前账户所能购买最大数额
							$b = $member_rank['CFI_split'] - $this->info()->CFI;
							$now_price = $this->price()->cfi_price;
							$v = $this->modelShop->where('user_id',$v->user_id)->find();
							if($after_amount >= $v->sell){//购买需求大于当前卖家
								if($b >= $v->sell){
									$now_pay = $v->sell * $now_price;
									$this->modelShop->where('user_id',$id)->dec('dianzibi',$now_pay)->update();
									$this->modelMember->where('id',$id)->inc('CFI',$v->sell)->update();
									$this->modelShop->where('user_id',$v->user_id)->dec('sell',$v->sell)->update();
									$this->modelMember->where('id',$v->user_id)->inc('bonus',$now_pay * 0.54)
																				->inc('wallet',$now_pay * 0.27,)
																				->inc('baoguanjin',$now_pay *0.09,)
																				->update();
									$this->priceUd($now_price,$this->price()->deal+$v_sell,$this->price()->cfi_total);
									continue;
								}
									$now_pay = $b *$now_price;
									$this->modelShop->where('user_id',$id)->dec('dianzibi',$now_pay)->update();
									$this->modelMember->where('id',$id)->inc('CFI',$b)->update();
									$this->modelShop->where('user_id',$v->user_id)->dec('sell',$b)->update();
									$this->modelMember->where('id',$v->user_id)->inc('bonus',$now_pay * 0.54)
																				->inc('wallet',$now_pay * 0.27,)
																				->inc('baoguanjin',$now_pay *0.09,)
																				->update();
									$this->priceUd($now_price,$this->price()->deal+$b,$this->price()->cfi_total);
									return 2;
							}else{//购买需求小于当前卖家
								if($b >= $amount){
									$now_pay = $amount * $now_price;
									$this->modelShop->where('user_id',$id)->dec('dianzibi',$now_pay)->update();
									$this->modelMember->where('id',$id)->inc('CFI',$amount)->update();
									$this->modelShop->where('user_id',$v->user_id)->dec('sell',$amount)->update();
									$this->modelMember->where('id',$v->user_id)->inc('bonus',$now_pay * 0.54)
																				->inc('wallet',$now_pay * 0.27,)
																				->inc('baoguanjin',$now_pay *0.09,)
																				->update();
									$this->priceUd($now_price,$this->price()->deal+$amount,$this->price()->cfi_total);
									return 2;
								}else{
									$now_pay = $b *$now_price;
									$this->modelShop->where('user_id',$id)->dec('dianzibi',$now_pay)->update();
									$this->modelMember->where('id',$id)->inc('CFI',$b)->update();
									$this->modelShop->where('user_id',$v->user_id)->dec('sell',$b)->update();
									$this->modelMember->where('id',$v->user_id)->inc('bonus',$now_pay * 0.54)
																				->inc('wallet',$now_pay * 0.27,)
																				->inc('baoguanjin',$now_pay *0.09,)
																				->update();
									$this->priceUd($now_price,$this->price()->deal+$b,$this->price()->cfi_total);
									return 2;
								}
							}
						}
					}
				}else{//购买需求小于当前用户挂卖数量
					if($rise_ca > $v->sell && $rise_ca  > $amount){//涨价条件交易量足够时
						if($b >= $amount){//账户能购买的数量足够时
							$this->modelMember->where('id',$v->user_id)->inc('dianzibi',$amount * $now_price)->update();
							$this->modelMember->where('id',$id)->inc('CFI',$amount)->update();
							$this->modelShop->where('user_id',$v->user_id)->dec('sell',$amount)->update();
							$this->modelShop->where('user_id',$id)->dec('dianzibi',$amount * $now_price)->update();
							$this->priceUd($now_price,$this->price()->deal+$amount,$this->price()->cfi_total);
							return 1;
						}else{//账户能购买的数量不够时
							$this->modelMember->where('id',$v->user_id)->inc('dianzibi',$b * $now_price)->update();
							$this->modelMember->where('id',$id)->inc('CFI',$b)->update();
							$this->modelShop->where('user_id',$v->user_id)->dec('sell',$b)->update();
							$this->modelShop->where('user_id',$id)->dec('dianzibi',$b * $now_price)->update();
							$this->priceUd($now_price,$this->price()->deal+$b,$this->price()->cfi_total);
							return 1;
						}

					}else{//涨价空间不够完成这笔交易时 A1
						
						/*首先完成未涨价部分的交易*/

						if($b >= $rise_ca){//账户能购买的数量足够时
							$now_pay = $rise_ca *$now_price;
							$this->modelShop->where('user_id',$id)->dec('dianzibi',$now_pay)->update();
							$this->modelMember->where('id',$id)->dec('CFI',$rise_ca)->update();
							$this->modelShop->where('user_id',$v->user_id)->dec('sell',$rise_ca)->update();
							$this->modelMember->where('id',$v->user_id)->inc('dianzibi',$now_pay)->update();
							$this->priceUd($now_price+0.1,$this->price()->deal+$cfi_total,$this->price()->cfi_total);
							$v = $this->modelShop->where('user_id',$v->user_id)->find();
						}else{//账户能购买的数量不够时
							$now_pay = $b *$now_price;
							$this->modelShop->where('user_id',$id)->dec('dianzibi',$now_pay)->update();
							$this->modelMember->where('id',$id)->dec('CFI',$b)->update();
							$this->modelShop->where('user_id',$v->user_id)->dec('sell',$b)->update();
							$this->modelMember->where('id',$v->user_id)->inc('dianzibi',$now_pay)->update();
							$this->priceUd($now_price+0.1,$this->price()->deal+$b,$this->price()->cfi_total);
							return 1;
						}
						/*进行涨价后进行交易*/
						if($this->price()->cfi_price >= 4){
							$this->splitCfi();
							return 2;
						}
						//当前账户挂买电子币剩余
						$buyer_dianzibi = $buyer->dianzibi - $now_pay;
						$after_amount = floor($buyer_dianzibi / $this->price()->cfi_price);
						//涨价后距离下一次涨价的个数
						$rise_ca = $this->price()->default_deal - $this->price()->deal;
						//目前账户所能购买最大数额
						$b = $member_rank['CFI_split'] - $this->info()->CFI;
						$now_price = $this->price()->cfi_price;
						$v = $this->modelShop->where('user_id',$v->user_id)->find();
						if($after_amount < $rise_ca || $v->sell <$rise_ca){//剩余交易大于一次涨价的交易量
							//购买需求小于当前卖家
								if($b >= $amount){
									$now_pay = $amount * $now_price;
									$this->modelShop->where('user_id',$id)->dec('dianzibi',$now_pay)->update();
									$this->modelMember->where('id',$id)->inc('CFI',$amount)->update();
									$this->modelShop->where('user_id',$v->user_id)->dec('sell',$amount)->update();
									$this->modelMember->where('id',$v->user_id)->inc('bonus',$now_pay * 0.54)
																				->inc('wallet',$now_pay * 0.27,)
																				->inc('baoguanjin',$now_pay *0.09,)
																				->update();
									$this->priceUd($now_price,$this->price()->deal+$amount,$this->price()->cfi_total);
									return 2;
								}else{
									$now_pay = $b *$now_price;
									$this->modelShop->where('user_id',$id)->dec('dianzibi',$now_pay)->update();
									$this->modelMember->where('id',$id)->inc('CFI',$b)->update();
									$this->modelShop->where('user_id',$v->user_id)->dec('sell',$b)->update();
									$this->modelMember->where('id',$v->user_id)->inc('bonus',$now_pay * 0.54)
																				->inc('wallet',$now_pay * 0.27,)
																				->inc('baoguanjin',$now_pay *0.09,)
																				->update();
									$this->priceUd($now_price,$this->price()->deal+$b,$this->price()->cfi_total);
									return 2;
								}
							
						}else{
							if($b >= $rise_ca){
								$now_pay = $rise_ca * $now_price;
								$this->modelShop->where('user_id',$id)->dec('dianzibi',$now_pay)->update();
								$this->modelMember->where('id',$id)->inc('CFI',$rise_ca)->update();
								$this->modelShop->where('user_id',$v->user_id)->dec('sell',$rise_ca)->update();
								$this->modelMember->where('id',$v->user_id)->inc('bonus',$now_pay * 0.54)
																			->inc('wallet',$now_pay * 0.27,)
																			->inc('baoguanjin',$now_pay *0.09,)
																			->update();
								$this->priceUd($now_price+0.1,$this->price()->deal+$rise_ca,$this->price()->cfi_total);
								$v = $this->modelShop->where('user_id',$v->user_id)->find();
							}else{
								$now_pay = $b *$now_price;
								$this->modelShop->where('user_id',$id)->dec('dianzibi',$now_pay)->update();
								$this->modelMember->where('id',$id)->dec('CFI',$b)->update();
								$this->modelShop->where('user_id',$v->user_id)->dec('sell',$b)->update();
								$this->modelMember->where('id',$v->user_id)->inc('bonus',$now_pay * 0.54)
																			->inc('wallet',$now_pay * 0.27,)
																			->inc('baoguanjin',$now_pay *0.09,)
																			->update();
								$this->priceUd($now_price,$this->price()->deal+$b,$this->price()->cfi_total);
								return 2;
							}
							//继续购买剩余需求
							//当前账户挂买电子币剩余
							$buyer_dianzibi = $buyer->dianzibi - $now_pay;
							$after_amount = floor($buyer_dianzibi / $this->price()->cfi_price);
							//涨价后距离下一次涨价的个数
							$rise_ca = $this->price()->default_deal - $this->price()->deal;
							//目前账户所能购买最大数额
							$b = $member_rank['CFI_split'] - $this->info()->CFI;
							$now_price = $this->price()->cfi_price;
							$v = $this->modelShop->where('user_id',$v->user_id)->find();
							//购买需求小于当前卖家
								if($b >= $amount){
									$now_pay = $amount * $now_price;
									$this->modelShop->where('user_id',$id)->dec('dianzibi',$now_pay)->update();
									$this->modelMember->where('id',$id)->dec('CFI',$amount)->update();
									$this->modelShop->where('user_id',$v->user_id)->dec('sell',$amount)->update();
									$this->modelMember->where('id',$v->user_id)->inc('bonus',$now_pay * 0.54)
																				->inc('wallet',$now_pay * 0.27,)
																				->inc('baoguanjin',$now_pay *0.09,)
																				->update();
									$this->priceUd($now_price,$this->price()->deal+$amount,$this->price()->cfi_total);
									return 2;
								}else{
									$now_pay = $b *$now_price;
									$this->modelShop->where('user_id',$id)->dec('dianzibi',$now_pay)->update();
									$this->modelMember->where('id',$id)->dec('CFI',$b)->update();
									$this->modelShop->where('user_id',$v->user_id)->dec('sell',$b)->update();
									$this->modelMember->where('id',$v->user_id)->inc('bonus',$now_pay * 0.54)
																				->inc('wallet',$now_pay * 0.27,)
																				->inc('baoguanjin',$now_pay *0.09,)
																				->update();
									$this->priceUd($now_price,$this->price()->deal+$b,$this->price()->cfi_total);
									return 2;
								}
						}

					}
				}
			}
		}
		
		

	}
	//挂卖
	public function transactionSell($data){

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
		if($number == $this->price()->default_deal){
			$number = 0;
		}
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
				'default_deal' => $this->price()->default_deal *$a,
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