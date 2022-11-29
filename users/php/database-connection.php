<?php

class db{
		private $db;
		public function database(){
			$this->db = new mysqli('sql311.byetcluster.com','epiz_32783317','NgqIcOVwfDGizG','epiz_32783317_email_demo');
			if(!$this->db->connect_error)
			{
				return $this->db;
			}
            else{
                die('Database Connection Error');
            }
		}
    }
    
?>