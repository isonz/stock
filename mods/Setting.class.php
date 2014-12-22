<?php
class Setting extends ABase
{
	static public $_class = __CLASS__;
	static public $_table = '#_setting';
	
	static function getValue($name)
	{
		if(!$name) return false;
		$info = self::getOne(array("name" => $name), "value");
		if(!$info) return array();
		$value = isset($info['value']) ? $info['value'] : null;
		return $value;
	}

	static function setValue($name, $value)
	{
		//DB::Debug();
		$val = self::getValue($name);
		if(is_array($val)){
			self::insert(array('name'=>$name, 'value'=>$value));
		}else{
			self::update(array('name'=>$name), array('value'=>$value));
		}
	}
	
}


