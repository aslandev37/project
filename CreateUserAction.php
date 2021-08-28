<?php
    session_start();
    require_once "Functions.php";

    if (isset($_POST['btn-create-user'])) {
        $email = checkData($_POST['email']);
        $pass = $_POST['password'];
        $user = getUserByEmail($email);

        if (!empty($user)) {
            setMessage('danger', "Этот email уже используется");
            redirect('CreateUser');
        }

        if (empty($email)){
            setMessage('danger', "Поле эл. адреса не может быть пустыми");
            redirect('CreateUser');
        } else {
            $id = addUser($email, $pass);
        }

        $name = $_POST['name'];
        $job = $_POST['job'];
        $phone = $_POST['phone'];
        $address = $_POST['address'];

        changeUserInfo($name, $job, $phone, $address, $id);

        $status = $_POST['status'];
        changeUserStatus($status, $id);

        $vk = $_POST['vk'];
        $tg = $_POST['tg'];
        $inst = $_POST['inst'];

        setSocialLinks($vk, $tg, $inst, $id);

        $photo = $_FILES['photo'];
        if (!empty($photo)) {
            setPhoto($photo, $id);
        }

        setMessage('success', "Пользователь добавлен.");
        redirect('Users');
    }
?>