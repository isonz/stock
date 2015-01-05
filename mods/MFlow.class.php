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

		$info = self::getData($days, $ticker);
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
		$mainInP 	= self::$total ? $mainIn / self::$total : 0;
		$mainOut 	= self::$r0_out + self::$r1_out;
		$mainOutP 	= self::$total ? $mainOut / self::$total : 0;
		$retailIn 	= self::$r3_in + self::$r2_in;
		$retailInP 	= self::$total ? $retailIn / self::$total : 0;
		$retailOut	= self::$r3_out + self::$r2_out;
		$retailOutP = self::$total ? $retailOut / self::$total : 0;
		
		$folws = array(
			'mainin'	=> array('i'=>$mainIn/10000, 'p'=>sprintf("%.5f",$mainInP)),
			'mainout'	=> array('i'=>$mainOut/10000, 'p'=>sprintf("%.5f",$mainOutP)),
			'retailin'	=> array('i'=>$retailIn/10000, 'p'=>sprintf("%.5f",$retailInP)),
			'retailout'	=> array('i'=>$retailOut/10000, 'p'=>sprintf("%.5f",$retailOutP)),
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

		$r3_p_svs = self::$cvs ? self::$r3 / self::$cvs : 0;
		$r2_p_svs = self::$cvs ? self::$r2 / self::$cvs : 0;
		$r1_p_svs = self::$cvs ? self::$r1 / self::$cvs : 0;
		$r0_p_svs = self::$cvs ? self::$r0 / self::$cvs : 0;
		
		$r3_p_turnover = self::$total ? self::$r3 / self::$total : 0;
		$r2_p_turnover = self::$total ? self::$r2 / self::$total : 0;
		$r1_p_turnover = self::$total ? self::$r1 / self::$total : 0;
		$r0_p_turnover = self::$total ? self::$r0 / self::$total : 0;
		
		$flows = array(
			'r3'	=> array('i'=>$r3_r_in/10000, 'p'=>sprintf("%.5f",$r3_p_svs), 't'=>sprintf("%.5f",$r3_p_turnover)),
			'r2'	=> array('i'=>$r2_r_in/10000, 'p'=>sprintf("%.5f",$r2_p_svs), 't'=>sprintf("%.5f",$r2_p_turnover)),
			'r1'	=> array('i'=>$r1_r_in/10000, 'p'=>sprintf("%.5f",$r1_p_svs), 't'=>sprintf("%.5f",$r1_p_turnover)),
			'r0'	=> array('i'=>$r0_r_in/10000, 'p'=>sprintf("%.5f",$r0_p_svs), 't'=>sprintf("%.5f",$r0_p_turnover)),
		);
		return $flows;
	}
	
}


