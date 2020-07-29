<?php 
namespace app\index\validate;

use think\Validate;

class Charge extends Validate
{
    protected $rule =   [
        'request_chongzhi'   => 'require|number|between:100,10000|token', 
    ];
    
    protected $message  =   [
        'request_chongzhi.require' => '请输入数额',
        'request_chongzhi.number'   => '数额必须是数字',
        'request_chongzhi.between'  => '数额只能在100-10000之间',
         
    ];
    protected function checkRequest_chongzhi($value)
    {
        if(!$value%100){
            return '请输入100的倍数';
        }
      return true;
    }
    
}