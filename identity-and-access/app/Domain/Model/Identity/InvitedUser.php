<?php

namespace App\Domain\Model\Identity;

use Illuminate\Database\Eloquent\Model;

class InvitedUser extends Model
{

    protected $table = 'invited_users';
    protected $fillable = ['userid', 'firstname', 'lastname', 'email', 'phone'];


    public function __construct($userid = null, $firstname = null, $lastname = null,$email = null,$phone = null, $attributes = array())
    {

        parent::__construct($attributes);
        $this->userid = $userid;
        $this->firstname = $firstname;
        $this->lastname = $lastname;
        $this->email = $email;
        $this->phone = $phone;
    }


    //
}
