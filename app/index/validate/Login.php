<?php
// +---------------------------------------------------------------------+
// | OneBase    | [ WE CAN DO IT JUST THINK ]                            |
// +---------------------------------------------------------------------+
// | Licensed   | http://www.apache.org/licenses/LICENSE-2.0 )           |
// +---------------------------------------------------------------------+
// | Author     | Bigotry <3162875@qq.com>                               |
// +---------------------------------------------------------------------+
// | Repository | https://gitee.com/Bigotry/OneBase                      |
// +---------------------------------------------------------------------+

namespace app\index\validate;

/**
 * 登录验证器
 */
class Login extends IndexBase
{
    
    // 验证规则
    protected $rule =   [
        'verify'        => 'require|captcha',
        'password'      => 'require',
    ];
    
    // 验证提示
    protected $message  =   [
        'verify.require'      => '验证码不能为空',
        'verify.captcha'      => '验证码不正确',
        'username.require'    => '用户名不能为空',
        'password.require'    => '密码不能为空',
        'password.length'     => '密码长度必须是6-12位！',
        'password.confirm'    => '两次输入的密码不一致！',
        'code.require'        => '验证码不能为空',
        'title.require'       => '申诉标题不能为空！',
        'content.require'     => '申诉内容不能为空！',
        'username.require'    => '账号不能为空！',
        'center_name.require'	=> '报单中心不能为空',
        'contact_name.require' => '接点人不能为空',
        'leader_username.require'  => '推荐人不能为空',
    ];

    // 应用场景
    protected $scene = [
        'member'   =>  [ 'username','password','verify'],
        'register' =>   ['username','password','father_username']
    ];

    // 登录页面修改密码 验证场景定义
    public function sceneFp(){
        return $this->only(['username', 'mobile', 'code', 'password'])
            ->append('username', 'require|checkUsername')
            ->append('mobile', 'require|mobile|checkMobile')
            ->append('code', 'require|checkCodeFp')
            ->append('password', 'require|length:6,12|confirm');
    }

    // 登录页  被封账号申诉
    public function sceneComplain(){
        return $this->only(['title', 'content', 'img_ids'])
            ->append('title', 'require|checkTitle')
            ->append('content', 'require')
            ->append('img_ids', 'checkImgIds');
    }
	//注册场景
	public function sceneRegister(){
		return $this->only(['username', 'password', 'img_ids'])
            ->append('title', 'require|checkTitle')
            ->append('content', 'require')
            ->append('img_ids', 'checkImgIds');
	}
	
    protected function checkMobile($value, $rule, $data = []){
        return $this->modelMember->where([['mobile', '=', $value]])->find() ? true : '该手机号未注册！';
    }
    protected function checkUsername($value, $rule, $data = []){
        return $this->modelMember->where([['username', '=', $value]])->find() ? true : '该账号不存在！';
    }
    protected function checkCodeFp($value, $rule, $data = []){
        $result = $this->logicCode->check('forget_password', $data);
        if ($result[0] == RESULT_ERROR) {
            return $result[1];
        }
        return true;
    }

    public function checkTitle($value, $rule, $data = []){
        $member = session('member_info_forbid');
        if (!$member) {
            return '账号不存在！';
        }
        $msg = $this->modelMsg->where([['username', '=', $member->username]])->order('id desc')->find();
        if ($msg && TIME_NOW < $msg->getData('create_time') + config_parse('send_second')) {
            return '不能频繁发送！最少间隔'.config_parse('send_second').'秒。';
        }
        return true;
    }
    public function checkImgIds($value, $rule, $data = []){
        if (!is_array($value) || count($value) > 6) {
            return '最多只能上传6张图片！';
        }
        return $this->modelPicture->where([['id', 'in', $value]])->count() == count($value) ? true : '图片不存在或有重复的图片，请检查！';
    }
}
