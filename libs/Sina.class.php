<?php
class Sina
{
	//获取总记录数
    static function getStockCount($url, $encoding)
    {
    	if(!$url) return false;
		$content = Func::curlGet($url);
		$content = mb_convert_encoding($content, _ENCODING, $encoding);
		$content = Func::strFindNum($content);
		return $content;
    }
    
    //把指定的字符替换成空
    static function replaceSymbol($str, $case = FALSE)
    {
    	$symbils = array('	', '\r', '\t', '\n', ' ', PHP_EOL);
    	if(!$case){
    		return str_ireplace($symbils, '', $str);
    	}
    	return str_replace($symbils, '', $str);
    }
    
    //替换括号，并只取括号内的内容
    static function replaceBrackets($str)
    {
    	$bracket_l = mb_strpos($str, '(');
    	$bracket_r = mb_strrpos($str, ')');
    	return mb_substr($str, $bracket_l+1, $bracket_r-$bracket_l-1, _ENCODING);
    }
    
    //判断数组中的数据是否符合配置文件中的要求
    static function isCondition(array $data, array $condition_numbers = array())
    {
    	if(!is_array($data) || empty($data)) return false;
    	$flag = 1;
    	$domain = Func::getUrlDomain($data['href']);
    	if(!$condition_numbers) $condition_numbers = Config::conditionNumber();
    	foreach ($condition_numbers as $k => $condition_number){
    		if('ship' == $k){
    			if($data[$k] > $condition_number){
    				$flag = 0;
    				break;
    			}
    		}else if('goodRate' == $k){
    			if('taobao.com' == $domain && $data[$k] < $condition_number){
    				$flag = 0;
    				break;
    			}
    		}else if($data[$k] < $condition_number){
    			$flag = 0;
    			break;
    		}
    	}
    	$condition_arrays = Config::conditionArray();
    	foreach ($condition_arrays as $k => $condition_array){
    		$d = $data[$k];
    		$d = explode(',', $d);
    		$a = array_intersect($d ,$condition_array);
    		if(empty($a)){
    			$flag = 0;
    			break;
    		}
    	}

		//名字中含有淘宝天猫 （不允许发布）
		if(false !== stripos($data['nick'],'天猫')) return false;
		if(false !== stripos($data['nick'],'淘宝')) return false;

    	if($flag) return true;
    	return false;
    }
    
    //给分页地址自动添加分页参数，返回所有分页地址
    static function autoPaging($url, $type='list')
    {
    	if(!$url) return false;
    	$urls = array();
    	$url = parse_url($url);
    	$scheme = isset($url['scheme']) ? $url['scheme'] : null;
    	$host = isset($url['host']) ? $url['host'] : null;
    	$path = isset($url['path']) ? $url['path'] : null;
    	$query = isset($url['query']) ? $url['query'] : null;
    	$new_url = "$scheme://$host$path?";
    	$params = Func::convertUrlQuery($query);
		$page = $_SESSION['pages'];    	

    	if('s' == $type){
    		$pagenum = (int)Config::paging('pagenum');
    		$pagesize = (int)Config::paging('s');
    		for($i=$page; $i<$pagenum; $i++){
    			$params['s'] = $pagesize*$i;
    			$params['sort'] = Config::paging('sort');
    			$urls[] = $new_url.Func::getUrlQuery($params);
    		}
    		return $urls;
    	}
    	$pagenum = (int)Config::paging('pagenum');
    	$pagesize = (int)Config::paging('pagesize');
    	$param = Config::paging('param');
    	for($i=$page; $i<$pagenum; $i++){
    		$params[$param] = $pagesize*$i;
    		$urls[] = $new_url.Func::getUrlQuery($params);
    		
    	}
    	return $urls;    	
    }
    
