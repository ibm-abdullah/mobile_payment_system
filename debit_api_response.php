<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
require 'application_functions.php';
require 'api_calls.php';
require 'data_processing.php';
require 'sms_functions.php';
if (isset($_REQUEST['transaction_id']) && isset($_REQUEST['responseMessage']) && isset($_REQUEST['status'])) {
    $transactionID   = $_REQUEST['transaction_id'];
    $status          = $_REQUEST['status'];
    $responseMessage = $_REQUEST['responseMessage'];

//Application variables
    $send_sms       = new SMS_Functions();
    $ussd           = new ApplicationFunctions();
    $data_processor = new ProcessUserInput();
    $api_accesor    = new APICalls();

    if ($status == 'success') {

        //Set debit_status to succes in the transaction table
        $ussd->updateColumn($transactionID, "debit_status", "success");

        //Then make API calls to credit recipient account
        $transaction = $ussd->getTransactionDetails($transactionID);
        if ($transaction != null) {
            $recipient_number = $transaction['recipient_msisdn'];
            $amount           = $transaction['amount'];

            //Send SMS
            $sms_response = $send_sms->sendDebitSuccessMessage($transaction, $responseMessage);

            $recipient_vendor = $data_processor->identifyVendor($recipient_number);
            //make API call
            $credit_api_response = $api_accesor->credit($amount, $recipient_number, $recipient_vendor, $transactionID);

        }
    } else {
        //Send  a text message that transaction could not be processed
        $transaction  = $ussd->getTransactionDetails($transactionID);
        $sms_response = $send_sms->sendDebitFailedMessage($transaction, $responseMessage);
    }
}
