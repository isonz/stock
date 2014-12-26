<?php
class TmpData extends ABase
{
	static public $_class = __CLASS__;
	static public $_table = '#_tmp_data';
	
	static function check($days, $type)
	{
		if(!$days || !$type) return false;
		$info = self::getOne(array("days" => $days, 'type' => $type), "id");
		return $info;
	}

	static function setData($data)
	{
		DB::Debug();
		$days = date('Y-m-d');
		$type = isset($data['type']) ? $data['type'] : 0;
		$info = self::check($days, $type);
		if(!$info){
			$data['days'] = $days;
			return self::insert($data);
		}else{
			$table = self::$_table;
			$dt = "~||~".$data['data'];
			$sql = "UPDATE $table SET data=concat(data,'$dt') WHERE days='$days' AND type='$type'";
			return DB::Execute($sql);
		}
	}
	
}


