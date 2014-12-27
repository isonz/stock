<?php
class Holder extends ABase
{
	static public $_class = __CLASS__;
	static public $_table = '#_holder';
	
	static function check($days, $ticker, $holder)
	{
		if(!$days || !$ticker || !$holder) return false;
		$info = self::getOne(array("ticker" => $ticker, "days" => $days, 'holder' => $holder), "id, type");
		return $info;
	}

	static function setData($data)
	{
		//DB::Debug();
		$days = isset($data['days']) ? $data['days'] : null;
		$ticker = isset($data['ticker']) ? $data['ticker'] : 0;
		$holder = isset($data['holder']) ? $data['holder'] : null;
		$info = self::check($days, $ticker, $holder);
		if(!$info){
			return self::insert($data);
		}else{
			//只保存第一次的结果
			if('zygd'== $data['type'] && false===stripos($info['type'], 'zygd')){
				if($info['type']) $data['type'] = $info['type'].','.$data['type'];
				return self::update(array("ticker" => $ticker, "days" => $days, 'holder' => $holder), $data);
			}
		}
	}
	
	static function getHolderInfoByName($holder)
	{
		if(!$holder) return false;
		//DB::Debug();
		$info = self::getList("holder='$holder'", '*', 'days DESC');
		return $info;
	}
	
}


