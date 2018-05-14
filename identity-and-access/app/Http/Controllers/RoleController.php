<?php

namespace App\Http\Controllers;

use App\Domain\Model\Identity\Group;
use App\Domain\Model\Identity\Role;
use App\Domain\Model\Identity\User;
use Illuminate\Http\Request;
use Webpatser\Uuid\Uuid;



class RoleController extends Controller
{


    public function createNewRole(Request $request){

        $roleToRegisterJsonString = file_get_contents('php://input');
        $roleToRegisterArray =  json_decode($roleToRegisterJsonString, true);


        $roleToRegister = new Role(Uuid::generate()->string, $roleToRegisterArray['name'], $roleToRegisterArray['description']
            , "[]", "[]");

        return $roleToRegister;

        $roleToRegister->save();
    }
    public function addGroupToRole(Request $request, $roleid){

        $groupPlayingRoleJsonString = file_get_contents('php://input');
        $groupPlayingRoleArray =  json_decode($groupPlayingRoleJsonString, true);

        //Verify Role exists

        $roleArray = Role::where('roleid', '=', $groupPlayingRoleArray['roleid'])->get();
        $groupArray = Group::where('groupid', '=', $groupPlayingRoleArray['grouptoplayrole'])->get();

        if(count($roleArray) === 1 && count($groupArray) === 1){

            //Get current groups
            $currentRoleGroups = json_decode($roleArray[0]->groupsplayingrole);

            //Verify group to add is not already in the list
            for($i=0; $i<count($currentRoleGroups);$i++){

                if($currentRoleGroups[$i] === $groupArray[0]->groupid){
                    return "group already playing role!";
                }
            }

            $newRoleGroups = [];

            //Add new group to the list

            array_push($newRoleGroups, $groupArray[0]->groupid);

            //Add previous elts
            for($i=0; $i<count($currentRoleGroups);$i++){

                array_push($newRoleGroups, $currentRoleGroups[$i]);

            }

            //Update Roles group list
            $roleArray[0]->groupsplayingrole = json_encode($newRoleGroups);


           $roleArray[0]->save();

            return $roleArray[0]->groupsplayingrole;



        }
        else{

            return "wrong results";
        }

        //return $groupPlayingRoleArray['grouptoplayroleid'];

    }
    public function addUserToRole(Request $request, $roleid){

        $usersPlayingRoleJsonString = file_get_contents('php://input');
        $userPlayingRoleArray =  json_decode($usersPlayingRoleJsonString, true);

        //Verify User exists

        $roleArray = Role::where('roleid', '=', $userPlayingRoleArray['roleid'])->get();
        $userArray = User::where('userid', '=', $userPlayingRoleArray['usertoplayrole'])->get();

        if(count($roleArray) === 1 && count($userArray) === 1){

            //Get current groups
            $currentRoleUsers = json_decode($roleArray[0]->usersplayingrole);

            //Verify group to add is not already in the list
            for($i=0; $i<count($currentRoleUsers);$i++){

                if($currentRoleUsers[$i] === $userArray[0]->groupid){
                    return "group already playing role!";
                }
            }

            $newRoleUsers = [];

            //Add new group to the list

            array_push($newRoleUsers, $userArray[0]->userid);

            //Add previous elts
            for($i=0; $i<count($currentRoleUsers);$i++){

                array_push($newRoleUsers, $currentRoleUsers[$i]);

            }

            //Update Roles users list
            $roleArray[0]->usersplayingrole = json_encode($newRoleUsers);


            $roleArray[0]->save();

            return $roleArray[0]->usersplayingrole;

        }
        else{

            return "wrong results";
        }

        //return $groupPlayingRoleArray['grouptoplayroleid'];
    }

}
