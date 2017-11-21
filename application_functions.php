
<?php

/**
 * This class implement the application Logic for rendering services 
 * 
 * */
require 'database.php';
class ApplicationFunctions
{

    public function IdentifyUser($msisdn)
    {
        $db = Database::getInstance();
        try {
            $stmt = $db->prepare("insert into sessionmanager(msisdn) values (:msisdn)");
            $stmt->bindParam(":msisdn", $msisdn);
            $stmt->execute();
            if ($stmt->rowCount() > 0){
                return true;
            }
        } catch (PDOException $e) {
            #$e->getMessage();
            return false;
        }
    }

    /**
     * Delete the session a session entry in the database.
     * 
     * */
    public function deleteSession($msisdn){
        $db = Database::getInstance();
        try{
            $stmt = $db->prepare("Delete FROM sessionmanager where msisdn= :msisdn");
            $stmt->bindParam(":msisdn", $msisdn);
            $stmt->execute();
            if ($stmt->rowCount() > 0) {
                return true;
            }
        } catch (PDOException $e) {
            #echo $e->getMessage();
            return false;
        }
    }

    /**
     *Method to reset a users session to the first case. (Delete all of the users records except his msisdn)
     *@param msisdn
     *@return Boolean
     */
    public function deleteAllSession($msisdn)
    {
        $db = Database::getInstance();
        try{
            $stmt = $db->prepare("UPDATE sessionmanager SET transaction_type = NULL where msisdn= :msisdn");
            $stmt->bindParam(":msisdn", $msisdn);
            $stmt->execute();
            if ($stmt->rowCount() > 0) {
                return true;
            }
        } catch (PDOException $e) {
            return false;
        }
    }

    /**
     *Method to update user session with the actual type of transaction or details of the transaction *currently being held
     *@param msisdn, collumn, transaction type
     *@param Boolean
     **/

    public function updateSessionLevel($msisdn, $col, $value)
    {
        $db = Database::getInstance();
        try{
            $stmt = $db->prepare("update sessionmanager set " . $col . " = :value where msisdn = :msisdn");
            $params = array(":msisdn" => $msisdn, ":value" => $value);
            $stmt->execute($params);
            if ($stmt->rowCount() > 0) {
                return true;
            }
        } catch (PDOException $e) {
            #echo $e->getMessage();
            return false;
        }
    }
    
    
    public function getColumnData($msisdn,$col){
        $db = Database::getInstance();
        try{
            $stmt = $db->prepare("SELECT " .$col. " FROM  sessionmanager WHERE  msisdn = :msisdn");
            $stmt->bindParam(":msisdn", $msisdn);
            $stmt->execute();
            $res = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($res !== false) {
                return $res[$col];
            }
        } catch (PDOException $e) {
            #echo $e->getMessage();
            return null;
        }
    }
    
    public function updateColumn($transactionID,$col,$value){
        $db = Database::getInstance();
        try{
            $stmt = $db->prepare("update transactions set " . $col . " = :value where transactionID = :trans_id");
            $params = array(":trans_id" => $transactionID, ":value" => $value);
            $stmt->execute($params);
            if ($stmt->rowCount() > 0) {
                return true;
            }
        } catch (PDOException $e) {
            #echo $e->getMessage();
            return false;
        }
    }

    /**
     *Method to query specific details from the session manager. (Get value held in a specific column)
     *@param msisdn, specific column to query
     *@return string
     */

    public function GetTransactionType($msisdn, $col)
    {
        $db = Database::getInstance();
        try{
            $stmt = $db->prepare("SELECT " . $col . " FROM  sessionmanager WHERE  msisdn = :msisdn");
            $stmt->bindParam(":msisdn", $msisdn);
            $stmt->execute();
            $res = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($res !== false) {
                return $res[$col];
            }
        } catch (PDOException $e) {
            #echo $e->getMessage();
            return null;
        }
    }

    /**
     *Method to query users session state. checking if the user has an existing session and if so the session count.
     *@param msisdn, specific column to query
     *@return string
     **/

