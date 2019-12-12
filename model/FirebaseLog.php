<?php
require_once "baseModel.php";
/**
 * Created by PhpStorm.
 * User: baran
 * Date: 12/12/19
 * Time: 5:51 PM
 */
class FirebaseLog extends  baseModel implements JsonSerializable
{

    private $id,$requestLogId,$notification,$payload,$firebaseMessageId,$firebaseKey,$firebaseConfirmation=0,$firebaseResponse,$createdAt,
    $updatedAt;


    public function insert(){
        $q = "insert into firebase_log(request_log_id,notification,payload,firebase_message_id,firebase_key,firebase_confirmation,
firebase_response)values(:requestLogId,:notification,:payload,:firebaseMessageId,:firebaseKey,:firebaseConfirmation,:firebaseResponse);";
        $params = array("requestLogId"=>$this->requestLogId,"notification"=>$this->notification,"payload"=>$this->payload,"firebaseMessageId"=>$this->firebaseMessageId,
            "firebaseKey"=>$this->firebaseKey,"firebaseConfirmation"=>$this->firebaseConfirmation,"firebaseResponse"=>$this->firebaseResponse);
        $this->setId($this->executeInsert($q,$params));
    }


    public function update(){
        $q = "update firebase_log set 
      request_log_id=:requestLogId,notification=:notification,payload=:payload,firebase_message_id=:firebaseMessageId,
      firebase_key=:firebaseKey,firebase_confirmation=:firebaseConfirmation,firebase_response=:firebaseResponse
      where id=:id";

        $params = array("requestLogId"=>$this->requestLogId,"notification"=>$this->notification,"payload"=>$this->payload,"firebaseMessageId"=>$this->firebaseMessageId,
            "firebaseKey"=>$this->firebaseKey,"firebaseConfirmation"=>$this->firebaseConfirmation
        ,"firebaseResponse"=>$this->firebaseResponse,"id"=>$this->id);
        return $this->executeUpdate($q,$params);
    }




    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getRequestLogId()
    {
        return $this->requestLogId;
    }

    /**
     * @param mixed $requestLogId
     */
    public function setRequestLogId($requestLogId)
    {
        $this->requestLogId = $requestLogId;
    }

    /**
     * @return mixed
     */
    public function getNotification()
    {
        return $this->notification;
    }

    /**
     * @param mixed $notification
     */
    public function setNotification($notification)
    {
        $this->notification = $notification;
    }

    /**
     * @return mixed
     */
    public function getPayload()
    {
        return $this->payload;
    }

    /**
     * @param mixed $payload
     */
    public function setPayload($payload)
    {
        $this->payload = $payload;
    }

    /**
     * @return mixed
     */
    public function getFirebaseMessageId()
    {
        return $this->firebaseMessageId;
    }

    /**
     * @param mixed $firebaseMessageId
     */
    public function setFirebaseMessageId($firebaseMessageId)
    {
        $this->firebaseMessageId = $firebaseMessageId;
    }

    /**
     * @return mixed
     */
    public function getFirebaseKey()
    {
        return $this->firebaseKey;
    }

    /**
     * @param mixed $firebaseKey
     */
    public function setFirebaseKey($firebaseKey)
    {
        $this->firebaseKey = $firebaseKey;
    }

    /**
     * @return mixed
     */
    public function getFirebaseConfirmation()
    {
        return $this->firebaseConfirmation;
    }

    /**
     * @param mixed $firebaseConfirmation
     */
    public function setFirebaseConfirmation($firebaseConfirmation)
    {
        $this->firebaseConfirmation = $firebaseConfirmation;
    }

    /**
     * @return mixed
     */
    public function getFirebaseResponse()
    {
        return $this->firebaseResponse;
    }

    /**
     * @param mixed $firebaseResponse
     */
    public function setFirebaseResponse($firebaseResponse)
    {
        $this->firebaseResponse = $firebaseResponse;
    }

    /**
     * @return mixed
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @param mixed $createdAt
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;
    }

    /**
     * @return mixed
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * @param mixed $updatedAt
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;
    }







    private function setAllFields($rs){

        foreach($rs as $key => $val) {
            $key = str_replace("_", " ", $key);
            $key = ucwords($key);
            $key = "set" . str_replace(" ", "", $key);
            $this->$key($val);
        }
    }



    /**
     * Specify data which should be serialized to JSON
     * @link http://php.net/manual/en/jsonserializable.jsonserialize.php
     * @return mixed data which can be serialized by <b>json_encode</b>,
     * which is a value of any type other than a resource.
     * @since 5.4.0
     */
    function jsonSerialize()
    {
        $vars = get_object_vars($this);
        return $vars;
    }
}