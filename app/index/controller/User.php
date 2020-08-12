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

/**
 * 前端首页控制器
 */
class User extends IndexBase
{
    // 首页
    public function index()
    {
        
        $id = session('user_id2');
        if($id == Null){
            return view('login/login');
        }
        if(session('user_id2') == $id){
        $data = $this->logicUser->index($id);
        //dump($data);die;
        //$data = $this->objarray_to_array($data);

        $this->assign('data',$data );

        return $this->fetch('index');
         }
         return "访问错误";
    }

    public function userInfoEdit($id){
        
        //dump($id);die;
        if(session('user_id2') == $id){
        $data = $this->logicUser->index($id);
        //dump($data);die;
        //$data = $this->objarray_to_array($data);

        $this->assign('data',$data );

        return $this->fetch('user_edit');
        }

        return "访问错误";
        
    }
    // 修改个人信息
    public function userEdit()
    {
        if(IS_POST){
            $data = $this->param;
            //dump($data);die;
            return  $this->jump($this->logicUser->edit($data));
        }
        return $this->fetch('user_edit');

    }

    //直接推荐人列表
    public function userRecommonder(){
       //$data = $username;
       $member = session('member_info2');
       if($member[0]['is_center'] > 0){
       $data = $this->logicUser->recommonderList();
        $this->assign('list',$data);

        return $this->fetch('recommonder_list');
        }
        return "您不是报单中心，如果申请已通过，请重新登录再试";
    }

    //推荐人激活会员
    public function memberEdit()
    {
        
        IS_POST && $this->jump($this->logicUser->memberEdit($this->param));
        //dump($this->param);die;
        
        $info = $this->logicMember->getMemberInfo(['id' => $this->param['id']]);
        
        $this->assign('info', $info);
        
        return $this->fetch('member_edit');
    }
     //删除会员
    public function memberDel($id = 0)
    {
        
        return $this->jump($this->logicUser->memberDel(['id' => $id]));
    }
    //充值界面
    public function chongzhi(){

        if(!session('user_id2') == Null){
        
        $data = $this->logicUser->chongzhi();
        //dump($data);die;
        $this->assign('data',$data);
        return $this->fetch('chongzhi');
        }
        $url = url('login/login');
        return $url;
    }

    //充值申请
    public function chongzhi1(){

        $userid = session('user_id2');
        //dump($this->param);die;
        $data = $this->param['request_chongzhi'];
        
       IS_POST && $this->jump($this->logicUser->request_chongzhi1($data));
    }
    //转账界面
    public function zhuanzhang(){
        //dd('1');
        if(session('member_info2')[0]['is_center'])
        return view();
        return "您不是报单中心";
        //echo '<script language="JavaScript">;alert("您不是报单中心");location.href="";</script>;';
        // echo '<script language="JavaScript">;alert("您不是报单中心")</script>;
        // <button><a class="btn" onclick="javascript:history.back(-1);return false;"><i class="fa fa-history"></i> 返 回</a></button>';
    }
    //转账列表
    public function transferRecord($username){
        //dump($username);die;
        if(session('member_info2')[0]['username'] == $username){
        $data = $this->logicUser->transferRecord($username);
        $this->assign('data',$data);
        return $this->fetch('user/transfer_record');
        }

        return "访问错误";
    }
    //转账操作
    public function transfer(){
        $data = $this->param;

        $validate = new \app\index\validate\Transfer;

        if (!$validate->check($data)) {
            return $validate->getError();
        }
       $data = $this->logicUser->transfer($data);
       //dump($data[0]);die;
       if(!$data == 0){
        
           if($data[0] == 'error'){
            echo '<script language="JavaScript">;alert("余额不足");location.href="zhuanzhang.html";</script>;';
                //return $data[1];
           }
           $this->assign('data',$data);
            return $this->fetch('user/transfer_record');   
       }
            echo '<script language="JavaScript">;alert("对方不是报单中心");location.href="zhuanzhang.html";</script>;';
    }

    public function withDrawl(){
        $data = $this->logicUser->withDD();
        $this->assign('data',$data);
        return $this->fetch('withdrawl');
    }
    //提现申请
    public function withDrawl1(){
        $data = $this->param;
        // $validate = new \app\index\validate\Transfer;
       IS_POST &&$this->jump($this->logicUser->withDrawl($data));
       
    }
    //提现记录
    public function withDrawlrecord($id){
        if(session('user_id2') == $id){
        $data = $this->logicUser->record($id);
        $this->assign('data',$data);
        return $this->fetch('user/withdrawl_record');
        }
        return "访问错误";
    }
    
