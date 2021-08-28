<?php
    session_start();
    require_once "Functions.php";

    if (isset($_POST['btn-status-user'])) {
        $status = $_POST['status'];
        $id = $_SESSION['change_user_id'];

        changeUserStatus($status, $id);
        setMessage('success', 'Профиль успешно обновлён');
        redirect('Profile');
    }
?>