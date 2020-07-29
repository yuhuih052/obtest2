<?php
namespace app\index\Logic;

/**
 * 
 */
class TreeAction extends IndexBase
{
    //会员级别颜色
    function ji_Color(){
        $Color = array();
        $Color['1'] = "#E6E8FA";
        $Color['2'] = "#5CACEE";//"#E6E6FA";//"#DDA0DD";
        $Color['3'] = "#D9D919";
        $Color['4'] = "#FF5555";//"#FFFF00";
        $Color['5'] = "#9BCD9B";
        $Color['6'] = "#FFFF00";
        return $Color;
    }
    
    //会员级别颜色
    function ji_Color_B(){
        $Color = array();
        $Color['1'] = "#D9D919";
        $Color['2'] = "#5CACEE";//"#E6E6FA";//"#DDA0DD";
        $Color['3'] = "#D9D919";
        $Color['4'] = "#FF5555";//"#FFFF00";
        $Color['5'] = "#9BCD9B";
//      $Color['6'] = "#7F7F7F";
//      $Color['7'] = "#FFFF00";
        return $Color;
    }

//     function AC_Color(){
//         $HYJJ="";
//         //$this->_levelConfirm($HYJJ);
//         $Color = array();
//         $Color['1'] = $HYJJ[1];
//         $Color['2'] = $HYJJ[2];
//         $Color['3'] = $HYJJ[3];
//         $Color['4'] = $HYJJ[4];
//         $Color['5'] = $HYJJ[5];
//         $Color['6'] = $HYJJ[6];
// //      $Color['7'] = "#0066FF";
//         return $Color;
//     }

    //开通 未开通 报单中心
    // function Mi_Cheng(){
    //     $Color['0']  = '临时会员';
    //     $Color['1']  = '正式会员';
    //     $Color['2']  = '报单中心';//'报单中心';
    //     return $Color;
    // }

