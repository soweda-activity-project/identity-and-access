<?php


namespace App\Domain\Model\Identity;
use Illuminate\Database\Eloquent\Model;
use Webpatser\Uuid\Uuid;


class Role extends Model
{

    protected $table = 'roles';
    protected $fillable = ['roleid', 'name', 'description', 'groupsplayingrole', 'usersplayingrole'];

    public function __construct($roleid = null, $name = null, $description = null, $groupsplayingrole=null, $usersplayingrole=null, $attributes = array()){

        parent::__construct($attributes);
        $this->roleid = $roleid;
        $this->name = $name;
        $this->description = $description;
        $this->groupsplayingrole = $groupsplayingrole;
        $this->usersplayingrole = $usersplayingrole;

    }

}
