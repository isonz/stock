<?php
class Func
{
	//时间格式转换
	static public function getTime($time = 0)
	{
		if(!$time) return date('Y-m-d H:i:s');
		if((int)$time > 0) return date('Y-m-d H:i:s', $time);
		return strtotime($time);
	}

	//获取客户端IP地址
	static public function getIP()
	{
		if (getenv('HTTP_CLIENT_IP')){
      		$ip = getenv('HTTP_CLIENT_IP'); 
     	}elseif (getenv('HTTP_X_FORWARDED_FOR')){
      		$ip = getenv('HTTP_X_FORWARDED_FOR');
		}elseif (getenv('HTTP_X_FORWARDED')){ 
         	$ip = getenv('HTTP_X_FORWARDED');
     	}elseif (getenv('HTTP_FORWARDED_FOR')){
         	$ip = getenv('HTTP_FORWARDED_FOR');
		}elseif (getenv('HTTP_FORWARDED')){
         	$ip = getenv('HTTP_FORWARDED');
     	}else {
          	$ip = $_SERVER['REMOTE_ADDR'];
     	}
     	return $ip;
	}
	
	//获取当前完整的带参数的URL
	static public function getCurrentURL()
	{
		$pageURL = 'http';
		if ($_SERVER["HTTPS"] == "on"){
			$pageURL .= "s";
		}$pageURL .= "://";
	
		if ($_SERVER["SERVER_PORT"] != "80"){
			$pageURL .= $_SERVER["SERVER_NAME"] . ":" . $_SERVER["SERVER_PORT"] . $_SERVER["REQUEST_URI"];
		}else{
			$pageURL .= $_SERVER["SERVER_NAME"] . $_SERVER["REQUEST_URI"];
		}
		return $pageURL;
	}
	
	//产生随机码 $n 为随机码长度
	static function getRandomCode($n)
	{
		$tt = null;
		$ss=array(
			'a','b','c','d','e','f','g','h','i','j','k','l','m','n','o','p','q','r','s','t','u','v','w','x','y','z',
			'A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z',
			'1','2','3','4','5','6','7','8','9','0'
		);
		for ($i=0; $i<$n; $i++){
			$tt .= $ss[rand(0, 61)];
		} 
		return $tt;
	}

	//动态加密字符串
	static public function encodeStr($tex, $type = "encode", $key = "key123@ison")
    {
		$chrArr = array(
					'a','b','c','d','e','f','g','h','i','j','k','l','m','n','o','p','q','r','s','t','u','v','w','x','y','z',
	                'A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z',
	                '0','1','2','3','4','5','6','7','8','9'
				);
	    if($type=="decode"){
	        if(strlen($tex)<14)return false;
	        $verity_str=substr($tex, 0,8);
	        $tex=substr($tex, 8);
	        if($verity_str!=substr(md5($tex),0,8)){
	            //完整性验证失败
	            return false;
	        }
	    }
	    $key_b = $type == "decode" ? substr($tex,0,6):$chrArr[rand()%62].$chrArr[rand()%62].$chrArr[rand()%62].$chrArr[rand()%62].$chrArr[rand()%62].$chrArr[rand()%62];
	    $rand_key = $key_b.$key;
	    $rand_key=md5($rand_key);
	    $tex=$type=="decode"?base64_decode(substr($tex, 6)):$tex;
	    $texlen=strlen($tex);
	    $reslutstr="";
	    for($i=0;$i<$texlen;$i++){
	        $reslutstr.=$tex{$i}^$rand_key{$i%32};
	    }
	    if($type!="decode"){
	        $reslutstr=trim($key_b.base64_encode($reslutstr),"==");
	        $reslutstr=substr(md5($reslutstr), 0,8).$reslutstr;
	    }
	    return $reslutstr;
	}
	
	//程序运行内存消耗
	static public function showMemory()
	{
		$size = memory_get_usage(true);
		$unit=array('b','kb','mb','gb','tb','pb');
		$size = @round($size/pow(1024,($i=floor(log($size,1024)))),2).' '.$unit[$i];
		echo '<br>程序运行内存消耗: ' . $size;
	}
	
	/* 无限极分类树形
	 * $items = array(
     *			1 => array('id' => 1, 'pid' => 0, 'name' => '安徽省'),
     *			2 => array('id' => 2, 'pid' => 0, 'name' => '浙江省'),
     *			3 => array('id' => 3, 'pid' => 1, 'name' => '合肥市'),
     *			4 => array('id' => 4, 'pid' => 3, 'name' => '长丰县'),
     *			5 => array('id' => 5, 'pid' => 1, 'name' => '安庆市'),
     * );
     * $tree = self::cateGenTree($items,'id','pid','son');
     * print_r($tree);
	 */
	static function cateGenTree($items,$id='id', $fid='fid', $son='son')
	{
		$tree = array();
		foreach($items as $item){
			if(isset($items[$item[$fid]])){
				$items[$item[$fid]][$son][] = &$items[$item[$id]];
			}else{
				$tree[] = &$items[$item[$id]];
			}
		}
		return $tree;
	}
	
