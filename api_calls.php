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
    public function debit($amount,$sender_number,$vendor,$transactionID)
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
                'pass'       => 'ibrahim.abdullah',
                'tp'         => "$transactionID",
                'trans_type' => 'debit',
                'msg'        => 'tranfering money',
                'vendor'     => "$vendor",
                'cbk'        => 'http://16d4a517.ngrok.io/mobilesystem/debit_api_response.php'
            )
        ));
        $response = curl_exec($curl);

        if(($response ===TRUE)||($response === FALSE)){
            //Could not access API. Send SMS of transaction could ot be processed
            return null;   
        }else{
            return json_decode($response,true);
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
                'pass'       => 'ibrahim.abdullah',
                'tp'         => "$transactionID",
                'trans_type' => 'credit',
                'msg'        => 'Recieving money',
                'vendor'     => "$vendor",
                'cbk'        => 'http://16d4a517.ngrok.io/mobilesystem/credit_api_response.php'
            )
        ));
        $response = curl_exec($curl);
        
        if(($response ===TRUE)||($response === FALSE)){
            //Could not access API. Send SMS of transaction could ot be processed
            return null;   
        }else{
            return json_decode($response,true);
        }
    }

}
