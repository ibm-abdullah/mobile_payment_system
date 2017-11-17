<?php

/**
 * This class implement method for making API calls
 */
class APICalls
{
    /**
     * 
     * @param type $amount The amount of money transfered
     * @param type $sender_number The phone number of the person sending the number
     * @param type $vendor The telecom member of the 
     * @return type The status of the call
     */
    public function debit($amount, $sender_number, $vendor,$transactionID)
    {
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_URL            => 'http://pay.npontu.com/api/pay',
            CURLOPT_POST           => 1,
            CURLOPT_POSTFIELDS     => array(
                'amt'        => "$amount",
                'number'     => "$sender_number",
                'uid'        => 'ibrahim.abdullah',
                'pass'       => 'profib2018',
                'tp'         => "$transactionID",
                'trans_type' => 'debit',
                'msg'        => 'tranfering money',
                'vendor'     => "$vendor",
                'cbk'        => '144.76.58.179/ashesi/700/debit_api_response.php',
            ),
        ));
        $response = curl_exec($curl);
        
        if(($response ===TRUE)||($response === FALSE)){
            //Could not access API. Send SMS of transaction could ot be processed
            
        }
    }

    /**
     * 
     * @param type $amount The amount of money transferred.
     * @param type $recipient_number The number the money was sent to
     * @param type $vendor the telecom vendor of the recipient
     * @return type The status of the API calls
     */
    public function credit($amount, $recipient_number, $vendor,$transactionID)
    {
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_URL            => 'http://pay.npontu.com/api/pay',
            CURLOPT_POST           => 1,
            CURLOPT_POSTFIELDS     => array(
                'amt'        => "$amount",
                'number'     => "$recipient_number",
                'uid'        => 'ibrahim.abdullah',
                'pass'       => 'profib2018',
                'tp'         => "$transactionID",
                'trans_type' => 'credit',
                'msg'        => 'Recieving money',
                'vendor'     => "$vendor",
                'cbk'        => '144.76.58.179/ashesi/700/credit_api_response.php',
            ),
        ));
        $response = curl_exec($curl);
        
        if(($response ===TRUE)||($response === FALSE)){
            //Could not access API. Send SMS of transaction could ot be processed
            
        }
    }

    /**
     * 
     * @param type $phone_number The phone number to send the text message to
     * @param type $message the message to be sent
     * @return type the status of the message
     */
    public function sendTextMessage($phone_number, $message)
    {
        $url = "api.deywuro.com/bulksms/?username=AshesiMoney&password=ashesi@123&type=0&dlr=1&destination="."$phone_number"."&source=Test&message="."$message";
        $curl = curl_init();

        //Set curl parameters
        curl_setopt_array($curl, array(
            CURLOPT_RETURNTRANSFER=>1,
            CURLOPT_URL=> "url"
        ));

        //Execute curl 
        $resp = curl_exec($curl);
        if ($resp === false) {
            $resp = false;
        } else if ($resp === true) {
            //No error but no response either
            $resp = false;
        } else {
            //encode the json response into an associative array
            $resp = json_decode($resp, true);
        }
        curl_close($curl);
        return $resp;

    }

}
