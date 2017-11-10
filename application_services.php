<?php

/**
 * This file contains the content that are displayed to the user in the USSD interface.
 *
 * @Author : Ibrahim Abdullah
 * @Since : November 9, 2017
 * This work is submitted as a Midsemester project for my  Mobile Application Development Class @ Ashesi University College
 *
 * */

//Error
error_reporting(E_ALL);
ini_set('display_errors', 1);

//Require that application_functions script in included in the file
require 'application_functions';

//Define script gobal variables

date_default_timezone_set('GMT'); //set timezone
$time = date('Y-m-d H:i:s');

//Create a seesion ID for a transaction
$ussd = new ApplicationFunctions();

//Get session variables from user request
$msisdn    = $_GET['number'];
$data      = $_GET['body'];
$sessionID = $_GET['sessionID'];

//Check for the seesion level of the user
$sess = intval($ussd->sessionManager($msisdn));

//Log a session reguest to file
$write = $time . "|Request|" . $msisdn . "|" . $sessionID . "|" . $data . "|" . $sess . PHP_EOL;
file_put_contents('ussd_access.log', $write, FILE_APPEND);

//Check the seesion level of the user

if ($sess == "0") {
    //If the session level is zero, display the application menu to the user
    $ussd->IdentifyUser($msisdn);

    $reply = "Welcome to Hamdulilah Mobile Payment System" . "\r\n" . "1. Send Money to all Networks" . "\r\n" . "2. Exit";
    $type = "1";

} else {

    switch ($sess) {

        case 1: #SESSION COUNT =1 #SERVICE LEVEL 1

            if ($data == '1') {

                $reply = "1. Enter recipient number" . "\r\n" . "2. Exit";

                $type = "1";

                $ussd->UpdateTransactionType($msisdn);

            } elseif ($data == '2') {

                $reply = "Trascation process cancelled.";

                $type = "0";

                $ussd->deleteSession($msisdn);

            } else {

                $reply = "Invalid Option Selected";

                $type = "0";

                $ussd->deleteSession($msisdn);

            }

            break;

        case 2: #SESSION COUNT =2 #SERVICE LEVEL 2

            //Check if the data is a valid telephone number
            //Find the regular expression for validating Ghana telephone number
            if (preg_match('/(0[0-9]{9})/', $data) {

                $reply = "Enter amount in GHS". "\r\n" . "2. Exit";

                $type = "1";

                $ussd->UpdateTransactionType($msisdn);

            } elseif ($data == '2') {

                $reply = "Trascation process cancelled.";

                $type = "0";

                $ussd->deleteSession($msisdn);

            }else {

                $reply = "Invalid phone number or option selected";

                $type = "0";

                $ussd->deleteSession($msisdn);

            }

            break;
        case 3:

            //Validate the amount of money entered by the user
            if(preg_match("/^\d+(?:\.\d{2})?$/",$data){
                //add send button and cancel button to the interface
            }elseif ($data == '2') {
                $reply = "Trascation process cancelled.";

                $type = "0";

                $ussd->deleteSession($msisdn);            
            }
            else{
                $reply = "Invalid amount or option selected";

                $type = "0";

                $ussd->deleteSession($msisdn);
            }
            break;

        default:

            $reply = "More session counts and menus to come.";

            $type = "0";

            $ussd->deleteSession($msisdn);

            break;

    }

}

$response = $msisdn . '|' . $reply . '|' . $sessionID . '|' . $type;

$write = $time . "|Request_reply|" . $response . PHP_EOL;

file_put_contents('ussd_access.log', $write, FILE_APPEND);

echo $response;
