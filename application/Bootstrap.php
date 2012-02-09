<?php
use Doctrine\DBAL\Types\Type;

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{

    protected function _initCaching()
    {
       /*
        * We inform the cache frontend that we want automatic serialization with a lifetime (in seconds) of 3600. This is the
        * default if omitted. This will apply to all items. We can also specify per-item expiration times. We want the
        * backend to be a file.
        *
        * Note: If navigation support is added to this application, consult wikiw/application/modules/wiki/Bootstrap.php
        * as an example of using an input file with navigation stored in a .xml file. I may need another cache frontend, and
        * therefore the cache manager.
        *
        * Comment: If you want your cache to be invalid whenever application/Bootstrap.php has been changd, which will happen if
        * new routes are added to the Bootstrap's _iniRoutes() method, you must use the 'master_files' frontend option. You
        * must also specify 'File' as the first parameter rather than 'Core' to Zend_Cache::factory().
        */

        $frontendOptions = array('lifetime' => 3600, 'automatic_serialization' => true);
        $backendOptions = array('cache_dir' => APPLICATION_PATH . '/../data/cache');

        $cache = Zend_Cache::factory('Core', 'File', $frontendOptions, $backendOptions);

        Zend_Registry::set('Zend_Cache', $cache);   
    }

    // Do not rename this method _initDoctrine() this will result in a circular dependency error.
    protected function _initDoctrineExtra()
    {
        $doctrine = $this->bootstrap('doctrine')->getResource('doctrine');
        $em = $doctrine->getEntityManager();          
        Zend_Registry::set('em', $em);
    }

    protected function _initDoctype()
    {
        define('TIMEZONE', 'Asia/Irkutsk');
        date_default_timezone_set(TIMEZONE);
        define('dtFormatDB','Y-m-d H:i:s');

    	//View
        $this->bootstrap('view');
        $view = $this->getResource('view');
        $view->doctype('HTML5');
        Zend_Validate_Abstract::setDefaultTranslator();
        $view->addHelperPath(APPLICATION_PATH.'/Views/Helpers/', 'Application_View_Helper');
    }

    protected function _initAuth()
    {
        $this->bootstrap('session');
        $auth = Zend_Auth::getInstance();
        if ($auth->hasIdentity()) {
            $view = $this->getResource('view');
            $view->user = $auth->getIdentity();
        }
        return $auth;
    }

    protected function _initFlashMessenger()
    {
        /** @var $flashMessenger Zend_Controller_Action_Helper_FlashMessenger */
        $flashMessenger = Zend_Controller_Action_HelperBroker::getStaticHelper('FlashMessenger');
        if ($flashMessenger->hasMessages()) {
            $view = $this->getResource('view');
            $view->messages = $flashMessenger->getMessages();
        }
    }    

    public function _initAutoloader()
    {
        require_once APPLICATION_PATH . '/../library/Doctrine/Common/ClassLoader.php';

        $autoloader = \Zend_Loader_Autoloader::getInstance();

        $bisnaAutoloader = new \Doctrine\Common\ClassLoader('Bisna');
        $autoloader->pushAutoloader(array($bisnaAutoloader, 'loadClass'), 'Bisna');
    }

    protected function _initRoutes ()
    {
    	$config = new Zend_Config_Xml(APPLICATION_PATH . '/configs/routers.xml');
    	$router = Zend_Controller_Front::getInstance()->getRouter();
        $router->addConfig($config);
 		return $router;
    }

    protected function _initAcl()
    {
        $Acl = new \Zend_Acl();
        $em = \Zend_Registry::get('doctrine')->getEntityManager();

        foreach ( $em->getRepository('\ZF\Entites\AclRole')->findAll() AS  $AclRole )
        {
			if ($Acl->hasRole($AclRole)) continue;

			if (is_null($AclRole->getParent()))
            {
                $Acl->addRole($AclRole);
            }
			else
			{
				if (!$Acl->hasRole($AclRole->getParent()))
                {
                    $parent = $AclRole->getParent();
                    while (!is_null($parent))
                    {
                        $Acl->addRole($parent, $parent->getParent());
                        $parent = $parent->getParent();
                    }
                }
				$Acl->addRole($AclRole, $AclRole->getParent());
			}
        }

        Zend_View_Helper_Navigation_HelperAbstract::setDefaultAcl($Acl);
        Zend_Registry::set('Zend_Acl', $Acl);

        $front = Zend_Controller_Front::getInstance();
        $front->registerPlugin(new ZF\Acl\Plugin());

    	return $Acl;
    }

    protected function _initMenu()
    {
        //ManagementMenuRel
        $auth = Zend_Auth::getInstance();
        if ($auth->hasIdentity())
        {
            $user = $auth->getIdentity();
            //Guest or user
            if ( $user->getAclrole()->getId() < 3 )
            {
                return true;
            }
            
            $em = \Zend_Registry::get('doctrine')->getEntityManager();

            $pages = array();
            foreach ( $em->getRepository('\ZF\Entites\ManagementMenuRel')->findByAclRole($user->getAclrole()->getId()) AS  $Menu )
            {
				$pages[] = array('controller' => $Menu->getManagementMenu()->getAclController()->getName(),
	                				'action' => ( is_null($Menu->getManagementMenu()->getAclAction()) ? 'index' : $Menu->getManagementMenu()->getAclAction()->getName()),
									'resource' => $Menu->getManagementMenu()->getAclController(),
									'privilege' => ( is_null($Menu->getManagementMenu()->getAclAction()) ? 'index' : $Menu->getManagementMenu()->getAclAction()->getName() ),
									'label' => $Menu->getManagementMenu()->getName(),
									'route' => 'default'
								);
            }

            $view = $this->getResource('view');
            $view->managementMenu = new Zend_Navigation($pages);
        }
        else
        {
            return true;
        }
        return $auth;
    }
}

