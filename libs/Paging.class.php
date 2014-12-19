<?php
class Paging
{
	private static $_data = null;
	private static $_total = 0;
	private static $_page = 0;
	private static $_page_siez = 0;
	private static $_total_page = 0;
	
	static public function getData($tableName, $page=1, $page_size = 0, $where = null, $order = null, $select = null)
	{
		if(!$tableName) return false;
		
		$total = self::getCounts($tableName, $where);
		if(!$page_size) $page_size = 30;
		$total_page = ceil($total/$page_size);
		if($page < 1) $page = 1;
		if($page > $total_page) $page = $total_page;
		
		self::$_page = $page;
		self::$_page_siez = $page_size;
		self::$_total_page = $total_page;
		
		$count = ($page-1) * $page_size;

		if(!$where) $where = "id>0";
		if(!$order){
			$order = " ORDER BY id DESC";
		}else{
			$order = " ORDER BY " . $order;
		}
		if(!$select) $select = '*';
		
		$options['condition'] = $where;
		$options['offset'] = $count;
		$options['size'] = $page_size;
		$options['order'] = $order;
		$options['select'] = $select;
		//DB::Debug();
		$data = DB::LimitQuery($tableName, $options);
		self::$_data = $data;
		return $data;
	}
	
	static public function getCounts($tableName, $where = null)
	{
		if(!$tableName) return false;	
		if($where) $where = " WHERE $where";
		$sql = "SELECT count(id) AS counts FROM ". $tableName . $where;
        $rs = DB::GetQueryResult($sql, false);
		self::$_total = isset($rs[0]['counts']) ? (int)$rs[0]['counts'] : 0;
		return self::$_total;
	}
	
	static public function getPage()
	{
		$total = self::$_total;
		$page = self::$_page;
		$page_size = self::$_page_siez;
		$total_page = self::$_total_page;

		if($total_page < 2) return '';
		
		$pre_page = $page - 1;
		$next = $page + 1;
		
		if($pre_page < 1) $pre_page = 1;
		if($next > $total_page) $next = $total_page;
		
		$paging = '<form action="">';
		$paging .= 'Total count:' . $total;
		$paging .= '&nbsp;&nbsp;';
		$paging .= 'Total page:'. $total_page;
		$paging .= '&nbsp;&nbsp;';
		$paging .= 'Current page:' . $page;
		$paging .= '&nbsp;&nbsp;';
		$paging .= '<a href="?page='. $pre_page .'&size='.$page_size.'">Previous</a>';
		$paging .= '&nbsp;&nbsp;';
		$paging .= '<a href="?page='. $next .'&size='.$page_size.'">Next</a>';
		$paging .= '&nbsp;&nbsp;';
		$paging .= 'Jump to:<input type="text" name="page" value="'. $page .'" size="5" maxlength="10" /> <input type="hidden" name="size" value="'. $page_size .'" size="5" maxlength="10" /> &nbsp;&nbsp;<input type="submit" value="Submit">';
		$paging .= '</form>';
		
		return $paging;
	}
	
	static public function showPaging($total = 0, $page = 1, $page_size = 0)
	{
		if($total < 1) return '';
		
		if(!$page_size) $page_size = 30;
		$total_page = ceil($total/$page_size);
		if($page < 1) $page = 1;
		if($page > $total_page) $page = $total_page;
		
		if($total_page < 2) return '';
		
		$pre_page = $page - 1;
		$next = $page + 1;
		
		if($pre_page < 1) $pre_page = 1;
		if($next > $total_page) $next = $total_page;
		
		$paging = '<form action="">';
		$paging .= 'Total count:' . $total;
		$paging .= '&nbsp;&nbsp;';
		$paging .= 'Total page:'. $total_page;
		$paging .= '&nbsp;&nbsp;';
		$paging .= 'Current page:' . $page;
		$paging .= '&nbsp;&nbsp;';
		$paging .= '<a href="?page='. $pre_page .'">Previous</a>';
		$paging .= '&nbsp;&nbsp;';
		$paging .= '<a href="?page='. $next .'">Next</a>';
		$paging .= '&nbsp;&nbsp;';
		$paging .= 'Jump to:<input type="text" name="page" value="'. $page .'" size="5" maxlength="10" />&nbsp;&nbsp;<input type="submit" value="Submit">';
		$paging .= '</form>';
		
		return $paging;
	}
	
}
