<?php
class ConnectDB {
    protected  $host='localhost';
    protected  $dbname='vdn_forum';
    protected  $user='root';
    protected  $pass='root';
    protected  $DBH;

    function __construct(){
        try {
            $this->DBH = new PDO("mysql:host=$this->host;dbname=$this->dbname", $this->user, $this->pass);
        }
        catch(PDOException $e) {
            echo $e->getMessage();
        }
    }
}

class SignUp extends ConnectDB {
    /**
     * Регистрация нового пользователя
     * @param $username
     * @param $password
     * @param $repassword
     * @return bool
     */
    public function writeData($username,$password,$repassword){
        session_start();
        if ($password==$repassword){
            $select = $this->DBH->prepare("SELECT * FROM vdn_users WHERE username = '$username'");
            $select->execute();
            $count=$select->rowCount();
            $row=$select->fetch();
            var_dump($count);
            if($count==0){
                $insert = $this->DBH->prepare("INSERT INTO vdn_users (username,password,date_registration,id_status)VALUE ('$username',SHA('$password'),NOW(),'2')");
                $id_user=$row['id_user'];
                $_SESSION['id_user']=$id_user;
                $username=$row['username'];
                $_SESSION['username']=$username;
                return $insert->execute();
            }else{$_SESSION['name_error']="that name already exists";}
        }else{$_SESSION['password_error']="passwords do not match";}
    }
}

class Authorization extends ConnectDB {
    /**
     * Вход зарегестрированного пользователя
     * @param $username
     * @param $password
     * @return bool
     */
    public function  LogIn($username,$password){
        session_start();
        $error_msg=" ";
        if(!isset($_SESSION['id_user'])){
                if(!empty($username) && !empty($password)){
                    $select = $this->DBH->prepare("SELECT * FROM vdn_users WHERE username='$username' AND password=SHA('$password')");
                    $select->execute();
                    $count=$select->rowCount();
                    if($count==1){
                        $row=$select->fetch();
                        $id_user=$row['id_user'];
                        $_SESSION['id_user']=$id_user;
                        setcookie('id_user',$row['id_user'],time()+ 60*60);
                        $username=$row['username'];
                        $_SESSION['username']=$username;
                        setcookie('username',$row['username'],time()+ 60*60);
                        return true;
                    }else{
                        $error_msg='To enter, you must enter a valid username and password';
                        $_SESSION['error_msg']=$error_msg;
                    }

                }else{
                    $error_msg='Name or password is not correct';
                    $_SESSION['error_msg']=$error_msg;
                }
        }
    }

    /**
     * Выход зарегестрированного пользователя
     */
    public function  LogOut(){
        session_start();
        if(isset($_SESSION['id_user'])){
            $_SESSION=array();
            if(isset($_COOKIE[session_name()])){
                setcookie(session_name(),'',time() - 3600);
            }
            session_destroy();
        }
        setcookie('username','$username',time() -3600);
        setcookie('id_user','$id_user',time() -3600);
        $home_url='http://'.$_SERVER['HTTP_HOST'].dirname($_SERVER['PHP_SELF']).'/index.php';
        header('Location:'.$home_url);
    }
}

class Validation {
    /**
     * @param $username
     * @return bool|int
     */
    public function validName($username){
        if (!empty($username)) {
            return preg_match('/^([a-zA-Z0-9]{3,}\s?).*/',$username);

        }else {
            $_SESSION['name_error'] = '|name error|';
            return false;
        }
    }

    /**
     * @param $comment
     * @return bool
     */
    public function validComment($comment){
        if (!empty($comment)){
            if (strlen($comment)<15){
                $_SESSION['comment_error']='|ERROR:entered text contains less than 15 characters|';
                return false;
            }else{
                return true;}
        }else{
            $_SESSION['comment_error']='|enter your comment|';
            return false;
        }
    }

    /**
     * @param $captcha
     * @return bool
     */
    public  function validCaptcha($captcha){
        if ($_SESSION['pass_phrase']!==$captcha){
            $_SESSION['captcha_error']='|Enter the word verification|';
            return false;
        }else{
            return true;}
    }
}

class Profile extends ConnectDB {


