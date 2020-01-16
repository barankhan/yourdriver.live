    <?php
error_reporting(E_ERROR);
class baseModel
{
    protected $conn;
    private $my_host = "103.86.134.90";
    private $my_user = "truck_driver";
    private $my_pwd = "!@E#y-S-W%F7h5CHBPh";
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
            die(" in Select");
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
            die("in insert");
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
            die("in update");
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




    public function getDateTimeDiffInMinutes($date1,$date2){
        $start = strtotime($date1);
        $end = strtotime($date2);
        return ($end - $start) / 60;
    }

}