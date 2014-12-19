<?php
class Asset
{
    static public function GetAssetUrl($absUrlPath, $mtime=true, $type=1)
    {
		if ( preg_match( '#^https?://#', $absUrlPath )){
            return $absUrlPath;
		}
		if (empty($absUrlPath)){
        	return '';
        }
        $abs_file = IMAGES_ . $absUrlPath;
        return $abs_file;
    }
    
}

