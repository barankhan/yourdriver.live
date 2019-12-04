<?php
require_once "baseModel.php";
/**
 * Created by PhpStorm.
 * User: baran
 * Date: 12/3/19
 * Time: 12:14 PM
 */
class LogRequest  extends  baseModel implements JsonSerializable
{

    private $id=0,$request_body,$response_body,$response_status,$mobile_number,$request_uri,$request_header;

    /**
     * LogRequest constructor.
     * @param int $id
     */
    public function __construct()
    {
        parent::__construct();
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
    public function getRequestBody()
    {
        return $this->request_body;
    }

    /**
     * @param mixed $request_body
     */
    public function setRequestBody($request_body)
    {
        $this->request_body = $request_body;
    }

    /**
     * @return mixed
     */
    public function getResponseBody()
    {
        return $this->response_body;
    }

    /**
     * @param mixed $response_body
     */
    public function setResponseBody($response_body)
    {
        $this->response_body = $response_body;
    }

    /**
     * @return mixed
     */
    public function getResponseStatus()
    {
        return $this->response_status;
    }

    /**
     * @param mixed $response_status
     */
    public function setResponseStatus($response_status)
    {
        $this->response_status = $response_status;
    }

    /**
     * @return mixed
     */
    public function getMobileNumber()
    {
        return $this->mobile_number;
    }

    /**
     * @param mixed $mobile_number
     */
    public function setMobileNumber($mobile_number)
    {
        $this->mobile_number = $mobile_number;
    }

    /**
     * @return mixed
     */
    public function getRequestUri()
    {
        return $this->request_uri;
    }

    /**
     * @param mixed $request_uri
     */
    public function setRequestUri($request_uri)
    {
        $this->request_uri = $request_uri;
    }

    /**
     * @return mixed
     */
    public function getRequestHeader()
    {
        return $this->request_header;
    }

    /**
     * @param mixed $request_header
     */
    public function setRequestHeader($request_header)
    {
        $this->request_header = $request_header;
    }


    public function insertLog(){
        $q = "INSERT INTO `request_log`
(`request_body`,`response_body`,`response_status`,`request_uri`,`request_header`,`mobile_number`)VALUES
(:request_body,:response_body,:response_status,:request_uri,:request_header,:mobile_number);";

        $params = array("request_body"=>$this->request_body,"response_body"=>$this->response_body,"response_status"=>$this->response_status,
            "request_uri"=>$this->request_uri, "request_header"=>$this->request_header,"mobile_number"=>$this->mobile_number);
         $this->setId($this->executeInsert($q,$params));
         return $this->getId();

    }



    public function updateResponse(){
        if(!empty($this->response_body)) {
            $q = "update request_log set response_body=:response_body where id=:id";
            $params = array("id" => $this->getId(), "response_body" => $this->response_body);
            $this->executeUpdate($q, $params);
        }
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