<?php
namespace app\index\Logic;

/**
 * 
 */
class TreeAction extends IndexBase
{
    public function mem($UID){
        $where =array();
            $where['id'] = $UID;
            //$where['_string'] = 'id>='.$id;
            $field = '*';
            $rs = $this->modelMember ->where($where)->field($field)->find();
            return $rs;
    }

    public function two($maxLev,$where,$field){
                
                
                $rs    = $this->modelMember->where($where)->field($field)->order('treeplace asc')->select();
                return $rs;
    }
}