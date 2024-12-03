<?php
include_once '../../Controller/userC.php';
include_once '../../config.php';
 $userC = new userC();
 if(isset($_GET['id'])){
     $userC->deleteUser($_GET['id']);
 
    header('Location:Dashboard.php');
    }

 ?>