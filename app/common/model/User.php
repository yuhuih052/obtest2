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
class User extends ModelBase
{
	public function xiaji($level)
    {
    	ini_set('memory_limit','2048M');    // 临时设置最大内存占用为2G
    	set_time_limit(0);   // 设置脚本最大执行时间 为0 永不过期
    	
        $id = $this->getData('id');
        dump($id);die;
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
    
}
