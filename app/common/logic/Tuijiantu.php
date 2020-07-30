<?php
namespace app\common\logic;

/**
 * 
 */
class Tuijiantu extends LogicBase
{
	
	public function Zhituitu($root_id){
		$root = $this->modelMember->select($root_id)->toArray();
		//dd($root);
		
		$root_one = $this->modelMember->where('re_id',$root[0]['id'])->select()->toArray();
                //dump($root_one);die;
			for($i = 0;$i<count($root_one);$i++){
			$root_two = $this->modelMember->where('re_id',$root_one[$i]['id'])->select()->toArray();
			
			}
			foreach ($root_two as $two) {
				if($two == null)continue;
				for($i = 0;$i < count($two);$i++){
					$root_three[] = $this->modelMember->where('re_id',$two[$i]['id'])->select()->toArray();	
					}
			}
			//dump(count($root));die;
			//dump($root_two);die;
			//dump($root_three);die;
		//dump($root_three[1][1]);die;

		$html = "<table border='1px'>";
        
        	$html .="<tr>";
        	foreach ($root as $r) {
        	$html .="<td>".$r['username']."</td><td>第一代</td><td>第二代</td><td>第三代</td>";
        }
        foreach ($root_one as $t) {
        	$html .= "<tr><td>&nbsp</td><td>".$t['username']."</td></tr>";
        }
        
         foreach ($root_two as $two) {
         	//dump($tre);die;
         		//$html .= "<tr>";
         		if($two == Null){
         			//$html .= "<td>&nbsp</td><td>&nbsp</td>";
         		}else{
         		for($i=0;$i<count($two);$i++){
        			$html .= "<tr><td>&nbsp</td><td>&nbsp</td><td>".$two[$i]['username']."</td></tr>";
        				}
        		}
        }
        foreach ($root_three as $tre) {
        	if($tre == Null){
         			//$html .= "<td>&nbsp</td><td>&nbsp</td><td>&nbsp</td>";
         		}else{
         		for($i=0;$i<count($tre);$i++){
        			$html .= "<tr><td>&nbsp</td><td>&nbsp</td><td>&nbsp</td><td>".$tre[$i]['username']."</td></tr>";
        				}
        		}
        }
        
        $html .= "</tr>";
        $html .= "</table>";
        return $html;

	}
	
}