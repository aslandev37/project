<?php
    session_start();
    require_once "Functions.php";

    if (isset($_POST['btn-security-user'])) {
        $email = $_POST['email'];
        $pass = $_POST['password'];
        $id = $_SESSION['change_user_id'];
        $user = getUserByEmail($email);

        if ($user) {
            if ($user['id'] == $id) {
                changeSignInData($email, $pass, $id);
                setMessage('success', 'Профиль успешно обновлён');
                redirect('Profile');
            }
            setMessage('danger', 'Данный эл. адрес занят.');
            redirect('Security');
        }

        changeSignInData($email, $pass, $id);
        setMessage('success', 'Профиль успешно обновлён');
        redirect('Profile');
    }
?>