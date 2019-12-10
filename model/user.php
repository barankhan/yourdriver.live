<?php
require_once "baseModel.php";
class User extends  baseModel implements JsonSerializable {

    private $id=0,$driverSteps=0,$name=null,$email=null,$password=null,$mobile=null,$verificationToken=null,
        $createdAt=null,$updatedAt=null,$isDeleted=0,$isActive=1,$isVerified=0,$isDriver=0,$response,$firebaseToken,
        $father,$cnic,$cnicFront,$cnicRear,$picture,$licence,$vehicleFront,$vehicleRear,$registration,$route,
        $regAlphabet,$regYear,$regNo,$lat,$lng,$isDriverOnline=0,$vehicleType='Auto';





    public function update(){
        if($this->id!=0) {
            $q = "update users set driver_steps=:driverSteps,name=:name,email=:email,password=:password,mobile=:mobile,verification_token=:verificationToken,
        is_deleted=:isDeleted,is_active=:isActive,is_verified=:isVerified,is_driver=:isDriver,father=:father,cnic=:cnic,cnic_front=:cnicFront,
        cnic_rear=:cnicRear,picture=:picture,licence=:licence,vehicle_front=:vehicleFront,vehicle_rear=:vehicleRear,registration=:registration,
        route=:route,reg_alphabet=:regAlphabet,reg_year=:regYear,reg_no=:regNo,lat=:lat,lng=:lng,is_driver_online=:is_driver_online,vehicle_type=:vehicleType where id=:id";
            $params = array("id" => $this->id, "driverSteps" => $this->driverSteps, "name" => $this->name, "email" => $this->email, "password" => $this->password,
                "mobile" => $this->mobile, "verificationToken" => $this->verificationToken,
                "isDeleted" => $this->isDeleted, "isActive" => $this->isActive, "isVerified" => $this->isVerified, "isDriver" => $this->isDriver,
                "father" => $this->father, "cnic" => $this->cnic, "cnicFront" => $this->cnicFront, "cnicRear" => $this->cnicRear, "picture" => $this->picture,
                "licence" => $this->licence, "vehicleFront" => $this->vehicleFront, "vehicleRear" => $this->vehicleRear, "registration" => $this->registration,
                "route" => $this->route, "regAlphabet" => $this->regAlphabet, "regYear" => $this->regYear, "regNo" => $this->regNo,"lat"=>$this->lat,"lng"=>$this->lng
            ,"is_driver_online"=>$this->isDriverOnline,"vehicleType"=>$this->vehicleType);
            return $this->executeUpdate($q, $params);
        }
    }


    /**
     * @return string
     */
    public function getVehicleType()
    {
        return $this->vehicleType;
    }

    /**
     * @param string $vehicleType
     */
    public function setVehicleType($vehicleType)
    {
        $this->vehicleType = $vehicleType;
    }

    /**
     * @return int
     */
    public function getIsDriverOnline()
    {
        return $this->isDriverOnline;
    }

    /**
     * @param int $isDriverOnline
     */
    public function setIsDriverOnline($isDriverOnline)
    {
        $this->isDriverOnline = $isDriverOnline;
    }


    /**
     * @return mixed
     */
    public function getLat()
    {
        return $this->lat;
    }

    /**
     * @param mixed $lat
     */
    public function setLat($lat)
    {
        $this->lat = $lat;
    }

    /**
     * @return mixed
     */
    public function getLng()
    {
        return $this->lng;
    }

    /**
     * @param mixed $lng
     */
    public function setLng($lng)
    {
        $this->lng = $lng;
    }


    /**
     * @return mixed
     */
    public function getVehicleFront()
    {
        return $this->vehicleFront;
    }

    /**
     * @param mixed $vehicleFront
     */
    public function setVehicleFront($vehicleFront)
    {
        $this->vehicleFront = $vehicleFront;
    }

    /**
     * @return mixed
     */
    public function getVehicleRear()
    {
        return $this->vehicleRear;
    }

    /**
     * @param mixed $vehicleRear
     */
    public function setVehicleRear($vehicleRear)
    {
        $this->vehicleRear = $vehicleRear;
    }

    /**
     * @return mixed
     */
    public function getRegistration()
    {
        return $this->registration;
    }

    /**
     * @param mixed $registration
     */
    public function setRegistration($registration)
    {
        $this->registration = $registration;
    }

    /**
     * @return mixed
     */
    public function getRoute()
    {
        return $this->route;
    }

