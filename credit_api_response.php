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

if($status == 'success'){
    
    //Set debit_status to succes in the transaction table
    //
    //Message recipient that money has accoun has been debited succesfully
    //Then make API calls to credit recipient account
    
    $ussd = new ApplicationFunctions();
    $data_processor = new ProcessUserInput();
    $api_accesor = new APICalls();
    $transaction = $ussd->getTransactionDetails($transactionID);
    
    if($transaction != null){
        $recipient_number = $transaction['recipient_msisdn'];
        $amount = $transaction['amount'];
        $recipient_vendor = $data_processor->identifyVendor($recipient_number);
        
        //make API call
        $api_accesor->credit($amount, $recipient_number, $recipient_vendor, $transactionID);
        
    }
}else{
    //Send  a text message that transaction could not be processed
}

?>
