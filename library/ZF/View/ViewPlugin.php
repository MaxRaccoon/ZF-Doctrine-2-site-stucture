<?php
/**
 * Created by JetBrains PhpStorm.
 * User: raccoon
 * Date: 10.02.12
 * Time: 13:58
 * To change this template use File | Settings | File Templates.
 */
namespace ZF\View;
class ViewPlugin extends \Zend_Controller_Action
{
    public static function setNoRender()
    {
        $viewRenderer = \Zend_Controller_Action_HelperBroker::getExistingHelper('viewRenderer');
        $viewRenderer->setNoRender(true);
    }
}
