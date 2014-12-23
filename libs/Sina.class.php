<?php
class Sina
{
	//入口函数，运行
	static function run()
	{
		$count = self::getStockCount();
		$page_urls = self::getPageUrls($count, 80);
		self::getPageData($page_urls);
	}
	
	//获取运行设置数据
	static function getRunInfo()
	{
		$runinfo = Setting::getValue('SINA_STOCK_RUN');
		if(!$runinfo) return array();
		$runinfo = json_decode($runinfo, true);
		return $runinfo;
	}
	
	//获取总记录数
    static function getStockCount()
    {
    	//先查询数据库是否有今天的设置数据
    	$runinfo = self::getRunInfo();
    	$count = isset($runinfo['SINA_STOCK_COUNT']) ? (int)$runinfo['SINA_STOCK_COUNT'] : 0;
    	$count_date = isset($runinfo['SINA_STOCK_COUNT_DATE']) ? (int)$runinfo['SINA_STOCK_COUNT_DATE'] : 0;
    	if($count>0 && $count_date==strtotime(date('Y-m-d'))) return $count;
    	
    	//没有今天的设置数据再请求网站上的
    	$url = Setting::getValue('SINA_STOCK_COUNT_URL');
    	$encoding = Setting::getValue('SINA_ENCODE');
    	if(!$encoding) $encoding = 'GBK';
		$content = Func::curlGet($url);
		$content = mb_convert_encoding($content, _ENCODING, $encoding);
		$content = Func::strFindNum($content);
		
		//把网站上的数据存入设置数据表
		$runinfo['SINA_STOCK_COUNT'] = $content;
		$runinfo['SINA_STOCK_COUNT_DATE'] = strtotime(date('Y-m-d'));
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

    static function getPageData($urls)
    {
    	if(!$urls) return false;

    	//查询数据库当前运行到哪一页
    	$runinfo = self::getRunInfo();
    	$page = isset($runinfo['SINA_STOCK_RUN_PAGE']) ? (int)$runinfo['SINA_STOCK_RUN_PAGE'] : 0;
    	$page_date = isset($runinfo['SINA_STOCK_RUN_PAGE_DATE']) ? (int)$runinfo['SINA_STOCK_RUN_PAGE_DATE'] : 0;
    	if($page_date != strtotime(date('Y-m-d'))) $page = 0;
    	if($page >= count($urls)) return $page;
    	
    	$encoding = Setting::getValue('SINA_ENCODE');
    	if(!$encoding) $encoding = 'GBK';
    	for($i=$page+1; $i <=count($urls); $i++){
    		$url = $urls[$i];
    		$content = Func::curlGet($url);
    		$content = mb_convert_encoding($content, _ENCODING, $encoding);
    		$content = self::strToJson($content);
    		$content = json_decode($content, true);
    		foreach ($content as $data){
    			$ticker = $data['symbol'];
    			unset($data['symbol'], $data['code'], $data['ticktime']);
    			Datas::setData($ticker, $data);
    		}
    		
    		//把当前运行的页码存入设置数据表
    		$runinfo['SINA_STOCK_RUN_PAGE'] = $i;
    		$runinfo['SINA_STOCK_RUN_PAGE_DATE'] = strtotime(date('Y-m-d'));
    		Setting::setValue('SINA_STOCK_RUN', json_encode($runinfo));
    		sleep(1);
    		echo "$i <br>";
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
    
}

