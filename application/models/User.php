<?php
class Application_Model_User extends ZF\Entity\User
{
    private $salt = ":default_salt",
            $role = null;

    /**
     * Set password
     *
     * @param string $password
     */
    public function setPassword($password)
    {
        parent::setPassword(MD5($password . $this->salt));
    }

    public function getUserRoleIndex()
    {
        return "user_" . $this->getId();
    }

    public function getEntity()
    {
        return parent::$self;
    }

}
