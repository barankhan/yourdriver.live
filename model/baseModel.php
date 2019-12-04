    <?php
error_reporting(E_ERROR);
class baseModel
{
    private $conn;
    private $my_host = "localhost";
    private $my_user = "root";
    private $my_pwd = "password";
    private $db = "driver";
    function __construct()
    {
        try {
            $this->conn = new PDO("mysql:host=$this->my_host;dbname=$this->db", $this->my_user, $this->my_pwd);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->conn->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
        } catch (PDOException $e) {
            echo 'Not Connected to Server : ';
            echo $e->getMessage();
        }
    }
    function executeSelect($query,$params=array())
    {
        try {
            $stmt = $this->conn->prepare($query);
            $stmt->execute($params);
//            $arr = $stmt->errorInfo();
//            print_r($arr);
//            echo "<hr/>";
//            echo $stmt->queryString;
//            echo "<hr/>";
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
        $records = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $records;
    }

    function executeSelectSingle($query,$params=array())
    {
        $records = $this->executeSelect($query,$params);
        return sizeof($records)>0?$records[0]:null;
    }


    function executeInsert($query,$params=array())
    {
        try {
            $stmt = $this->conn->prepare($query);
            $stmt->execute($params);

//            $arr = $stmt->errorInfo();
//            if($arr[0]!="00000"){
//                print_r($arr);
//                die;
//            }

        } catch (PDOException $e) {
            echo $e->getMessage();
            return false;
        }
        return $this->conn->lastInsertId();
    }

    function executeUpdate($query,$params=array())
    {
        try {
            $stmt = $this->conn->prepare($query);
            $stmt->execute($params);
        } catch (PDOException $e) {
            echo $e->getMessage();
            return false;
        }
        return true;
    }

    function generateRandomString($length = 10) {
        $characters = '0123456789abcdefghijklmno)(*&^%$#@!_pqrs=tuvwx+yzABCDE-*FGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

}