    public function sessionManager($msisdn)
    {
        $db = Database::getInstance();
        try{
            $stmt = $db->prepare("SELECT (COUNT(msisdn)+ COUNT(transaction_type)+COUNT(amount)+COUNT(recipient_number)) AS counter FROM sessionmanager WHERE msisdn = :msisdn");
            $stmt->bindParam(":msisdn", $msisdn);
            $stmt->execute();
            $res = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($res !== false) {
                return $res['counter'];
            }
        } catch (PDOException $e) {
            #echo $e->getMessage();
            return null;
        }
    }

    public function addTransaction($sender_number, $recipient_number,$amount,$transactionID){
        $db = Database::getInstance();
        try {
            $stmt = $db->prepare("insert into transactions(sender_msisdn,recipient_msisdn,amount,transactionID) values (:sender_msisdn,:recipient_msisdn,:amount,:transactionID)");
            $stmt->bindParam(":sender_msisdn", $sender_number);
            $stmt->bindParam(":recipient_msisdn", $recipient_number);
            $stmt->bindParam(":amount", $amount);
            $stmt->bindParam(":transactionID",$transactionID);
            $stmt->execute();
            if ($stmt->rowCount() > 0) {
                return true;
            }
        } catch (PDOException $e) {
            #$e->getMessage();
            return false;
        }
    }
    
    public function getTransactionDetails($transactionID){
        $db = Database::getInstance();
        try{
            $stmt = $db->prepare("SELECT sender_msisdn,recipient_msisdn,amount FROM  transactions WHERE  transactionsID = :trans_id");
            $stmt->bindParam(":trans_id", $transactionID);
            $stmt->execute();
            $res = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($res !== false) {
                return $res;
            }
        } catch (PDOException $e) {
            #echo $e->getMessage();
            return null;
        }
    }


    /**
     * 
     * @param type $recipient_number phone number to send money to
     * @param type $sender_number phone number to send money from
     * @return string generated transaction id
     */
    public function generateTransactionId($recipient_number,$sender_number){
        $prefix = random_int(10, 1000);
        $resp = substr($recipient_number,3,5);
        $send = substr($sender_number,5);
        
        $transactionID = $prefix.$send.$resp;
        return $transactionID;
    }

    /**
     * Retrieve the total number of transactions 
     * 
     * */
    public function transactionCount(){
        $db = Database::getInstance();
        try{

            $stmt = $db->prepare("SELECT COUNT(*) FROM transactions");
            $stmt->execute();
            $num_rows = $stmt->fetchColumn();

            return $num_rows;
        }catch (PDOException $e) {
            #echo $e->getMessage();
            return null;
        }
    }

    /**
     * Reteieve all transaction from database
     * 
     * */
    public function getTransactions(){
        $db = Database::getInstance();
        try{
            $stmt = $db->prepare("SELECT * FROM  transactions ORDER BY timestamp DESC");
            $stmt->execute();
            $res = $stmt->fetchAll();
            if ($res !== false) {
                return $res;
            }
        } catch (PDOException $e) {
            #echo $e->getMessage();
            return null;
        }
    }

    /**
     * Retieve transactions for the current month
     * 
     * */
    public function getCurrentMonthTransactions(){
       $db = Database::getInstance();
        try{
            $stmt = $db->prepare("SELECT SUM(amount) AS totaltrans FROM transactions WHERE MONTH(timestamp) = MONTH(NOW()) AND YEAR(timestamp) = YEAR(NOW())");

            $stmt->execute();
            $res = $stmt->fetch(PDO::FETCH_ASSOC);
            if ($res !== false) {
                return $res['totaltrans'];
            }
        } catch (PDOException $e) {
            #echo $e->getMessage();
            return null;
        }
    }

    public function countOfCompleteTransfers(){
        $db = Database::getInstance();
        try{

            $stmt = $db->prepare("SELECT COUNT(*) FROM transactions WHERE debit_status=success AND credit_status=success");
            //$stmt->bindParam(":status1","success");
            //$stmt->bindParam(":status2","success");
            $stmt->execute();
            $num_rows = $stmt->fetchColumn();

            return $num_rows;
        }catch (PDOException $e) {
            #echo $e->getMessage();
            return null;
        }
    }

}
