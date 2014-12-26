<?php
Abstract class ABase
{	
	static protected $_class = __CLASS__;
	
	static protected function _init()
	{
		if(__CLASS__ == static::$_class){
			exit('Cannot invoking abstract class '.__CLASS__);
		}
	}
	
	static function insert(array $data)
	{
		self::_init();
		return DB::Insert(static::$_table, $data);
	}
	
	static function check($options = array())
	{
		self::_init();
		$options['one'] = 1;
		return DB::LimitQuery(static::$_table, $options);
	}
	
	static function getAll()
	{
		self::_init();
		return DB::getRows(static::$_table);
	}
	
	static function getOne(array $where,  $select = '*')
	{
		if(!$where) return false;
		self::_init();
		return DB::getRow($where, static::$_table, $select);
	}
	
	static function getList($where,  $select = '*', $order='id DESC')
	{
		if(!$where) return false;
		self::_init();
		return DB::getRows(static::$_table, $where, $select, $order);
	}
	
    static function update($condition, array $data)
    {
    	self::_init();
    	return DB::update(static::$_table, $condition, $data);
    }
        
    static function del($id)
    {
    	if(!$id) return false;
    	self::_init();
    	$condition = array('id' => $id);
    	return DB::Delete(static::$_table, $condition);
    }

    static function paging($page, $page_size=50, $where = null, $order = null, $select = null)
    {
    	self::_init();
    	$data = Paging::getData(static::$_table, $page, $page_size, $where, $order, $select);
    	$paging = Paging::getPage();
    	$result['data'] = $data;
    	$result['page'] = $paging;
    	 
    	return $result;
    }
    
    static function log($error)
    {
    	error_log(date('Y-m-d H:i:s').", Error:".$error."\n\t", 3, _LOGS . 'code.'.date('Ymd').'.log');
    }
    
}


