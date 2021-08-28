<?php
    session_start();
    require_once "Functions.php";

    if (isset($_POST['edit-user'])) {
        $name = $_POST['name'];
        $job = $_POST['job'];
        $phone = $_POST['phone'];
        $address = $_POST['address'];
        $currentUser = $_SESSION['change_user_id'];
        changeUserInfo($name, $job, $phone, $address, $currentUser);

        setMessage('success', 'Профиль успешно изменен');
        redirect('Profile');
    }
?>