<?php

class ImageUploader {
    private $pdo = null;
    private $allowed = ['image/jpeg', 'image/png']; // look for MIME-types
    private $path = '/path/to/main-images'; // destination path
    
    public function __construct($pdo){ // hold database connection
        $this->pdo = $pdo;
    }
    
    public function upload($file,$upload_dir){
        $maxsize    = 2097152;
        $acceptable = array(
        'image/jpeg',
        'image/jpg',
        'image/gif',
        'image/png',
        'application/pdf');      
        if(is_uploaded_file($file["tmp_name"]))
        {      
          if($file['size']>=$maxsize || $file['size'] == 0)
          { 
            $erroMessage .= "<li>Image size too large .</li>";
            $errorFlag = true;  
          }
          else if(!in_array($file['type'],$acceptable))
          {
            $erroMessage .= "<li> Invalid file type. Only JPEG,JPG, GIF and PNG types are accepted.!.</li>";  
            $errorFlag = true;
          }       
          if($erroMessage=='')
          { 
            $erroMessage = "Success";       
            //$filename=mysqli_real_escape_string($DatabaseCo->dbLink,$file['name']);
            $path = $filename= $file['name'];      
            $ext = pathinfo($path, PATHINFO_EXTENSION);   
            $filename_new= time().'.'.$ext;  
            $upload_file=copy($file['tmp_name'], "../uploads/".$upload_dir."/".$filename_new); 
            $errorFlag = false;
            return $filename_new;
          } 
          else {
            return false;
          }    
        }
    }
}