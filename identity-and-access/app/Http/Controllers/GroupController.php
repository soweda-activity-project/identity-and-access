<?php

namespace App\Http\Controllers;

use App\Domain\Model\Identity\Group;
use App\Domain\Model\Identity\GroupMember;
use Illuminate\Http\Request;
use Webpatser\Uuid\Uuid;
use App\Domain\Model\Identity\User;


class GroupController extends Controller
{

    ///helper functions


    public function groupmember($group, $userorgroup, $membertype)
    {

        if($membertype === 'user'){
            return new GroupMember($group->groupid, $userorgroup->userid, $userorgroup->firstname.' '.$userorgroup->lastname, 'USER');
        }
        elseif ($membertype === 'group'){

            return new GroupMember($group->groupid, $userorgroup->groupid, $userorgroup->name, 'GROUP');

        }
    }
    public function createNewGroup(Request $request){

        $groupToRegisterJsonString = file_get_contents('php://input');
        $groupToRegisterArray =  json_decode($groupToRegisterJsonString, true);

        //return $groupToRegisterArray;

        $groupToRegister = new Group(Uuid::generate()->string, $groupToRegisterArray['name'], $groupToRegisterArray['description']);

        //return $groupToRegister->groupid.' '.$groupToRegister->name.' '.$groupToRegister->description;

        $groupToRegister->save();
    }
    public function addGroupMemberToGroup(Request $request, $groupid)
    {

        $groupToMemberToJsonString = file_get_contents('php://input');
        $groupToMemberToArray =  json_decode($groupToMemberToJsonString, true);
        $userorgroupid = $groupToMemberToArray['$userorgroupid'];
        $membertype = $groupToMemberToArray['membertype'];



        //return $groupToMemberToArray;

        //Get the user or Get the group

        $ausertoadd = [];
        $agrouptoadd = [];


        if($membertype === 'user'){
            $ausertoadd = User::where('userid', '=', $userorgroupid)->get();
        }elseif($membertype === 'group'){
            $agrouptoadd = Group::where('groupid', '=', $userorgroupid)->get();
        }else{
            return 'check the membertype!';
        }


        $agrouptoaddTo = Group::where('groupid', '=', $groupid)->get();

        //return count($ausertoadd).PHP_EOL. count($agrouptoaddTo).PHP_EOL.$agrouptoadd;

        if (count($ausertoadd) === 1 && count($agrouptoaddTo) === 1 && count($agrouptoadd) === 0) {

            $groupmenber = $this->groupmember($agrouptoaddTo[0], $ausertoadd[0], $membertype);

            $groupmenber->save();

        } elseif (count($agrouptoadd) === 1 && count($agrouptoaddTo) === 1 && count($ausertoadd) === 0){

            $groupmenber = $this->groupmember($agrouptoaddTo[0], $agrouptoadd[0], $membertype);

            $groupmenber->save();
        }
        else{
            return 'mutiple instances found';

        }

        return $groupmenber;

    }




}
