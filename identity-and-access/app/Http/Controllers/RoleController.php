<?php

namespace App\Http\Controllers;

use App\Domain\Model\Identity\Group;
use App\Domain\Model\Identity\Role;
use Illuminate\Http\Request;
use Webpatser\Uuid\Uuid;



class RoleController extends Controller
{

    public static $ROLE_GROUP_PREFIX = 'INTERNAL-GROUP-FOR-ROLE-NAMED: ';

    public function createNewRole(Request $request){

        $roleToRegisterJsonString = file_get_contents('php://input');
        $roleToRegisterArray =  json_decode($roleToRegisterJsonString, true);


        $roleToRegister = new Role(Uuid::generate()->string, $roleToRegisterArray['name'], $roleToRegisterArray['description']);

        $rolesInternalGroup = new Group(Uuid::generate()->string.'----'.$roleToRegister->roleid, 'INTERNAL-GROUP-FOR-ROLE-NAMED:'.' '.$roleToRegister->name );
        //return $roleToRegister->roleid.' '.$roleToRegister->name.' '.$roleToRegister->description;

        $roleToRegister->save();
        $rolesInternalGroup->save();
    }
}
