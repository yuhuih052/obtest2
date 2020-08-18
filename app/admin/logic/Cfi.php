<?php
namespace app\admin\logic;

/**
 * 
 */
class Cfi extends AdminBase
{	
	 //挂卖列表
	 public function sellCfiList($data)
     {
       
         $data = $this->modelShop->where('sell_a','>',0)->select();
         if(count($data) > 0){
	         //根据statuss值修改内容
	         foreach ($data as $key => $da) {
	       
	 	        switch ($da['statuss']) {
	 	        	case '1':
	 	        		$da['statuss'] = '挂卖中';
	 	        		break;
	 	        	case '2':
	 	        		$da['statuss'] = '交易完成';
	 	        		break;
	 	        	case '3':
	 	        		$da['statuss'] = '订单关闭';
	 	        		break;
	 	        	default:
	 	        		# code...
	 	        		break;
	 	        }
		        
	 	        return $data;
 	
         	}
         }else{
         	return $data;
         }
     }

     //挂买列表
	 public function buyCfiList($data)
     {
       
         $data = $this->modelShop->where('buy','>',0)->select();
         if(count($data) > 0){
	         //根据statuss值修改内容
	         foreach ($data as $key => $da) {
	       
	 	        switch ($da['statuss']) {
	 	        	case '1':
	 	        		$da['statuss'] = '挂买中';
	 	        		break;
	 	        	case '2':
	 	        		$da['statuss'] = '交易完成';
	 	        		break;
	 	        	case '3':
	 	        		$da['statuss'] = '订单关闭';
	 	        		break;
	 	        	default:
	 	        		# code...
	 	        		break;
	 	        }
		        
	 	        return $data;
 	
         	}
         }else{
         	return $data;
         }
     }

     public function dealDetail3($data){
        $dat = $this->modelDealRecord->where('shop_id','=',$data['id'])->select();
        if(count($dat)>0){
            return $dat[0]['shop_id'];
        }else{
            return 0;
        }
     }
    //获取详细交易信息
    public function dealDetail4($data){

        return $this->modelDealRecord->where('shop_id','=',$data['id'])->select();
    }
    /**************************************************************/
	public function buyCfi($data){
		return $this->modelShop->where('buy','>',0)->select();
	}
	public function sellCfi($data){
		return $this->modelShop->where('sell_a','>',0)->select();
	}
	public function dealDetail($data){
		return $this->modelDealRecord->where('shop_id',$data['id'])->select();
	}
}