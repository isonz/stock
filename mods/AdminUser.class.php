<?php
class AdminUser extends ABase
{
	static public $_class = __CLASS__;
	static public $_table = '#_admin_user';
	static public $_check_online_time = 30; 	//second
	
	
	static function getOnlineUsers($organize_id)
	{
		$users = array();
		if(!$organize_id) return $users;
			
		$info = self::getList("organize_id=$organize_id");
		foreach ($info as $data){
			if( (time() - $data["is_online_bytime"]) <= self::$_check_online_time){
				$users[] = array("username" => $data['username'], "nickname" => $data['nickname']);
			}
		}
		return $users;
	}

	static function getOnlineStatus($organize_id, $username)
	{
		if(!$username || !$organize_id) return false;
		$info = self::getOne(array("username" => $username, "organize_id" => $organize_id), "is_online_bytime");
		$is_online_bytime = $info['is_online_bytime'];
		if($is_online_bytime >= time() - self::$_check_online_time) return true;
		return false;
	}
	
	static function selectAStaffToCustomer($sessionid, $organize_id)
	{
		if(!$organize_id || !$sessionid) return array();
		$user = FrontUser::getStaff($sessionid, $organize_id);
		if($user['staff']){
			$username = $user['staff'];
			if(self::getOnlineStatus($organize_id, $username)){
				$nickname = self::getNickName($username, $organize_id);
				return array("username"=>$username, "nickname"=>$nickname);
			}
		}
		$to_user = array("username"=>'', "nickname"=>'');
		$onlie_users = self::getOnlineUsers($organize_id);
		$on_len = count($onlie_users);
		if($on_len > 0){
			$to_user = $onlie_users[rand(0, ($on_len-1))];
		}
		return $to_user;
	}
	
	static function getNickName($username, $organize_id)
	{
		if(!$username || !$organize_id) return null;
		$info = self::getOne(array("username" => $username, "organize_id" => $organize_id), "nickname");
		return isset($info['nickname']) ? $info['nickname'] : null;
	}

	
}


