<?php

namespace App\Http\Controllers;

use App\Domain\Model\Identity\Group;
use App\Domain\Model\Identity\GroupMember;
use Illuminate\Http\Request;
use Webpatser\Uuid\Uuid;
use App\Domain\Model\Identity\User;


class GroupController extends Controller
{


    public function createNewGroup(Request $request){

        $groupToRegisterJsonString = file_get_contents('php://input');
        $groupToRegisterArray =  json_decode($groupToRegisterJsonString, true);

        $groupToRegister = new Group(Uuid::generate()->string, $groupToRegisterArray['name'], $groupToRegisterArray['description'],"[]");


        $groupToRegister->save();
    }
    public function addGroupMemberToGroup(Request $request, $groupid)
    {

        $groupToMemberToJsonString = file_get_contents('php://input');
        $groupToMemberToArray =  json_decode($groupToMemberToJsonString, true);
        $userid = $groupToMemberToArray['$userid'];


        //return $userid;
        //Get the user or Get the group



        $ausertoadd = User::where('userid', '=', $userid)->get();

        //return $ausertoadd[0];

        //Get the group we are adding the user to
        $agrouptoaddto = Group::where('groupid', '=', $groupid)->get();




        if (count($ausertoadd) === 1 && count($agrouptoaddto) === 1 ) {

            //Get current groups members
            $currentgroupmembers = json_decode($agrouptoaddto[0]->members);



            //Verify user to add is not already in the list
            for($i=0; $i<count($currentgroupmembers);$i++){

                if($currentgroupmembers[$i] === $ausertoadd[0]->userid){
                    return "user already a member!";
                }
            }


            $newgroupmembers = [];

            //Add new member to the list



            array_push($newgroupmembers, $ausertoadd[0]->userid);

            //Add previous elts
            for($i=0; $i<count($currentgroupmembers);$i++){

                array_push($newgroupmembers, $currentgroupmembers[$i]);

            }

            //Update Group member list
            $agrouptoaddto[0]->members = json_encode($newgroupmembers);

            //return $agrouptoaddto[0]->members;


            $agrouptoaddto[0]->save();

            return $agrouptoaddto[0]->members;



        }
        else{

            return "wrong state!";

        }


    }




}
