<?php
class Sina
{
	//---------------------------------------- 数据
	//数据入口函数，运行
	static function dataRun()
	{
		if(self::stopDay()) return false;
		$count = self::getStockCount();
		$page_urls = self::getPageUrls($count, 80);
		self::getStockData($page_urls);
	}
	
	//获取停止交易日
	static function stopDay()
	{
		$stopinfo = Setting::getValue('STOCK_STOP_DAY');
		if(!$stopinfo) true;
		$stopinfo = explode(',', $stopinfo);
		foreach ($stopinfo as $stopday){
			if(1==strlen($stopday)){
				$week = date('w');
				if($stopday==$week || $stopday==$week) return true;
			}else{
				$day = date('Y-m-d');
				if($day == $stopday) return true;
			}
		}
		return false;
	}
	
	//获取运行设置数据
	static function getRunInfo($name)
	{
		$runinfo = Setting::getValue($name);
		if(!$runinfo) return array();
		$runinfo = json_decode($runinfo, true);
		return $runinfo;
	}
	
	//获取总记录数
    static function getStockCount()
    {
    	//先查询数据库是否有今天的设置数据
    	$runinfo = self::getRunInfo('SINA_STOCK_RUN');
    	$count = isset($runinfo['SINA_STOCK_COUNT']) ? (int)$runinfo['SINA_STOCK_COUNT'] : 0;
    	$count_date = isset($runinfo['SINA_STOCK_COUNT_DATE']) ? $runinfo['SINA_STOCK_COUNT_DATE'] : null;
    	if($count>0 && $count_date==date('Y-m-d')) return $count;
    	
    	//没有今天的设置数据再请求网站上的
    	$url = Setting::getValue('SINA_STOCK_COUNT_URL');
    	$encoding = Setting::getValue('SINA_ENCODE');
    	if(!$encoding) $encoding = 'GBK';
		$content = Func::curlGet($url);
		$content = mb_convert_encoding($content, _ENCODING, $encoding);
		$content = Func::strFindNum($content);
		
		//把网站上的数据存入设置数据表
		$runinfo['SINA_STOCK_COUNT'] = $content;
		$runinfo['SINA_STOCK_COUNT_DATE'] = date('Y-m-d');
		Setting::setValue('SINA_STOCK_RUN', json_encode($runinfo));
		
		return $content;
    }
    
    //获取所有分页页面的URL
    static function getPageUrls($count = 0, $page_size = 80)
    {
    	if(!$count) return false;
    	$first = Setting::getValue('SINA_STOCK_PAGE_URL');
    	if(!$first) return false;
    	
    	$urls = array();
    	$url = parse_url($first);
    	$scheme = isset($url['scheme']) ? $url['scheme'] : null;
    	$host = isset($url['host']) ? $url['host'] : null;
    	$path = isset($url['path']) ? $url['path'] : null;
    	$query = isset($url['query']) ? $url['query'] : null;
    	$new_url = "$scheme://$host$path?";
    	$params = Func::convertUrlQuery($query);
    	
    	$page_num = ceil($count/$page_size);
    	for($i=1; $i<=$page_num; $i++){
    		$params['page'] = $i;
    		$urls[$i] = $new_url.Func::getUrlQuery($params);
    	}
    	return $urls;
    }

