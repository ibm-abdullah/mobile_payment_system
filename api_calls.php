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
    public function credit($amount, $sender_number, $vendor)
    {
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_URL            => 'http://pay.npontu.com/api/pay',
            CURLOPT_POST           => 1,
            CURLOPT_POSTFIELDS     => array(
                'amt'        => "$amount",
                'number'     => "$sender_number",
                'uid'        => 'ashesi',
                'pass'       => 'ashesi',
                'tp'         => '19486393035a03c90fd2afd',
                'trans_type' => 'debit',
                'msg'        => 'tranfering money',
                'vendor'     => "$vendor",
                'cbk'        => 'http://gmpay.npontu.com/api/tigo',
            ),
        ));
        // Send the request & save response to $resp
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

    /**
     * 
     * @param type $amount The amount of money transferred.
     * @param type $recipient_number The number the money was sent to
     * @param type $vendor the telecom vendor of the recipient
     * @return type The status of the API calls
     */
    public function debit($amount, $recipient_number, $vendor)
    {
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_URL            => 'http://pay.npontu.com/api/pay',
            CURLOPT_POST           => 1,
            CURLOPT_POSTFIELDS     => array(
                'amt'        => "$amount",
                'number'     => "$recipient_number",
                'uid'        => 'ashesi',
                'pass'       => 'ashesi',
                'tp'         => '19486393035a03c90fd2afd',
                'trans_type' => 'credit',
                'msg'        => 'Recieving money',
                'vendor'     => "$vendor",
                'cbk'        => 'http://gmpay.npontu.com/api/tigo',
            ),
        ));
        // Send the request & save response to $resp
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
