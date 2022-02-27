<?php
class crud
{
    private $db;

    //constroctor to initialize private to the db connection
    function __construct($conn)
    {
        $this->db = $conn;
    }
    //function to insert a new record into the attendee database
    public function insert($fname, $lname, $sex, $email, $birth, $phone, $country, $ip,$textArea)
    {
        try {
            $sql = "INSERT INTO `user`( `firstname`, `lastname`, `sex`, `email`, `birth`, `phone`, `country`, `IP`, `creatAt`, `updateAt`, `cnt`, `textArea`)VALUES (:fname,:lname, :sex, :email,:birth,:phone,:country,:ip,  NOW() + INTERVAL 1 HOUR ,NOW() + INTERVAL 1 HOUR, 1, :textArea)";
            //preparation sql
            $stmt = $this->db->prepare($sql);
            //bind all placeholder to the actual values
            $stmt->bindparam(':fname', $fname);
            $stmt->bindparam(':lname', $lname);
            $stmt->bindparam(':sex', $sex);
            $stmt->bindparam(':email', $email);
            $stmt->bindparam(':birth', $birth);
            $stmt->bindparam(':phone', $phone);
            $stmt->bindparam(':country', $country);
            $stmt->bindparam(':ip', $ip);
            $stmt->bindparam(':textArea', $textArea);
            $stmt->execute();
            return true;
        } catch (PDOException $e) {
            echo $e->getMessage();
            return false;
        }
    }
    public function updateDB($id, $fname, $lname, $sex,$email, $birth, $phone, $country, $ip, $creatAt, $cnt, $textArea)
    {
        try {
            $sql = "UPDATE `user` SET `firstname`=:fname,`lastname`=:lname,`sex`=:sex,`email`=:email,`birth`=:birth,`phone`=:phone,`country`=:country,`IP`=:ip,`creatAt`=:creatAt,`updateAt`= NOW() + INTERVAL 1 HOUR,`cnt`=:cnt, `textArea`=:textArea WHERE `id`=:id";
            //preparation sql
            $stmt = $this->db->prepare($sql);
            //bind all placeholder to the actual values
            $stmt->bindparam(':id', $id);
            $stmt->bindparam(':fname', $fname);
            $stmt->bindparam(':lname', $lname);
            $stmt->bindparam(':sex', $sex);
            $stmt->bindparam(':email', $email);
            $stmt->bindparam(':birth', $birth);
            $stmt->bindparam(':phone', $phone);
            $stmt->bindparam(':country', $country);
            $stmt->bindparam(':ip', $ip);
            $stmt->bindparam(':creatAt', $creatAt);
            $stmt->bindparam(':cnt', $cnt);
            $stmt->bindparam(':textArea', $textArea);
            $stmt->execute();
            return true;
        } catch (PDOException $e) {
            echo $e->getMessage();
            return false;
        }
    }
    public function listDB()
    {
        $sql = "SELECT * FROM `user`";
        $result = $this->db->query($sql);
        return $result;
    }
}
