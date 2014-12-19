<?php
class Templates
{
    static private $itemplate = NULL;
    static private $csses = array();
    static private $jses = array();

    static function Instance()
    {
    	if(!is_object(self::$itemplate)){
            self::getSmarty();
		}
        return self::$itemplate;
    }

    static private function getSmarty()
    {
		if (NULL == self::$itemplate){
		    $smarty = new Smarty();
	        $smarty->template_dir = _SMARTY_TEMPLATE;
		    $smarty->compile_dir = _SMARTY_COMPILED;
		    $smarty->cache_dir = _SMARTY_CACHE;
		    $smarty->left_delimiter = '<{';
	        $smarty->right_delimiter = '}>';
		    $smarty->debugging = false;
		    $smarty->caching = false;
		    $smarty->cache_lifetime = 2;
		    self::$itemplate = $smarty;
		}
    }

    static public function Display($template, $cache_id = null, $compile_id = null, $parent = null)
    {
		$smarty = self::Instance();
		self::Assign('javascripts', array_keys(self::$jses));
		self::Assign('csses', array_keys(self::$csses));
        $smarty->display($template, $cache_id = null, $compile_id = null, $parent = null);
        self::closeTemplate();
    }

    static public function Assign($var, $value = null)
    {
		$smarty = self::Instance();
		$smarty->assign($var, $value);
    }

    static public function AddCss($css = array(), $mtime = true)
    {
		self::Instance();
		if (!is_array($css)){
            $css = array($css);
		}
		foreach ($css as $v) {
	    	$v = CSS_ . $v;
            if (isset(self::$csses[$v])){
				continue;
	    	}
            self::$csses[$v] = 1;
		}
    }

    static public function AddJs($js = array())
    {
		self::Instance();
		if (!is_array($js)){
            $js = array($js);
		}
        foreach ($js as $v) {
	    	$v = JS_ . $v;
            if (isset(self::$jses[$v])){
        		continue;
	    	}
            self::$jses[$v] = 1;
        }
    }

    static private function closeTemplate()
    {
        self::$itemplate = NULL;
    }

}

?>
