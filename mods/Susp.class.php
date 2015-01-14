<?php
class Susp extends ABase
{
	static public $_class = __CLASS__;
	static public $_table = '#_susp';
	
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
		$open = $data['open'];
		if($open<=0) return false;
		
		$name = $data['name'];
		unset($data);

		//--- 检查10天内是否有成交
		$pdd = self::getPreDaysData($ticker, 10);
		$pre = isset($pdd[0]) ? $pdd[0] : null;
		if($pre){
			if($pre['open'] > 0) return false;
		}
		//-- end 10天内是否成交
		
		$info = self::getData($ticker);
		if(!$info){
			$data['days'] = $days;
			$data["ticker"] = $ticker;
			Stock::setStock($ticker, $name);
			return self::insert($data);
		}
		return null;
	}
	
	static function getPreDaysData($ticker, $day_num=10, $from_date=0)
	{
		if(!$from_date) $from_date = strtotime(date('Y-m-d'));
		if(!$day_num) $day_num = 10;
		
		//DB::Debug();
		$table = Datas::$_table;
		$sql = "SELECT id, FROM_UNIXTIME(days, '%Y-%m-%d') AS date, ticker, trade, open FROM $table 
				WHERE ticker='$ticker' AND ($from_date-days)<(3600*24*$day_num)
				ORDER BY days DESC";
		$stmt = DB::Execute($sql);
		$datas = $stmt->fetchAll();
		return $datas;
	}
	
	
}


