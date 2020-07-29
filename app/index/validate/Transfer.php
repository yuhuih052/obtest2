<?php 
namespace app\index\validate;

use think\Validate;

class Transfer extends Validate
{
    protected $rule =   [
        'inputname'  => 'require|max:25|token',
        'money'   => 'number|between:100,10000', 
    ];
    
    protected $message  =   [
        'inputname.require' => '请输入收款方',
        'money.require' => '请输入数额',
        'money.number'   => '数额必须是数字',
        'money.between'  => '数额只能在100-10000之间',
         
    ];

    protected $scene = [
        'withDrawl'  =>  ['money'],
    ];
    
}