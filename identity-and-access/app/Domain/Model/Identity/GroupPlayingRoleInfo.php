<?php

namespace App\Domain\Model\Identity;


class GroupPlayingRoleInfo
{
    public $groupid;
    public $name;


    public function __construct($groupid, $name)
    {
        $this->groupid = $groupid;
        $this->name = $name;
    }


}
