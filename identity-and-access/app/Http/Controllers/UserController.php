<?php

namespace App\Http\Controllers;

use App\Domain\Model\Identity\InvitedUser;
use App\Domain\Model\Identity\User;
use App\Domain\Model\Identity\UserDescriptor;
use App\Mail\MailNotificator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;


use Webpatser\Uuid\Uuid;

use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;


class UserController extends Controller
{



    public function inviteUserToRegister(Request $request){

        $registrationInvitationJsonString = file_get_contents('php://input');
        $registrationInvitationArray =  json_decode($registrationInvitationJsonString, true);


        $anInvitedUser = new InvitedUser(Uuid::generate()->string, $registrationInvitationArray['firstname'], $registrationInvitationArray['lastname'],
            $registrationInvitationArray['email'], $registrationInvitationArray['phone']);


        $anInvitedUser->save();


        Mail::to($registrationInvitationArray['emailaddress'])->send(new MailNotificator("REGISTRATION INVITATION",
            [' ', 'Dear Mr. '.$registrationInvitationArray['lastname'], 'Please use the link below to register into the system',' ', 'Sincerely,'],
            url('/users/'.$anInvitedUser->userid.'/registration-invitations/'), 'mails.invitation-registration-temp'));

        return response($anInvitedUser, 200);


    }
    public  function  registerIvitedUser(Request $request, $inviteduserid){

        $userToRegisterJsonString = file_get_contents('php://input');
        $userToRegisterArray =  json_decode($userToRegisterJsonString, true);




        $password = $userToRegisterArray['password'];



        //Verify user was invited
        $inviteduser = InvitedUser::where('userid', '=', $inviteduserid)->get();

       //return $inviteduser;

        if(count($inviteduser) === 1){

            $foundInvitedUserId = $inviteduser[0]->userid;

        }


        if($inviteduserid === $foundInvitedUserId){


            $encryptedpassword = bcrypt($password);

            //return $encryptedpassword;
        }
        else{

            //Attacckkkkkk
        }

        $aNewUser = new User($inviteduserid, $inviteduser[0]->firstname,  $inviteduser[0]->lastname, true,
            $inviteduser[0]->email,  $inviteduser[0]->phone, $userToRegisterArray['username'], $encryptedpassword);

        //return $aNewUser;

        $aNewUser->save();

        //Notify

        $connection = null;

        try {
            $connection = new AMQPStreamConnection('localhost', 5672, 'test', 'test');
            $channel = $connection->channel();
            $channel->queue_declare('REGISTERED-USERS-QUEUE', false, true, false, false);

            $name = $aNewUser->lastname.', '.$aNewUser->firstname;

            $event = "{\"eventName\":\"NewUserCreated\", \"userId\":\"$aNewUser->userid\", \"username\":\"$aNewUser->username\", 
                   \"name\":\"$name\", \"email\":\"$aNewUser->email\", \"phone\":\"$aNewUser->phone\"}";


            $msg = new AMQPMessage($event);

            $channel->basic_publish($msg, '', 'REGISTERED-USERS-QUEUE');
            $channel->close();
        } catch (\Exception $exception){
            // manage failled event
        }

        if ($connection != null){
            $connection->close();
        }


        return $aNewUser;

}
    public  function login(Request $request, $username){

        $loginInfoJsonString = file_get_contents('php://input');
        $loginInfoArray =  json_decode($loginInfoJsonString, true);

        $username = $loginInfoArray['username'];
        $password = $loginInfoArray['password'];


        //retrieve user with given username
        $userWithUsernameArray = User::where('username', '=', $username)->get();

        if(count($userWithUsernameArray) === 1){

            $userWithUsername = $userWithUsernameArray[0];
        }
        else{

            return "multiple users were returned!";
        }
        //Check the user is enabled

        if(!($userWithUsername->enablement)){

            return "useris disabled!";
        }


        //check the given password matches
        if (Hash::check($password, $userWithUsername->password))
        {

            $userdescriptor = new UserDescriptor($userWithUsername->username, $userWithUsername->email, $userWithUsername->lastname.' '.$userWithUsername->firstname);

            //var_dump($userdescriptor);

            //return response(json_encode($userdescriptor), 200);
            return response($userdescriptor, 200);
        }
        else{
            return "Bad credentials";
        }

    }
    public  function changePassword(Request $request, $username){

        $loginInfoJsonString = file_get_contents('php://input');
        $loginInfoArray =  json_decode($loginInfoJsonString, true);

        $username = $loginInfoArray['username'];
        $oldpassword = $loginInfoArray['oldpassword'];
        $newpassword = $loginInfoArray['newpassword'];


        //retrieve user with given username
        $userWithUsernameArray = User::where('username', '=', $username)->get();

        if(count($userWithUsernameArray) === 1){

            $userWithUsername = $userWithUsernameArray[0];
        }
        else{

            return "multiple users were returned!";
        }
        //Check the user is enabled

        if(!($userWithUsername->enablement)){

            return "useris disabled!";
        }


        //check the given password matches
        if (Hash::check($oldpassword, $userWithUsername->password))
        {

            $userWithUsernameArray[0]->password = bcrypt($newpassword);

            $userWithUsernameArray[0]->save();


            //Send notification of password changed???

            return $userWithUsernameArray[0]->password;
        }
        else{
            return "Bad credentials";
        }

    }
}
