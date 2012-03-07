<?php
/**
 * User: raccoon
 * Date: 07.03.12 15:41
 */
namespace ZF\Error;
class Page
{
    /**
     * Set action 404
     * @static
     * @param \Zend_Controller_Request_Abstract $request
     * @return void
     */
    public static function pageNotFound(\Zend_Controller_Request_Abstract $request)
    {
        $request->setControllerName('error');
        $request->setActionName('notfound');
    }

    /**
     * Set action access deny
     * @static
     * @param \Zend_Controller_Request_Abstract $request
     * @return void
     */
    public static function accessDeny(\Zend_Controller_Request_Abstract $request)
    {
        $request->setControllerName('error');
        $request->setActionName('deny');
    }
}