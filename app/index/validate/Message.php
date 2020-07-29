<?php 
namespace app\index\validate;

use think\Validate;

class Message extends Validate
{
    protected $rule =   [
        'message'   => 'require|min:2|max:250|token', 
    ];
    
    protected $message  =   [
        'message.require' => '请输入内容',
        'message.max'	=> '请输入不超过250个字符',
        'message.min'	=> '请输入不低于2个字符',
         
    ];

    
}