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

namespace app\common\model;

/**
 * 会员模型
 */
class Member extends ModelBase
{
    private $flag = '0';

	public function xiaji($level)
    {
    	ini_set('memory_limit','2048M');    // 临时设置最大内存占用为2G
    	set_time_limit(0);   // 设置脚本最大执行时间 为0 永不过期
    	//dump($level);die;
        $id = $this->getData('id');

        $username = $this->getData('username');
        $re_path = $this->getData('p_path');
        $re_level = $this->getData('p_level');
        $like = $re_path ? $re_path . $username . ',%' : ',' . $username . ',%';
        $where = [
            ['p_path', 'like', $like],
            ['p_level', '=', $re_level + $level],
        ];
        
 
//         dd($this->modelMember->where($where)->fetchSql(true)->select());
//         return $level;
//         return $this->modelMember->where($where)->select();
        return $this->modelMember->where($where)->paginate(DB_LIST_ROWS, false, ['query' => request()->param()]);
    }

     //生成顶层
    public function systemTree($id)
    {

        $html = '';
        //占比根据层数的不同改变，最顶层为100，下一层是上一次的1/2，被除数的当前层数的个数
        $few = 0;
        $accounted = 100 / ($few + 1);
        $color = '#E6E8FA';
        $list = $this->where('id', $id)->find();
        $color = '';
        $l = '';
        //顶部
        $level = '经理';
        $sum = $this->getMemberAll($list['id']);
        $top = '<table width="100%" border="0" cellpadding="1" cellspacing="1">';
        $top .= '       <tbody>';
        $top .= '           <tr align="center">';
        //层数，每层所占比例 （$accounted），当前层数（$few）
        $layer_number1 = '          <td class="borderlrt" width="' . $accounted . '%" valign="top" title="第1层"><img width="12" height="0"><br>';
        //Generated 生成表格
        $generated = '                  <table border="0" cellpadding="0" cellspacing="1" class="tu_box" style="width:100px;">';
        $generated .= '                     <tbody>';
        $generated .= '                         <tr style="border:#000000 1px solid;height:30px;">';
        $generated .= '                             <td colspan="3" style="background:#E6E8FA;font-weight:bold;text-align: center;">';
        $generated .= '                                 <a href="' . url('index/member/system', ['id' => $list['id']]) . '">' . $list['username'] . '<span class="badge">' . $l . '</span></a>' . '</a>';
        $generated .= '                             </td>';
        $generated .= '                         </tr>';
        $generated .= '                         <tr style="border:#000000 1px solid;height:30px;">';
        $generated .= '                             <td colspan="3" style="background:#DDD000;text-align: center;" ;="">';
        $generated .= '                                 <a class="title" href="#" title="会员编号：' . $list['username'] . '|手机：' . $list['mobile'] . '">' . $level . '</a>';
        $generated .= '                             </td>';
        $generated .= '                         </tr>';
        $generated .= '                         <tr style="border:#000000 1px solid;height:30px;">';
        $generated .= '                             <td class="tu_l" style="background:#DDD000;text-align: center;width:40%;border:#000000 1px solid;" ;="">' . $list['yejil_Total'] . '</td>';
        $generated .= '                             <td class="tu_z" style="background:#DDD000;text-align: center;width:20%;border:#000000 1px solid;" ;="">總</td>';
        $generated .= '                             <td class="tu_r" style="background:#DDD000;text-align: center;width:40%;border:#000000 1px solid;" ;="">' . $list['yejir_Total'] . '</td>';
        $generated .= '                         </tr>';
        $generated .= '                         <tr style="border:#000000 1px solid;height:30px;">';
        $generated .= '                             <td class="tu_l" style="background:#DDD000;text-align: center;width:40%;border:#000000 1px solid;" ;="">' . $list['LiftChild'] . '</td>';
        $generated .= '                             <td class="tu_z" style="background:#DDD000;text-align: center;width:20%;border:#000000 1px solid;" ;="">餘</td>';
        $generated .= '                             <td class="tu_r" style="background:#DDD000;text-align: center;width:40%;border:#000000 1px solid;" ;="">' . $list['RightChild'] . '</td>';
        $generated .= '                         </tr>';
        $generated .= '                         <tr style="border:#000000 1px solid;height:30px;">';
        $generated .= '                             <td class="tu_l" style="background:#DDD000;text-align: center;width:40%;border:#000000 1px solid;" ;="">' . $sum['left'] . '</td>';
        $generated .= '                             <td class="tu_z" style="background:#DDD000;text-align: center;width:20%;border:#000000 1px solid;" ;="">人數</td>';
        $generated .= '                             <td class="tu_r" style="background:#DDD000;text-align: center;width:40%;border:#000000 1px solid;" ;="">' . $sum['right'] . '</td>';
        $generated .= '                         </tr>';
        $generated .= '                     </tbody>';
        $generated .= '                 </table>';
        $layer_number2 = '          </td>';
        $footer_t = '           </tr>';
        $footer_t .= '          <tr align="center">';
        //生成线条，第一次一条，循环一次加加一倍 ，2的多次方
        $line = '               <td class="borderno" width="' . $accounted . '%" valign="top"><img src="/static/module/admin/img/t_tree_bottom_l.gif" height="20"><img src="/static/module/admin/img/t_tree_line.gif" width="25%" height="20"><img src="/static/module/admin/img/t_tree_top.gif" height="20" alt="顶层"><img src="/static/module/admin/img/t_tree_line.gif" width="25%" height="20"><img src="/static/module/admin/img/t_tree_bottom_r.gif" height="20"></td>';
        //底部
        $footer = '         </tr>';
        $footer .= '        </tbody>';
        $footer .= '    </table>';

        $father_id = $list['id'];
        $father_username = $list['username'];
        $html .= $top . $layer_number1 . $generated . $layer_number2 . $footer_t . $line . $footer;
        $html .= $this->systemTree2($father_id, $father_username, 50);
        //dd($html);
        return $html;

    }

