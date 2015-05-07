<?php

namespace MingLibrary;

class MingUtil
{
    /**
     * @var array
     */
    private static $search = array('ソ');

    /**
     * @var array
     */
    private static $replace = array('ソ\\');

    /**
     * @param  string $str
     * @return string
     */
    public static function encoding_to_sjis($str = ' ')
    {
        return mb_convert_encoding(str_replace(self::$search, self::$replace, $str), 'SJIS-win', 'UTF-8');
    }

    /**
     * @return string
     */
    public static function getSwfDir()
    {
        return \Phalcon\DI::getDefault()->get('config')->get('ming')->get('swf')->get('dir');
    }

    /**
     * @return string
     */
    public static function getBitmapDir()
    {
        return \Phalcon\DI::getDefault()->get('config')->get('ming')->get('bitmap')->get('dir');
    }

    /**
     * ヘッダー作成
     */
    public static function setHeader()
    {
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: GET, OPTIONS');
        header('Access-Control-Allow-Headers: X_REQUESTED_WITH');
        header('Content-type: application/x-shockwave-flash');
        header('Expires: Thu, 01 Dec 1994 16:00:00 GMT, -1');
        header('Last-Modified: '. gmdate('D, d M Y H:i:s'). ' GMT');
        header('Cache-Control: no-cache, no-store, max-age=0, must-revalidate');
        header('Pragma: no-cache');
    }
}