	/* 格式化的树形数据 如
	 * Array(
     *	[2] => 男装
     *	[5] => -羽绒棉服
     *	[6] => --红色
     *	[1] => 女装
     *	[3] => -羽绒棉服
	 *	)
	 */
	static public  $_array_tmp = array();
	static function formatTreeData($tree, $id='id', $title='title', $son='son', $i=0)
	{
		$symbol = '';
		for($j=$i; $j>0; $j--){
			$symbol .= '--';
		}
		foreach($tree as $t){
			self::$_array_tmp[$t[$id]] = $symbol . $t[$title];
			if(isset($t[$son])){
				$k = $i;
				$k++;
				self::formatTreeData($t[$son],$id, $title, $son, $k);
			}
		}
		return self::$_array_tmp;
	}
	
	//获取 URL 的主域名
	static function getUrlDomain($url)
	{
		if(!$url) return false;
		$domain = parse_url($url);
		$domain = strtolower($domain['host']);
		$domain = explode('.', $domain);
		$len = count($domain);
		$domain = $domain[$len-2].'.'.$domain[$len-1];
		return $domain;
	}
	
	//分离一个标准URL地址中的参数，返回一个数组
	static function convertUrlQuery($query)
	{
		if(!$query) return false;
		$queryParts = explode('&', $query);
		$params = array();
		foreach ($queryParts as $param){
			$item = explode('=', $param);
			$item_0 = isset($item[0]) ? $item[0] : null;
			$item_1 = isset($item[1]) ? $item[1] : null;
			$params[$item_0] = $item_1;
		}
		return $params;
	}
	
	//把分类的URL参数合并成完整的URL，返回字符串
	static function getUrlQuery(array $array_query)
	{
		if(!$array_query) return false;
		$tmp = array();
		foreach($array_query as $k=>$param){
			$tmp[] = $k.'='.$param;
		}
		$params = implode('&',$tmp);
		return $params;
	}
	
	static function urlParams($url)
	{
		if(!$url) return false;
		$url = parse_url($url);
		$query = isset($url['query']) ? $url['query'] : null;
		$params = self::convertUrlQuery($query);
		return $params;
	}
	
	static function curlChangeIp($url)
	{
		if(!$url) return false;
		$ip = rand(1,255).".".rand(1,255).".".rand(1,255).".".rand(1,255)."";
		$header = array(
			"CLIENT-IP:$ip",
			"X-FORWARDED-FOR:$ip",
		);		
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER,true);
		$content = curl_exec($ch);
		//var_dump(curl_getinfo($ch));
		curl_close($ch);
		return $content;
	}
	
	static function curlPost($url,array $data)
	{
		if(!$url || !$data) return false;
	
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_POST,true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
		curl_setopt($ch, CURLOPT_HTTPHEADER, true);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch,CURLOPT_TIMEOUT,5);
		$content = curl_exec($ch);
		curl_close($ch);
	
		return $content;
	}
	
	static function curlGet($url)
	{
		if(!$url) return false;
		$header[] = "Content-type: text/xml";
		
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
		curl_setopt($ch,CURLOPT_TIMEOUT,5);
		$content = curl_exec($ch);
		curl_close($ch);
	
		return $content;
	}
	
	//限制显示的字符数，{$content|strip_tags|truncate_cn=460,'..',0}
	static function truncate_cn($string,$length=0,$ellipsis='…',$start=0)
	{
		$string=strip_tags($string);
		$string=preg_replace('/\n/is','',$string);
		//$string=preg_replace('/ |　/is','',$string);//清除字符串中的空格
		$string=preg_replace('/&nbsp;/is','',$string);
		preg_match_all("/[\x01-\x7f]|[\xc2-\xdf][\x80-\xbf]|\xe0[\xa0-\xbf][\x80-\xbf]|[\xe1-\xef][\x80-\xbf][\x80-\xbf]|\xf0[\x90-\xbf][\x80-\xbf][\x80-\xbf]|[\xf1-\xf7][\x80-\xbf][\x80-\xbf][\x80-\xbf]/",$string,$string);
		if(is_array($string)&&!empty($string[0])){
			$string=implode('',$string[0]);
			if(strlen($string)<$start+1)return '';
			preg_match_all("/./su",$string,$ar);
			$string2='';
			$tstr='';
			for($i=0;isset($ar[0][$i]);$i++){
				if(strlen($tstr)<$start){
					$tstr.=$ar[0][$i];
				}else{
					if(strlen($string2)<$length+strlen($ar[0][$i])){
						$string2.=$ar[0][$i];
					}else{
						break;
					}
				}
			}
			return $string==$string2?$string2:$string2.$ellipsis;
		}else{
			$string='';
		}
		return $string;
	}
	
	//获取上一个月或者下一个月的时间
	static function getMonth($date, $flag='-1')
	{
		$time = strtotime("$date  $flag month");
		return array('zh'=>date("Y年m月",$time), 'date'=>date("Y-m",$time));
	}
	
	//字符串中查找数字。
	static function strFindNum($str)
	{
		$str=trim($str);
		if(empty($str)){return '';}
		$result='';
		for($i=0;$i<strlen($str);$i++){
			if(is_numeric($str[$i])){
				$result.=$str[$i];
			}
		}
		return (int)$result;
	}
	
}
