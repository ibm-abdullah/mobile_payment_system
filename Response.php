<?php

//INCLUDE APPLICATIONS SCRIPT
include 'ApplicationFunctions.php';
$ussdr = new ApplicationFunctions();

//Perform another operation, either credit or debit if the variables are back
if (isset($_POST['transaction_id'])&&isset($_POST['status'])&&isset($_POST['responseMessage'])) {
    $id=$_POST['transaction_id'];
    $status=$_POST['status'];
    $msg = $_POST['responseMessage'];
    $transT=$ussdr->getTransType($id);
   
        $data= "id: ".$id."| status: ".$status."| response ".$msg;
          $write =  $data . PHP_EOL;
          
          file_put_contents('apires.log', $write, FILE_APPEND);
          
          date_default_timezone_set('GMT');
          $time = date('Y-m-d H:i:s');
          $ussdr->updateContent($id, $time,$msg);

    if(strpos($transT, "debit-credit")!== false||strpos($transT, "credit-debit")!== false){
      $write =  "End of transactions" . PHP_EOL;
      file_put_contents('apires.log', $write, FILE_APPEND);
    }elseif (strpos($transT, 'debit')!== false) {
        $transT="credit";
        $ussdr->sendData($id, $transT);
        $ussdr->UpdateTransType($id, "debit-credit");
        $ussdr->updateContent($id, $time,$msg);
    } elseif (strpos($transT, 'credit')!== false) {
        $transT="debit";
        $ussdr->sendData($id, $transT);
        $ussdr->UpdateTransType($id, "credit-debit");
        $ussdr->updateContent($id, $time,$msg);
    } else{

    }
}else{

  $write =  "no data received" . PHP_EOL;
  
  file_put_contents('apires.log', $write, FILE_APPEND);
}