    //留言功能
    public function message(){
        return view();
    }

    public function messagel(){
        $data = $this->param;
        $validate = new \app\index\validate\Message;

        if (!$validate->check($data)) {
            return $validate->getError();
        }

        $this->logicUser->message($data);
        $record = $this->logicUser->messageRecord($data['id']);
        $this->assign('list',$record);
        return $this->fetch('user/message_record');
    }
    public function messageRecord($id){
        if(session('user_id2') == $id){
        $record = $this->logicUser->messageRecord($id);
        $this->assign('list',$record);
        return $this->fetch('user/message_record');
        }
        return "访问错误";
    }
    //双轨图
    public function twoPathway()
    {
        $top_id = session('user_id2');
        $level = 4;
        $this->assign('html', $this->logicUser->twoPathway($top_id,$level));
        
        return $this->fetch('two_pathway');
    }
    //双轨图查询
    public function twoPathwayfind(){
        $data = $this->param;
        if($data['username'] == Null){
            $top_id = session('user_id2');
        }else{
            $top_user = $this->logicUser->twoFind($data);
            //如果通过用户名找不到用户
            if (!$top_user) {
            return '<div class="no-data">暂无数据！</div>';
            }
            $top_id = $top_user['id'];
        }

        
        $this->assign('html', $this->logicUser->twoPathway($top_id,$data['level']));
        
        return $this->fetch('two_pathway');
    }
    //左右区注册搜索
    public function placeTreesize(){
        //dd('12222');die;
        $data = $this->param;
     
        if($data['username'] == Null){
            $top_id = $this->logicUser->findId($data);
        }else{
        $top_id = $this->logicUser->placeFind($data);
        }
        //dd($top_id);
        if (!$top_id) {
            return '<div class="no-data">暂无数据！</div>';
        }
        $this->assign('html', $this->logicUser->twoPathway($top_id,$data['level']));
        
        return $this->fetch('two_pathway');
    }
    //推荐图
    public function tuijiantu(){
        $root_id = session('user_id2');
        $data = $this->logicUser->tuijiantu($root_id);
        //dump($data);die;
        $this->assign('html',$data);
        return $this->fetch('tuijiantu');
    }
    //树形图
    public function teamTree(){
        $root_id = session('user_id2');
        $this->assign($this->logicUser->teamTree($this->request->param('root_id', '')));
        return $this->fetch('team_tree');
    }
    // 获取下级会员
    public function getChildrenMembers(){

        return $this->jump($this->logicUser->getChildrenMembers($this->request->param('pid', '0', 'intval')));
    }
//插件——树形图
   public function re_tree(){
    
    $id = session('user_id2');
    $data = $this->logicUser->re_tree($id);
    $zhitui_all = $data[0]['zhitui_all'];
    $all_number = $this->logicUser->find_all_number($id) +1;

    //dump($data);die;
    $this->assign('zhitui_all',$zhitui_all);
    $this->assign('all_number',$all_number);
    $this->assign('level',$data);
    return $this->fetch('re_tree');
   }

   //树形图查询用户
   public function find_re_tree(){
    //dd($this->param);
    $username = $this->param;
    $user = $this->logicUser->find_re_tree_id($username);
    
    
    $data = $this->logicUser->re_tree($user->id);

    $zhitui_all = $data[0]['zhitui_all'];
    $all_number = $this->logicUser->find_all_number($user->id) +1;
    //dump($data);die;
    $this->assign('zhitui_all',$zhitui_all);
    $this->assign('all_number',$all_number);
    $this->assign('level',$data);
    return $this->fetch('re_tree');
   }

/**************************************************************************************************/
     public function system()
    {
        
        $se_id = session('user_id2');
        $father['id']   = 2;
        if (!empty($this->param)) {
            $the = $this->param;

            if (!empty($the['username'])) {

                $id = $this->logicUser->queryOne($the);

                $cur = db('member')->where(['id' => $id])->find();
                //dd($cur);
                //上级用户
                $father = db('member')->where(['id' => $cur['Father_id']])->find();
                if ($id == 0) {
                    $this->error('没有此用户');
                } elseif ($id == 400) {
                    $this->error('查询用户不是该会员下级');
                }
            } elseif (!empty($the['id'])) {
                $id = $the['id'];
                $cur = db('member')->where(['id' => $id])->find();

                //上级用户
                $father = db('member')->where('id', $cur['Father_id'])->find();
            }
        }
        if (empty($id)) {
            $id = $se_id;
        }
        if (!empty($this->param['level'])) {
            //dump($this->param['level']);
        }

        $this->assign('id',$father['id']);
        $html = $this->logicUser->system($id);
        $this->assign('html', $html);
        //dd($html);
        return $this->fetch('system');
    }

