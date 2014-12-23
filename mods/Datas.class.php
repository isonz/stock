<?php
class Datas extends ABase
{
	static public $_class = __CLASS__;
	static public $_table = '#_datas';
	
	static function getData($ticker)
	{
		$days = strtotime(date('Y-m-d'));
		if(!$ticker) return false;
		
		$info = self::getOne(array("ticker" => $ticker, "days" => $days), "*");
		return $info;
	}

	static function setData($ticker, $data)
	{
		//DB::Debug();
		$days = strtotime(date('Y-m-d'));
		$info = self::getData($ticker);
		if(!$info){
			$data['days'] = $days;
			$data["ticker"] = $ticker;
			$name = $data['name'];
			unset($data['name']);
			Stock::setStock($ticker, $name);
			return self::insert($data);
		}else{
			return self::update(array('ticker'=>$ticker, "days" => $days), $data);
		}
	}
	
}


