<?php
namespace app\index\logic;

/**
 * 
 */
class CFI extends IndexBase
{
	public function info($id){

		return $this->modelMember->where('id',$id)->find();
	}
	
	public function price(){
		return $this->modelPrice->where('id',1)->find();
	}
	public function shop(){

		return $this->modelShop->where('user_id',$this->info(session('user_id2'))->id)->find();
	}
	//挂买CFI
	public function sys_buy($data){

		$id = $this->info(session('user_id2'))->id;
		$member = $this->modelMember->where('id',$id)->find();
		
		//获取会员等级信息
		$member_rank = $this->logicUser->checkMember_rank($member['member_rank']);
		
		$pay = $data['cfi_amount'];
		$amount = floor($data['cfi_amount'] / $this->price()->cfi_price);
		if($pay < $this->price()->cfi_price){
			return [RESULT_SUCCESS,'请输入不低于单价'];
		}
		if($amount > $member_rank['CFI_split']){
			return [RESULT_SUCCESS,'当前价格挂买金额超过账户CFI封顶额'];
		}
		if($member->CFI >= $member_rank['CFI_split']){
			return [RESULT_SUCCESS,'账户CFI已达到封顶'];
		}
		if($pay > $member['dianzibi'] ){
			return [RESULT_SUCCESS,'电子币不足'];
		}

		$this->modelMember->where('id',$id)->dec('dianzibi',$pay)->update();
			$re =[
				'user_id' => $id,
                'user_name'=>$this->info(session('user_id2'))->username,
				'dianzibi' => $pay,
                'buy' => $pay,
				'create_time' => time(),
			];
			
			$shop_id = $this->modelShop->setInfo($re);

			$this->bill($id,$member['username'],'dianzibi_all',$pay,'挂买CFI');

		$re = $this->transaction($id,$member_rank);
		
		switch($re){
            case 1 :
			    return [RESULT_SUCCESS,'排队成功'];break;
            case 2 :
			    return [RESULT_SUCCESS,'匹配到订单,已进行交易'];break;
            case 3 :
                return [RESULT_SUCCESS,'匹配到订单，交易后进行拆分'];break;
		}

	}
	//购买逻辑
	public function transaction($id,$member_rank){
		$seller_list = $this->modelShop->where('sell','>',0)
                                        ->where('statuss',1)
                                        ->order('create_time','asc')
                                        ->select();
        //当前交易的shop订单
        $buyer = $this->modelShop->where('user_id',$id)
                                    ->where('statuss',1)
                                    ->where('dianzibi','>',0)
                                    ->order('create_time','asc')
                                    ->find();
		//当前价格挂买数量
		$amount = floor($buyer->dianzibi / $this->price()->cfi_price);
		$rise_ca = $this->price()->default_deal - $this->price()->deal;

		if(count($seller_list) == 0){
			if($this->price()->cfi_total > 0){
				$cfi_total = $this->price()->cfi_total;
				if($amount < $rise_ca){
					if($amount <= $cfi_total){
						$now_pay = $amount * $this->price()->cfi_price;
                        $this->priceUd($this->price()->cfi_price,$this->price()->deal+$amount,$this->price()->cfi_total - $amount);
						$this->upDataSys($buyer,$now_pay,$amount,$this->price()->cfi_price);
						return 2;
					}else{
						$now_pay = $cfi_total * $this->price()->cfi_price;
         $this->priceUd($this->price()->cfi_price,$this->price()->deal+$cfi_total,$this->price()->cfi_total - $cfi_total);
						$this->upDataSys($buyer,$now_pay,$amount,$this->price()->cfi_price);
						return 2;
					}
				}else{
					//先计算未涨价部分
					$now_pay = $rise_ca * $this->price()->cfi_price;
                    $this->upDataSys($buyer,$now_pay,$rise_ca,$this->price()->cfi_price);
                    $this->priceUd($this->price()->cfi_price+0.1,$this->price()->deal+$rise_ca,$this->price()->cfi_total-$rise_ca);

					//判断是否达到拆分条件
                    if($this->price()->cfi_price >= $this->price()->default_price){
                        $this->splitCfi();
                        return 3;
                    }
					//再计算涨价后的
					$amount = $amount - $rise_ca;

					$now_pay = $amount * $this->price()->cfi_price;
					$this->upDataSys($buyer,$now_pay,$amount,$this->price()->cfi_price);
                    $this->priceUd($this->price()->cfi_price,$this->price()->deal+$amount,$this->price()->cfi_total-$amount);
                    return 2;
				}
				
			}else{
				return 1;
			}	
		}else{//检测到卖家
			foreach($seller_list as $key=>$v){
			    //当前订单购买大于当前卖家挂卖订单

				if($amount > $v->sell){
				    //挂卖小于涨价空间
					if($v->sell <$rise_ca){
						$now_pay = $v->sell * $this->price()->cfi_price;
						$this->upData($buyer,$v,$now_pay,$v->sell,$this->price()->cfi_price);
						continue;
					}else{
						$now_pay = $this->price()->cfi_prce * $rise_ca;
                        $this->upData($buyer,$v,$now_pay,$rise_ca,$this->price()->cfi_price+0.1);
                            if($this->price()->cfi_price >= 4){
                                $this->splitCfi();
                                return 2;
                            }
                        $buyer_buy = $buyer->dianzibi - $now_pay;
                            //当前价格能挂买的个数
                            $amount = floor($buyer_buy / $this->price()->cfi_price);
                            if($amount < 1){
                                return 2;
                            }
                            $now_pay = $amount * $this->price()->cfi_price;
                            $this->upData($buyer,$v,$now_pay,$amount,$this->price()->cfi_price);
					}
				}else{
                    //挂买小于涨价空间
                    if($amount <$rise_ca){
                        $now_pay = $amount * $this->price()->cfi_price;
                        $this->upData($buyer,$v,$now_pay,$amount,$this->price()->cfi_price);
                        return 1;
                    }else{
                        $now_pay = $this->price()->cfi_prce * $rise_ca;
                        $this->upData($buyer,$v,$now_pay,$rise_ca,$this->price()->cfi_price+0.1);
                        if($this->price()->cfi_price >= 4){
                            $this->splitCfi();
                            return 2;
                        }
                        $buyer_buy = $buyer->dianzibi - $now_pay;
                        //当前价格能挂买的个数
                        $amount = floor($buyer_buy / $this->price()->cfi_price);
                        if($amount < 1){
                            return 2;
                        }
                        $now_pay = $amount * $this->price()->cfi_price;
                        $this->upData($buyer,$v,$now_pay,$amount,$this->price()->cfi_price);
                        return 2;
                    }
                }
			}
			$id1 = $buyer->id;
			$this->buyAndsys($id1);
			return 2;
		}
		
	}
	//与系统交易
    public function buyAndsys($id){
        $seller_list = $this->modelShop->where('sell','>',0)
            ->where('statuss',1)
            ->order('create_time','asc')
            ->select();
        $buyer = $this->modelShop->where('id',$id)
            ->where('statuss',1)
            ->where('dianzibi','>',0)
            ->order('create_time','asc')
            ->find();
        //当前价格挂买数量
        $amount = floor($buyer->dianzibi / $this->price()->cfi_price);
        $rise_ca = $this->price()->default_deal - $this->price()->deal;
        if($this->price()->cfi_total > 0){
            $cfi_total = $this->price()->cfi_total;
            if($amount < $rise_ca){
                if($amount <= $cfi_total){
                    $now_pay = $amount * $this->price()->cfi_price;
                    $this->priceUd($this->price()->cfi_price,$this->price()->deal+$amount,$this->price()->cfi_total - $amount);
                    $this->upDataSys($buyer,$now_pay,$amount,$this->price()->cfi_price);
                    return 2;
                }else{
                    $now_pay = $cfi_total * $this->price()->cfi_price;
                    $this->priceUd($this->price()->cfi_price,$this->price()->deal+$cfi_total,$this->price()->cfi_total - $cfi_total);
                    $this->upDataSys($buyer,$now_pay,$amount,$this->price()->cfi_price);
                    return 2;
                }
            }else{
                //先计算未涨价部分
                $now_pay = $rise_ca * $this->price()->cfi_price;
                $this->upDataSys($buyer,$now_pay,$rise_ca,$this->price()->cfi_price);
                $this->priceUd($this->price()->cfi_price+0.1,$this->price()->deal+$rise_ca,$this->price()->cfi_total-$rise_ca);

                //判断是否达到拆分条件
                if($this->price()->cfi_price >= $this->price()->default_price){
                    $this->splitCfi();
                    return 3;
                }
                //再计算涨价后的
                $amount = $amount - $rise_ca;

                $now_pay = $amount * $this->price()->cfi_price;
                $this->upDataSys($buyer,$now_pay,$amount,$this->price()->cfi_price);
                $this->priceUd($this->price()->cfi_price,$this->price()->deal+$amount,$this->price()->cfi_total-$amount);
                return 2;
            }

        }else{
            return 1;
        }
    }
    /*******与系统交易函数结束*/
    //挂卖
    public function sys_sell($data){
        $id = $this->info(session('user_id2'))->id;
        $member = $this->modelMember->where('id',$id)->find();
        if($data['cfi_amount'] == Null){
            return [RESULT_SUCCESS,'请输入有效数字'];
        }
        $pay = $data['cfi_amount'];
        if($pay > $member['CFI'] ){
            return [RESULT_SUCCESS,'账户CFI不足'];
        }
        $this->modelMember->where('id',$id)->dec('CFI',$pay)->update();
        $seller = $this->modelShop->where('user_id',$id)->find();

            $res =[
                'user_id' => $id,
                'user_name' =>$this->info(session('user_id2'))->username,
                'sell' => $pay,
                'sell_a' => $pay,
                'create_time' => time(),
            ];
            $this->modelShop->setInfo($res);

        $re = $this->transaction2($id);

        switch($re){
            case 1 ;
                return [RESULT_SUCCESS,'挂卖排队成功'];break;
            case 2 ;
                return [RESULT_SUCCESS,'匹配到买家，已进行成交'];break;
        }
    }
	//挂卖逻辑
    public function transaction2($id){

        $now_price = $this->price()->cfi_price;
        $buyer_list = $this->modelShop->where('dianzibi','>=',2)
                                        ->where('statuss',1)
                                        ->order('create_time','asc')
                                            ->select();
        $v = $this->modelShop->where('user_id',$id)
                                ->where('statuss',1)
                                ->where('sell','>',0)
                                ->order('create_time','asc')->find();
        //距离涨价的个数
        $rise_ca = $this->price()->default_deal - $this->price()->deal;
        //当没有用户挂买时
        if(count($buyer_list) == 0){
            return 1;
        }elseif(!count($buyer_list) == 0){//有人挂买时
            foreach ($buyer_list as $key => $buyer) {

                $buyer_info = $this->modelMember->where('id',$buyer->user_id)->find();
                $member_rank = $this->logicUser->checkMember_rank($buyer_info->member_rank);
                $cfi_total = $this->price()->cfi_total;
                //判断账户目前能购买的最大数
                $b = $member_rank['CFI_split'] - $this->info($buyer->user_id)->CFI;
                //当前账户挂买需求能购买的个数
                $amount = floor($buyer->dianzibi / $now_price);
                if($amount >= $v->sell){//购买需求大于当前挂卖数量
                    if($rise_ca > $v->sell){//涨价空间足够完成这笔交易时 A1
                        if($b >= $v->sell){//账户能购买的数量足够时
                            $now_pay = $v->sell * $now_price;

                            $this->upData($buyer,$v,$now_pay,$v->sell,$now_price);

                            return 2;
                        }else{//账户能购买的数量不够时
                            $now_pay = $b * $now_price;
                            $this->upData($buyer,$v,$now_pay,$b,$now_price);

                            return 2;
                        }

                    }else{//涨价空间不够完成这笔交易时 A1

                        //首先完成未涨价部分的交易
                        if($b >= $rise_ca){//账户能购买的数量足够时
                            $now_pay = $rise_ca *$now_price;

                            $this->updata($buyer,$v,$now_pay,$rise_ca,$now_price+0.1);

                        }else{//账户能购买的数量不够时
                            $now_pay = $b *$now_price;
                            $this->updata($buyer->user_id,$v,$now_pay,$b,$now_price);

                            return 2;
                        }
                        $v = $this->modelShop->where('id',$v->id)->find();
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
                        $buyer = $this->modelShop->where('id',$buyer->id)
                                                    ->where('statuss',1)
                                                    ->find();
                        if(empty($buyer)){
                            return 2;
                        }
                        //目前账户所能购买最大数额
                        $b = $member_rank['CFI_split'] - $this->info($buyer->user_id)->CFI;
                        $now_price = $this->price()->cfi_price;
                        $v = $this->modelShop->where('id',$v->id)->where('statuss',1)->find();
                        if($after_amount < $rise_ca || $v->sell <$rise_ca){//剩余交易小于一次涨价的交易量
                            if($after_amount >= $v->sell){//购买需求大于当前卖家
                                if($b >= $v->sell){
                                    $now_pay = $v->sell * $now_price;
                                    $this->upData($buyer,$v,$now_pay,$v->sell,$now_price);

                                    continue;
                                }
                                $now_pay = $b *$now_price;
                                $this->upData($buyer,$v,$now_pay,$b,$now_price);

                                return 2;
                            }else{//购买需求小于当前卖家
                                if($b >= $amount){
                                    $now_pay = $amount * $now_price;
                                    $this->upData($buyer,$v,$now_pay,$amount,$now_price);

                                    return 2;
                                }else{
                                    $now_pay = $b *$now_price;
                                    $this->updata($buyer,$v,$now_pay,$b,$now_price);

                                    return 2;
                                }
                            }
                        }else{
                            if($b >= $rise_ca){//进行涨价
                                $now_pay = $rise_ca * $now_price;
                                $this->updata($buyer,$v,$now_pay,$rise_ca,$now_price+0.1);

                                $v = $this->modelShop->where('user_id',$id)->find();
                            }else{
                                $now_pay = $b *$now_price;
                                $this->updata($buyer,$v,$now_pay,$b,$now_price);

                                return 2;
                            }
                            //继续购买剩余需求
                            //当前账户挂买电子币剩余
                            $buyer_dianzibi = $buyer->dianzibi - $now_pay;
                            $after_amount = floor($buyer_dianzibi / $this->price()->cfi_price);
                            //涨价后距离下一次涨价的个数
                            $rise_ca = $this->price()->default_deal - $this->price()->deal;
                            //目前账户所能购买最大数额
                            $b = $member_rank['CFI_split'] - $this->info($buyer->usre_id)->CFI;
                            $now_price = $this->price()->cfi_price;
                            $buyer = $this->modelShop->where('id',$buyer->id)
                                                    ->where('statuss',1)
                                                    ->find();
                            $v = $this->modelShop->where('id',$v->id)->where('statuss',1)->find();
                            if($after_amount >= $v->sell){//购买需求大于当前卖家
                                if($b >= $v->sell){
                                    $now_pay = $v->sell * $now_price;
                                    $this->updata($buyer,$v,$now_pay,$v->sell,$now_price);

                                    continue;
                                }
                                $now_pay = $b *$now_price;
                                $this->updata($buyer,$v,$now_pay,$b,$now_price);

                                return 2;
                            }else{//购买需求小于当前卖家
                                if($b >= $amount){
                                    $now_pay = $amount * $now_price;
                                    $this->updata($buyer,$v,$now_pay,$amount,$now_price);

                                    return 2;
                                }else{
                                    $now_pay = $b *$now_price;
                                    $this->updata($buyer,$v,$now_pay,$b,$now_price);

                                    return 2;
                                }
                            }
                        }
                    }
                }else{//购买需求小于当前用户挂卖数量
                    if($rise_ca  > $amount){//涨价条件交易量足够时
                        if($b >= $amount){//账户能购买的数量足够时
                            $now_pay = $amount * $now_price;
                            $this->updata($buyer,$v,$now_pay,$amount,$now_price);

                            return 1;
                        }else{//账户能购买的数量不够时
                            $now_pay = $b * $now_price;
                            $this->updata($buyer,$v,$now_pay,$b,$now_price);

                            return 1;
                        }

                    }else{//涨价空间不够完成这笔交易时 A1

                        /*首先完成未涨价部分的交易*/

                        if($b >= $rise_ca){//账户能购买的数量足够时
                            $now_pay = $rise_ca *$now_price;
                            $this->updata($buyer,$v,$now_pay,$rise_ca,$now_price+0.1);

                            $v = $this->modelShop->where('id',$id)->where('statuss',1)->find();
                        }else{//账户能购买的数量不够时
                            $now_pay = $b *$now_price;
                            $this->updata($buyer,$v,$now_pay,$b,$now_price+0.1);
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
                        $b = $member_rank['CFI_split'] - $this->info($buyer->user_id)->CFI;
                        $now_price = $this->price()->cfi_price;
                        $v = $this->modelShop->where('id',$id)->where('statuss',1)->find();
                        if($after_amount < $rise_ca || $v->sell <$rise_ca){//剩余交易大于一次涨价的交易量
                            //购买需求小于当前卖家
                            if($b >= $amount){
                                $now_pay = $amount * $now_price;
                                $this->updata($buyer,$v,$now_pay,$amount,$now_price);
                                return 2;
                            }else{
                                $now_pay = $b *$now_price;
                                $this->updata($buyer,$v,$now_pay,$b,$now_price);
                                return 2;
                            }

                        }else{
                            if($b >= $rise_ca){
                                $now_pay = $rise_ca * $now_price;
                                $this->updata($buyer,$v,$now_pay,$rise_ca,$now_price+0.1);
                                $v = $this->modelShop->where('user_id',$id)->find();
                            }else{
                                $now_pay = $b *$now_price;
                                $this->updata($buyer,$v,$now_pay,$b,$now_price);
                                return 2;
                            }
                            //继续购买剩余需求
                            //当前账户挂买电子币剩余
                            $buyer_dianzibi = $buyer->dianzibi - $now_pay;
                            $after_amount = floor($buyer_dianzibi / $this->price()->cfi_price);
                            //涨价后距离下一次涨价的个数
                            $rise_ca = $this->price()->default_deal - $this->price()->deal;
                            //目前账户所能购买最大数额
                            $b = $member_rank['CFI_split'] - $this->info($buyer->user_id)->CFI;
                            $now_price = $this->price()->cfi_price;
                            $v = $this->modelShop->where('user_id',$id)->find();
                            //购买需求小于当前卖家
                            if($b >= $amount){
                                $now_pay = $amount * $now_price;
                                $this->updata($buyer,$v,$now_pay,$amount,$now_price);
                                return 2;
                            }else{
                                $now_pay = $b *$now_price;
                                $this->updata($buyer,$v,$now_pay,$b,$now_price);
                                return 2;
                            }
                        }

                    }
                }
            }
        }



    }

    //跟系统交易时刷新数据
    public function upDataSys($buyer,$now_pay,$amount,$now_price){

		$this->modelShop->where('id',$buyer->id)->dec('dianzibi',$now_pay)->update();
        $this->modelMember->where('id',$buyer->user_id)->inc('CFI',$amount)->update();
        $this->priceUd($now_price,$this->price()->deal+$amount,$this->price()->cfi_total - $amount);
		$user_name = $this->modelMember->where('id',$buyer->user_id)->value('username');
		$buyer = $this->modelShop->where('id',$buyer->id)->find();
		if($buyer->dianzibi < $this->price()->cfi_price){
			$this->modelMember->where('id',$buyer->user_id)->inc('dianzibi',$buyer->dianzibi)->update();
			$this->modelShop->where('id',$buyer->id)->update(['dianzibi'=>0,'statuss'=>2]);
		}
        $re = [
            'shop_id' => $buyer->id,
            'buyer_id' => $buyer->user_id,
            'seller_id' => 0,
            'deal_price' => $now_pay / $amount,
            'deal_number' => $amount,
        ];
        $this->modelDealRecord->setInfo($re);
	}

	//刷新数据
	public function upData($buyer,$v,$now_pay,$amount,$now_price){

        $this->modelShop->where('id',$buyer->id)->dec('dianzibi',$now_pay)->update();
        $this->modelMember->where('id',$buyer->user_id)->inc('CFI',$amount)->update();
        $this->modelShop->where('id',$v->id)->dec('sell',$amount)->update();
        $this->modelMember->where('id',$v->user_id)->inc('bonus',$now_pay * 0.54)
            ->inc('wallet',$now_pay * 0.27)
            ->inc('baoguanjin',$now_pay *0.09)
            ->update();
        $user_name = $this->modelMember->where('id',$v->user_id)->value('username');
        $rere = [
          'user_id' => $v->user_id,
            'user_name' =>$user_name,
            'bonus' => "+".$now_pay * 0.54,
            'wallet' => "+".$now_pay * 0.27,
            'baoguanjin' =>"+".$now_pay *0.09,
            'shuoming' => "CFI交易",
        ];
        $this->modelBill->setInfo($rere);
        $rerer = [
            'shop_id' => $buyer->id,
            'buyer_id' => $buyer->user_id,
            'seller_id' => $v->user_id,
            'deal_price' => $now_pay / $amount,
            'deal_number' => $amount,
        ];
        $this->modelDealRecord->setInfo($rerer);
		$this->priceUd($now_price,$this->price()->deal+$amount,$this->price()->cfi_total);
		$v = $this->modelShop->where('id',$v->id)->find();
		if($v->sell < 1){
			$this->modelShop->where('id',$v->id)->update(['statuss'=>2]);
		}
        $buyer = $this->modelShop->where('id',$buyer->id)->find();

        if($buyer->dianzibi < $this->price()->cfi_price){
            $this->modelMember->where('id',$buyer->user_id)->inc('dianzibi',$buyer->dianzibi)->update();
            $rss = [
                'user_id' => $buyer->user_id,
                'user_name' => $this->info($buyer->user_id)->username,
                'dianzibi' => "+".$buyer->dianzibi,
                'shuoming' => '挂买CFI退回剩余不足单价的电子币',
            ];
            $this->modelBill->setInfo($rss);
            $this->modelShop->where('id',$buyer->id)->update(['statuss'=>2,'dianzibi'=>0]);
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
			if($va->statuss == 1){
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
    //撤销挂卖买界面
    public function withBuyS(){
	    return $this->modelShop->where('user_id',session('user_id2'))
            ->where('statuss',1)
            ->where('buy','>',0)
            ->select();
    }
    //撤销挂卖买界面
    public function withSellS(){
        return $this->modelShop->where('user_id',session('user_id2'))
            ->where('statuss',1)
            ->where('sell_a','>',0)
            ->select();
    }
    //撤销挂买
    public function withdralbuy($data){
	    $this->modelMember->where('id',session('user_id2'))->inc('dianzibi',$data['dianzibi'])->update();
	    $this->modelShop->where('id',$data['id'])->dec('dianzibi',$data['dianzibi'])->data(['statuss'=>3])->update();
	    return [RESULT_SUCCESS,'操作成功'];
    }
    //撤销挂卖
    public function withdralsell($data){
        $this->modelMember->where('id',session('user_id2'))->inc('CFI',$data['cfi'])->update();
        $this->modelShop->where('id',$data['id'])->dec('sell',$data['cfi'])->data(['statuss'=>3])->update();
        return [RESULT_SUCCESS,'操作成功'];
    }
    public function buyRecord(){
        $member = $this->info(session('user_id2'));
        return $this->modelDealRecord->where('buyer_id',$member->id)->select();
    }
    public function sellRecord(){
        $member = $this->info(session('user_id2'));
        return $this->modelDealRecord->where('seller_id',$member->id)->select();
    }
    public function shop_buy(){
	    return $this->modelShop->where('buy','>',0)->order('create_time','asc')->select();
    }
    public function shop_sell(){
        return $this->modelShop->where('sell_a','>',0)->order('create_time','asc')->select();
    }
}