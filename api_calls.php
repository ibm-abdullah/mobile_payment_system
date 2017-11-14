<?php

/**
 *
 */
class APICalls {

    public function credit($amount, $sender_number, $vendor) {

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_URL => 'http://pay.npontu.com/api/pay',
            CURLOPT_POST => 1,
            CURLOPT_POSTFIELDS => array(
                amt => $amount,
                number => $sender_number,
                uid => 'ashesi',
                pass => 'ashesi',
                tp => '19486393035a03c90fd2afd',
                trans_type => 'debit',
                msg => 'tranfering money',
                vendor => $vendor,
                cbk => 'http://gmpay.npontu.com/api/tigo',
            ),
        ));
        // Send the request & save response to $resp
        $resp = curl_exec($curl);

        processAPIRequest($resp);
        // Close request to clear up some resources
        curl_close($curl);
        if ($resp === FALSE) {
            $resp = false;
        } else if ($response === TRUE) {
            //No error but no response either
            $resp = false;
        } else {
            //encode the json response into an associative array
            $resp = json_decode($response, true);
        }
        curl_close($curl);
        return $resp;
    }

    public function debit($amount, $recipient_number, $vendor) {

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_URL => 'http://pay.npontu.com/api/pay',
            CURLOPT_POST => 1,
            CURLOPT_POSTFIELDS => array(
                amt => $amount,
                number => $recipient_number,
                uid => 'ashesi',
                pass => 'ashesi',
                tp => '19486393035a03c90fd2afd',
                trans_type => 'credit',
                msg => 'Recieving money',
                vendor => $vendor,
                cbk => 'http://gmpay.npontu.com/api/tigo',
            ),
        ));
        // Send the request & save response to $resp
        $resp = curl_exec($curl);

        if ($resp === FALSE) {
            $resp = false;
        } else if ($response === TRUE) {
            //No error but no response either
            $resp = false;
        } else {
            //encode the json response into an associative array
            $resp = json_decode($response, true);
        }
        curl_close($curl);
        return $resp;
    }

}

?>