    static function getStockData($urls)
    {
    	if(!$urls) return false;

    	//查询数据库当前运行到哪一页
    	$runinfo = self::getRunInfo('SINA_STOCK_RUN');
    	$page = isset($runinfo['SINA_STOCK_RUN_PAGE']) ? (int)$runinfo['SINA_STOCK_RUN_PAGE'] : 0;
    	$page_date = isset($runinfo['SINA_STOCK_RUN_PAGE_DATE']) ? $runinfo['SINA_STOCK_RUN_PAGE_DATE'] : null;
    	if($page_date != date('Y-m-d')) $page = 0;
    	if($page >= count($urls)) return $page;

    	$encoding = Setting::getValue('SINA_ENCODE');
    	if(!$encoding) $encoding = 'GBK';
    	for($i=$page+1; $i <=count($urls); $i++){
    		$url = $urls[$i];
    		$content = Func::curlGet($url);
    		$content = mb_convert_encoding($content, _ENCODING, $encoding);
    		
    		self::tmpData('datas', $content);
    		
    		$content = self::strToJson($content);
    		$content = json_decode($content, true);
    		foreach ($content as $data){
    			$ticker = $data['symbol'];
    			unset($data['symbol'], $data['code'], $data['ticktime']);
    			Datas::setData($ticker, $data);
    		}
    		
    		//把当前运行的页码存入设置数据表
    		$runinfo['SINA_STOCK_RUN_PAGE'] = $i;
    		$runinfo['SINA_STOCK_RUN_PAGE_DATE'] = date('Y-m-d');
    		Setting::setValue('SINA_STOCK_RUN', json_encode($runinfo));
    		sleep(10);
    		echo "data:$url \n";
    	}
    }

    static function strToJson($str)
    {
    	$json = '';
    	for($i=0; $i<strlen($str); $i++){
    		$strh = isset($str[$i-1]) ? $str[$i-1] : null;
    		$stri = $str[$i];
    		$strj = isset($str[$i+1]) ? $str[$i+1] : null;
    		if('{'==$stri || (','==$stri && '{'!=$strj)){
    			$json .= $stri.'"';
    		}else if(':' == $stri && !is_numeric($strh)){
    			$json .= '"'.$stri;
    		}else{
    			$json .= $stri;
    		}
    	}
    	return $json;
    }
    
    
    //---------------------------------------------- 股东
    //股东入口函数，运行
    static function holderRun()
    {
    	if(!self::publishDate()) return false;
    	if(!defined('_HTMLDOM')){
    		define('_HTMLDOM', _LIBS . 'Htmldom' . DS);
    	}
    	foreach (glob(_HTMLDOM."/*.php") as $htmldom){
    		require_once $htmldom;
    	}
    	self::getHolderData();
    }
    
    //公布股东组成日期
    static function publishDate()
    {
    	$date = (int)date('d');
    	if (1 == $date || 29 == $date) return true;
    	return false;
    }
    
    static function getHolderData()
    {
    	$tickers = Stock::getList('1=1','ticker', $order='ticker ASC');
    	if(!$tickers) return Holder::log('Can not get the Stock data in Sina.class.php getHolderData() function.');
    	
    	//查询数据库获取对应URL
    	$main_holder_url = Setting::getValue('SINA_MAIN_HOLDER_URL');
    	$liutong_holder_url= Setting::getValue('SINA_LIUTONG_HOLDER_URL');
    	
    	foreach ($tickers as $ticker){
    		$ticker = $ticker['ticker'];
    		$tick = Stock::tickerToNumber($ticker);
    		$liut_url = str_replace("#ticker#", $tick, $liutong_holder_url);
    		$main_url = str_replace("#ticker#", $tick, $main_holder_url);
    		if($liut_url){
    			$runinfo = self::getRunInfo('SINA_LIUTONG_HOLDER_PAGES');
    			$save_date = isset($runinfo[$ticker]) ? $runinfo[$ticker] : null;
    			if(!$save_date || $save_date != date('Y-m-d')){
    				if(self::getLiutongHolder($ticker, $liut_url)){
    					$runinfo[$ticker] = date('Y-m-d');
    					Setting::setValue('SINA_LIUTONG_HOLDER_PAGES', json_encode($runinfo));
    				}
    			}
    		}
    		if($main_url){
    			$runinfo = self::getRunInfo('SINA_MAIN_HOLDER_PAGES');
    			$save_date = isset($runinfo[$ticker]) ? $runinfo[$ticker] : null;
    			if(!$save_date || $save_date != date('Y-m-d')){
    				if(self::getMainHolder($ticker, $main_url)){
    					$runinfo[$ticker] = date('Y-m-d');
    					Setting::setValue('SINA_MAIN_HOLDER_PAGES', json_encode($runinfo));
    				}
    			}
    		}
    		sleep(30);
    		echo "holder:$ticker \n";
    	}
    }
    
