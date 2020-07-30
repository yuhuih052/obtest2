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

namespace app\index\logic;

/**
 * 登录逻辑
 */
class Login extends IndexBase
{

    /**
     * 登录处理
     */
    public function loginHandle($username = '', $password = '', $verify = '')
    {

        $validate_result = $this->validateLogin->scene('member')->check(compact('username', 'password', 'verify'));

        if (!$validate_result) {

            return [RESULT_ERROR, $this->validateLogin->getError()];
        }

//        $passw = $this->modelMember->where('username',$username)->value('password');
//        $id = $this->modelMember->where('username',$username)->value('id');
//        $status = $this->modelMember->where('username',$username)->value('status');
//
//        if (!empty($passw) && $password == $passw){
//
//            return [RESULT_SUCCESS, '登录成功', url('index/index')];
//        }else{
//            $error = empty($id) ? '用户账号不存在！' : '密码输入错误！';
//
//            return [RESULT_ERROR, $error];
//        }
///////////////////////////////////////////////////////////////////////////
         $where = [];
         $where[] = [
             ['username', '=', $username],
             ['is_inside', '=', DATA_DISABLE]
         ];
         $where[1] = [
             ['mobile', '=', $username],
             ['is_inside', '=', DATA_DISABLE]
         ];

         $sys_status = $this->modelSiteArgm->where('id',1)->value('sys_status');
        $member = $this->modelMember->where('username','=',$username)->select()->toArray();
        $mb = $this->modelMember->where('username','=',$username)->find();
        //dump($member);die;
        //判断系统开关状态
        if($sys_status || $member[0]['is_inside']){
            if (!empty($member[0]['password']) && data_md5_key($password) == $member[0]['password'])
            {

                if ($member[0]['status'] == 2) 
                {
                    session('member_info_forbid', $member);
                    return [RESULT_ERROR, '账号已被锁定，请联系客服。'];
                }

                 if ($member[0]['status'] == 0) 
                 {
                     return [RESULT_ERROR, '账号未激活，无法登陆系统，请联系推荐人激活。'];
                 }
                //$this->logicMember->setMemberValue(['id' => $member[0]['id']], TIME_UT_NAME, TIME_NOW);
                $auth = ['member_id' => $member[0]['id'], TIME_UT_NAME => TIME_NOW];
                session('member_info2', $member);
                session('mb',$mb);
                session('user_id2',$member[0]['id']);
                 session('member_auth2', $auth);
                 session('member_auth_sign2', data_auth_sign($auth));
                //dump($auth);die;
                return [RESULT_SUCCESS, '登录成功', url('index/home1')];

            } else {

                $error = empty($member[0]['id']) ? '用户账号不存在！' : '密码输入错误！';

                return [RESULT_ERROR, $error];
            }
        }

        return [RESULT_ERROR,'当前系统维护状态，不可登录'];

///////////////////////////////////////////////////////////////////////
    }
    