    /**
     * Запрос данных о пользователе
     * @param $id_user
     * @return array
     */
    public function viewProfile($id_user){
        $select = $this->DBH->prepare(
           "SELECT * FROM vdn_forum.vdn_profiles
            join vdn_forum.vdn_status
            ON id_user=$id_user and vdn_profiles.id_status=vdn_status.id_status");
        $select->execute();
        $row=$select->fetch();
        $arr_var=array();
        $arr_var[]=$row['last_name'];
        $arr_var[]=$row['first_name'];
        $arr_var[]=$row['gender'];
        $arr_var[]=$row['date_birth'];
        $arr_var[]=$row['photo'];
        //$arr_var[]=$row['status_name'];
        return $arr_var;

    }

    /**
     * Изменение данных пользователя
     * @param $id_user
     * @return bool
     */
    public  function editProfile($id_user,$last_name,$first_name,$gender,$date_birth){
        define('MM_UPLOADPATH', 'images/');
        define('MM_MAXFILESIZE', 320768);      // 320 KB
        define('MM_MAXIMGWIDTH', 320);        // 320 pixels
        define('MM_MAXIMGHEIGHT', 320);       // 320 pixels
        $photo_type=$_FILES['photo']['type'];
        $photo_size=$_FILES['photo']['size'];
        $photo=$_FILES['photo']['name'];
        $photo=time().$photo;
        list($photo_width, $photo_height) = getimagesize($_FILES['photo']['tmp_name']);
        if (!empty($photo)) {
            if ((($photo_type == 'image/gif') || ($photo_type == 'image/jpeg') || ($photo_type == 'image/pjpeg') ||
                    ($photo_type == 'image/png')) && ($photo_size > 0) && ($photo_size <= MM_MAXFILESIZE) &&
                ($photo_width <= MM_MAXIMGWIDTH) && ($photo_height <= MM_MAXIMGHEIGHT)){
                if($_FILES['file']['error']==0){
                    $target=MM_UPLOADPATH.$photo;
                    if(move_uploaded_file($_FILES['photo']['tmp_name'],$target)){

                    }else{
                        @unlink($_FILES['photo']['tmp_name']);
                        $error = true;
                        echo '<p class="error">К сожалению, возникла проблема при загрузке фотографии.</p>';
                    }
                }
            }else{
                @unlink($_FILES['photo']['tmp_name']);
                $error = true;
                echo '<p class="error">Ваше изображение должно быть в формате GIF, JPEG, или PNG,объем не более ' . (MM_MAXFILESIZE / 1024) .
                    ' KB и размером ' . MM_MAXIMGWIDTH . 'x' . MM_MAXIMGHEIGHT . ' pixels.</p>';
            }
        }
        $update = $this->DBH->prepare(
            "UPDATE vdn_forum.vdn_profiles
             SET last_name='$last_name',first_name='$first_name',gender='$gender'
                ,date_birth='$date_birth',photo='$photo'
             WHERE id_user='$id_user'");
        return $update->execute();
    }
}
/*$obj= new Profile();
var_dump($obj->viewProfile(1));
var_dump($obj->edirProfile(1));
*/

class CreateForm
{
    /**
     * @param $name
     * @return string
     */
    public function getTPL($name){
        $tpl = "";
        $fileName = 'tpl' . DIRECTORY_SEPARATOR . $name . '.html';
        if (file_exists($fileName)) {
            $tpl = file_get_contents($fileName);
        }
        return $tpl;
    }

    /**
     * @return array
     */
    public  function arrayLabels(){
        $arr_lab=array();
        $arr_lab[]='{{LASTNAME}}';
        $arr_lab[]='{{FIRSTNAME}}';
        $arr_lab[]='{{GENDER}}';
        $arr_lab[]='{{DATE_BIRTH}}';
        $arr_lab[]='{{PHOTO}}';


        return $arr_lab;
    }
}
//$obj= new CreateForm();
//var_dump($obj->arrayLabels());

class ProcessTPL{
      public function processTemplace(array $arr_lab,array $arr_var,$tpl){

        $tpl = str_replace($arr_lab, $arr_var, $tpl);

        return $tpl;
    }
}