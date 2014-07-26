<?php
/**
 * Created by PhpStorm.
 * User: Admin
 * Date: 11.07.14
 * Time: 21:16
 */
class dbDataProcessing {

    protected  $host='localhost';
    protected  $dbName='forum';
    protected  $user='root';
    protected  $password='7233992';
    public $DBH;

    public function __construct(){
        date_default_timezone_set("Europe/Minsk");
        try{
            $this->DBH=new PDO("mysql:host=$this->host;dbname=$this->dbName", $this->user, $this->password);
            $this->DBH->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->DBH->query("SET CHARACTER SET utf8");
        }
        catch(PDOException $e){
                echo $e->getMessage();
        }
    }

    public function saveData(array $formData){
        $tmp=$this->DBH->prepare("INSERT INTO messages(time,user_ip,user_browser,user_name,user_email,user_message) value (:time,:ip,:browser,:user_name,:email,:msg)");
        $tmp->execute($formData);

    }

    public function writeToDb($pageName){
        if ($pageName=='posts'){
            $tmp=$this->DBH->prepare("INSERT INTO "."$pageName"." (post_author,post_date,post_message,post_topic) value (:user_id,:date,:textarea,:topic_id )");
            $tmp->bindParam(':user_id', $user);
            $tmp->bindParam(':date', $date);
            $tmp->bindParam(':textarea', $textarea);
            $tmp->bindParam(':topic_id', $topic);
            $user=$_SESSION['id_user'];
            $date=date('Y-m-d H:i:s');
            $textarea=$_POST['textarea'];
            $topic=$_GET['id'];
            $tmp->execute();
        }
        elseif($pageName=='topics'){
            $tmp=$this->DBH->prepare("INSERT INTO "."$pageName"." (topic_name,topic_starter,topic_startdate,topic_startmessage) VALUE (:topic_name, :user_id, :date, :textarea)");
            $tmp->bindParam(':topic_name', $topic_name);
            $tmp->bindParam(':user_id', $user);
            $tmp->bindParam(':date', $date);
            $tmp->bindParam(':textarea', $textarea);
            $topic_name=$_POST['topic_name'];
            $user=$_SESSION['id_user'];
            $date=date('Y-m-d H:i:s');
            $textarea=$_POST['textarea'];
            $tmp->execute();
            $id=$this->DBH->query("SELECT id_topic FROM topics WHERE topic_name='"."$topic_name"."' and topic_starter='"."$user"."' AND topic_startmessage='"."$textarea"."'");
            $topicId=$id->fetch(PDO::FETCH_ASSOC);
            var_dump($topicId);

            $tmp=$this->DBH->prepare("INSERT INTO posts (post_author,post_date,post_message,post_topic) value (:user_id,:date,:textarea,:topic_id )");
            $tmp->bindParam(':user_id', $user);
            $tmp->bindParam(':date', $date);
            $tmp->bindParam(':textarea', $textarea);
            $tmp->bindParam(':topic_id', $topicId['id_topic']);
            $tmp->execute();
            return $topicId;
        }
    }


}
