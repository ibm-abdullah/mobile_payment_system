<?php

/**
 * This file contains the content that are displayed to the user in the USSD interface.
 * @Author : Ibrahim Abdullah
 * @Since : November 9, 2017
 * This work is submitted as a Midsemester project for my  Mobile Application Development Class @ Ashesi University College
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
$msisdn               = $_GET['number'];
$data                 = $_GET['body'];
$sessionID            = $_GET['sessionID'];
$reply                = '';
$amountToBeTransfered = '';
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
                $reply = "1. Enter Amount" . "\r\n" . "0. Exit";
                $type  = "1";
                $ussd->UpdateTransactionType($msisdn, "transaction_type", 'DEBIT');
            } elseif ($data == '0') {
                $reply = "Trascation process cancelled.";
                $type  = "0";
                $ussd->deleteSession($msisdn);
            } else {
                $reply = "Invalid Option Selected";
                $type  = "0";
                $ussd->deleteSession($msisdn);
            }
            break;
        case 2:

            //Validate the amount of money entered by the user
            if (preg_match("/^\d+(?:\.\d{2})?$/", $data)) {
                //add send button and cancel button to the interface
                $amountToBeTransfered = $data;
                echo "Amount to be tranfered" . "$amountToBeTransfered" . "\r\n";
                $reply = " 1. Enter recipient phone number" . "\r\n" . "0. Exit";
                $type  = "1";
                $ussd->UpdateTransactionType($msisdn, "amount_added", 'YES');
            } elseif ($data == '0') {
                $reply = "Trascation process cancelled.";
                $type  = "0";
                $ussd->deleteSession($msisdn);
            } else {
                $reply = "Invalid amount or option selected";
                $type  = "0";
                $ussd->deleteSession($msisdn);
            }
            break;

        case 3: #SESSION COUNT =2 #SERVICE LEVEL 2
            //Check if the data is a valid telephone number
            //Find the regular expression for validating Ghana telephone number
            if (preg_match('/(0[0-9]{9})/', $data)) {

                //Determine the vendor of the both the sender and the recipient
                $data_processor = new ProcessUserInput();
                $api_accessor   = new APICalls();

                //Determine the vendor of the sender and recipient phone  numbers
                $sender_vendor    = $data_processor->identifyVendor($msisdn);
                $recipient_vendor = $data_processor->identifyVendor($data);

                if ($recipient_vendor == null) {
                    $reply = "Transaction could not be processed. Phone number invalid";
                    $type  = "0";
                    $ussd->deleteAllSession($msisdn);
                }
                //Firs API call to credit senders account
                $creditResponse = $api_accessor->credit($amountToBeTransfered, $msisdn, $sender_vendor);

                if ($creditResponse == false) {
                    //Money has not been sent to Npontu
                    $reply = "Trasaction could not be processed. Try Again";
                    $type  = "0";
                    $ussd->deleteAllSession($msisdn);
                } else {
                    $result_status = $creditResponse['status'];
                    $transactionID = $creditResponse['trans_id'];

                    if ($result_status == 'success') {

                        //Transfer money from mpontu to the mobile money
                        //account of the recipient phone number
                        $debitResponse = $api_accessor->debit($amountToBeTransfered, $data, $recipient_vendor);

                        if ($debitResponse == false) {
                            $reply = "Trascation couldn't be processed";
                            $type  = '0';
                            $ussd->deleteSession($msisdn);
                        } else {
                            $result_status = $debitResponse['status'];
                            $transactionID = $debitResponse['trans_id'];

                            if ($result_status == 'success') {

                                //Send a text message to the to both the sender and the recipient phone numbers.
                                $messageToSender = "$amountToBeTransfered" . "GHS has been tranfered from your mobile money account to " . " " . "$recipient_number " . " " . " with transactionID" . " " . "$transactionID";

                                $messageToRecipient = "The number " . " " . "$sender_number" . " " . " has transfered an amount of " . "$amountToBeTransfered" . " to your mobile money account";

                                //Send text messages
                                $senderTextMessageResponse    = $api_accessor->sendTextMessage($msisdn, $messageToSender);
                                $recipientTextMessageResponse = $api_accessor->sendTextMessage($data, $messageToRecipient);

                                if ($messageToRecipient == true) {
                                    $reply = "Transaction successful. SMS has been sent to your phone";
                                    $type  = "0";
                                    $ussd->deleteSession($msisdn);
                                }

                            } else {
                                //Transaction was not successful
                                $reply = "Trascation couldn't be processed";
                                $type  = '0';
                                $ussd->deleteSession($msisdn);
                            }
                        }

                    } else {
                        //transaction was not succesful
                        $reply = "Trascation couldn't be processed";
                        $type  = '0';
                        $ussd->deleteSession($msisdn);
                    }
                }

                $type = "1";
            } elseif ($data == '2') {

                $reply = "Trascation process cancelled.";
                $type  = "0";
                $ussd->deleteSession($msisdn);
            } else {
                $reply = "Invalid phone number";
                $type  = "0";
                $ussd->deleteSession($msisdn);
            }
            break;
        default:
            $reply = "More session counts and menus to come.";
            $type  = "0";
            $ussd->deleteSession($msisdn);
            break;
    }
}

//$response = $msisdn.'|'.$reply .'|'. $sessionID.'|'.$type;
$response = $reply . '|' . $type;
$write    = $time . "|Request_reply|" . $response . PHP_EOL;
file_put_contents('ussd_access.log', $write, FILE_APPEND);
echo $response;
