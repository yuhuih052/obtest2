<?php
namespace app\common\logic;

class Tree_2 extends LogicBase
{

    public function getTwoPathwayHtml($top_id, $layer_number)
    {
        $top = $this->modelMember->find($top_id);
        $member_array = [[$top]];
        for ($i = 1; $i < $layer_number; $i++) {
            $member_array[$i] = [];
            foreach ($member_array[$i - 1] as $m) {
                if ($m) {
                    $member_array[$i][] = $this->modelMember->where('father_id',$m->id)
                                                            ->where('treeplace',0)
                                                            ->find();
                    $member_array[$i][] = $this->modelMember->where('father_id',$m->id)
                                                            ->where('treeplace',1)
                                                            ->find();
                }else{
                    $member_array[$i][] = null;
                    $member_array[$i][] = null;
                }
            }
        }
        
         //dd($member_array);
        $td_num = pow(2, $layer_number - 1);

        $html = "<table>";
        foreach ($member_array as $tr) {
            $html .= "<tr>";
            foreach ($tr as $td) {
                if ($td) {
                    $html .= '<td colspan="' . $td_num . '">' . $this->createHtml($td) . '</td>';
                }else{
                    $html .= '<td colspan="' . $td_num . '">' . $this->createHtmlEmpty() . '</td>';
                }
            }
            $html .= "</tr>";
            $td_num /= 2;
        }
        $html .= "</table>";
        return $html;
    }
    
    private function createHtml($member)
    {

        $html = '<div class="td">';

        if ($member->totalChild == 0) {
            $style = 'style="color:red"';
        }else{
            $style =  'style="color:blue"';
        }

        $html .= '<div><a ' . $style . ' href="' . url('User/twoPathway', ['top_id' => $member->id]) . '">' . $member->username . '</a><br/>(总业绩:' . $member->totalChild . ')</div>';

        $html .= '<div style="float:left;width:49%;border-right:1px solid #ccc;">余:';
        $html .= $member->LiftChild . '<br/>';
      //  $html .= $member->left_yeji . '<br/>';
        $html .= '</div>';
        
        $html .= '<div style="float:right;width:49%;border-left:1px solid #ccc;">余:';
        $html .= $member->RightChild . '<br/>';
       // $html .= $member->right_yeji . '<br/>';
        $html .= '</div>';

        // if (request()->module() == 'admin') {
        //     $html .= '<a href="' . url('Member/twoPathway', ['top_id' => $member->id]) . '">' . $member->username . '</a>';
        // }else{
        //     $html .= '<span>' . $member->username . '</span>';
        // }
        //$html .= '<span>' . $member->level_html . '</span>';
        //$html .= '<span>' . $member->scale_html . '</span>';
        
        $html .= '</div>';

        return $html;
    }

    private function createHtmlEmpty()
    {
        $html = '<div class="td">空</div>';
        return $html;
    }