    //注册
    public function register($param)
    {
       $validate_result = $this->validateLogin->scene('register')->check($param);

       if (!$validate_result) {

           return [RESULT_ERROR, $this->validateLogin->getError()];
       }
        //dump($param);die;
        if(IS_POST){
            //$param = $this->param;
            //$member_number = time().rand(10,99);
            //最后一位会员编号
            $last_number = $this->modelMember->where('is_inside',0)->limit(1)->order("id desc")->value('member_number');
            
            $new_number = preg_replace("/[a-zA-Z]/u",'',$last_number)+1;
            //dd($new_number);
            //推荐人信息
            $name_status = $this->modelMember->where('username','=',$param['leader_username'])->select()->toArray();
            //接点人信息
            $re_name_status = $this->modelMember->where('username','=',$param['contact_name'])->select()->toArray();
            //报单中心
            $is_center = $this->modelMember->where('username',$param['center_name'])->select()->toArray();
            //注册名字是否存在
            $username_status = $this->modelMember->where('username','=',$param['realname'])->select()->toArray();
            //查询接点人左右区位置是否已被注册
            $father_name_treeplace = $this->modelMember->where('father_name',$param['contact_name'])
                                                        ->where('treeplace',$param['treeplace'])->select()->toArray();
            //dump($father_name_treeplace);die;
            if(empty($father_name_treeplace)){
            if(!empty($name_status) && !empty($re_name_status)){
                if(empty($is_center)){
                    return [RESULT_ERROR,'报单中心不存在'];
                }
                //推荐人激活状态
                $fa_status = $name_status[0]['status'];
                //接点人激活状态
                $re_status = $re_name_status[0]['status'];
                //dd($is_center);
                //dump($restatus);die;
                if(!$fa_status){
                    return [RESULT_ERROR,'请确认推荐人已激活，注册失败'];
                }elseif (!$re_status){
                    return [RESULT_ERROR,'请确认接点人已激活，注册失败'];
                }elseif (!$is_center[0]['is_center']){
                    return [RESULT_ERROR,'该会员不是报单中心'];
                }elseif($username_status){
                    return [RESULT_ERROR,'用户名已被注册'];
                }
                else{
                //dump($data);die;
                $data = [
                'member_number' => "ID".$new_number,
                'Re_name' =>$param['leader_username'],
                'Re_id'     =>$name_status[0]['id'],
                'Re_level'  =>$name_status[0]['Re_level'] + 1,
                'Re_path'   =>$name_status[0]['Re_path'].$param['leader_username'].",",
                're_path_id' =>$name_status[0]['re_path_id'].$name_status[0]['id'].",",
                'father_name' =>$param['contact_name'],
                'Father_id' =>$re_name_status[0]['id'],
                'p_level' =>$re_name_status[0]['p_level'] + 1,
                'p_path' =>$re_name_status[0]['p_path'].$param['contact_name'].",",
                'p_path_id' => $re_name_status[0]['p_path_id'].$re_name_status[0]['id'].",",
                'treeplace' => $param['treeplace'],
                'username'=>$param['realname'],
                'center_id' =>$is_center[0]['id'],
                'center_name'=>$param['center_name'],
                'mobile'  =>$param['mobile'],
                'bankcard'=>$param['bankcard'],
                'bankname'=>$param['bankname'],
                'password'=>data_md5_key($param['password']),
                'realname'=>$param['realname'],
                'mibaowenti'=>$param['mibaowenti'],
                'mibaodaan'=>$param['mibaodaan'],
                'member_rank'=>$param['member_rank'],
                'password_confirm'=>$param['password_confirm'],
            ];
                //dump($data);die;
                $result = $this->modelMember->setInfo($data);
                
                $data1 = $this->modelMember->where('member_number','=',$data['member_number'])->select()->toArray();
                //$url = url('user/index',['id'=>$data1[0]['id']]);
                $url = url('login/login');
                //dump($data1);die;
                return $result ? [RESULT_SUCCESS, '注册成功', $url] : [RESULT_ERROR, $this->modelMember->getError()];
                }
            }
            return [RESULT_ERROR,'接点人或推荐人不存在,注册失败'];
        }
        return [RESULT_ERROR,'该位置已被注册，请换个区注册，或者换个接点人注册'];

        }

        return view();
    }

    /**
    /**
     * 根据推荐人编号获取推荐人的姓名
     */
    public function getRealname($username){
        $leader = $this->modelMember->where(['username' => $username,'is_inside' => 0])->value('realname');
        $code = 0;
        if ($leader){
            $code = 1;
        }
        return ['code' => $code,'realname' => $leader];
    }

    public function findMibao($username){

        $data = $this->modelMember->where('username','=',$username['username'])->select()->toArray();
        //dump($data[0]['mibaowenti']);die;
        //$url = url('login/',[''=>$data]);
        return $data;
    }
    public function changePassword($params)
    {
        $validate_result = $this->validateLogin->scene('fp')->check($params);
        if (!$validate_result) {
            return [RESULT_ERROR, $this->validateLogin->getError()];
        }

        $data = $this->modelMember->where('member_number','=',$params['member_number'])->select()->toArray();
        //dump($params);die;
        if($data[0]['mibaodaan'] == $params['mibaodaan']){
            //dd($params['password']);
            //dd(data_md5_key($params['password']));
            $param = [
                'password' => data_md5_key($params['password']),
            ];

            $result = $this->modelMember->where('member_number','=',$params['member_number'])->update($param);
            //dump($result);die;
            $url = url('login/login');
            return [RESULT_SUCCESS, '恭喜！修改成功！请登录！',$url];
        }

        return [RESULT_ERROR, '答案错误！'];

    }

