<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
class SMS_Functions {
    
    public function sendDebitSuccessMessage($transaction,$message) {

        $curl = curl_init();
        // Set some options - we are passing in a useragent too here
        curl_setopt_array($curl, array(
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_URL => 'api.deywuro.com/bulksms/?username=AshesiMoney&password=ashesi@123&type=0&dlr=1&destination='.$transaction['sender_msisdn'].'&source=Test&message='.$message
        ));
        // Send the request & save response to $resp
        $resp = curl_exec($curl);
        // Close request to clear up some resources
        curl_close($curl);
       
       //$url = "api.deywuro.com/bulksms/?username=AshesiMoney&password=ashesi@123&type=0&dlr=1&destination=".$transaction['sender_msisdn']."&source=Test&message=".$message;
       
        //Send message
        //$response = new HttpRequest($url, HttpRequest::METH_GET);
        //$response->send();
    }
    
    public function sendDebitFailedMessage($transaction,$message) {

        $curl = curl_init();
        // Set some options - we are passing in a useragent too here
        curl_setopt_array($curl, array(
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_URL => 'api.deywuro.com/bulksms/?username=AshesiMoney&password=ashesi@123&type=0&dlr=1&destination='.$transaction['sender_msisdn'].'&source=Test&message='.$message
        ));
        // Send the request & save response to $resp
        $resp = curl_exec($curl);
        // Close request to clear up some resources
        curl_close($curl);
       //$url = "api.deywuro.com/bulksms/?username=AshesiMoney&password=ashesi@123&type=0&dlr=1&destination=".$transaction['sender_msisdn']."&source=Test&message=".$message;
       
        //Send message
        //$response = new HttpRequest($url, HttpRequest::METH_GET);
        //$response->send();
    }
    public function sendCreditSuccessMessage($transaction,$who,$message) {
        $url = '';
        if($who =="sender"){
            $url = "api.deywuro.com/bulksms/?username=AshesiMoney&password=ashesi@123&type=0&dlr=1&destination=".$transaction['sender_msisdn']."&source=Test&message=".$message;
        }else if($who =="reciever"){
            $url = "api.deywuro.com/bulksms/?username=AshesiMoney&password=ashesi@123&type=0&dlr=1&destination=".$transaction['recipient_msisdn']."&source=Test&message=".$message;
        }else{
            
        }

        $curl = curl_init();
        // Set some options - we are passing in a useragent too here
        curl_setopt_array($curl, array(
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_URL =>"$url"
        ));
        // Send the request & save response to $resp
        $resp = curl_exec($curl);
        // Close request to clear up some resources
        curl_close($curl);
        //Send message
        //$response = new HttpRequest($url, HttpRequest::METH_GET);
        //$response->send();
    }
    
    public function sendCreditFailedMessage($msisdn,$message) {       
        $curl = curl_init();
        // Set some options - we are passing in a useragent too here
        curl_setopt_array($curl, array(
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_URL =>'api.deywuro.com/bulksms/?username=AshesiMoney&password=ashesi@123&type=0&dlr=1&destination='.$transaction['recipient_msisdn'].'&source=Test&message='.$message
        ));
        // Send the request & save response to $resp
        $resp = curl_exec($curl);
        // Close request to clear up some resources
        curl_close($curl);

        //Send message
        //$response = new HttpRequest($url, HttpRequest::METH_GET);
        //$response->send();

    }

}

?>