    //生成第二层表格
    public function systemTree2($father_id, $father_username, $accounted)
    {


        $html = '';
        $reg_blank1 = '';
        $reg_blank2 = '';
        //大小
        $layer_number1 = '';
        //表格
        $generated = '';
        //线条
        $line = '';
        $line2 = '';
        //表格
        $center = '';
        $top = '<table width="100%" border="0" cellpadding="1" cellspacing="1">';
        $top .= '       <tbody>';
        $top .= '           <tr align="center">';

        $footer_t = '       </tr>';
        $footer_t .= '      <tr align="center">';

        $footer = '         </tr>';
        $footer .= '        </tbody>';
        $footer .= '    </table>';

        //获取所有会员信息
        $next_member = $this->where('father_id', $father_id)->order('treeplace asc')->select();
        $count = count($next_member);
        $third_layer = '';
        $linea = 4;
        $id = '';
        $tree = 0;
        if ($count == 2) {
            foreach ($next_member as $k => $v) {
                $l = '';
                $level = '经理';
                $sum = $this->getMemberAll($v['id']);
                $layer_number1 = '          <td class="borderlrt" width="' . $accounted . '%" valign="top" title="第' . $v['p_level'] . '层"><img width="12" height="0"><br>';
                //Generated 生成表格
                $generated = '                  <table border="0" cellpadding="0" cellspacing="1" class="tu_box" style="width:100px;">';
                $generated .= '                     <tbody>';
                $generated .= '                         <tr style="border:#000000 1px solid;height:30px;">';
                $generated .= '                             <td colspan="3" style="background:#E6E8FA;font-weight:bold;text-align: center;">';
                $generated .= '                                 <a href="' . url('index/user/system', ['id' => $v['id']]) . '">' . $v['username'] . '<span class="badge">' . $l . '</span></a>' . '</a>';
                $generated .= '                             </td>';
                $generated .= '                         </tr>';
                $generated .= '                         <tr style="border:#000000 1px solid;height:30px;">';
                $generated .= '                             <td colspan="3" style="background:#DDD000;text-align: center;" ;="">';
                $generated .= '                                 <a class="title" href="#" title="会员编号：' . $v['username'] . '|手机：' . $v['mobile'] . '">' . $level . '</a>';
                $generated .= '                             </td>';
                $generated .= '                         </tr>';
                $generated .= '                         <tr style="border:#000000 1px solid;height:30px;">';
                $generated .= '                             <td class="tu_l" style="background:#DDD000;text-align: center;width:40%;border:#000000 1px solid;" ;="">' . $v['yejil_Total'] . '</td>';
                $generated .= '                             <td class="tu_z" style="background:#DDD000;text-align: center;width:20%;border:#000000 1px solid;" ;="">總</td>';
                $generated .= '                             <td class="tu_r" style="background:#DDD000;text-align: center;width:40%;border:#000000 1px solid;" ;="">' . $v['yejir_Total'] . '</td>';
                $generated .= '                         </tr>';
                $generated .= '                         <tr style="border:#000000 1px solid;height:30px;">';
                $generated .= '                             <td class="tu_l" style="background:#DDD000;text-align: center;width:40%;border:#000000 1px solid;" ;="">' . $v['LiftChild'] . '</td>';
                $generated .= '                             <td class="tu_z" style="background:#DDD000;text-align: center;width:20%;border:#000000 1px solid;" ;="">餘</td>';
                $generated .= '                             <td class="tu_r" style="background:#DDD000;text-align: center;width:40%;border:#000000 1px solid;" ;="">' . $v['RightChild'] . '</td>';
                $generated .= '                         </tr>';
                $generated .= '                         <tr style="border:#000000 1px solid;height:30px;">';
                $generated .= '                             <td class="tu_l" style="background:#DDD000;text-align: center;width:40%;border:#000000 1px solid;" ;="">' . $sum['left'] . '</td>';
                $generated .= '                             <td class="tu_z" style="background:#DDD000;text-align: center;width:20%;border:#000000 1px solid;" ;="">人數</td>';
                $generated .= '                             <td class="tu_r" style="background:#DDD000;text-align: center;width:40%;border:#000000 1px solid;" ;="">' . $sum['right'] . '</td>';
                $generated .= '                         </tr>';
                $generated .= '                     </tbody>';
                $generated .= '                 </table>';

                $layer_number2 = '          </td>';


//              echo $center;die;
                //生成线条，第一次一条，循环一次加加一倍 ，2的多次方


                $center .= $layer_number1 . $generated . $layer_number2;
                $linea = 2;

                $id = $v['id'];
                $username = $v['username'];
                $third_layer .= $this->systemTree3($id, $username, $linea, 25);
            }
        } else if ($count == 1) {

            foreach ($next_member as $k => $v) {
                $l = '';
                //报单等级
                $level = '经理';
                //左右区人数
                $sum = $this->getMemberAll($v['id']);
                $reg_blank = '<td class="borderlrt" width="' . $accounted . '%" valign="top" title="第' . '2' . '层"><img width="12" height="0"><br>';
                $reg_blank .= ' <table border="0" cellpadding="0" cellspacing="1" class="tu_box">';
                $reg_blank .= '     <tbody>';
                $reg_blank .= '         <tr>';
                $reg_blank .= '             <td class="tu_ko">';
                $url = !$this->flag ? url('index/login/register', ['contact_name' => $v['username'], 'treeplace' => 0]) : '';
                $reg_blank .= '                 <button class="btn btn-default" type="button" onClick="location.href=' . '\'' . $url . '\'" target="_self" ' . $this->flag . '>點擊註冊</button>';
                $reg_blank .= '             </td>';
                $reg_blank .= '         </tr>';
                $reg_blank .= '     </tbody>';
                $reg_blank .= '    </table>';
                $reg_blank .= '</td>';
                $reg_blank2 = '<td class="borderlrt" width="' . $accounted . '%" valign="top" title="第' . '2' . '层"><img width="12" height="0"><br>';
                $reg_blank2 .= '    <table border="0" cellpadding="0" cellspacing="1" class="tu_box">';
                $reg_blank2 .= '        <tbody>';
                $reg_blank2 .= '            <tr>';
                $reg_blank2 .= '                <td class="tu_ko">';
                $url = !$this->flag ? url('index/login/register', ['contact_name' => $father_username, 'treeplace' => 1]) : '';
                $reg_blank2 .= '                    <button class="btn btn-default" type="button" onClick="location.href=' . '\'' . $url . '\'"  target="_self" ' . $this->flag . '>點擊註冊</button>';
                $reg_blank2 .= '                </td>';
                $reg_blank2 .= '            </tr>';
                $reg_blank2 .= '        </tbody>';
                $reg_blank2 .= '    </table>';
                $reg_blank2 .= '</td>';
                $layer_number1 = '          <td class="borderlrt" width="' . $accounted . '%" valign="top" title="第' . $v['p_level'] . '层"><img width="12" height="0"><br>';
                //Generated 生成表格
                $generated = '                  <table border="0" cellpadding="0" cellspacing="1" class="tu_box" style="width:100px;">';
                $generated .= '                     <tbody>';
                $generated .= '                         <tr style="border:#000000 1px solid;height:30px;">';
                $generated .= '                             <td colspan="3" style="background:#E6E8FA;font-weight:bold;text-align: center;">';
                $generated .= '                                 <a href="' . url('index/user/system', ['id' => $v['id']]) . '">' . $v['username'] . '<span class="badge pull-right">' . $l . '</span></a>' . '</a>';
                $generated .= '                             </td>';
                $generated .= '                         </tr>';
                $generated .= '                         <tr style="border:#000000 1px solid;height:30px;">';
                $generated .= '                             <td colspan="3" style="background:#DDD000;text-align: center;" ;="">';
                $generated .= '                                 <a class="title" href="#" title="会员编号：' . $v['username'] . '|手机：' . $v['mobile'] . '">' . $level . '</a>';
                $generated .= '                             </td>';
                $generated .= '                         </tr>';
                $generated .= '                         <tr style="border:#000000 1px solid;height:30px;">';
                $generated .= '                             <td class="tu_l" style="background:#DDD000;text-align: center;width:40%;border:#000000 1px solid;" ;="">' . $v['yejil_Total'] . '</td>';
                $generated .= '                             <td class="tu_z" style="background:#DDD000;text-align: center;width:20%;border:#000000 1px solid;" ;="">總</td>';
                $generated .= '                             <td class="tu_r" style="background:#DDD000;text-align: center;width:40%;border:#000000 1px solid;" ;="">' . $v['yejir_Total'] . '</td>';
                $generated .= '                         </tr>';
                $generated .= '                         <tr style="border:#000000 1px solid;height:30px;">';
                $generated .= '                             <td class="tu_l" style="background:#DDD000;text-align: center;width:40%;border:#000000 1px solid;" ;="">' . $v['LiftChild'] . '</td>';
                $generated .= '                             <td class="tu_z" style="background:#DDD000;text-align: center;width:20%;border:#000000 1px solid;" ;="">餘</td>';
                $generated .= '                             <td class="tu_r" style="background:#DDD000;text-align: center;width:40%;border:#000000 1px solid;" ;="">' . $v['RightChild'] . '</td>';
                $generated .= '                         </tr>';
                $generated .= '                         <tr style="border:#000000 1px solid;height:30px;">';
                $generated .= '                             <td class="tu_l" style="background:#DDD000;text-align: center;width:40%;border:#000000 1px solid;" ;="">' . $sum['left'] . '</td>';
                $generated .= '                             <td class="tu_z" style="background:#DDD000;text-align: center;width:20%;border:#000000 1px solid;" ;="">人數</td>';
                $generated .= '                             <td class="tu_r" style="background:#DDD000;text-align: center;width:40%;border:#000000 1px solid;" ;="">' . $sum['right'] . '</td>';
                $generated .= '                         </tr>';
                $generated .= '                     </tbody>';
                $generated .= '                 </table>';

                $layer_number2 = '          </td>';
                if ($v['treeplace'] == 0) {
                    $center .= $layer_number1 . $generated . $reg_blank2 . $layer_number2;
                    $linea = 0;
                } else {
                    $center .= $reg_blank . $layer_number1 . $generated . $layer_number2;
                    $linea = 1;
                }
                $id = $v['id'];
                $username = $v['username'];
                $third_layer .= $this->systemTree3($id, $username, $linea, 25);
            }
        } else {
            $reg_blank = '<td class="borderlrt" width="' . $accounted . '%" valign="top" title="第' . '2' . '层"><img width="12" height="0"><br>';
            $reg_blank .= ' <table border="0" cellpadding="0" cellspacing="1" class="tu_box">';
            $reg_blank .= '     <tbody>';
            $reg_blank .= '         <tr>';
            $reg_blank .= '             <td class="tu_ko">';
            $url = !$this->flag ? url('index/login/register', ['contact_name' => $father_username, 'treeplace' => 0]) : '';
            $reg_blank .= '                 <button class="btn btn-default" type="button" onClick="location.href=' . '\'' . $url . '\'"  target="_self" ' . $this->flag . '>點擊註冊</button>';
            $reg_blank .= '             </td>';
            $reg_blank .= '         </tr>';
            $reg_blank .= '     </tbody>';
            $reg_blank .= '    </table>';
            $reg_blank .= '</td>';
            $reg_blank .= '<td class="borderlrt" width="' . $accounted . '%" valign="top" title="第' . '2' . '层"><img width="12" height="0"><br>';
            $reg_blank .= ' <table border="0" cellpadding="0" cellspacing="1" class="tu_box">';
            $reg_blank .= '     <tbody>';
            $reg_blank .= '         <tr>';
            $reg_blank .= '             <td class="tu_ko">';
            $url = !$this->flag ? url('index/login/register', ['contact_name' => $father_username, 'treeplace' => 1]) : '';
            $reg_blank .= '                 <button class="btn btn-default" type="button" onClick="location.href=' . '\'' . $url . '\'"  target="_self" ' . $this->flag . '>點擊註冊</button>';
            $reg_blank .= '             </td>';
            $reg_blank .= '         </tr>';
            $reg_blank .= '     </tbody>';
            $reg_blank .= '    </table>';
            $reg_blank .= '</td>';

            $third_layer .= $this->systemTree3($father_id, '', 25);
            $center .= $reg_blank;

        }
        $lienone = '<td class="borderno" width="' . $accounted . '%" valign="top"></td>';
        $lientwe = '<td class="borderno" width="25%" valign="top"></td>';

        $line .= '  <td class="borderno" width="' . $accounted . '%" valign="top"><img src="/static/module/admin/img/t_tree_bottom_l.gif" height="20"><img src="/static/module/admin/img/t_tree_line.gif" width="25%" height="20"><img src="/static/module/admin/img/t_tree_top.gif" height="20" alt="顶层"><img src="/static/module/admin/img/t_tree_line.gif" width="25%" height="20"><img src="/static/module/admin/img/t_tree_bottom_r.gif" height="20"></td>';

        for ($i = 1; $i <= 2; $i++) {
            $line2 .= ' <td class="borderno" width="25%" valign="top"><img src="/y6032/Public/Images/tree/t_tree_bottom_l.gif" height="20"><img src="/y6032/Public/Images/tree/t_tree_line.gif" width="25%" height="20"><img src="/y6032/Public/Images/tree/t_tree_top.gif" height="20" alt="顶层"><img src="/y6032/Public/Images/tree/t_tree_line.gif" width="25%" height="20"><img src="/y6032/Public/Images/tree/t_tree_bottom_r.gif" height="20"></td>';
        }
//      if($father_username=''){
//          $line='';
//      }
        if ($linea == 0) {
            $line = $line . $lienone;
        } elseif ($linea == 1) {
            $line = $lienone . $line;
        } elseif ($linea == 2) {
            $line .= $line;
        } else {
            $line = '';
        }

        $html .= $top . $center . $footer_t . $line . $footer;
        $html .= $top . $third_layer . $footer_t . $lientwe . $lientwe . $lientwe . $lientwe . $footer;
//      echo $html2;die;
        return $html;
    }