    //把START URL 里的 CONDITION分割成数组形式
    //ship=0,tradeNum=500,commend=200,ratesum=11,goodRate=99.5,dsrScore=4.8
    static function equalStringToArray($str)
    {
    	$rs = array();
    	$array = explode(',', $str);
    	foreach ($array as $arr){
			if(!$arr) continue;
    		$tmp = explode('=', $arr);
    		$tmp_0 = isset($tmp[0]) ? $tmp[0] : null;
    		$tmp_1 = isset($tmp[1]) ? $tmp[1] : null;
    		$rs[$tmp_0] = $tmp_1;
    	}
    	return $rs;
    }
    
    //只能是2级分类
    static function uzUrl($itemId = 0, $catelog_id=0, $title=null)
    {
    	if(!$itemId || !$catelog_id || !$title) return false;
    	$url = Config::uzUrls('add_url');
    	$_tb_token_ = Settings::getValue('_tb_token_');
    	$comments = $title;
    	$className = Catelog::getFather($catelog_id);
    	$tags = isset($className[0]['title']) ? $className[0]['title'] : null;
    	$fid = isset($className[0]['fid']) ? $className[0]['fid'] : 0;
    	if(!$fid){
    		echo "No found fid for $title, itemId:$itemId, catelog_id:$catelog_id";
    		return false;
    	}
    	$className = Catelog::getFather($fid);
    	$className = isset($className[0]['title']) ? $className[0]['title'] : null;
    	if(!$_tb_token_ || !$itemId || !$className || !$comments || !$tags) return false;
    	
    	$rand=rand(1, 1000);
    	$rand1=rand(1, 1000);
    	$_ksTS = time().$rand1."_".$rand;
    	$url .= "_tb_token_=$_tb_token_&itemId=$itemId&className=$className&comments=$comments&tags=$tags&_ksTS=$_ksTS&jsonp=jsonp";
    	//$url = urlencode($url);
    	return $url;
    }
 
	//把搜索地址变成JSON地址
	static function surlToJsonUrl($url)
	{
		if(!$url) return false;
        $urls = array();
		$oldurl = $url;
        $url = parse_url($url);
        $scheme = isset($url['scheme']) ? $url['scheme'] : null;
        $host = isset($url['host']) ? $url['host'] : null;
        $path = isset($url['path']) ? $url['path'] : null;
        $query = isset($url['query']) ? $url['query'] : null;
        $new_url = "$scheme://$host$path?";
        $params = Func::convertUrlQuery($query);
		if(isset($params['data-value'])) return $oldurl;
		if(isset($params['spm']) || isset($params['scm'])){
			unset($params['spm']);
			if(!isset($params['scm_t'])) $params['scm_t'] = $params['scm'];
			unset($params['scm']);
		}

		$params['as'] = 1;
		$params['json'] = 'on';
		$params['sort'] = 'biz30day';
		$params['tid'] = 0;
		$params['src_t'] = 'home';
		$params['pSize'] = 96;
		$params['data-key'] = 's';
		$params['data-value'] = 0;
		$params['data-action'] = '';
		$params['module'] = 'page';
		$params['_ksTS'] = time();
		$params['callback'] = 'jsonp';

        $urls = $new_url.Func::getUrlQuery($params);
		return $urls;
	}

	static function surlToSUrl($url)
	{
		if(!$url) return false;
		$urls = array();
		$url = parse_url($url);
		$scheme = isset($url['scheme']) ? $url['scheme'] : null;
        $host = isset($url['host']) ? $url['host'] : null;
        $path = isset($url['path']) ? $url['path'] : null;
        $query = isset($url['query']) ? $url['query'] : null;
        $new_url = "$scheme://$host$path?";
        $params = Func::convertUrlQuery($query);
        if(isset($params['initiative_id']))	unset($params['initiative_id']);
		$params['s'] = 0;
        $params['sort'] = Config::paging('sort');
		$urls = $new_url.Func::getUrlQuery($params);
        return $urls;
	}
	
	static function seachUTF8($content)
	{
		return trim(mb_convert_encoding($content, _ENCODING, 'GBK'));
	} 
   