    static function getLiutongHolder($ticker, $url)
    {
    	if(!$url) return false;
    	$html = file_get_html($url);
    	if(!$html) return false;
    	foreach ($html->find("#CirculateShareholderTable") as $table){    		
    		$datas = self::ltHolderPageCodeFormat($table->plaintext);
    		foreach ($datas as $date => $data){
    			foreach ($data as $dt){
    				$dt['days'] = $date;
    				$dt['ticker'] = $ticker;
    				$dt['type'] = 'ltgd';
    				Holder::setData($dt);
    			}
    			return true;  //只取最前面一个。
    		}
    	}
    	return true;
    }

    static function ltHolderPageCodeFormat($str)
    {
    	$str = strip_tags($str);
    	$str = str_replace("&nbsp;", '', $str);
    	$str = preg_replace('/\s+/', "-||-" ,$str);
    	
    	self::tmpData('holder_ltgd', $str);
    	
    	$arr = explode('-||-', $str);
    	$data = array();
    	$tmp_date = null;
    	
    	foreach ($arr as $k => $v){
    		if(preg_match('/^\d{4}-\d{2}-\d{2}/', $v)){
    			$tmp_date[] = $k;
    		}
    	}

    	foreach ($arr as $k => $v){
    		foreach ($tmp_date as $dk => $dv){
    			$dk1 = isset($tmp_date[$dk+1]) ? $tmp_date[$dk+1] : (count($arr)+1);
    			if($k > $dv && $k <= $dk1){
    				if($v > 10000){
    					$data[$arr[$dv]][] = array(
    						'holder' 	=> $arr[$k-1],
    						'shares' 	=> $v,
    						'stake' 	=> $arr[$k+1],
    						'nature' 	=> $arr[$k+2],
    					); 
    				}
    			}
    		}
    	}
		return $data;
    }
    
    static function getMainHolder($ticker, $url)
    {
    	if(!$url) return false;
    	$html = file_get_html($url);
    	if(!$html) return false;
    	foreach ($html->find("#Table1") as $table){
    		$datas = self::mainHolderPageCodeFormat($table->plaintext);
    		foreach ($datas as $date => $data){
    			foreach ($data as $dt){
    				$dt['days'] = $date;
    				$dt['ticker'] = $ticker;
    				$dt['type'] = 'zygd';
    				Holder::setData($dt);
    			}
    			return true;  //只取最前面一个。
    		}
    	}
    	return true;
    }
    
    static function mainHolderPageCodeFormat($str)
    {
    	$str = strip_tags($str);
    	$str = str_replace("&nbsp;", '', $str);
    	$str = preg_replace('/\s+/', "-||-" ,$str);
    	
    	self::tmpData('holder_zygd', $str);
    	 
    	$arr = explode('-||-', $str);
    	$data = array();
    	$tmp_date = null;
    	
    	foreach ($arr as $k => $v){
    		if(preg_match('/^\d{4}-\d{2}-\d{2}/', $v)){
    			$tmp_date[] = $k;
    		}
    	}
    
    	foreach ($arr as $k => $v){
    		foreach ($tmp_date as $dk => $dv){
    			$dk1 = isset($tmp_date[$dk+1]) ? $tmp_date[$dk+1] : (count($arr)+1);
    			if($k > $dv && $k <= $dk1){
    				if($v > 1000000){
    					$data[$arr[$dv]][] = array(
    							'holder' 	=> $arr[$k-1],
    							'shares' 	=> $v,
    							'stake' 	=> $arr[$k+1],
    							'nature' 	=> $arr[$k+2],
    					);
    				}
    			}
    		}
    	}
    	return $data;
    }
    //------------------------------------------- 公共
    
    static function tmpData($type, $str)
    {
    	error_log("$str \n\t", 3, _LOGS . "stock/".$type."_".date('Y-m-d').'.log');
    }
}

