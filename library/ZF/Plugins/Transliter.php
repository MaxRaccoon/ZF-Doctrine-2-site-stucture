<?php
/**
 * User: raccoon
 * Date: 17.02.12 13:27
 */
namespace ZF\Plugins;
class Transliter
{
    /**
     * @static
     * @param  $string
     * @return string
     */
    public static function generateUrl($string)
    {

		$string = self::UtfToWin($string);
		$string = strtr($string, self::UtfToWin("абвгдеёзийклмнопрстуфхъыэ_"),
		    						"abvgdeeziyklmnoprstufh'iei");
		$string = strtr($string, self::UtfToWin("АБВГДЕЁЗИЙКЛМНОПРСТУФХЪЫЭ_"),
		    						"ABVGDEEZIYKLMNOPRSTUFH'IEI");
		$string = strtr($string,
		                    array(
		                        self::UtfToWin("ж")=>"zh",
		                        self::UtfToWin("ц")=>"ts",
		                        self::UtfToWin("ч")=>"ch",
		                        self::UtfToWin("ш")=>"sh",
		                        self::UtfToWin("щ")=>"shch",
		                        self::UtfToWin("ь")=>"",
		                        self::UtfToWin("ю")=>"yu",
		                        self::UtfToWin("я")=>"ya",
		                        self::UtfToWin("Ж")=>"ZH",
		                        self::UtfToWin("Ц")=>"TS",
		                        self::UtfToWin("Ч")=>"CH",
		                        self::UtfToWin("Ш")=>"SH",
		                        self::UtfToWin("Щ")=>"SHCH",
		                        self::UtfToWin("Ь")=>"",
		                        self::UtfToWin("Ю")=>"YU",
		                        self::UtfToWin("Я")=>"YA",
		                        self::UtfToWin("ї")=>"i",
		                        self::UtfToWin("Ї")=>"Yi",
		                        self::UtfToWin("є")=>"ie",
		                        self::UtfToWin("Є")=>"Ye"
		                        )
		             );
		$string = strtr($string,array(" "=>"_"));
		return $string;        
    }

    /**
     * @static
     * @param  $string
     * @return string
     */
    public static function UtfToWin($string)
    {
        return iconv("UTF-8", "windows-1251", $string);
    }
}