    //生成第三层表格
    public function systemTree3($father_id, $father_username, $linea, $acc = 25)
    {
        //所在半区，0为左，1为右
//      $treeplace=0;
        $html = '';
        $reg_blank1 = '';
        $reg_blank2 = '';
        //大小
        $layer_number1 = '';
        //表格
        $generated = '';
        //线条
        $line = '';
        //表格
        $center = '';
        $reg_blankt = '<td class="borderlrt" width="' . $acc . '%" valign="top" title="第' . '3' . '层"><img width="12" height="0"><br>';
        $reg_blankt .= '    <table border="0" cellpadding="0" cellspacing="1" class="tu_box">';
        $reg_blankt .= '        <tbody>';
        $reg_blankt .= '            <tr>';
        $reg_blankt .= '                <td class="tu_ko">';
        $url = !$this->flag ? url('index/login/register', ['contact_name' => $father_username, 'treeplace' => 0]) : '';
        $reg_blank2 = '                 <button class="btn btn-default" type="button" onClick="location.href=' . '\'' . $url . '\'"  target="_self" ' . $this->flag . '>點擊註冊</button>';
        $reg_blankb = '             </td>';
        $reg_blankb .= '            </tr>';
        $reg_blankb .= '        </tbody>';
        $reg_blankb .= '    </table>';
        $reg_blankb .= '</td>';
        $url = !$this->flag ? url('index/login/register', ['contact_name' => $father_username, 'treeplace' => 1]) : '';
        $reg_blank22 = '    <button class="btn btn-default" type="button" onClick="location.href=' . '\'' . $url . '\'"  target="_self" ' . $this->flag . '>點擊註冊</button>';

        //获取所有会员信息
        $next_member = $this->where('father_id', $father_id)->order('treeplace asc')->select();
        $count = count($next_member);
//      echo $count;die;
        if ($count == 2) {

            if ($linea == 1) {
                $center .= $reg_blankt . $reg_blankb . $reg_blankt . $reg_blankb;
            }
            foreach ($next_member as $k => $v) {
                $sum = $this->getMemberAll($v['id']);
                $color = '';
                $l = '';
                $level = '经理';
                $layer_number1 = '          <td class="borderlrt" width="' . $acc . '%" valign="top" title="第' . $v['p_level'] . '层"><img width="12" height="0"><br>';
                //Generated 生成表格
                $generated = '                  <table border="0" cellpadding="0" cellspacing="1" class="tu_box" style="width:100px;">';
                $generated .= '                     <tbody>';
                $generated .= '                         <tr style="border:#000000 1px solid;height:30px;">';
                $generated .= '                             <td colspan="3" style="background:#E6E8FA;font-weight:bold;text-align: center;background:' . $color . '">';
                $generated .= '                                 <a href="' . url('index/user/system', ['id' => $v['id']]) . '">' . $v['username'] . '<span class="badge">' . $l . '</span></a>' . '</a>';
                $generated .= '                             </td>';
                $generated .= '                         </tr>';
                $generated .= '                         <tr style="border:#000000 1px solid;height:30px;">';
                $generated .= '                             <td colspan="3" style="background:#DDD000;text-align: center;" ;="">';
                $generated .= '                                 <a class="title" href="#" title="会员编号：' . $v['username'] . '|手机：' . $v['mobile'] . '">' . $level . '</a>';
                $generated .= '                             </td>';
                $generated .= '                         </tr>';
                $generated .= '                         <tr style="border:#000000 1px solid;height:30px;">';
                $generated .= '                             <td class="tu_l" style="background:#DDD000;text-align: center;width:40%;border:#000000 1px solid;" ;="">' . $v['yejil_Total'] . '</td>';
                $generated .= '                             <td class="tu_z" style="background:#DDD000;text-align: center;width:20%;border:#000000 1px solid;" ;="">總</td>';
                $generated .= '                             <td class="tu_r" style="background:#DDD000;text-align: center;width:40%;border:#000000 1px solid;" ;="">' . $v['yejir_Total'] . '</td>';
                $generated .= '                         </tr>';
                $generated .= '                         <tr style="border:#000000 1px solid;height:30px;">';
                $generated .= '                             <td class="tu_l" style="background:#DDD000;text-align: center;width:40%;border:#000000 1px solid;" ;="">' . $v['LiftChild'] . '</td>';
                $generated .= '                             <td class="tu_z" style="background:#DDD000;text-align: center;width:20%;border:#000000 1px solid;" ;="">餘</td>';
                $generated .= '                             <td class="tu_r" style="background:#DDD000;text-align: center;width:40%;border:#000000 1px solid;" ;="">' . $v['RightChild'] . '</td>';
                $generated .= '                         </tr>';
                $generated .= '                         <tr style="border:#000000 1px solid;height:30px;">';
                $generated .= '                             <td class="tu_l" style="background:#DDD000;text-align: center;width:40%;border:#000000 1px solid;" ;="">' . $sum['left'] . '</td>';
                $generated .= '                             <td class="tu_z" style="background:#DDD000;text-align: center;width:20%;border:#000000 1px solid;" ;="">人數</td>';
                $generated .= '                             <td class="tu_r" style="background:#DDD000;text-align: center;width:40%;border:#000000 1px solid;" ;="">' . $sum['right'] . '</td>';
                $generated .= '                         </tr>';
                $generated .= '                     </tbody>';
                $generated .= '                 </table>';

                $layer_number2 = '          </td>';


                //生成线条，第一次一条，循环一次加加一倍 ，2的多次方


                $center .= $layer_number1 . $generated . $layer_number2;

            }
            if ($linea == 0) {
                $center .= $reg_blankt . $reg_blankb . $reg_blankt . $reg_blankb;
            }
        } else if ($count == 1) {
            foreach ($next_member as $k => $v) {

                $l = '';
                $level = '经理';
                $sum = $this->getMemberAll($v['id']);
                $layer_number1 .= '         <td class="borderlrt" width="' . $acc . '%" valign="top" title="第' . $v['p_level'] . '层"><img width="12" height="0"><br>';
                //Generated 生成表格
                $generated = '                  <table border="0" cellpadding="0" cellspacing="1" class="tu_box" style="width:100px;">';
                $generated .= '                     <tbody>';
                $generated .= '                         <tr style="border:#000000 1px solid;height:30px;">';
                $generated .= '                             <td colspan="3" style="color:black;background:#E6E8FA;font-weight:bold;text-align: center;">';
                $generated .= '                                 <a href="' . url('index/user/system', ['id' => $v['id']]) . '">' . $v['username'] . '<span class="badge">' . $l . '</span></a>' . '</a>';
                $generated .= '                             </td>';
                $generated .= '                         </tr>';
                $generated .= '                         <tr style="border:#000000 1px solid;height:30px;">';
                $generated .= '                             <td colspan="3" style="background:#DDD000;text-align: center;" ;="">';
                $generated .= '                                 <a class="title" href="#" title="会员编号：' . $v['username'] . '|手机：' . $v['mobile'] . '">' . $level . '</a>';
                $generated .= '                             </td>';
                $generated .= '                         </tr>';
                $generated .= '                         <tr style="border:#000000 1px solid;height:30px;">';
                $generated .= '                             <td class="tu_l" style="background:#DDD000;text-align: center;width:40%;border:#000000 1px solid;" ;="">' . $v['yejil_Total'] . '</td>';
                $generated .= '                             <td class="tu_z" style="background:#DDD000;text-align: center;width:20%;border:#000000 1px solid;" ;="">總</td>';
                $generated .= '                             <td class="tu_r" style="background:#DDD000;text-align: center;width:40%;border:#000000 1px solid;" ;="">' . $v['yejir_Total'] . '</td>';
                $generated .= '                         </tr>';
                $generated .= '                         <tr style="border:#000000 1px solid;height:30px;">';
                $generated .= '                             <td class="tu_l" style="background:#DDD000;text-align: center;width:40%;border:#000000 1px solid;" ;="">' . $v['LiftChild'] . '</td>';
                $generated .= '                             <td class="tu_z" style="background:#DDD000;text-align: center;width:20%;border:#000000 1px solid;" ;="">餘</td>';
                $generated .= '                             <td class="tu_r" style="background:#DDD000;text-align: center;width:40%;border:#000000 1px solid;" ;="">' . $v['RightChild'] . '</td>';
                $generated .= '                         </tr>';
                $generated .= '                         <tr style="border:#000000 1px solid;height:30px;">';
                $generated .= '                             <td class="tu_l" style="background:#DDD000;text-align: center;width:40%;border:#000000 1px solid;" ;="">' . $sum['left'] . '</td>';
                $generated .= '                             <td class="tu_z" style="background:#DDD000;text-align: center;width:20%;border:#000000 1px solid;" ;="">人數</td>';
                $generated .= '                             <td class="tu_r" style="background:#DDD000;text-align: center;width:40%;border:#000000 1px solid;" ;="">' . $sum['right'] . '</td>';
                $generated .= '                         </tr>';
                $generated .= '                     </tbody>';
                $generated .= '                 </table>';

                $layer_number2 = '          </td>';

                if ($v['treeplace'] == 0) {
                    if ($linea == 1) {
                        $center .= $reg_blankt . $reg_blankb . $reg_blankt . $reg_blankb;
                    }
                    $center .= $layer_number1 . $generated . $layer_number2 . $reg_blankt . $reg_blank22 . $reg_blankb;
                } else {

                    $center .= $reg_blankt . $reg_blank2 . $reg_blankb . $layer_number1 . $generated . $layer_number2;
                    if ($linea == 1) {
                        $center .= $reg_blankt . $reg_blankb . $reg_blankt . $reg_blankb;
                    }
                }
            }
        } else {
            $reg_blankt = '<td class="borderlrt" width="' . $acc . '%" valign="top" title="第' . '3' . '层"><img width="12" height="0"><br>';
            $reg_blankt .= '    <table border="0" cellpadding="0" cellspacing="1" class="tu_box">';
            $reg_blankt .= '        <tbody>';
            $reg_blankt .= '            <tr>';
            $reg_blankt .= '                <td class="tu_ko">';
            $url = !$this->flag ? url('index/login/register', ['contact_name' => $father_username, 'treeplace' => 0]) : '';
            $reg_blank2 = '                 <button class="btn btn-default" type="button" onClick="location.href=' . '\'' . $url . '\'" target="_self" ' . $this->flag . '>點擊註冊</button>';
            $reg_blankb = '             </td>';
            $reg_blankb .= '            </tr>';
            $reg_blankb .= '        </tbody>';
            $reg_blankb .= '    </table>';
            $reg_blankb .= '</td>';

            $reg_blankt2 = '<td class="borderlrt" width="' . $acc . '%" valign="top" title="第' . '3' . '层"><img width="12" height="0"><br>';
            $reg_blankt2 .= '   <table border="0" cellpadding="0" cellspacing="1" class="tu_box">';
            $reg_blankt2 .= '       <tbody>';
            $reg_blankt2 .= '           <tr>';
            $reg_blankt2 .= '               <td class="tu_ko">';
            $url = !$this->flag ? url('index/login/register', ['contact_name' => $father_username, 'treeplace' => 1]) : '';
            $reg_blank22 = '                    <button class="btn btn-default" type="button" onClick="location.href=' . '\'' . $url . '\'" target="_self" ' . $this->flag . '>點擊註冊</button>';
            $reg_blankb2 = '                </td>';
            $reg_blankb2 .= '           </tr>';
            $reg_blankb2 .= '       </tbody>';
            $reg_blankb2 .= '    </table>';
            $reg_blankb2 .= '</td>';
            if ($linea == 0) {
                $center .= $reg_blankt . $reg_blank2 . $reg_blankb . $reg_blankt . $reg_blank2 . $reg_blankb . $reg_blankt . $reg_blankb . $reg_blankt . $reg_blankb;
            } elseif ($linea == 1) {

                $center .= $reg_blankt . $reg_blankb . $reg_blankt . $reg_blankb . $reg_blankt . $reg_blank2 . $reg_blankb . $reg_blankt . $reg_blank2 . $reg_blankb;

            } elseif ($linea == 2) {

                $center .= $reg_blankt . $reg_blank2 . $reg_blankb . $reg_blankt2 . $reg_blank22 . $reg_blankb2;
            } else {

                $center .= $reg_blankt . $reg_blankb . $reg_blankt . $reg_blankb;
            }
            // echo $center;die;
        }

        $html .= $center;

         //dd($html);
        return $html;
    }

    /**
     * 获取每个区域伞下的会员总数
     */
    public function getMemberAll($id)
    {
        $left = 0;
        $right = 0;

        foreach ($this->select() as $k => $v) {
            //dd($this->select());
            if($v['status'] == 1){
            $p_path = explode(',', $v['p_path_id']);
                if (in_array($id, $p_path)) {
                    $v['treeplace'] == 0 ? $left++ : $right++;

                }
            }
        }

        $arr['left'] = $left;
        $arr['right'] = $right;
       
        return $arr;
    }
    
}
