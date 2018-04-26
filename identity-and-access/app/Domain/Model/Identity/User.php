<?php
namespace App\Domain\Model\Identity;
use Illuminate\Database\Eloquent\Model;


class User extends Model
{

    protected $table = 'confirmed_users';
    protected $fillable = ['userid', 'firstname', 'lastname', 'enablement', 'username', 'password', 'email', 'phone'];


    public function __construct($userid = null, $firstname = null, $lastname = null, $enablement = null, $email = null, $phone = null,  $username = null, $password = null, $attributes = array())
    {
        parent::__construct($attributes);
        $this->userid = $userid;
        $this->firstname = $firstname;
        $this->lastname = $lastname;
        $this->enablement = $enablement;
        $this->username = $username;
        $this->password = $password;
        $this->email = $email;
        $this->phone = $phone;
    }



/*
    public function changePassword($aCurrentPassword, $aChangedPassword)
    {

    }

    public function changePersonalContactInformation( $aContactInformation)
    {
    }

    public function changePersonalName( $aPersonalName)
    {
    }

    public function defineEnablement( $anEnablement)
    {

    }

    public function isEnabled()
    {
    }

    */

}
