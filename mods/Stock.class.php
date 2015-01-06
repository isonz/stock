<?php
class Stock extends ABase
{
	static public $_class = __CLASS__;
	static public $_table = '#_stock';
	
	static function getStock($ticker)
	{
		if(!$ticker) return false;
		$info = self::getOne(array("ticker" => $ticker), "*");
		return $info;
	}

	static function setStock($ticker, $name)
	{
		//DB::Debug();
		$date_time = date('Y-m-d');
		$info = self::getStock($ticker);
		if(!$info){
			return self::insert(array('ticker'=>$ticker, 'name'=>$name, 'update_at'=>$date_time, "created_at"=>$date_time));
		}else{
			return self::update(array('ticker'=>$ticker), array('name'=>$name, 'update_at'=>$date_time));
		}
	}
	
	static function tickerToNumber($ticker)
	{
		return substr($ticker, 2);
	}
	
	//最近10天上市
	static function getNewStock($date_num = 10)
	{
		$today = date('Y-m-d');
		$time = date('Y-m-d', strtotime("$today -$date_num day"));
		//DB::Debug();
		$info = self::getList("created_at>='$time'","*", "created_at DESC");
		return $info;
	}
}


