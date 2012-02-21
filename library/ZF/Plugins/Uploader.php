<?php
/**
 * User: raccoon
 * Date: 21.02.12 17:00
 */
namespace ZF\Plugins;
class Uploader
{
    private static $_filestorage = "filestorage",
                    $_extentions = "jpg,png,gif,doc",
                    $_maxsize = 1024000; //1Mb

    /**
     * @static
     * @param  $dir
     * @param null $new_name
     * @return array|bool
     */
    public static function upload($dir, $new_name = null)
    {
        if (is_null($new_name))
        {
            $new_name = uniqid("pic");
        }

        $path = APPLICATION_PATH . DIRECTORY_SEPARATOR . ".." . DIRECTORY_SEPARATOR . "public" .
                DIRECTORY_SEPARATOR .  self::$_filestorage . DIRECTORY_SEPARATOR . $dir . DIRECTORY_SEPARATOR;

        if (!file_exists($path))
        {
            mkdir($path, 0777);
        }

        $adapter = new \Zend_File_Transfer_Adapter_Http();
        $adapter->addValidator('Extension', false, self::$_extentions);
        $adapter->addValidator('Size', false, self::$_maxsize);

        $adapter->addFilter('Rename', array(
                                            'target'=> $path . $new_name . "." . self::getExtension($adapter->getMimeType())
                                            )
                            );
        if ( !$adapter->receive() ) {
            throw new \Exception($adapter->getMessages());
        }
        else
        {
            return "/" . self::$_filestorage . "/" . $dir . "/" . $new_name . "." . self::getExtension($adapter->getMimeType());
        }
    }

    /**
     * @param  $mime_type
     * @return bool|string
     */
    public static function getExtension($mime_type)
    {
        switch ($mime_type)
        {
            case "image/jpeg": return "jpg";
            case "image/png": return "png";
            case "image/gif": return "gif";
            default: return false;
        }
    }
}