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
		$check = [
			'recharge_min' => ($data['recharge_min'] == null) ? 100 :$data['recharge_min'],
			'recharge_max' => ($data['recharge_max'] == null) ? 1000 :$data['recharge_max'],
			'recharge_mult' => ($data['recharge_mult'] == null) ? 100 :$data['recharge_mult'],
			'withdrawl_min' => ($data['withdrawl_min'] == null) ? 100 :$data['withdrawl_min'],
			'withdrawl_max' => ($data['withdrawl_max'] == null) ? 1000 :$data['withdrawl_max'],
			'withdrawl_mult' => ($data['withdrawl_mult'] == null) ? 100 :$data['withdrawl_mult'],
			'withdrawl_server' => ($data['server'] == null) ? 100 :$data['server'],
			'sys_status'	=> $data['sys_status'],
			'withdrawl_switch'	=> $data['withdrawl_switch'],
		];
		//dump($check);die;
		$result = $this->modelSiteArgm->where('id',$data['id'])->update($check);
		return $result ? [RESULT_SUCCESS, '保存成功'] : [RESULT_ERROR, $this->modelBlogroll->getError()];
	}
}