    /**
     * @param mixed $route
     */
    public function setRoute($route)
    {
        $this->route = $route;
    }

    /**
     * @return mixed
     */
    public function getRegAlphabet()
    {
        return $this->regAlphabet;
    }

    /**
     * @param mixed $regAlphabet
     */
    public function setRegAlphabet($regAlphabet)
    {
        $this->regAlphabet = $regAlphabet;
    }

    /**
     * @return mixed
     */
    public function getRegYear()
    {
        return $this->regYear;
    }

    /**
     * @param mixed $regYear
     */
    public function setRegYear($regYear)
    {
        $this->regYear = $regYear;
    }

    /**
     * @return mixed
     */
    public function getRegNo()
    {
        return $this->regNo;
    }

    /**
     * @param mixed $regNo
     */
    public function setRegNo($regNo)
    {
        $this->regNo = $regNo;
    }

    /**
     * @return int
     */
    public function getDriverSteps()
    {
        return $this->driverSteps;
    }

    /**
     * @param int $driverSteps
     */
    public function setDriverSteps($driverSteps)
    {
        $this->driverSteps = $driverSteps;
    }

    /**
     * @return mixed
     */
    public function getFirebaseToken()
    {
        return $this->firebaseToken;
    }

    /**
     * @return mixed
     */
    public function getFather()
    {
        return $this->father;
    }

    /**
     * @param mixed $father
     */
    public function setFather($father)
    {
        $this->father = $father;
    }

    /**
     * @return mixed
     */
    public function getCnic()
    {
        return $this->cnic;
    }

    /**
     * @param mixed $cnic
     */
    public function setCnic($cnic)
    {
        $this->cnic = $cnic;
    }

    /**
     * @return mixed
     */
    public function getCnicFront()
    {
        return $this->cnicFront;
    }

    /**
     * @param mixed $cnicFront
     */
    public function setCnicFront($cnicFront)
    {
        $this->cnicFront = $cnicFront;
    }

    /**
     * @return mixed
     */
    public function getCnicRear()
    {
        return $this->cnicRear;
    }

    /**
     * @param mixed $cnicRear
     */
    public function setCnicRear($cnicRear)
    {
        $this->cnicRear = $cnicRear;
    }

    /**
     * @return mixed
     */
    public function getPicture()
    {
        return $this->picture;
    }

    /**
     * @param mixed $picture
     */
    public function setPicture($picture)
    {
        $this->picture = $picture;
    }

    /**
     * @return mixed
     */
    public function getLicence()
    {
        return $this->licence;
    }

    /**
     * @param mixed $licence
     */
    public function setLicence($licence)
    {
        $this->licence = $licence;
    }

    /**&&isset($_FILES["picture"])
     * @return mixed
     */
    public function getResponse()
    {
        return $this->response;
    }

