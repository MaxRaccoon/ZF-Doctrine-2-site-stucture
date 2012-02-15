<?php
/**
 * User: raccoon
 * Date: 15.02.12 9:42
 */
namespace ZF\Auth;
class Plugin
{
    /**
     * @static
     * @param int $length
     * @return string
     */
    public static function generatePassword($length = 8)
    {
        $chars = "ABCDEFGIJKLMNOPQRSTUVWXYZabcdefgijklmnopqrstuvwxyz0123456789#$!@";
        $len = 0;
        $password = "";
        while ($len<$length)
        {
            $password .= substr($chars, rand(0, ( strlen($chars)-1 ) ), 1);
            $len++;
        }
        return $password;
    }
}