	static function getStaobaoData($url)
	{
		if(!$url) return false;
		$html = file_get_html($url);
		if(!$html) return false;
		$data = array();
		foreach ($html->find(".tb-content .item-box") as $k => $items){
			$data[$k]['image'] = null;
			$data[$k]['price'] = 0.01;
			
			$href = $items->find(".pic .pic-box a");
			$data[$k]['href'] = Common::seachUTF8($href[0]->href);
			$commend = $items->find(".row .count a");
			$commendHref = isset($commend[0]) ? $commend[0]->href : '';
			$data[$k]['commendHref'] = Common::seachUTF8($commendHref);
			$nick = $items->find(".row .seller a");
			$data[$k]['nick'] = Common::seachUTF8($nick[0]->plaintext);
			$storeLink = $items->find(".row .seller a");
			$data[$k]['storeLink'] = Common::seachUTF8($storeLink[0]->href);
			$loc = $items->find(".row .loc");
			$data[$k]['loc'] = Common::seachUTF8($loc[0]->plaintext);
		
			$title = $items->find(".summary a");
			$data[$k]['title'] = str_replace(" ", "", Common::seachUTF8($title[0]->plaintext));
			$currentPrice = $items->find(".row-focus .price");
			$data[$k]['currentPrice'] = str_replace("￥", "", Common::seachUTF8($currentPrice[0]->plaintext));
			$tradeNum = $items->find(".row .dealing");
			$data[$k]['tradeNum'] = str_replace("人收货", "", Common::seachUTF8($tradeNum[0]->plaintext));
			$commendnumb = isset($commend[0]) ? $commend[0]->plaintext : 503;
			$data[$k]['commend'] = str_replace("条评论", "", Common::seachUTF8($commendnumb));
		
			$ship = $items->find(".row-focus .shipping");
			$ship = str_replace(" ", "", Common::seachUTF8($ship[0]->plaintext));
			if('免运费'==$ship){ $data[$k]['ship'] = 0; }else{$data[$k]['ship'] = str_replace("运费：", "", $ship);}
		
			$itemId = Func::urlParams($data[$k]['href']);
			$data[$k]['itemId'] = isset($itemId['id']) ? $itemId['id'] : 0;
			$sellerId = Func::urlParams($data[$k]['storeLink']);
			$data[$k]['sellerId'] = isset($sellerId['user_number_id']) ? $sellerId['user_number_id'] : 0;
		
			$sevenDayReturn = $items->find(".service-box .service-btns ..icon-service-qitian");
			$sevenDayReturnJ= $items->find(".service-box .service-btns .icon-service-qitian-jishi");
			$sevenDayReturn = isset($sevenDayReturn[0]) ? 'sevenDayReturn' : (isset($sevenDayReturnJ[0]) ? 'sevenDayReturn' : null);
			$tmall = $items->find(".service-box .service-btns .icon-service-tianmao");
			$tmall = isset($tmall[0]) ? 'tmall' : null;
			$data[$k]['service'] = "$sevenDayReturn,$tmall";
		
			$url = "http://s.taobao.com/search?app=api&m=get_shop_card&sid=".$data[$k]['sellerId']."&bid=".$data[$k]['itemId'];
			//$json = json_decode(file_get_contents($url),true);
			$json = json_decode(Func::curlChangeIp($url),true);
			if(!$json) return false;
			//var_dump($json);
			$data[$k]['ratesum'] = $json['ratesum'];
			$data[$k]['goodRate'] = $json['favorableRate'];
			$data[$k]['dsrScore'] = ($json['matchDescription'] + $json['serviceAttitude'] + $json['deliverySpeed'])/3;
			$condition_numbers = Config::conditionNumber();		
			if($data[$k]['dsrScore'] < $condition_numbers['dsrScore'] && 'h' == $json['attitudeCompared']['numberClass'] && 'h' == $json['deliveryCompared']['numberClass'] && 'h' == $json['descriptionCompared']['numberClass']){
				$data[$k]['dsrScore'] = $condition_numbers['dsrScore'];
			}
		}
		return $data;
	}

	//获取验证返现URL 
	static function uzMoneyHref($url)
	{
		if(!$url) return false;
		$url = urlencode($url);
		$url = Config::uzUrls('money_url'). $url;
		return $url;
	}

}

