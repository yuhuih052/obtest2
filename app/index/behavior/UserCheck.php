<?php
namespace app\index\behavior;
use think\Controller;
 
class UserCheck
{
    use \traits\controller\Jump;//类里面引入jump类
 
    //绑定到CheckAuth标签，可以用于检测Session以用来判断用户是否登录
    public function run(&$params){
        $uid = session('user_id2');
        // 这里的session 是当用户登录成功后创建的一个session 如果没有的话就代表没有用户登录
        // var_dump($uid);
        if(!isset($uid)){
          $uid = "";
        }
        if($uid == null || $uid == "" || $uid == "null" || $uid == 0){
          return $this->error('您还未登录，请先登录！','login/login', 1);
        }
    }
}