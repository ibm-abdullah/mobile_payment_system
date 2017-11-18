<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
class SMS_Functions {
    
    public function sendDebitSuccessMessage($transaction) {
       $message = $transaction['amount']." GHS  has been tranfered succesfully to merchant Ibrahim Abdullah";
       $url = "api.deywuro.com/bulksms/?username=AshesiMoney&password=ashesi@123&type=0&dlr=1&destination=".$transaction['sender_msisdn']."&source=Test&message=".$message;
       
        //Send message
        $response = new HttpRequest($url, HttpRequest::METH_GET);
        $response->send();
    }
    
    public function sendDebitFailedMessage($transaction) {
       $message ="Transaction unsuccesful. Make sure you have enough money if your account";
       $url = "api.deywuro.com/bulksms/?username=AshesiMoney&password=ashesi@123&type=0&dlr=1&destination=".$transaction['sender_msisdn']."&source=Test&message=".$message;
       
        //Send message
        $response = new HttpRequest($url, HttpRequest::METH_GET);
        $response->send();
    }
    public function sendCreditSuccessMessage($transaction,$who) {
        $message = '';
        $url = '';
        if($who =="sender"){
            $message = $transaction['amount']." GHS  has been tranfered succesfully to ". $transaction['recipient_msisdn'];
            $url = "api.deywuro.com/bulksms/?username=AshesiMoney&password=ashesi@123&type=0&dlr=1&destination=".$transaction['sender_msisdn']."&source=Test&message=".$message;
        }else if($who =="reciever"){
            $message = $transaction['sender_msisdn']. " has tranfered ".$transaction['amount']." GHS to your mobile money account";
            $url = "api.deywuro.com/bulksms/?username=AshesiMoney&password=ashesi@123&type=0&dlr=1&destination=".$transaction['recipient_msisdn']."&source=Test&message=".$message;
        }else{
            
        }
        //Send message
        $response = new HttpRequest($url, HttpRequest::METH_GET);
        $response->send();
    }
    
    public function sendCreditFailedMessage($msisdn) {
        
    }

}

?>