    function kd_Color(){
        $Color['0']    = '#C0C0C0';
        $Color['1']    = '#F5FFFA';
        $Color['2']    = '#DDA0DD';
        return $Color;
    }
    
    
    //双轨图
    public function Tree2(){
            $time = date('H');
            //$this->_checkUser();
            $ji_c = $this->ji_Color();  //级别颜色
            $kd_c = $this->kd_Color();  //是否开通
            //$mi_c = $this->Mi_Cheng();  //级别名称
            //$ac_c = $this->AC_Color();
            // $fee = M ('fee');
            // $fee_rs = $fee->field('i4')->find();

            // $i4 = $fee_rs['i4'];
            // if ($i4==0){
            //     $openm=1;
            // }else{
            //     $openm=0;
            // }

            //$fck   =  M('fck');
            $fee_rs = $this->modelMember->find();
            $id  = session('user_id');
            $UID = $id;
             //$UID = (int) $_GET['ID'];
             //if (empty($UID)){$UID = $id;}
    //         $UserID = $_POST['UserID'];  //跳转到 X 用户
    //         if (!empty($UserID)){
    //             if (strlen($UserID) > 20 ){
    //                 $this->error( '错误操作！');
    //                 exit;
    //             }
    //             $where = "p_path like '%,". $UID .",%' and user_id='". $UserID ."' ";
    // //			$where = "user_id='". $UserID ."' ";
    //             $field = 'id,is_boss';
    //             $rs = $fck ->where($where)->field($field)->find();
    //             if($rs == false){
    //                 $this->error('没有该用户!');
    //                 exit();
    //             }else{
    //                 $UID = $rs['id'];
    //             }
    //         }


            $where =array();
            $where['id'] = $UID;
            //$where['_string'] = 'id>='.$id;
            $field = '*';
            $rs = $this->modelMember ->where($where)->field($field)->find();
            if (!$rs){
                $this->error('没有该用户!');
                exit();
            }else{
                $ID			= $rs['id'];
                $UserID		= $rs['member_number'];
                $UserName	= $rs['username'];
                $TreePlace	= $rs['treeplace'];   //区分左右 0为左边,1为右边
                if($ID==$id){
                    $FatherID = $id;
                }else{
                    $FatherID	= $rs['father_id'];    //安置人ID
                }

                //$isPay		= $rs['is_pay'];		  //是否为正式(开通时为正式)
                //$isLock		= $rs['is_lock'];	  //锁定(是否可以登录系统)
                // /$uLevel		= $rs['u_level'];      //级别
                $pPath		= $rs['p_path'];       //自已的路径
                $pLevel		= $rs['p_level'];	  //层数(数字)
                //$Rid		= $rs['id'];
                $L			= $rs['LiftChild'];
                $R			= $rs['RightChild'];
                // $benqiL		= $rs['benqi_l'];//本期新增
                // $benqiR		= $rs['benqi_r'];
                // $SpareL		= $rs['shangqi_l'];//上期剩余
                // $SpareR		= $rs['shangqi_r'];
                // $zjj        = $rs['zjj'];        //总奖金
                // $user_tel   = $rs['user_tel'];
                // $qq   		= $rs['qq'];

                // $isagent	= $rs['is_agent'];  //
                // $cpzj 		= $rs['cpzj'];

    //			$LL=0;
    //			$RR=0;
    //			$this->todayindan($ID,$LL,$RR);
            }
            // if ($isPay>1) $isPay=1;


            // if($rs['is_agent'] > 1){
            //     $isPay = 2;    //服务中心
            // }

            //显示层数
            $uLev =4;// (int) $_GET['uLev'];		//$Lev 记录显示层数
            //if (is_numeric($uLev) == false) $uLev = $_SESSION['uLev2'];
            //if (is_numeric($uLev) == false) $uLev = 3;
            //if ($uLev < 2 || $uLev > 11)    $uLev = 3;
            $_SESSION['uLev2']=$uLev;
            $Nums = 0;
            for ($i=1;$i<=$uLev;$i++){
                $Nums = $Nums + pow(2,$i);		//pow(x,y) 返回x的y次方
            }
            global $TreeArray;
            $TreeArray = array();

            for ($i=1;$i<=$Nums;$i++){
                $TreeArray[$i] = "<table border='0' cellpadding='0' cellspacing='1' class='tu_box'><tr><td class='tu_ko'> 空位".$i." </td></tr></table>";
            }

            $bj = "style='background:#000';";  //表格背景色
            $StTab = "<table border='0' cellpadding='0' cellspacing='1' class='tu_box'><tr><td colspan='3' style='background:". $ji_c[1] .";font-weight:bold;'>";
            $MyYJ = "</td></tr>";
    //		$MyYJ .= "<tr><td colspan='3' $bj><a class='title' href='#' title='会员编号：".$UserID."|手机：".$user_tel."|QQ：".$qq."'>联系方式</a></td></tr>";
    //		$MyYJ .= "<tr><td colspan='3' $bj><a class='title' href='#' title='投资金额：".$cpzj."'>投资金额</a></td></tr>";
    // 		$MyYJ .= "<tr><td class='tu_l' $bj>$L</td><td class='tu_z' $bj>总</td><td class='tu_r' $bj>$R</td></tr>";
    //		$MyYJ .= "<tr><td class='tu_l' $bj>$SpareL</td><td class='tu_z' $bj>余</td><td class='tu_r' $bj>$SpareR</td></tr>";
    // 		$MyYJ .= "<tr><td class='tu_l' $bj>$benqiL</td><td class='tu_z' $bj>新</td><td class='tu_r' $bj>$benqiR</td></tr>";
            $MyYJ .= "</table>";



            $ZiJi   = $StTab."<a href='#'>". $UserID."</a>". $MyYJ;
            $Str4C0 = "<table  border='0' cellpadding='0' cellspacing='1' class='tu_box'><tr><td class='tu_ko'>";
            $Str4C1 = "<a>#</a>";
            $Str4C4 = "</td></tr></table>";
            //if ($isPay > 0){
                $i = pow(2,$uLev);
    //            $TreeArray['1'] = $Str4C0.$Str4C1."0/FID/". $ID ."' target='_self'>点击注册</a>". $Str4C4;
    //            $TreeArray[$i]  = $Str4C0.$Str4C1."1/FID/". $ID ."' target='_self'>点击注册</a>". $Str4C4;
            //}else{
    //			$i = pow(2,$uLev);
    //			$TreeArray['1']	= $Str4C0.$Str4C1."0/FID/".$ID."' target='_self'>点击注册</a>".$Str4C4;
    //			$TreeArray[$i]	= $Str4C0.$Str4C1."1/FID/".$ID."' target='_self'>点击注册</a>".$Str4C4;
            //}

            $TreeArray['0'] = $ZiJi;

            $this->Tree2_MtKass($UID, 0, $pLevel, $uLev, $Str4C0, $Str4C1, $Str4C4,  $TreeArray, $Nums);
            //会员ID,0,绝对层次,显示层高,表开始,表内链接,表结束  ,级别颜色数组,所有空位表格,显示多少会员数(包括空位数)
            $wop = '';
            $this->Tree2_showTree($uLev, $TreeArray, $wop);

            //$fee = M('fee');
            // $fee_rs = $fee->field('s10,s9')->find();
            // $Level = explode(',',$fee_rs['s10']);
            // $L_cpzj = explode(',',$fee_rs['s9']);

            // $this->assign('Level',$Level);
            // $this->assign('L_cpzj',$L_cpzj);

            //$this->assign('openm',$openm);
            $this->assign('ColorUA',$ji_c);
            $this->assign('TU_Color',$kd_c);
            $this->assign('TU_MiCheng',$mi_c);
            //$this->assign('AC_Color',$ac_c);
            $this->assign('UID',$UID);
            $this->assign('uLev',$uLev);
            $this->assign('FatherID',$FatherID);
            $this->assign('wop',$wop);
            $this->display('Tree2');

        }