    public function left(){
        $data = $this->param;
        if (!empty($data['username'])) {
            $the = $this->param;

            if (!empty($the['username'])) {

                $id = $this->logicUser->queryOne($the);

                $cur = db('member')->where(['id' => $id])->find();
                //dd($cur);
                //上级用户
                $father = db('member')->where(['id' => $cur['Father_id']])->find();
                if ($id == 0) {
                    $this->error('没有此用户');
                } elseif ($id == 400) {
                    $this->error('查询用户不是该会员下级');
                }
            } elseif (!empty($the['id'])) {
                $id = $the['id'];
                $cur = db('member')->where(['id' => $id])->find();

                //上级用户
                $father = db('member')->where('id', $cur['Father_id'])->find();
            }
        }else{
            $id = session('user_id2');
            $father['id'] = $id;
        }
        //dd($id);
        $p = 0;
        $id = $this->logicUser->findPlid($id,$p);

        if(!$id){
            $this->error('用户左区没有人');
        }
        $this->assign('id',$father['id']);
        $html = $this->logicUser->system($id);
        $this->assign('html', $html);
        //dd($html);
        return $this->fetch('system');
    }

    public function right(){
        $data = $this->param;
        if (!empty($data['username'])) {
            $the = $this->param;

            if (!empty($the['username'])) {

                $id = $this->logicUser->queryOne($the);

                $cur = db('member')->where(['id' => $id])->find();
                //dd($cur);
                //上级用户
                $father = db('member')->where(['id' => $cur['Father_id']])->find();
                //dd($father);
                if ($id == 0) {
                    $this->error('没有此用户');
                } elseif ($id == 400) {
                    $this->error('查询用户不是该会员下级');
                }
            } elseif (!empty($the['id'])) {
                $id = $the['id'];
                $cur = db('member')->where(['id' => $id])->find();

                //上级用户
                $father = db('member')->where('id', $cur['Father_id'])->find();

            }
        }else{
            $id = session('user_id2');
            $father['id'] = $id;
        }
        $p = 1;
        $id = $this->logicUser->findPlid($id,$p);

        if(!$id){
            $this->error('用户右区没有人');
        }
         $this->assign('id',$father['id']);
        $html = $this->logicUser->system($id);
        $this->assign('html', $html);
        //dd($html);
        return $this->fetch('system');
    }
   
   //直推关系图
    public function my_teamTree()
    {

        $this->assign($this->logicUser->teamTree3($this->request->param('root_id', '')));
        return $this->fetch('my_team_tree');
    }
//////////////////////////////////////////////////////////////////

    //货币转换
    public function zhuanhuan(){
        return view();
    }
    //货币转换操作
    public function zhuanhuan1(){
        $this->jump($this->logicUser->zhuanhuan1($this->param));
    }
    //货币明细
    public function billDetail(){
        $data = $this->logicUser->billDetail();
        //dd($data);
        $this->assign('data',$data);
        return $this->fetch('billdetail');
    }
    //申请报单中心
    public function request_is_center(){
        
        $this->jump($this->logicUser->request_is_center($this->param));
    }
    //点位升级
    public function upgrade(){
        $member = session('member_info2');
        if($member[0]['member_rank'] < $this->param['v']){

            $this->jump($this->logicUser->upgrade($this->param));

        }
        $this->error('只能往上升级');
    }
    //奖金明细
    public function bonusDetail(){
        $data = $this->logicUser->bonusDetail($this->param);
        $this->assign('data',$data);
        return $this->fetch('bonus_detail');
    }
}
