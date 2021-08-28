<?php
    session_start();
    require_once "Functions.php";

    if (isset($_POST['btn-media-user'])) {
        $photo = $_FILES['photo'];
        $id = $_SESSION['change_user_id'];
        setPhoto($photo, $id);

        setMessage('success', 'Профиль успешно обновлён');
        redirect('Profile');
    }
?>