<?php

namespace WebLinks\Domain;

use Symfony\Component\Security\Core\User\UserInterface;

class User implements UserInterface
{
    /**
     * User id.
     * 
     * @var integer
     */
    private $id;
    
    /**
     * User name.
     * 
     * @var string
     */
    private $username;
    
    /**
     * User password.
     * 
     * @var string
     */
    private $password;
    
    /**
     * Salt that was originally used to encode the password.
     * 
     * @var string
     */
    private $salt;
    
    /**
     * Role.
     * Values : ROLE_USER or ROLE_ADMIN.
     * 
     * @var string
     */
    private $role;
    
    function getId() {
        return $this->id;
    }
    
    /**
     * 
     * @inheritDoc
     */
    function getUsername() {
        return $this->username;
    }

    /**
     * 
     * @inheritDoc
     */
    function getPassword() {
        return $this->password;
    }
    
    /**
     * 
     * @inheritDoc
     */
    function getSalt() {
        return $this->salt;
    }

    function getRole() {
        return $this->role;
    }
    
    /**
     * 
     * @inheritDoc
     */
    public function getRoles() {
        return array($this->getRole());
    }

    function setId($id) {
        $this->id = $id;
    }

    function setUsername($username) {
        $this->username = $username;
    }

    function setPassword($password) {
        $this->password = $password;
    }

    function setSalt($salt) {
        $this->salt = $salt;
    }

    function setRole($role) {
        $this->role = $role;
    }

    /**
     * 
     * @inheritDoc
     */
    public function eraseCredentials() {
        // Nothing to do here
    }

}