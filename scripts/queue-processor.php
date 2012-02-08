<?php

// See cron howto at https://help.ubuntu.com/community/CronHowto
define('APPLICATION_PATH', '/home/kurt/public_html/zf-beginners-doctrine2/ch5/application');

set_include_path(implode(PATH_SEPARATOR, array(
      APPLICATION_PATH . '/../library',
      get_include_path(),
    )));
/*
 * Provides autoloading for Zend Framework's pseudo namespaces (that use the underscored) and PHP5.3-compliant namespaces.
 */
spl_autoload_register(function ($className) { 
    
      // We assume classes with underscores are ZF's pseudo namespaced.
      if (strpos($className, '_' ) !== FALSE) {
         
            $className = str_replace('_', '/', $className );   
      }

      $file = str_replace('\\', '/', $className . '.php'); // real namespace support
       
      // search include path for the file.
      $include_dirs = explode(PATH_SEPARATOR, get_include_path());
      
      foreach($include_dirs as $dir) {
  
        $full_file = $dir . '/'. $file;
      
        if (file_exists($full_file)) { 
          
            require_once $full_file; 
            return true; 
        }
      }
            
      return false; 
}); 

// Load/parse ini file. Grab sections we need.
$ini = new Zend_Config_Ini(APPLICATION_PATH . '/configs/application.ini', 'production');
      
$queue = new GPLibrary\Queue\SerializeObjectsQueue('Db', $ini->queue->toArray());

// get the smtp configuration
$smtp_config = $ini->email->smtp->toArray();

// remove the 'server' entry from the array.
$smtp_config = array_diff_key($smtp_config, array('server' => 0) ); 

// set default smtp transport.
$smtp = new Zend_Mail_Transport_Smtp($ini->email->smtp->server, $smtp_config);

Zend_Mail::setDefaultTransport($smtp);

// read in up to 10 
$messages = $queue->receive(10); 

// UnserializeObjectsQueueIterator, the custom iterator for SerializeObjectsQueue, ensures that $message->body is 
// a unserialized instance of Square_Email_ContactMessage.
foreach($messages as $message) {
    
        $email = $message->body;
            
        try {
            
            $email->send();
            
        }  catch(Zend_Mail_Exception $e) {
               
               // Log the error?
               $msg = $e->getMessage();
               $str = $e->__toString();
               $trace =  preg_replace('/(\d\d?\.)/', '\1\r', $str);
        } // end try
                                    
	$queue->deleteMessage($message);
	
} // end foreach
