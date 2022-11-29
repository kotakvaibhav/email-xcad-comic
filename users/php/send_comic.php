<?php
require_once 'PHPMailer/src/Exception.php';
require_once 'PHPMailer/src/PHPMailer.php';
require_once 'PHPMailer/src/SMTP.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once __DIR__.'/database-connection.php';
require_once '../config/config.php';
//require_once __DIR__.'/verify_otp.php';

ini_set('display_errors',1); 
error_reporting(E_ALL);
    function comic_data($user_mail){
        $db = new db();
        $db = $db->database();
        $query = $db->prepare('SELECT otp FROM user_data WHERE email=?');  
        $query->bind_param('s',$user_mail);    
        $user_status = $query->execute();
        if($user_status == 1) {
            $comic = getComicFromXKCD();  
            $title = 'Your New Comic ' . $comic['safe_title'];
            $file         = file_get_contents( $comic['img'] );
            $encoded_file = chunk_split( base64_encode( $file ) );   //Embed image in base64 to send with email
            $attachments[] = array(
                'name'     => $comic['title'] . '.jpg',
                'data'     => $encoded_file,
                'type'     => 'application/pdf',
                'encoding' => 'base64',
            );      
            $Body = '
                <p >Hello Subscriber</p>
                Here is your Comic for the day
                <h3>' . $comic['safe_title'] . "</h3>
                <img src='" . $comic['img'] . "' alt='some comic hehe'/>
                <br />
                To read the comic head to <a target='_blank' href='https://xkcd.com/" . $comic['num'] . "'>Here</a><br />
                To unsubscribe kindly visit : http://testphprandomcomic.epizy.com/unsubscribe.php";
                sendComic( $user_mail, $title, $Body, $attachments );
        }
    }
    
function getComicFromXKCD() { 
    $url = 'https://c.xkcd.com/random/comic/';
    try {
        $head       = get_headers( $url );
        $comic_link = $head[7]; 
        preg_match( '/[0-9]+/', $comic_link, $matches );
        $rand_comic = $matches[0]; 
    } catch (\Throwable$th) {
        print($th->getMessage());
    }

    $url    = 'https://xkcd.com/' . $rand_comic . '/info.0.json'; 
    $result = json_decode( file_get_contents( $url ), true );
    return $result;
}
function sendComic( $to, $subject, $message, $attachments = array() ) {
    $mail = new PHPMailer(true);   print_r($to);                           // Passing `true` enables exceptions
        try {
            //Server settings
            $mail->SMTPDebug = 1;                                 // Enable verbose debug output
            $mail->isSMTP();                                      // Set mailer to use SMTP
            $mail->Host = 'smtp.gmail.com';  // Specify main and backup SMTP servers
            $mail->SMTPAuth = true;                               // Enable SMTP authentication
            $mail->Username = 'maruti20021208@gmail.com';                 // SMTP username
            $mail->Password = 'gxvobjfbejacjzzb';                           // SMTP password
            $mail->SMTPSecure = 'ssl';                           
            $mail->Port = 465;     // TCP port to connect to

            //Recipients
            $mail->setFrom('maruti20021208@gmail.com');
            $mail->addAddress($to);     // Add a recipient
            
            //Content
            $mail->isHTML(true);      // Set email format to HTML
            $mail->Subject = $subject;
            $mail->Body    = $message;

            $mail->send();
            if($mail->send()){
                $user_mail = $to;
                $db = new db();
                $db = $db->database();
                $query = $db->prepare('SELECT count FROM user_data WHERE email=? ');
                $query->bind_param('s',$to);
                $query->execute();
                $query->store_result();
                $query->bind_result($count);
                $query->fetch();
                $count = $count+1;
                $query = $db->prepare('UPDATE user_data SET count=? WHERE email=?');
                $query->bind_param('is',$count,$to);
                $query->execute();
                //$this->comic_data($_POST['email']);
                //header('Location: index.php');
                echo 'Email Verified'.$message;
                return;
            }  
            
            
        exit();
        } 
        catch (Exception $e) {
                echo 'Message could not be sent.';
                echo 'Mailer Error: ' . $mail->ErrorInfo;
        }
}

?>