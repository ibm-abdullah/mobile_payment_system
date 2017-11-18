<?php

/**
 * This file contains the content that are displayed to the user in the USSD interface.
 * @Author : Ibrahim Abdullah
 * @Since : November 9, 2017
 * This work is submitted as a Midsemester project for my  Mobile Application Development Class @Ashesi 
 * University College
 *
 **/
//Error
error_reporting(E_ALL);
ini_set('display_errors', 1);

//Require that application_functions script in included in the file
require 'application_functions.php';
require 'api_calls.php';
require 'data_processing.php';

//Define script gobal variables

date_default_timezone_set('GMT'); //set timezone
$time = date('Y-m-d H:i:s');

//Create a seesion ID for a transaction
$ussd = new ApplicationFunctions();

//Get session variables from user request
$msisdn = $_GET['number'];
$data = $_GET['body'];
$sessionID= $_GET['sessionID'];
$reply= '';
//Check for the seesion level of the user
$sess = intval($ussd->sessionManager($msisdn));

//Log a session reguest to file
$write = $time . "|Request|" . $msisdn . "|" . $sessionID . "|" . $data . "|" . $sess . PHP_EOL;
file_put_contents('ussd_access.log', $write, FILE_APPEND);

//Check the seesion level of the user

if ($sess == "0") {
    //If the session level is zero, display the application menu to the user
    $ussd->IdentifyUser($msisdn);

    $reply = "Welcome to Hamdulilah Mobile Payment System" . "\r\n" . "1. Send Money to all Networks" . "\r\n" . "0. Exit";
    $type  = "1";
} else {

    switch ($sess) {

        case 1: #SESSION COUNT =1 #SERVICE LEVEL 1

            if ($data == '1') {
                $reply = "Enter the amount to be transferred" . "\r\n" . "0. Exit";
                $type  = "1";
                $ussd->updateSessionLevel($msisdn, "transaction_type", "DEBIT");
            } elseif ($data == '0') {
                $reply = "Transaction process cancelled.";
                $type  = "0";
                $ussd->deleteSession($msisdn);
            } else {
                $reply = "Invalid Option Selected";
                $type  = "0";
                $ussd->deleteSession($msisdn);
            }
            break;
        case 2: #SESSION COUNT =2 #SERVICE LEVEL 2

            //Validate the amount of money entered by the user
            if (preg_match("/^\d+(?:\.\d{2})?$/", $data)) {
                //add send button and cancel button to the interface
                $amountToBeTransfered = $data;
                $reply = "Enter the phone number the send money to" . "\r\n" . "0. Exit";
                $type  = "1";
                $ussd->updateSessionLevel($msisdn, "amount", "$data");
            } elseif ($data == '0') {
                $reply = "Transaction process cancelled.";
                $type  = "0";
                $ussd->deleteSession($msisdn);
            } else {
                $reply = "Invalid amount or option selected";
                $type  = "0";
                $ussd->deleteSession($msisdn);
            }
            break;

        case 3: #SESSION COUNT =3 #SERVICE LEVEL 3
            //Check if the data is a valid telephone number
            //Find the regular expression for validating Ghana telephone number
            if (preg_match('/(0[0-9]{9})/', $data)) {

                //Determine the vendor of the both the sender and the recipient
                $data_processor = new ProcessUserInput();

                //Determine the vendor of the sender and recipient phone  numbers
                $recipient_vendor = $data_processor->identifyVendor($data);
                $recipient_number = $data;
                
                //Cancel transaction if recipient number number does not have 
                //the correct venfor name
                if($recipient_vendor == NULL){
                    //Incorrect recipient number
                    //Cancel transaction
                    $reply = "Recipient number incorrect";
                    $type  = "0";
                    $ussd->deleteSession($msisdn);
                }else{
                    $amountToBeTransfered = $ussd->getColumnData($msisdn,"amount");
                    $reply ="Confirm you want to transfer ".$amountToBeTransfered." GHS from your account to ".$data."\r\n"
                        . "1. Confirm". "\r\n" ."0. Cancel";
                    $type ="1";
                    $ussd->updateSessionLevel($msisdn, "recipient_number", "$data");
                }
            }else if($data == 0){
                $reply = "Transaction process cancelled.";
                $type  = "0";
                $ussd->deleteSession($msisdn);
            }else{
                $reply = "Transaction process cancelled.";
                $type  = "0";
                $ussd->deleteSession($msisdn);
            }
            break;
        case 4: #SESSION COUNT =4 #SERVICE LEVEL 4
            if($data == '1'){
                
                $amount = $ussd->getColumnData($msisdn,"amount");
                $recipient_number = $ussd->getColumnData($msisdn,"recipient_number");
                
                $data_processor = new ProcessUserInput();
                $sender_vendor    = $data_processor->identifyVendor($msisdn);
                
                //Generate transaction ID
                $transactionID = $ussd->generateTransactionId($recipient_number, $msisdn);
                
                //Insert transaction details into database
                $ussd->addTransaction($msisdn, $recipient_number, $amount, $transactionID);
                
                //make the firs API call 
                $api_accesor = new APICalls();
                $debit_response = $api_accesor->debit($amount, $msisdn, $sender_vendor, $transactionID);
                
                if(($debit_response == null) ||($debit_response['status'] =="failed")){
                    $reply = "Apllication server is down. Transaction cancelled";
                    $type = "0";
                    $ussd->deleteSession($msisdn);
                }else{ 
                    $reply = "Your transcation is being processed";
                    $type = "0";
                    $ussd->deleteSession($msisdn);
                }
            }else if($data == '0'){
                $reply = "Transaction process cancelled.";
                $type  = "0";
                $ussd->deleteSession($msisdn);
            }else{
                $reply = "Transaction process cancelled.";
                $type  = "0";
                $ussd->deleteSession($msisdn);
            }
            break;
        default:
            $reply = "Trnsaction cancelled.";
            $type  = "0";
            $ussd->deleteSession($msisdn);
            break;
    }
}

$response = $msisdn.'|'.$reply .'|'. $sessionID.'|'.$type;
//$response = $reply . '|' . $type;
$write    = $time . "|Request_reply|" . $response . PHP_EOL;
file_put_contents('ussd_access.log', $write, FILE_APPEND);
echo $response;
