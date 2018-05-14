<?php

namespace App\Domain\Model\Identity;

use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    public static $ROLE_GROUP_PREFIX = 'ROLE-INTERNAL-GROUP: ';


    protected $table = 'groups';
    protected $fillable = ['groupid', 'name', 'description', 'members'];



    public function __construct($groupid = null, $name =null, $description = null, $groupmembers = null, $attributes = array())
    {
        parent::__construct($attributes);
        $this->groupid = $groupid;
        $this->description = $description;
        $this->name = $name;
        $this->groupmembers = $groupmembers;

    }

}
