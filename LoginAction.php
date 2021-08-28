<?php

    session_start();
    require_once "Functions.php";
    
    $email = checkData($_POST['email']);
    $password = $_POST['password'];
    
    if (signIn($email, $password)) {
        redirect('Users');
    } else {
        redirect('Login');
    }
    
?>