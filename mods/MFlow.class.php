<?php
class MFlow extends ABase
{
	static public $_class = __CLASS__;
	static public $_table = '#_mflow';
	static private $_data = array();
	
	static public $r0_in = 0;
	static public $r1_in = 0;
	static public $r2_in = 0;
	static public $r3_in = 0;
	static public $r0_out = 0;
	static public $r1_out = 0;
	static public $r2_out = 0;
	static public $r3_out = 0;
	static public $r0 = 0;
	static public $r1 = 0;
	static public $r2 = 0;
	static public $r3 = 0;
	static public $curr_capital = 0;
	static public $trade = 0;
	static public $cvs = 0;
	static public $total = 0;
	
	static function getData($days, $ticker)
	{
		if(!$ticker || !$days) return false;
		self::$_data = self::getOne(array("days" => $days, "ticker" => $ticker), "*");
		return self::$_data;
	}

	static function setData($ticker, $data)
	{
		//DB::Debug();
		if(isset($data['name'])) unset($data['name']);
		if(isset($data['opendate'])) unset($data['opendate']);
		if(isset($data['ticktime'])) unset($data['ticktime']);
		
		$days = date('Y-m-d');
		$data['days'] = $days;
		$data['ticker']	= $ticker;
		$data['data'] = json_encode($data);

		$info = self::getStock($days, $ticker);
		if(!$info){
			return self::insert($data);
		}else{
			unset($data['days'], $data['ticker']);
			return self::update(array("days" => $days, "ticker" => $ticker), $data);
		}
	}
	
	static function initInfoData($days, $ticker)
	{
		if(!self::$_data) self::getData($days, $ticker);
		$data = self::$_data;
		
		self::$r0_in = isset($data['r0_in']) ? $data['r0_in'] : 0;
		self::$r1_in = isset($data['r1_in']) ? $data['r1_in'] : 0;
		self::$r0_out = isset($data['r0_out']) ? $data['r0_out'] : 0;
		self::$r1_out = isset($data['r1_out']) ? $data['r1_out'] : 0;
		self::$r3_in = isset($data['r3_in']) ? $data['r3_in'] : 0;
		self::$r2_in = isset($data['r2_in']) ? $data['r2_in'] : 0;
		self::$r3_out = isset($data['r3_out']) ? $data['r3_out'] : 0;
		self::$r2_out = isset($data['r2_out']) ? $data['r2_out'] : 0;
		self::$r0 = isset($data['r0']) ? $data['r0'] : 0;
		self::$r1 = isset($data['r1']) ? $data['r1'] : 0;
		self::$r2 = isset($data['r2']) ? $data['r2'] : 0;
		self::$r3 = isset($data['r3']) ? $data['r3'] : 0;
		
		self::$curr_capital = isset($data['curr_capital']) ? $data['curr_capital'] : 0;
		self::$trade = isset($data['trade']) ? $data['trade'] : 0;
		
		self::$cvs = self::$curr_capital * self::$trade * 10000;
		self::$total = self::$r0 + self::$r1 + self::$r2 + self::$r3;
	}
	
	//主力、散户资金流向
	static function getMainRetailFlows($days, $ticker)
	{
		self::initInfoData($days, $ticker);
		$mainIn 	= self::$r0_in + self::$r1_in;
		$mainInP 	= $mainIn / self::$total;
		$mainOut 	= self::$r0_out + self::$r1_out;
		$mainOutP 	= $mainOut / self::$total;
		$retailIn 	= self::$r3_in + self::$r2_in;
		$retailInP 	= $retailIn / self::$total;
		$retailOut	= self::$r3_out + self::$r2_out;
		$retailOutP = $retailOut / self::$total;
		
		$folws = array(
			'mainin'	=> array($mainIn, $mainInP),
			'mainout'	=> array($mainOut, $mainOutP),
			'retailin'	=> array($retailIn, $retailInP),
			'retailout'	=> array($retailOut, $retailOutP),
		);
		return $folws;
	}
	
	//分类资金净流入额
	static function getCategoryFlows($days, $ticker)
	{
		self::initInfoData($days, $ticker);
		
		$r3_r_in = self::$r3_in - self::$r3_out;
		$r2_r_in = self::$r2_in - self::$r2_out;
		$r1_r_in = self::$r1_in - self::$r1_out;
		$r0_r_in = self::$r0_in - self::$r0_out;

		$r3_p_svs = self::$r3 / self::$cvs;
		$r2_p_svs = self::$r2 / self::$cvs;
		$r1_p_svs = self::$r1 / self::$cvs;
		$r0_p_svs = self::$r0 / self::$cvs;
		
		$r3_p_turnover = self::$r3 / self::$total;
		$r2_p_turnover = self::$r2 / self::$total;
		$r1_p_turnover = self::$r1 / self::$total;
		$r0_p_turnover = self::$r0 / self::$total;
		
		$flows = array(
			'r3'	=> array($r3_r_in, $r3_p_svs, $r3_p_turnover),
			'r2'	=> array($r2_r_in, $r2_p_svs, $r2_p_turnover),
			'r1'	=> array($r1_r_in, $r1_p_svs, $r1_p_turnover),
			'r0'	=> array($r0_r_in, $r0_p_svs, $r0_p_turnover),
		);
		return $flows;
	}
	
}


