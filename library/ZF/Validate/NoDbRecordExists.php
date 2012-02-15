<?php
/**
 * User: raccoon
 * Date: 15.02.12 10:18
 */
namespace ZF\Validate;
class NoDbRecordExists extends \Zend_Validate_Abstract
{
    /**
     * @var const
     */
    const RECORD_EXISTS = 'dbRecordExists';
    const USER_EMAIL_EXISTS = 'dbUserEmailExists';
    const USER_NICKNAME_EXISTS = 'dbUserNicknameExists';
    
    /**
     * Error message
     * @var array
     */
    protected $_messageTemplates = array(
        self::RECORD_EXISTS => 'Record with value %value% already exists in table',
        self::USER_EMAIL_EXISTS => 'User with this email already exists',
        self::USER_NICKNAME_EXISTS => 'User with this nickname already exists',
    );

    /**
     * Entity for search
     * @var object
     */
    protected $_entity = null;

    /**
     * Cheeck function
     * @var string
     */
    protected $_cheeckFunction = null;

    /**
     * @var string
     */
    protected $_returnError = null;


    /**
     * @param  $entity
     * @param  $function
     */
    public function __construct($entity, $function, $error)
    {
        $this->_entity = $entity;
        $this->_cheeckFunction = $function;
        $this->_returnError = $error;
    }

    /**
     * @param  $value
     * @return bool
     */
    public function isValid($value)
    {
        $this->_setValue($value);

        $func = $this->_cheeckFunction;
        if ( !is_null($this->_entity->$func($value)) )
        {
            $this->_error( $this->_returnError );
            return false;
        }
        else
        {
            return true;
        }
    }    
}