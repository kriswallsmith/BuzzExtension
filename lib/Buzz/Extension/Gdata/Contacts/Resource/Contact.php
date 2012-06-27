<?php

namespace Buzz\Extension\Gdata\Contacts\Resource;

class Contact
{
    private $name;
    private $email;
    private $photo;

    public function __construct($name = null, $email = null, $photo = null)
    {
        $this->name = $name;
        $this->email = $email;
        $this->photo = $photo;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function setEmail($email)
    {
        $this->email = $email;
    }

    public function getPhoto()
    {
        return $this->photo;
    }

    public function setPhoto($photo)
    {
        $this->photo = $photo;
    }
}
