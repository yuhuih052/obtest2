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

namespace app\index\controller;
use app\common\controller\ControllerBase;
use think\captcha\Captcha;

/**
 * 登录控制器
 */
class Login extends ControllerBase
{
    /**
     * 登录
     */
    public function login()
    {
        
        $lang = request()->param('lang');
        if (in_array($lang, ['zh-cn', 'en-us'])) {
            cookie('think_var', $lang);
        }
        //is_login('member') && $this->jump(RESULT_REDIRECT, '已登录则跳过登录页', url('user/index',['id'=>session('user_id')]));

        // 关闭布局
        $this->view->engine->layout(false);

        return $this->fetch('login');
    }

    /**
     * 注册
     */
    public function register()
    {
        //dump($this->param);
        if(IS_POST){
            $data = $this->param;
            //dump($data);die;
            return  $this->jump($this->logicLogin->register($data));
        }
        return $this->fetch('register');
    }

    /**
     * 根据推荐人编号获取推荐人的姓名
     */
    public function getRealname(){
        $username = input('post.username','');
        return $this->logicLogin->getRealname($username);

    }
    /**
     * 注册：发送手机验证码
     */
    public function sendCode()
    {
        $this->jump($this->logicCode->send('register', $this->param));
    }
    // 注册：检测验证码是否正确
    public function checkCode()
    {

        $this->jump($this->logicCode->check('register', $this->param));
    }
    /**
     * 找回密码：发送手机验证码
     */
    public function sendCode2()
    {

        $this->jump($this->logicCode->send('forget_password', $this->param));

    }


    // 检测真实姓名、账号是否已存在
    public function checkInfo($field, $value)
    {

        $this->jump($this->logicLogin->checkInfo($field, $value));
    }

    public function verify()
    {

        $captcha = new Captcha();
        $captcha->codeSet = '0123456789';
        $captcha->fontSize = 16;
        $captcha->imageW = 120;
        $captcha->imageH = 40;
        $captcha->length   = 4;
        $captcha->useCurve = false;
        $captcha->useNoise = false;
        $captcha->fontttf = '4.ttf';
        return $captcha->entry();
    }

    /**
     * 登录处理
     */
    public function loginHandle($username = '', $password = '', $verify = '')
    {

        $this->jump($this->logicLogin->loginHandle($username, $password, $verify));
    }

    /**
     * 找回密码，修改密码
     */
    //找回密码
    public function findw(){
        return view();
    }
    public function findPassword($username){
        //dump($username);die;
        $data = $this->logicLogin->findMibao($this->param);

        //dump($data);die;
        $this->assign('data',$data);
        return $this->fetch('findpassword');
    }
    public function changePassword(){
        IS_POST && $this->jump($this->logicLogin->changePassword($this->param));

    }
    public function forgetPassword()
    {

        IS_POST && $this->jump($this->logicLogin->changePassword($this->param));

        // 关闭布局
        $this->view->engine->layout(false);

        return $this->fetch('forget_password');
    }

    /**
     *
     * 账号申诉
     */
    public function complain()
    {
        $this->jump($this->logicLogin->complain($this->param));
    }

    /**
     * 注销登录
     */
    public function logout()
    {

        $this->jump($this->logicLogin->logout());
    }

    /**
     * 查看协议
     */
    public function showAgreement()
    {
        // 关闭布局
        $this->view->engine->layout(false);
        $this->assign('agreement_title', config('agreement_title'));
        $this->assign('agreement_content', config('agreement_content'));
        return $this->fetch('show_agreement');
    }

    /**
     * 图片上传
     */
    public function pictureUpload()
    {

        $result = $this->logicFile->pictureUpload();

        return json($result);
    }
    //找回密码--检查用户名
    public function checkUsername(){
        $result = $this->logicLogin->checkUsername($this->param);

        return $result;
    }
    //找回密码--检查密保答案
    public function checkAnswer(){
        $result = $this->logicLogin->checkAnswer($this->param);

        return $result;
    }
    //找回密码--修改密码
    public function changePasswordTwo(){
        $result = $this->logicLogin->changePasswordTwo($this->param);

        return $result;
    }

}