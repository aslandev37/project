<?php
    session_start();
    require_once "Functions.php";

    if (notSignedIn()) {
        redirect('Login');
    }

    $userId = $_SESSION['id'];
    $changeUserId = $_GET['id'];
    if (!checkAdmin()) {
        if (!checkCurrentUser($userId, $changeUserId)) {
            setMessage('danger', 'Вы можете изменить только свой профиль');
            redirect('Users');
        }
    }

    $userPhoto = getUserPhoto($changeUserId);
    if ($userPhoto) {
        removeFile("photo/".$userPhoto);
    };

    removeUser($changeUserId);

    if ($userId == $changeUserId) {
        unset($_SESSION['id']);
        session_destroy();
        redirect('Register');
    }

    setMessage('success', 'Пользоваетль удален');
    redirect('Profile');
?>