<?php

namespace App\Domain\Model\Identity;

use Illuminate\Database\Eloquent\Model;

class GroupMember extends Model
{

    protected $table = 'group_members';
    protected $fillable = ['groupid', 'memberid', 'membername', 'membertype'];

    public function __construct($groupid = null, $memberid =null, $membername = null, $membertype=null, $attributes = array())
    {
        parent::__construct($attributes);
        $this->groupid = $groupid;
        $this->memberid = $memberid;
        $this->membername = $membername;
        $this->membertype = $membertype;
    }

}
