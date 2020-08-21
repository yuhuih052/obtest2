<?php
namespace app\admin\logic;

/**
 * 参数设置
 */
use app\index\logic\User;

class Site extends AdminBase
{
	public function indexList($where = [], $field = true, $order = '', $paginate = 0){
		
	$data = $this->modelSiteArgm->getList($where, $field, $order, $paginate);
	//dump($data);die;
	return $data;
	}
	public function price_table(){
		return $this->modelPrice->where('id',1)->find();
	}

	public function siteSys($data = []){
		//dump($data);die;
		$si = $this->modelSiteArgm->where('id',1)->select();
		$check = [
			'recharge_min' => ($data['recharge_min'] == null) ? $si[0]['recharge_min'] :$data['recharge_min'],
			'recharge_max' => ($data['recharge_max'] == null) ? $si[0]['recharge_max'] :$data['recharge_max'],
			'recharge_mult' => ($data['recharge_mult'] == null) ? $si[0]['recharge_mult'] :$data['recharge_mult'],
			'withdrawl_min' => ($data['withdrawl_min'] == null) ? $si[0]['withdrawl_min'] :$data['withdrawl_min'],
			'withdrawl_max' => ($data['withdrawl_max'] == null) ? $si[0]['withdrawl_max'] :$data['withdrawl_max'],
			'withdrawl_mult' => ($data['withdrawl_mult'] == null) ? $si[0]['withdrawl_mult'] :$data['withdrawl_mult'],
			'withdrawl_server' => ($data['server'] == null) ? $si[0]['withdrawl_server'] :$data['server'],
			'overtime' => ($data['overtime'] == null) ? $si[0]['overtime'] :$data['overtime']*60*60,
			'sys_status'	=> $data['sys_status'],
			'withdrawl_switch'	=> $data['withdrawl_switch'],
		];
		//dump($check);die;
		$result = $this->modelSiteArgm->where('id',$data['id'])->update($check);
		return $result ? [RESULT_SUCCESS, '保存成功'] : [RESULT_ERROR, $this->modelSiteArgm->getError()];
	}
	//修改cfi交易参数
	public function cfi_deal($data){

		$c = $this->modelPrice->where('id',1)->find();
		$cc = [
			'deal' => ($data['default_deal'] == null) ? $c['deal'] :$data['deal'],
			'cfi_price' => ($data['cfi_price'] == null) ? $c['cfi_price'] :$data['cfi_price'],
			'default_price' => ($data['default_price'] == null) ? $c['default_price'] :$data['default_price'],
            'default_deal' =>($data['default_deal'] == null) ? $c['default_deal'] :$data['default_deal'],
            'cfi_total' => ($data['cfi_total'] == null) ? $c['cfi_total'] :$data['cfi_total'],
		];
	
		$result = $this->modelPrice->where('id',1)->update($cc);
		return $result ? [RESULT_SUCCESS, '保存成功'] : [RESULT_ERROR, $this->modelPrice->getError()];
	}
	//查看价格表
	public function price(){
		return $this->modelPrice->where('id',1)->find();
	}
	//股票拆分
	public function splitCfi($data){
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
                //从交易市场返回未交易的CFI至账户
                $this->modelMember->where('id',$va->user_id)->inc('CFI',$va->sell)->update();
                $reres = [
                    'user_id'=>$va->user_id,
                    'user_name'=>$va->user_name,
                    'CFI'=>$va->sell,
                    'shuoming'=>'拆分CFI，从交易市场返回挂卖的CFI至账户',
                ];
                $this->modelBill->setInfo($reres);
            }
        }

        $user_split = $this->modelMember->where('CFI','>',0)
            ->where('status',1)
            ->select();
        foreach ($user_split as $key => $v) {
            //用户拆分封顶
            $v_info_rank = $this->checkMember_rank($v->member_rank);
            if($v->CFI *$a <= $v_info_rank['CFI_split']){
                $b = $v->CFI;
                $c = $v->CFI *$a;
                $d = $c -$b;

                $this->modelMember->where('id',$v->id)->inc('CFI',$d)->update();
                $reres = [
                    'user_id'=>$v->id,
                    'user_name'=>$v->username,
                    'CFI'=>$v->CFI*$a - $v->CFI,
                    'shuoming'=>'拆分CFI，账户增加相应比列的CFI，拆分时交易价格为:'.$a,
                ];
                $this->modelBill->setInfo($reres);
            }else{
                $this->modelMember->where('id',$v->id)->update(['CFI'=>$v_info_rank['CFI_split']]);
                $reres = [
                    'user_id'=>$v->id,
                    'user_name'=>$v->username,
                    'CFI'=>$v->CFI*$a - $v_info_rank['CFI_split'],
                    'shuoming'=>'拆分CFI，由于账户达到拆分封顶，无法获得全部拆分数量的CFI，拆分时交易价格为:'.$a,
                ];
                $this->modelBill->setInfo($reres);
                //用户达到封顶后，把多余股票加入系统账号中
                //$this->modelPrice->where('id',1)->inc('cfi_total',$v->CFI *$a - $v_info_rank['CFI_split']);
            }

        }
        //遍历持有cFi用户结束
		
		return [RESULT_SUCCESS,'操作成功'];
				
	}

	//根据会员等级查询不同奖励值
    public function checkMember_rank($mmm){
        switch ($mmm) {
            case 1:
            $checkData = [
                'CF' => 50,
                'baodanbi_co' => 100,
                'tuijian_co' => 0.05,
                'duipeng_co' =>0.05,
                'jiandian_co' =>0.005,
                'guanli_co' =>0.02,
                'bonus_day' => 100,
                'CFI_split' => 400,
            ];
                break;
            case 2:
                $checkData = [
                'CF' => 260,
                'baodanbi_co' => 500,
                'tuijian_co' => 0.06,
                'duipeng_co' =>0.06,
                'jiandian_co' =>0.005,
                'guanli_co' =>0.02,
                'bonus_day' => 500,
                'CFI_split' => 2000,
            ];
                break;
            case 3:
                $checkData = [
                'CF' => 540,
                'baodanbi_co' => 1000,
                'tuijian_co' => 0.07,
                'duipeng_co' =>0.07,
                'jiandian_co' =>0.005,
                'guanli_co' =>0.03,
                'bonus_day' => 1000,
                'CFI_split' => 4000,
            ];
                break;
            case 4:
                $checkData = [
                'CF' => 1120,
                'baodanbi_co' => 2000,
                'tuijian_co' => 0.08,
                'duipeng_co' =>0.08,
                'jiandian_co' =>0.005,
                'guanli_co' =>0.04,
                'bonus_day' => 2000,
                'CFI_split' => 8000,
            ];
                break;
            case 5:
                $checkData = [
                'CF' => 2900,
                'baodanbi_co' => 5000,
                'tuijian_co' => 0.10,
                'duipeng_co' =>0.10,
                'jiandian_co' =>0.005,
                'guanli_co' =>0.05,
                'bonus_day' => 5000,
                'CFI_split' => 20000,
            ];
                break;
            case 6:
                 $checkData = [
                'CF' => 6000,
                'baodanbi_co' => 10000,
                'tuijian_co' => 0.12,
                'duipeng_co' =>0.12,
                'jiandian_co' =>0.005,
                'guanli_co' =>0.06,
                'bonus_day' => 10000,
                'CFI_split' => 40000,
            ];
                break;
            default:
                $checkData = Null;
                break;
        }
        return $checkData;
    }
    //电子币利息发放到保管金
    public function refresh_in(){
    	$mem = $this->modelMember->where('status','=',1)
    							->where('username','<>','admin')
    							->select();
    	foreach ($mem as $key => $v) {
    		//查询挂买CFI账户的电子币
    		$s_dianzibi = $this->modelShop->where('user_id',$v->id)
    										->where('statuss',1)
    										->value('dianzibi');
    		$s_dianzibi = $s_dianzibi == null ? 0 :$s_dianzibi;
    		$all_dianzibi = $v->dianzibi + $s_dianzibi;
    		$this->modelMember->where('id',$v->id)->inc('baoguanjin',$all_dianzibi * 0.001)->data(['refresh_interest'=>time()])->update();
    		$r = [
    			'user_id'=> $v->id,
    			'user_name'=> $v->username,
    			'baoguanjin'=> $all_dianzibi * 0.001,
    			'shuoming'=> '电子币利息发放到保管金',
    		];
    		$this->modelBill->setInfo($r);
    	}
    	return 1;
    }
}
