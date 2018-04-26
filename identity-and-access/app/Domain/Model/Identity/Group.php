<?php

namespace App\Domain\Model\Identity;

use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    public static $ROLE_GROUP_PREFIX = 'ROLE-INTERNAL-GROUP: ';


    protected $table = 'groups';
    protected $fillable = ['groupid', 'name', 'description'];



    public function __construct($groupid = null, $name =null, $description = null, $attributes = array())
    {
        parent::__construct($attributes);
        $this->groupid = $groupid;
        $this->description = $description;
        $this->name = $name;

    }

    public function members()
    {
        return $this->hasMany('App\Domain\Model\Identity\GroupMember');
    }
}