    // 小公排，获取位置信息
    public function getPlaceInfoTwo($leader){
    
    	
    	//$has = $this->modelMember->where([['is_inside', '=', 0]])->count();
    
    	// 如果他的第一层没排满，那么就先排第一层
    	$leader = $this->modelMember->where('username', '=', $leader['username'])->find();
        
    	$treeplace = $this->modelMember->where('father_id', '=', $leader['id'])->count();
  
    	if ($treeplace < 2) {
    		return [
    				'father_id' => $leader->id,
                    'father_name' => $leader->username,
    				'p_level'   => $leader->p_level + 1,
    				//'tp_path'   => $leader->tp_path ? $leader->tp_path . ',' . $treeplace : $treeplace,
    				'p_path_id'    => $leader->p_path_id ? $leader->p_path_id . $leader->id . ',' : ',' . $leader->id . ',',
                    'p_path'    => $leader->p_path ? $leader->p_path . $leader->username . ',' : ',' . $leader->username . ',',
    				'treeplace' => $treeplace,
    				'u_pai'     => (int)$leader['u_pai'] * 2 + $treeplace,
    		];
    	}
    	  	
    	$list = $this->modelMember->where('is_inside', '=', 0)
                                    ->where('p_level', '>=', $leader->p_level)
                                    ->where('p_path_id', 'like', "%,{$leader->id},%")
                                    ->order('p_level asc,u_num desc,u_pai asc')->select();
    	
    	//dd($list);
    	$xh = 0;
    	foreach ($list as $member) {

    		$xh += 1;
    		$treeplace = 5201314;
            $L = $this->modelMember->where('father_id', '=', $member->id)
                                    ->where('treeplace', '=', 0)->find();
            $R = $this->modelMember->where('father_id', '=', $member->id)
                                    ->where('treeplace', '=', 1)->find();
            //dd($R);
    		if (!$L) {
    			$treeplace = 0;
    		}
    		elseif(!$R){
    			$treeplace = 1;
    		}
    		/* if($member['p_level'] == 1){
    			$num = 2;
    		}
    		else{
    			$num = pow($member['p_level'],2);
    		}
    		
    		if($num == $xh && $treeplace == 0){
    			$xh = 1;
    		}
    		if($treeplace == 1 && $num == $xh){
    			break;
    		}
    		  */
//     		d($member->username);
    		//if($xh == 4){dd($member);}
    		
    		if ($treeplace != 5201314) {
    			
    			$member->u_num += 1;
    			$member->save();

    			return [
                        'father_name'=>$member->username,
    					'father_id' => $member->id,
    					'p_level'   => $member->p_level + 1,
    					//'tp_path'   => $member->tp_path ? $member->tp_path . ',' . $treeplace : $treeplace,
    					'p_path_id'    => $member->p_path_id ? $member->p_path_id . $member->id . ',' : ',' . $member->id . ',',
                        'p_path'    => $member->p_path ? $member->p_path . $member->username . ',' : ',' . $member->username . ',',
    					'treeplace' => $treeplace,
    					'u_pai'     => (int)$member['u_pai'] * 2 + $treeplace,
    			];
    		}
    	}
    	
    	/* foreach ($list as $member) {
    	
    		$treeplace = 5201314;
    		if(!$this->modelMember->where([['father_id', '=', $member->id], ['treeplace', '=', 1]])->find()){
    			$treeplace = 1;
    		}
    		
    		 
    		if ($treeplace == 1) {
    			return [
    					'leader_id' => $member->id,
    					'p_level'   => $member->p_level + 1,
    					'tp_path'   => $member->tp_path ? $member->tp_path . ',' . $treeplace : $treeplace,
    					'p_path'    => $member->p_path ? $member->p_path . $member->id . ',' : ',' . $member->id . ',',
    					'treeplace' => $treeplace,
    					'u_pai'     => (int)$member['u_pai'] * 2 + $treeplace,
    			];
    		}
    	} 
    	 */
    	
    }

    // 大公排，获取位置信息
    public function getPlaceInfo(){
        $info = [];
        
        foreach ($this->modelMember->where('is_inside', '=', 0)->order('id asc')->select() as $member) {
            $treeplace = 5201314;
            
            if (!$this->modelMember->where('father_id', '=', $member->id, ['treeplace', '=', 0])->find()) {
                $treeplace = 0;
            }elseif(!$this->modelMember->where(['father_id', '=', $member->id], ['treeplace', '=', 1])->find()){
                $treeplace = 1;
            }
            
            if ($treeplace != 5201314) {
                return [
                    'father_id' => $member->id,
                    'p_level'   => $member->p_level + 1,
                    'tp_path'   => $member->tp_path ? $member->tp_path . ',' . $treeplace : $treeplace,
                    'p_path'    => $member->p_path ? $member->p_path . $member->id . ',' : ',' . $member->id . ',',
                    'treeplace' => $treeplace,
                ];
            }
        }
    }

    
}
