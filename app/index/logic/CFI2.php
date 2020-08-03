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

        $re = $this->transaction($id,$member_rank);

        switch($re){
            case 1 :
                return [RESULT_SUCCESS,'挂买成功'];break;
            case 2 :
                return [RESULT_SUCCESS,'挂买成功,当前价格已刷新，请留意交易价格,如继续购买请点击继续'];break;
            case 3 :
                return [RESULT_SUCCESS,'请检查账户交易钱包'];break;
        }


    }
    //购买逻辑
    public function transaction($id,$member_rank){
        //dd($member_rank);
        $now_price = $this->price()->cfi_price;
        $cfi_total = $this->price()->cfi_total;
        $buyer_list = $this->modelShop->where('dianzibi','>',0)->select();
        $seller_list = $this->modelShop->where('sell','>',0)->select();
        $buyer = $this->modelShop->where('user_id',$id)->find();
        //当前账户挂买需求能购买的个数
        $amount = floor($buyer->dianzibi / $now_price);

        //判断账户目前能购买的最大数
        $b = $member_rank['CFI_split'] - $this->info($id)->CFI;
        if($amount < 1){
            return 3;
        }
        //距离涨价的个数
        $rise_ca = $this->price()->default_deal - $this->price()->deal;
        //当没有用户挂卖且系统账户有剩余，从系统账户购买
        if(count($seller_list) == 0 && $cfi_total >0){
            if($rise_ca >= $amount){
                if($cfi_total >$amount){
                    if($b >= $amount){
                        $now_pay = $amount * $this->price()->cfi_price;
                        $this->upDataSys($id,$now_pay,$amount,$now_price);

                        return 1;
                    }
                    $now_pay = $b * $now_price;

                    $this->upDataSys($id,$now_pay,$b,$now_price);
                    return 1;
                }else{
                    if($b >= $cfi_total){
                        $now_pay = $now_price * $cfi_total;
                        $this->upDataSys($id,$now_pay,$cfi_total,$now_price);
                        return 1;
                    }
                    $now_pay = $b * $now_price;
                    $this->upDataSys($id,$now_pay,$b,$now_price);
                    return 1;
                }
            }else{
                $after_buy = $amount - $rise_ca;
                //如果账户接近封顶数
                if($b < $rise_ca){
                    $now_pay = $b * $now_price;
                    $this->upDataSys($id,$now_pay,$b,$now_price);
                    return 1;
                }
                //先结算未涨价部分
                $now_pay = $rise_ca * $now_price;
                $this->upDataSys($id,$now_pay,$amount,$now_price+0.1);
                //刷新购买者账户信息
                $buyer = $this->modelShop->where('user_id',$id)->find();

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
                            $now_pay = $b * $now_price;
                            $this->upDataSys($id,$now_pay,$b,$now_price);
                            return 1;
                        }
                        $now_pay = $after_amount * $now_price;
                        $this->upDataSys($id,$now_pay,$after_amount,$now_price);
                        return 2;
                    }else{
                        //如果账户接近封顶数
                        if($b < $cfi_total){
                            $now_pay = $b * $now_price;
                            $this->upDataSys($id,$now_pay,$b,$now_price);
                            return 1;
                        }
                        $now_pay = $now_price * $cfi_total;
                        $this->upDataSys($id,$now_pay,$cfi_total,$now_price);
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
                            $now_pay = $v->sell * $now_price;
                            $this->updata($id,$v,$now_pay,$v->sell,$now_price);
                            return 1;
                        }else{//账户能购买的数量不够时
                            $now_pay = $b * $now_price;
                            $this->updata($id,$v,$now_pay,$b,$now_price);
                            return 1;
                        }

                    }else{//涨价空间不够完成这笔交易时 A1

                        //首先完成未涨价部分的交易
                        if($b >= $rise_ca){//账户能购买的数量足够时
                            $now_pay = $rise_ca *$now_price;
                            $this->updata($id,$v,$now_pay,$rise_ca,$now_price);
                            $v = $this->modelShop->where('user_id',$v->user_id)->find();
                        }else{//账户能购买的数量不够时
                            $now_pay = $b *$now_price;
                            $this->updata($id,$v,$now_pay,$b,$now_price);
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
                        $b = $member_rank['CFI_split'] - $this->info($id)->CFI;
                        $now_price = $this->price()->cfi_price;
                        $v = $this->modelShop->where('user_id',$v->user_id)->find();
                        if($after_amount < $rise_ca || $v->sell <$rise_ca){//剩余交易大于一次涨价的交易量
                            if($after_amount >= $v->sell){//购买需求大于当前卖家
                                if($b >= $v->sell){
                                    $now_pay = $v->sell * $now_price;
                                    $this->updata($id,$v,$now_pay,$v->sell,$now_price);
                                    continue;
                                }
                                $now_pay = $b *$now_price;
                                $this->updata($id,$v,$now_pay,$b,$now_price);
                                return 2;
                            }else{//购买需求小于当前卖家
                                if($b >= $amount){
                                    $now_pay = $amount * $now_price;
                                    $this->updata($id,$v,$now_pay,$amount,$now_price);
                                    return 2;
                                }else{
                                    $now_pay = $b *$now_price;
                                    $this->updata($id,$v,$now_pay,$b,$now_price);
                                    return 2;
                                }
                            }
                        }else{
                            if($b >= $rise_ca){
                                $now_pay = $rise_ca * $now_price;
                                $this->updata($id,$v,$now_pay,$rise_ca,$now_price+0.1);
                                $v = $this->modelShop->where('user_id',$v->user_id)->find();
                            }else{
                                $now_pay = $b *$now_price;
                                $this->updata($id,$v,$now_pay,$b,$now_price);
                                return 2;
                            }
                            //继续购买剩余需求
                            //当前账户挂买电子币剩余
                            $buyer_dianzibi = $buyer->dianzibi - $now_pay;
                            $after_amount = floor($buyer_dianzibi / $this->price()->cfi_price);
                            //涨价后距离下一次涨价的个数
                            $rise_ca = $this->price()->default_deal - $this->price()->deal;
                            //目前账户所能购买最大数额
                            $b = $member_rank['CFI_split'] - $this->info($id)->CFI;
                            $now_price = $this->price()->cfi_price;
                            $v = $this->modelShop->where('user_id',$v->user_id)->find();
                            if($after_amount >= $v->sell){//购买需求大于当前卖家
                                if($b >= $v->sell){
                                    $now_pay = $v->sell * $now_price;
                                    $this->updata($id,$v,$now_pay,$v->sell,$now_price);
                                    continue;
                                }
                                $now_pay = $b *$now_price;
                                $this->updata($id,$v,$now_pay,$b,$now_price);
                                return 2;
                            }else{//购买需求小于当前卖家
                                if($b >= $amount){
                                    $now_pay = $amount * $now_price;
                                    $this->updata($id,$v,$now_pay,$amount,$now_price);
                                    return 2;
                                }else{
                                    $now_pay = $b *$now_price;
                                    $this->updata($id,$v,$now_pay,$b,$now_price);
                                    return 2;
                                }
                            }
                        }
                    }
                }else{//购买需求小于当前用户挂卖数量
                    if($rise_ca > $v->sell && $rise_ca  > $amount){//涨价条件交易量足够时
                        if($b >= $amount){//账户能购买的数量足够时
                            $now_pay = $amount * $now_price;
                            $this->updata($id,$v,$now_pay,$amount,$now_price);
                            return 1;
                        }else{//账户能购买的数量不够时
                            $now_pay = $b * $now_price;
                            $this->updata($id,$v,$now_pay,$b,$now_price);
                            return 1;
                        }

                    }else{//涨价空间不够完成这笔交易时 A1

                        /*首先完成未涨价部分的交易*/

                        if($b >= $rise_ca){//账户能购买的数量足够时
                            $now_pay = $rise_ca *$now_price;
                            $this->updata($id,$v,$now_pay,$rise_ca,$now_price);
                            $v = $this->modelShop->where('user_id',$v->user_id)->find();
                        }else{//账户能购买的数量不够时
                            $now_pay = $b *$now_price;
                            $this->updata($id,$v,$now_pay,$b,$now_price+0.1);
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
                        $b = $member_rank['CFI_split'] - $this->info($id)->CFI;
                        $now_price = $this->price()->cfi_price;
                        $v = $this->modelShop->where('user_id',$v->user_id)->find();
                        if($after_amount < $rise_ca || $v->sell <$rise_ca){//剩余交易大于一次涨价的交易量
                            //购买需求小于当前卖家
                            if($b >= $amount){
                                $now_pay = $amount * $now_price;
                                $this->updata($id,$v,$now_pay,$amount,$now_price);
                                return 2;
                            }else{
                                $now_pay = $b *$now_price;
                                $this->updata($id,$v,$now_pay,$b,$now_price);
                                return 2;
                            }

                        }else{
                            if($b >= $rise_ca){
                                $now_pay = $rise_ca * $now_price;
                                $this->updata($id,$v,$now_pay,$rise_ca,$now_price+0.1);
                                $v = $this->modelShop->where('user_id',$v->user_id)->find();
                            }else{
                                $now_pay = $b *$now_price;
                                $this->updata($id,$v,$now_pay,$b,$now_price);
                                return 2;
                            }
                            //继续购买剩余需求
                            //当前账户挂买电子币剩余
                            $buyer_dianzibi = $buyer->dianzibi - $now_pay;
                            $after_amount = floor($buyer_dianzibi / $this->price()->cfi_price);
                            //涨价后距离下一次涨价的个数
                            $rise_ca = $this->price()->default_deal - $this->price()->deal;
                            //目前账户所能购买最大数额
                            $b = $member_rank['CFI_split'] - $this->info($id)->CFI;
                            $now_price = $this->price()->cfi_price;
                            $v = $this->modelShop->where('user_id',$v->user_id)->find();
                            //购买需求小于当前卖家
                            if($b >= $amount){
                                $now_pay = $amount * $now_price;
                                $this->updata($id,$v,$now_pay,$amount,$now_price);
                                return 2;
                            }else{
                                $now_pay = $b *$now_price;
                                $this->updata($id,$v,$now_pay,$b,$now_price);
                                return 2;
                            }
                        }

                    }
                }
            }
        }



    }
    //挂卖逻辑
    public function transaction2($id){

        $now_price = $this->price()->cfi_price;
        $buyer_list = $this->modelShop->where('dianzibi','>=',2)->select();
        $v = $this->modelShop->where('user_id',$id)->find();
        //距离涨价的个数
        $rise_ca = $this->price()->default_deal - $this->price()->deal;
        //当没有用户挂买时
        if(count($buyer_list) == 0){
            return 1;
        }elseif(!count($buyer_list) == 0){//有人挂买时
            foreach ($buyer_list as $key => $buyer) {
                $buyer_info = $this->modelMember->where('id',$buyer->user_id)->find();
                $member_rank = $this->logicUser->checkMember_rank($buyer_info->member_rank);

                //判断账户目前能购买的最大数
                $b = $member_rank['CFI_split'] - $this->info($buyer->user_id)->CFI;
                //当前账户挂买需求能购买的个数
                $amount = floor($buyer->dianzibi / $now_price);
                if($amount >= $v->sell){//购买需求大于当前挂卖数量
                    if($rise_ca > $v->sell && $rise_ca  > $amount){//涨价空间足够完成这笔交易时 A1
                        if($b >= $v->sell){//账户能购买的数量足够时
                            $now_pay = $v->sell * $now_price;
                            $this->updata($buyer->user_id,$v,$now_pay,$v->sell,$now_price);
                            return 1;
                        }else{//账户能购买的数量不够时
                            $now_pay = $b * $now_price;
                            $this->updata($buyer->user_id,$v,$now_pay,$b,$now_price);
                            return 1;
                        }

                    }else{//涨价空间不够完成这笔交易时 A1

                        //首先完成未涨价部分的交易
                        if($b >= $rise_ca){//账户能购买的数量足够时
                            $now_pay = $rise_ca *$now_price;
                            $this->updata($buyer->user_id,$v,$now_pay,$rise_ca,$now_price);
                            $v = $this->modelShop->where('user_id',$v->user_id)->find();
                        }else{//账户能购买的数量不够时
                            $now_pay = $b *$now_price;
                            $this->updata($buyer->user_id,$v,$now_pay,$b,$now_price);
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
                        $b = $member_rank['CFI_split'] - $this->info($buyer->user_id)->CFI;
                        $now_price = $this->price()->cfi_price;
                        $v = $this->modelShop->where('user_id',$id)->find();
                        if($after_amount < $rise_ca || $v->sell <$rise_ca){//剩余交易大于一次涨价的交易量
                            if($after_amount >= $v->sell){//购买需求大于当前卖家
                                if($b >= $v->sell){
                                    $now_pay = $v->sell * $now_price;
                                    $this->updata($buyer->user_id,$v,$now_pay,$v->sell,$now_price);
                                    continue;
                                }
                                $now_pay = $b *$now_price;
                                $this->updata($buyer->user_id,$v,$now_pay,$b,$now_price);
                                return 2;
                            }else{//购买需求小于当前卖家
                                if($b >= $amount){
                                    $now_pay = $amount * $now_price;
                                    $this->updata($buyer->user_id,$v,$now_pay,$amount,$now_price);
                                    return 2;
                                }else{
                                    $now_pay = $b *$now_price;
                                    $this->updata($buyer->user_id,$v,$now_pay,$b,$now_price);
                                    return 2;
                                }
                            }
                        }else{
                            if($b >= $rise_ca){
                                $now_pay = $rise_ca * $now_price;
                                $this->updata($buyer->user_id,$v,$now_pay,$rise_ca,$now_price+0.1);
                                $v = $this->modelShop->where('user_id',$id)->find();
                            }else{
                                $now_pay = $b *$now_price;
                                $this->updata($buyer->user_id,$v,$now_pay,$b,$now_price);
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
                            $v = $this->modelShop->where('user_id',$v->id)->find();
                            if($after_amount >= $v->sell){//购买需求大于当前卖家
                                if($b >= $v->sell){
                                    $now_pay = $v->sell * $now_price;
                                    $this->updata($buyer->user_id,$v,$now_pay,$v->sell,$now_price);
                                    continue;
                                }
                                $now_pay = $b *$now_price;
                                $this->updata($buyer->user_id,$v,$now_pay,$b,$now_price);
                                return 2;
                            }else{//购买需求小于当前卖家
                                if($b >= $amount){
                                    $now_pay = $amount * $now_price;
                                    $this->updata($buyer->user_id,$v,$now_pay,$amount,$now_price);
                                    return 2;
                                }else{
                                    $now_pay = $b *$now_price;
                                    $this->updata($buyer->user_id,$v,$now_pay,$b,$now_price);
                                    return 2;
                                }
                            }
                        }
                    }
                }else{//购买需求小于当前用户挂卖数量
                    if($rise_ca > $v->sell && $rise_ca  > $amount){//涨价条件交易量足够时
                        if($b >= $amount){//账户能购买的数量足够时
                            $now_pay = $amount * $now_price;
                            $this->updata($buyer->user_id,$v,$now_pay,$amount,$now_price);
                            return 1;
                        }else{//账户能购买的数量不够时
                            $now_pay = $b * $now_price;
                            $this->updata($buyer->user_id,$v,$now_pay,$b,$now_price);
                            return 1;
                        }

                    }else{//涨价空间不够完成这笔交易时 A1

                        /*首先完成未涨价部分的交易*/

                        if($b >= $rise_ca){//账户能购买的数量足够时
                            $now_pay = $rise_ca *$now_price;
                            $this->updata($buyer->user_id,$v,$now_pay,$rise_ca,$now_price);
                            $v = $this->modelShop->where('user_id',$id)->find();
                        }else{//账户能购买的数量不够时
                            $now_pay = $b *$now_price;
                            $this->updata($buyer->user_id,$v,$now_pay,$b,$now_price+0.1);
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
                        $v = $this->modelShop->where('user_id',$id)->find();
                        if($after_amount < $rise_ca || $v->sell <$rise_ca){//剩余交易大于一次涨价的交易量
                            //购买需求小于当前卖家
                            if($b >= $amount){
                                $now_pay = $amount * $now_price;
                                $this->updata($buyer->user_id,$v,$now_pay,$amount,$now_price);
                                return 2;
                            }else{
                                $now_pay = $b *$now_price;
                                $this->updata($buyer->user_id,$v,$now_pay,$b,$now_price);
                                return 2;
                            }

                        }else{
                            if($b >= $rise_ca){
                                $now_pay = $rise_ca * $now_price;
                                $this->updata($buyer->user_id,$v,$now_pay,$rise_ca,$now_price+0.1);
                                $v = $this->modelShop->where('user_id',$id)->find();
                            }else{
                                $now_pay = $b *$now_price;
                                $this->updata($buyer->user_id,$v,$now_pay,$b,$now_price);
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
                                $this->updata($buyer->user_id,$v,$now_pay,$amount,$now_price);
                                return 2;
                            }else{
                                $now_pay = $b *$now_price;
                                $this->updata($buyer->user_id,$v,$now_pay,$b,$now_price);
                                return 2;
                            }
                        }

                    }
                }
            }
        }



    }
    //挂卖
    public function sys_sell($data){
        $id = $this->info(session('user_id2'))->id;
        $member = $this->modelMember->where('id',$id)->find();
        $data['cfi_amount'] = $data['cfi_amount'] == Null ? 0:$data['cfi_amount'];
        $pay = $data['cfi_amount'];
        if($pay > $member['CFI'] ){
            return [RESULT_SUCCESS,'账户CFI不足'];
        }
        $this->modelMember->where('id',$id)->dec('CFI',$pay)->update();
        $seller = $this->modelShop->where('user_id',$id)->find();
        if(empty($seller)){
            $res =[
                'user_id' => $id,
                'sell' => $pay,
                'create_time' => time(),
            ];
            $this->modelShop->setInfo($res);
        }else{
            $res =[
                'sell' => $seller->sell + $pay,
                'update_time' => time(),
            ];
            $this->modelShop->where('user_id',$id)->update($res);

        }
        $re = $this->transaction2($id);

        switch($re){
            case 1 ;
                return [RESULT_SUCCESS,'挂卖成功'];break;
            case 2 ;
                return [RESULT_SUCCESS,'挂卖成功,当前价格已刷新，请留意交易价格,如继续挂卖请点击继续'];break;
        }
    }
    //跟系统交易时刷新数据
    public function upDataSys($id,$now_pay,$amount,$now_price){
        //dump($id);dump($now_price);dump($amount);dump($now_price);die;
        $this->modelShop->where('user_id',$id)->dec('dianzibi',$now_pay)->update();
        $this->modelMember->where('id',$id)->inc('CFI',$amount)->update();
        $this->priceUd($now_price,$this->price()->deal+$amount,$this->price()->cfi_total - $amount);
        $user_name = $this->modelMember->where('id',$id)->value('username');
        $re = [
            'buyer_id' => $id,
            'seller_id' => 0,
            'deal_price' => $now_pay / $amount,
            'deal_number' => $amount,
        ];
        $this->modelDealRecord->setInfo($re);
    }
    //刷新数据
    public function upData($id,$v,$now_pay,$amount,$now_price){
        $this->modelShop->where('user_id',$id)->dec('dianzibi',$now_pay)->update();
        $this->modelMember->where('id',$id)->dec('CFI',$amount)->update();
        $this->modelShop->where('user_id',$v->user_id)->dec('sell',$amount)->update();
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
            'buyer_id' => $id,
            'seller_id' => $v->user_id,
            'deal_price' => $now_pay / $amount,
            'deal_number' => $amount,
        ];
        $this->modelDealRecord->setInfo($rerer);
        $this->priceUd($now_price,$this->price()->deal+$amount,$this->price()->cfi_total);
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

    //撤销挂买
    public function withdralbuy($data){
        $member = $this->info(session('user_id2'));
        $this->modelMember->where('id',$member->id)->inc('dianzibi',$data['dianzibi'])->update();
        $this->modelShop->where('user_id',$member->id)->dec('dianzibi',$data['dianzibi'])->update();
        return [RESULT_SUCCESS,'操作成功'];
    }
    public function withdralsell($data){
        $member = $this->info(session('user_id2'));
        $this->modelMember->where('id',$member->id)->inc('dianzibi',$data['cfi'])->update();
        $this->modelShop->where('user_id',$member->id)->dec('dianzibi',$data['cfi'])->update();
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

}