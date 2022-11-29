<h1>hello</h1>
<?php 
ini_set('display_errors',1); 
error_reporting(E_ALL);


require_once __DIR__.'/database-connection.php';
require_once __DIR__.'/send_comic.php';
new mycronjob();
class mycronjob{ 
	private $db;
    private $query;
    private $user_mail;
    private $otp;
    private $header;
    private $message;
    private $config;

    public function __construct()
    {    
    	$this->db = new db();
        $this->db = $this->db->database();
        $status = 1;                           
        $this->query = $this->db->prepare('SELECT email FROM user_data WHERE otp=? ');
        $this->query->bind_param('i',$status);
        $this->query->execute();    
        $result = $this->query->get_result(); 
        $all_email =array();
        while ($row = $result->fetch_assoc()) { 
            $all_email[] = $row['email'];            
        }
       //print_r(count($all_email));
        // $new = array(1,2,3,4);
        // for($i=0;$i<count($all_email);$i++)
        // {
        //     $val = $all_email[$i];
        //     comic_data($val); //may you was intended to pass $val here?
        //     //echo $val;
        // }


        foreach($all_email as $email){ 
            $useremail = trim($email);
            $useremail = htmlspecialchars($email,ENT_QUOTES);
            $useremail = mysqli_real_escape_string($this->db,$email);
           
         comic_data($email);
        }
        // $user_mail = 'test2@gmail.com';
        // $new_otp = 123456;

        // 	$this->query = $this->db->prepare('INSERT INTO user_data(email,otp) VALUES(?,?)');
        //     $this->query->bind_param('si',$user_mail,$new_otp);           
        //     $this->query->execute();
        
 	}       
}

