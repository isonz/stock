<?php
/* @version 1.0
 * @param       (String)        $url            | 要格式化的网址
 * @param       (Boolean)       $mtime          | 可选,文件创建时间
 * @return      (String)        格式化后的网址
 */

require_once( _LIBS . 'IZAsset.class.php');

function smarty_modifier_formaturl( $url , $mtime=true , $type=1)
{
        return IZAsset::GetAssetUrl( $url , $mtime , $type) ;
}

