<?php

namespace App\Domain\Model\Identity;


class UserPlayingRoleInfo
{
    public $userid;
    public $name;


    public function __construct($userid, $name)
    {
        $this->userid = $userid;
        $this->name = $name;
    }


    //
}