        //双轨图---生成下层会员内容
        private function Tree2_MtKass($FatherID,$iL,$pLevel,$uLev,$Str4C0,$Str4C1,$Str4C4,&$TreeArray,$Nums){
            $ji_c = $this->ji_Color();  //级别颜色
            $kd_c = $this->kd_Color();  //是否开通
            //$mi_c = $this->Mi_Cheng();  //级别名称
            if (!empty($FatherID)){
                //$fck = M("fck");
                //$where = array();
                $maxLev = $pLevel+$uLev;
                $where = "father_id=". $FatherID ." and p_level<=". $maxLev ." and treeplace<2";
                $field = '*';
                $rs    = $this->modelMember->where($where)->field($field)->order('treeplace asc')->select();
                foreach($rs as $rss){
                    if ($rss['treeplace'] == 0){
                        $k = $iL + 1;
                    }else{
                        $i = ($pLevel + $uLev) - $rss['p_level'] + 1;
                        $j = pow(2,$i);
                        $k = $j + $iL;
                    }
                    $i			= ($pLevel + $uLev) - $rss['p_level'];
                    $Uo			= $k + 1;
                    $Yo			= $k + pow(2,($pLevel + $uLev) - $rss['p_level']);
                    //$Leve		= $rss['u_level'];	//用户级别
                    //$uisLock	= $rss['is_lock'];	//是否为正式会员
                    $Lo			= $rss['LiftChild'];		//
                    $Ro			= $rss['RightChild'];		//
                    //$SpareLo	= $rss['shangqi_l'];
                    //$SpareRo	= $rss['shangqi_r'];
                    //$benqiLo	= $rss['benqi_l'];
                    //$benqiRo	= $rss['benqi_r'];
                    $Rid		= $rss['id'];
                    $uUserID	= $rss['member_number'];
                    //$uisPay		= $rss['is_pay'];
                    $upLevel	= $rss['p_level'];
                    // $zjj        = $rss['zjj'];
                    // $uis_agent	= $rss['is_agent'];
                    // $uuser_tel	= $rss['user_tel'];
                    // $uqq		= $rss['qq'];
                    // $cpzj       = $rss['cpzj'];

    //				$LL=0;
    //				$RR=0;
    //				$this->todayindan($rss['id'],$LL,$RR);

                    // if ($uisPay>1) $uisPay=1;
                    // if($rss['is_agent'] > 0){
                    //     $uisPay = 2;    //服务中心
                    // }
                    $bj = "style='background:". $kd_c[1] .";'";  //表格背景色
                    $StTab = "<table border='0' cellpadding='0' cellspacing='1' class='tu_box'><tr><td colspan='3' style='background:".$ji_c[1].";font-weight:bold;'>";
                    $MyYJ = "</td></tr>";
    //				$MyYJ .= "<tr><td colspan='3' $bj><a class='title' href='#' title='会员编号：".$uUserID."|手机：".$uuser_tel."|QQ：".$uqq."'>联系方式</a></td></tr>";
    //				$MyYJ .= "<tr><td colspan='3' $bj><a class='title' href='#' title='投资金额：".$cpzj."'>投资金额</a></td></tr>";
    //				$MyYJ .= "<tr><td class='tu_l' $bj>$Lo</td><td class='tu_z' $bj>总</td><td class='tu_r' $bj>$Ro</td></tr>";
    //				$MyYJ .= "<tr><td class='tu_l' $bj>$SpareLo</td><td class='tu_z' $bj>余</td><td class='tu_r' $bj>$SpareRo</td></tr>";
    // 				$MyYJ .= "<tr><td class='tu_l' $bj>$benqiLo</td><td class='tu_z' $bj>新</td><td class='tu_r' $bj>$benqiRo</td></tr>";
                    $MyYJ .= "</table>";

                    //			$Str = $StTab."<a href='". __URL__ ."/PuTao/ID/". $Rid ."'>会员编号：". $uUserID ."</a>". $MyYJ;
                    $Str = $StTab."<a href='#'>". $uUserID ."</a>". $MyYJ;
                    $Str4C2 = "/FID/". $Rid ."'>点击注册</a>";

    //                 if ($uisPay > 0){
    //                     if ($Yo <= $Nums + 1 && $i>0){
    // //                        $TreeArray[$Uo] = $Str4C0. $Str4C1 ."0". $Str4C2 . $Str4C4;
    // //                        $TreeArray[$Yo] = $Str4C0. $Str4C1 ."1". $Str4C2 . $Str4C4;
    //                     }
    //                 }else{
    // //					if ($Yo<=$Nums+1 && $i>0){
    // //						$TreeArray[$Uo]=$Str4C0.$Str4C1."0".$Str4C2.$Str4C4;
    // //						$TreeArray[$Yo]=$Str4C0.$Str4C1."1".$Str4C2.$Str4C4;
    // //					}
    //                 }
                    $TreeArray[$k] = $Str;
                    if ($upLevel < $pLevel + $uLev){
                        //查出来的下级的绝对层	 //上级的绝对层,显示层数
                        $this->Tree2_MtKass($Rid, $k, $pLevel, $uLev, $Str4C0, $Str4C1, $Str4C4,  $TreeArray, $Nums, );
                    }
                }

            }
        }
    	//双轨图----生成表格内容
    	private function Tree2_showTree($uLev,$TreeArray,&$wop){
    					       //显示层高,所有空位表格,空
    		for ($i = 1;$i <= $uLev;$i++){
                $Nums = 0;
    			$Nums = $Nums + pow(2,$i);    //要显示用户的数量
    		}
    		$wid = 12;
    		$arr = array();
    		global $arrs;
    		$arrs = array();

    		for ($i = 0;$i <= $Nums;$i++){
    			$arr[$i] = $TreeArray[$i];
    		}

    		$arrs[0][0] = $arr;

    		for ($i = 1;$i <= $uLev;$i++){
    			for ($u = 1 ; $u <= pow(2,($i-1)) ; $u++){
    				$yyyo = $arrs[$i-1][$u-1];
    				$ta = array();
    				$tar = count($yyyo);
    				//echo $tar."<br>";
    				for ($ti = 0 ; $ti < $tar ; $ti++){
                        //dump($tar);die;
    					$ta[$ti] = $yyyo[$ti];

    				}
    				$to    = floor($tar/2)-1;
    				$tarr1 = array();
    				$tarr2 = array();

    				for ($tj = 0 ; $tj <= $to ; $tj++){
    					$tarr1[$tj] = $ta[$tj];
    					$tarr2[$tj] = $ta[$to+$tj+1];
    				}
    				$arrs[$i][($u-1)*2]   = $tarr1;
    				$arrs[$i][($u-1)*2+1] = $tarr2;
    			}
    		}

    		$lhe = 60;//行高
    		$tps = '/images/tree/';
    		$strL = "<img src='". $tps ."t_tree_bottom_l.gif' height='". $lhe ."'><img src='". $tps ."t_tree_line.gif' width='25%' height='". $lhe ."'><img src='". $tps . "t_tree_top.gif' height='". $lhe ."' alt='顶层'><img src='". $tps ."t_tree_line.gif' width='25%' height='". $lhe ."'><img src='". $tps ."t_tree_bottom_r.gif' height='". $lhe ."'>";

    		$strW = "<img width='" . $wid . "' height='0'><br />";

            $wop = '';

    		for ($i = 0  ;  $i <= $uLev  ;  $i++){
    			$wop .= "<table width='100%' border='0' cellpadding='1' cellspacing='1'>";
    			for ($t = 0  ;  $t <= 1  ;  $t++){
    				if ($t != 1 || $i != $uLev){
    					$wop.="<tr align='center'>";
    					$oop= pow(2,$i)-1;
    					for ($j = 0  ;  $j <= $oop ;  $j++){
    						$eop=100/pow(2,$i);
    						if ($t==1){
    							$wop.="<td class='borderno' width='". $eop ."%' valign='top'>";
    							$wop.=$strL;
    						}else{
    							$bcxx=$arrs[$i][$j][0];
    							$rp=$i+1;
    							$wop.="<td class='borderlrt' width='". $eop ."%' valign='top' title='第" . $rp . "层'>";
    							$wop.=$strW;
    							$wop.=$bcxx;
    						}
    						$wop.="</td>";
    					}
    					$wop.="</tr>";
    				}
    			}
    			$wop.="</table>";
    		}
    		$wop.="</td></tr></table>";
    	}
}