<?php

    session_start();
    require_once "Functions.php";
    
    $email = checkData($_POST['email']);
    $password = $_POST['password'];
    
    $user = getUserByEmail($email);
    
    if (!empty($user)) {
        setMessage('danger', "Этот эл. адрес уже занят другим пользователем.");
        redirect('Register');
    }
    
    $_SESSION['id'] = addUser($email, $password);
    
    setMessage('success', "Регистрация успешна");
    redirect('Login');

?>