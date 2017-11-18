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

$transactionID = POST['transaction_id'];
$status = POST['status'];
$responseMessage = POST['responseMessage'];


//Application variables
$send_sms  = new SMS_Functions();
$ussd = new ApplicationFunctions();
$data_processor = new ProcessUserInput();
$api_accesor = new APICalls();

if($status == 'success'){
    
    //Set debit_status to succes in the transaction table
    $ussd->updateColumn($transactionID, "debit_status", "success");
    
    //Then make API calls to credit recipient account
    $transaction = $ussd->getTransactionDetails($transactionID);
    if($transaction != null){
        $recipient_number = $transaction['recipient_msisdn'];
        $amount = $transaction['amount'];
        
        //Send SMS
        $send_sms->sendDebitSuccessMessage($transaction);
        $recipient_vendor = $data_processor->identifyVendor($recipient_number);
        
        //make API call
        $credit_api_response = '';
        
        //Try to debit account untill API responsd succesfully
        do{
            $credit_api_response =$api_accesor->credit($amount, $recipient_number, $recipient_vendor, $transactionID);
        }while($credit_api_respose == null);
        
    }
}else{
    //Send  a text message that transaction could not be processed
    $transaction = $ussd->getTransactionDetails($transactionID);
    $send_sms->sendDebitFailedMessage($transaction);
}

?>
