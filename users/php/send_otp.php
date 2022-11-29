<?php

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once __DIR__.'/database-connection.php';
require_once '../config/config.php';


ini_set('display_errors',1); 
error_reporting(E_ALL);

class send_otp 
{
    private $db;
    private $query;
    private $user_mail;
    private $otp;
    private $new_otp;
    private $header;
    private $message;
    private $config;

    public function send_mail_fun($user_mail, $otp) {
        $this->config = new config();
        $this->user_mail = $user_mail;
        $this->otp = $otp;
        $this->message = '
            <body style=\'background-color:rgb(238,238,238);padding-top:10px;padding-bottom:10px;text-align:center;\'>
                <div style=\'width:50%;margin:0 auto;background-color:rgb(248,248,248);padding:10px\'>
                    <h3>Your OTP for email verification is</h3>
                    <h1>'.$this->otp.'</h1>
                </div>
            </body>
        ';
		$mail = new PHPMailer(true);                              // Passing `true` enables exceptions
		try {
		    //Server settings
		   	$mail->SMTPDebug = 1;                                 // Enable verbose debug output
		    $mail->isSMTP();                                      // Set mailer to use SMTP
		    $mail->Host = 'smtp.gmail.com';                       // Specify main and backup SMTP servers
		    $mail->SMTPAuth = true;                               // Enable SMTP authentication
		    $mail->Username = 'maruti20021208@gmail.com';         // SMTP username
		    $mail->Password = 'gxvobjfbejacjzzb';                 // SMTP password
		    $mail->SMTPSecure = 'ssl';                           
		    $mail->Port = 465;                                    // TCP port to connect to

		    //Recipients
		    $mail->setFrom('maruti20021208@gmail.com');
		    $mail->addAddress($_POST['email']);                   // Add a recipient

		    //Content
		    $mail->isHTML(true);                                  // Set email format to HTML
		    $mail->Subject = 'Email subscription';
		    $mail->Body    = $this->message;

		    $mail->send();
		    //header('Location: index.php');
		    echo $this->message.'OTP Sent Successfully';
		    
		exit();
		} 
		catch (Exception $e) {
			    echo 'Message could not be sent.';
			    echo 'Mailer Error: ' . $mail->ErrorInfo;
		}

	}
	public function __construct()
    {               
        if(isset($_POST['email'])){
            $this->user_mail = $_POST['email'];
        }
        else{
            echo 'Please try Again';
            exit();
        }
        if (!filter_var($this->user_mail, FILTER_VALIDATE_EMAIL)) {
            echo 'Invalid Email';
            exit();
        }
        $this->new_otp = random_int(100000, 999999);
        $this->db = new db();
        $this->db = $this->db->database();
        $this->user_mail = trim($this->user_mail);
        $this->user_mail = htmlspecialchars($this->user_mail,ENT_QUOTES);
        $this->user_mail = mysqli_real_escape_string($this->db,$this->user_mail);
        $this->query = $this->db->prepare('SELECT otp FROM user_data WHERE email=?');
        $this->query->bind_param('s',$this->user_mail);
        $this->query->execute();
        $this->query->store_result();
        if ($this->query->num_rows != 0) {
            $this->query->bind_result($this->otp);
            $this->query->fetch();            

            if($this->otp != 1 || isset($_POST['status'])){
                // if($this->otp !=0){        
                //     $this->send_mail_fun($this->user_mail,$this->otp);
                // }
                //else{
                    $this->query = $this->db->prepare('UPDATE user_data SET otp=? WHERE email=?');
                    $this->query->bind_param('is',$this->new_otp,$this->user_mail);
                    $this->query->execute();
                    if($this->query->affected_rows!=0){
                        $this->send_mail_fun($this->user_mail,$this->new_otp);
                    }
                    else{
                        echo 'Please try Again';
                    }
                //}
            }   
            else{
                if(!isset($_POST['status']) ){
                    echo 'Email is Already Verified';
                }
            }
        }
        else {
           	$this->query = $this->db->prepare('INSERT INTO user_data(email,otp) VALUES(?,?)');
            $this->query->bind_param('ss',$this->user_mail,$this->new_otp);           
            $this->query->execute();
            if($this->query->affected_rows!=0) {
                $this->send_mail_fun($this->user_mail,$this->new_otp);
            }
            else {
                echo 'Please try Again';
            }
        }
        $this->db->close();
     }

    public function __destruct()
    {
        unset($this->db);
        unset($this->query);
        unset($this->user_mail);
        unset($this->otp);
        unset($this->new_otp);
        unset($this->header);
        unset($this->message);
        unset($this->config);
    }
} 
new send_otp();
?>