    public function checkInfo($field, $value)
    {

        $arr = ['realname' => '真实姓名', 'username' => '账号', 'mobile' => '手机号'];

        if (!isset($arr[$field]) || $value == '') {
            return [RESULT_ERROR, '非法检测！'];
        }
        if ($field == 'username' && !\think\facade\Validate::alphaNum($value)) {
            return [RESULT_ERROR, '账号只能是字母和数字！'];
        }
        if($this->modelMember->getInfo([[$field, '=', $value], ['status', '=', 0]])){
            return [RESULT_ERROR, '该'.$arr[$field].'已被注册！'];
        }else{
            return [RESULT_SUCCESS, '恭喜！该'.$arr[$field].'可以注册！'];
        }
    }



    /**
     * 注销当前用户
     */
    public function logout()
    {
        session('member_info2',      null);
        session('member_auth2',      null);
        session('user_id2',          null);
        session('member_auth_sign2', null);
        session('mb',null);

        return [RESULT_SUCCESS, '您已成功退出系统！', url('login/login')];
    }

    public function complain($data = [])
    {
        $validate_result = $this->validateLogin->scene('complain')->check($data);
        if (!$validate_result) {
            return [RESULT_ERROR, $this->validateLogin->getError()];
        }
        $member = session('member_info_forbid');
        $insert_data = [
            'username'      => $member->username,
            'to_username'   => '',
            'title'         => $data['title'],
            'content'       => $data['content'],
            'img_ids'       => !empty($data['img_ids']) ? implode(',', $data['img_ids']) : '',
            'is_reply'      => 0,
        ];

        return $this->modelMsg->addInfo($insert_data, true) ? [RESULT_SUCCESS, '发送成功！请等待管理员的审核！'] : [RESULT_ERROR, $this->modelMsg->getError()];
    }

    //检查用户名
    public function checkUsername($data){
        $member=$this->modelMember->where('username',$data['username'])->find();
        if($member){
            return ['code'=>200,'error'=>1,'msg'=>$member['problem']];
        }else{
            return ['code'=>401,'error'=>0,'msg'=>'无此用户,请重新输入'];
        }
    }
    //验证答案
    public function checkAnswer($data){
        $where=[
            ['username','=',$data['username']],
            ['answer','=',$data['answer']]
        ];
        $member=$this->modelMember->where($where)->find();
        if($member){
            return ['code'=>200,'error'=>1,'msg'=>'验证成功'];
        }else{
            return ['code'=>401,'error'=>0,'msg'=>'验证失败，请检查答案是否正确'];
        }
    }

    public function checkAnswer2($data){
        //dump($data);die;
        $where=[
            ['username','=',$data['username']],
            ['question','=',$data['question']],
            ['answer','=',$data['answer']]
        ];
        $member=$this->modelMember->where($where)->find();
        if($member){
            return ['code'=>200,'error'=>1,'msg'=>'验证成功'];
        }else{
            return ['code'=>401,'error'=>0,'msg'=>'验证失败，请检查答案是否正确'];
        }
    }


    //修改密码
    public function changePasswordTwo($data){
        $where=[
            ['username','=',$data['username']],
            ['question','=',$data['question']],
            ['answer','=',$data['answer']]
        ];
        $member1=$this->modelMember->where($where)->find();
        if($member1){
            if($data['password']==''){
                return ['code'=>402,'error'=>0,'msg'=>'请输入密码'];
            }
            $member=$this->modelMember->where($where)->setField('password',$data['password']);
            if($member){
                return ['code'=>200,'error'=>1,'msg'=>'修改成功'];
            }else{
                return ['code'=>401,'error'=>0,'msg'=>'修改失败，系统故障，请联系管理员'];
            }
        }else{
            return ['code'=>401,'error'=>0,'msg'=>'请检查答案或问题是否正确'];
        }
        // if($data['password']==''){
        // 	return ['code'=>402,'error'=>0,'msg'=>'请输入密码'];
        // }
        // $member=$this->modelMember->where($where)->setField('password',data_md5_key($data['password']));

        // if($member){
        // 	return ['code'=>200,'error'=>1,'msg'=>'修改成功'];
        // }else{
        // 	return ['code'=>401,'error'=>0,'msg'=>'修改失败，系统故障，请联系管理员'];
        // }
    }


}