    /**
     * @param mixed $response
     */
    public function setResponse($response)
    {
        $this->response = $response;
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

    /**
     * @return mixed
     */
    public function getIsDeleted()
    {
        return $this->isDeleted;
    }

    /**
     * @param mixed $isDeleted
     */
    public function setIsDeleted($isDeleted)
    {
        $this->isDeleted = $isDeleted;
    }

    /**
     * @return mixed
     */
    public function getIsActive()
    {
        return $this->isActive;
    }

    /**
     * @param mixed $isActive
     */
    public function setIsActive($isActive)
    {
        $this->isActive = $isActive;
    }

    /**
     * @return mixed
     */
    public function getIsVerified()
    {
        return $this->isVerified;
    }

    /**
     * @param mixed $isVerified
     */
    public function setIsVerified($isVerified)
    {
        $this->isVerified = $isVerified;
    }

    /**
     * @return mixed
     */
    public function getIsDriver()
    {
        return $this->isDriver;
    }

    /**
     * @param mixed $isDriver
     */
    public function setIsDriver($isDriver)
    {
        $this->isDriver = $isDriver;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @param mixed $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @param mixed $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * @param mixed $password
     */
    public function setPassword($password)
    {
        $this->password = $password;
    }

    /**
     * @param mixed $mobile
     */
    public function setMobile($mobile)
    {
        $this->mobile = $mobile;
    }

    /**
     * @param mixed $verificationToken
     */
    public function setVerificationToken($verificationToken)
    {
        $this->verificationToken = $verificationToken;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @return mixed
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @return mixed
     */
    public function getMobile()
    {
        return $this->mobile;
    }

    /**
     * @return mixed
     */
    public function getVerificationToken()
    {
        return $this->verificationToken;
    }


    public function __construct(){
        parent::__construct();
    }

    public function registerUser(){
        $q = "INSERT INTO users(name, email, password, mobile,verification_token,firebase_token) VALUES(:name,:email,:password,:mobile,:verification_token,:firebase_token)";
        $params = array('name'=>$this->name,'email'=>$this->email,'password'=>$this->password,'mobile'=>$this->mobile,"verification_token"=>$this->verificationToken,"firebase_token"=>$this->firebaseToken);
        $this->setId($this->executeInsert($q,$params));
    }

    /**
     * @param mixed $firebaseToken
     */
    public function setFirebaseToken($firebaseToken)
    {
        $this->firebaseToken = (empty($firebaseToken)?$this->firebaseToken:$firebaseToken);
    }


    public function getUserWithMobile($mobile){
        $q  = "select * from users where mobile=:mobile";
        $params = array("mobile"=>$mobile);
        $rs = $this->executeSelectSingle($q,$params);
        if($rs!=null){
            $this->setAllFields($rs);
        }
    }


    public function getUserWithId($id){
        $q  = "select * from users where id=:id";
        $params = array("id"=>$id);
        $rs = $this->executeSelectSingle($q,$params);
        if($rs!=null){
            $this->setAllFields($rs);
        }
    }

    public function getUserWithMobileAndPassword($mobile,$password){
        $q  = "select * from users where mobile=:mobile and password=:password";
        $params = array("mobile"=>$mobile,"password"=>$password);
        $rs = $this->executeSelectSingle($q,$params);
        if($rs!=null){
            $this->setAllFields($rs);
        }
    }


    public function updateFirebaseToken($firebaseToken){
        if(!empty($firebaseToken)) {
            $q = "update users set firebase_token=:firebase_token where id=:id";
            $params = array("id" => $this->getId(), "firebase_token" => $firebaseToken);
            $this->executeUpdate($q, $params);
        }
    }



    public function updateDriverStep1(){
            $q = "update users set driver_steps=:driver_steps,father=:father,cnic=:cnic,picture=:picture,name=:name,
                    cnic_front=:cnic_front,cnic_rear=:cnic_rear,licence=:licence where id=:id";
            $params = array("id" => $this->getId(),"driver_steps"=>$this->driverSteps,"father"=>$this->father,"cnic"=>$this->cnic,
                "picture"=>$this->picture,"cnic_front"=>$this->cnicFront,"cnic_rear"=>$this->cnicRear,"licence"=>$this->licence,"name"=>$this->name);
            $this->executeUpdate($q, $params);
    }



    public function updateDriverStep2(){
        $q = "update users set driver_steps=:driver_steps,vehicle_front=:vehicle_front,vehicle_rear=:vehicle_rear,registration=:registration,
                    route=:route,reg_alphabet=:reg_alphabet,reg_year=:reg_year,reg_no=:reg_no,vehicle_type=:vehicleType where id=:id";
        $params = array("id" => $this->getId(),"driver_steps"=>$this->driverSteps,"vehicle_front"=>$this->vehicleFront,"vehicle_rear"=>$this->vehicleRear,
            "vehicleType"=>$this->vehicleType,"registration"=>$this->registration,"route"=>$this->route,"reg_alphabet"=>$this->regAlphabet,"reg_year"=>$this->regYear,"reg_no"=>$this->regNo);
        $this->executeUpdate($q, $params);
    }



    public function validateRegistrationToken($token){
        if($this->verificationToken==$token){
            $q = "update users set is_verified=1 where id=:id";
            $params = array("id"=>$this->getId());
            if($this->executeUpdate($q,$params)){
                return true;
            }
        }
        return false;
    }


    public function getAllApprovalRequiredDrivers(){
        $q  = "select * from users where is_driver=0 and driver_steps=2 and is_verified=1";
        return $this->executeSelect($q);
    }






    public function setAllFields($rs){

        foreach($rs as $key => $val) {
            $key = str_replace("_", " ", $key);
            $key = ucwords($key);
            $key = "set" . str_replace(" ", "", $key);
            $this->$key($val);
        }
    }



    public function jsonSerialize()
    {
        $vars = array_filter(
            get_object_vars($this),
            function ($item) {
                // Keep only not-NULL values
                return ! is_null($item);
            }
        );
        unset($vars['password']);
        unset($vars['firbaseToken']);


        return $vars;
    }

}