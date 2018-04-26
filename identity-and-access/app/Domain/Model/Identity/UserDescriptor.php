<?php

namespace App\Domain\Model\Identity;


class UserDescriptor
{

    private $username;
    private $email;
    private $name;



    public function __construct($username, $email, $name)
    {
       $this->username = $username;
       $this->email = $email;
       $this->name = $name;
    }

    public function __toString()
    {
        return "{\"username\" : \"$this->username\" , \"email\" : \"$this->email\", \"name\" : \"$this->name\"}";
    }


}
