<?php
namespace app\admin\logic;

/**
 * 参数设置
 */
class Site extends AdminBase
{
	public function indexList($where = [], $field = true, $order = '', $paginate = 0){
		
	$data = $this->modelSiteArgm->getList($where, $field, $order, $paginate);
	//dump($data);die;
	return $data;
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
		return $result ? [RESULT_SUCCESS, '保存成功'] : [RESULT_ERROR, $this->modelBlogroll->getError()];
	}
}
