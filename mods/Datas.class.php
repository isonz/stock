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
			$name = $data['name'];
			unset($data['name']);
			Stock::setStock($ticker, $name);
			return self::update(array('ticker'=>$ticker, "days" => $days), $data);
		}
	}
	
	static function getOptimizationData($from_date=0, $day_num=0)
	{
		if(!$from_date) $from_date = 'unix_timestamp(DATE(NOW()))';
		if(!$day_num) $day_num = 10;
		//DB::Debug();
		$table = self::$_table;
		$stock_table = Stock::$_table;
		$sql = "SELECT a.*, c.name AS sname FROM 
				(SELECT id,days,ticker,trade,pricechange,changepercent FROM $table WHERE changepercent>=8 AND ($from_date-days)<(3600*24*$day_num)) AS a, 
				(SELECT id,days,ticker FROM $table WHERE changepercent>=8 AND ($from_date-days)<(3600*24*$day_num)) AS b,
				$stock_table AS c
				WHERE 
				c.ticker = a.ticker AND	a.ticker = b.ticker 
				ORDER BY a.ticker ASC, a.days DESC";
		$stmt = DB::Execute($sql);
		$datas = $stmt->fetchAll();
		$data = array();
		foreach ($datas as $dt){
			$dt['days'] = date('Y-m-d', $dt['days']);
			$data[$dt['ticker'].'_'.$dt['days']] = $dt;
		}
		unset($datas,$dt);
		$rs = array();
		foreach ($data as $i => $dti){
			$k=0;
			foreach ($data as $j => $dtj){
				if($dti['ticker'] == $dtj['ticker']) $k++;
			}
			$dti['numb'] = $k;
			$rs[$k.'_'.$i] = $dti;
		}
		unset($data,$dti,$dtj);
		
		krsort($rs);
		//print_r($rs);
		return $rs;
	}
}


