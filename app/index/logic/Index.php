<?php
namespace app\index\logic;

/**
 * 
 */
class Index extends IndexBase
{
	
	public function indexnew(){
		return $this->modelArticle->where('category_id',7)
									->order('create_time', 'desc')
										->limit(1)
										->select();
	}
}