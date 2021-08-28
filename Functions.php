<?php

// Подключаемся к БД
function connectDb() {
    return new PDO('mysql:host=localhost;dbname=php-course', 'root', '');
}

// Добавляем юзера
function addUser($email, $password) {
    $db = connectDb();

    $sql = "INSERT INTO users (email, password) VALUES (:email, :password)";
    $stmt = $db->prepare($sql);
    $stmt->execute([
        'email' => $email,
        'password' => encryptPass($password)
    ]);
    
    $user_id = "SELECT id FROM users WHERE email=:email";
    $stmt = $db->prepare($user_id);
    $stmt->execute([
        'email' => $email
    ]);
    
    return $stmt->fetch(PDO::FETCH_ASSOC)['id'];
}

// Ищем юзера по почте
function getUserByEmail($email) {
    $db = connectDb();

    $sql = "SELECT * FROM users WHERE email=:email";
    $stmt = $db->prepare($sql);
    $stmt->execute([
        "email" => $email
    ]);

    return $stmt->fetch(PDO::FETCH_ASSOC);
}

// Получаем текущего юзера
function getCurrentUser($id) {
    $db = connectDb();

    $sql = "SELECT * FROM users WHERE id=:id";
    $stmt = $db->prepare($sql);
    $stmt->execute([
        'id' => $id
    ]);

    return $stmt->fetch(PDO::FETCH_ASSOC);
}

//  Получаем всех юзеров
function getUsers() {
    $db = connectDb();

    $sql = "SELECT * FROM users";
    $stmt = $db->prepare($sql);
    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Редактируем профиль
function changeUserInfo($name, $job, $phone, $address, $id) {
    $db = connectDb();

    $sql = "UPDATE `users` 
            SET name = :name, job = :job, phone = :phone, address = :address 
            WHERE `users`.`id` = :id";
    $stmt = $db->prepare($sql);

    $userInfo = [
        ':name' => $name,
        ':job' => $job,
        ':phone' => $phone,
        ':address' => $address,
        ':id' => $id
    ];

    return $stmt->execute($userInfo);
}

// Меняем статус юзера
function changeUserStatus($status, $id) {
    $db = connectDb();

    $sql= "UPDATE `users` SET status = :status WHERE `users`.`id` = :id";
    $stmt = $db->prepare($sql);

    $userStatus = [
        ':status' => $status,
        ':id' => $id
    ];

    return $stmt->execute($userStatus);
}

// Устанавлиаем фото профиля
function setPhoto($img, $id) {
    $imgs = explode('.', $img['name']);
    $imgName = 'img'.uniqid().'.'.$imgs[1];
    move_uploaded_file($img['tmp_name'], 'img/photo/'.$imgName);

    $db = connectDb();
    $sql = "UPDATE `users` SET photo = :img WHERE `users`.`id` = :id";
    $stmt = $db->prepare($sql);

    $imgsArr = [
        ':img' => $imgName,
        ':id' => $id
    ];

    return $stmt->execute($imgsArr);
}

// Задаем ссылки на соцсети
function setSocialLinks($vk, $tg, $inst, $id) {
    $db = connectDb();

    $sql = "UPDATE `users` SET vk = :vk, tg = :tg, inst = :inst WHERE `users`.`id` = :id";
    $stmt = $db->prepare($sql);

    $info = [
        ':vk' => $vk,
        ':tg' => $tg,
        ':inst' => $inst,
        ':id' => $id
    ];

    return $stmt->execute($info);
}

// Изменение данных авторизации
function changeSignInData($email, $pass, $id) {
    $db = connectDb();

    $sql = "UPDATE `users` SET email = :email, password = :pass WHERE `users`.`id` = :id";
    $stmt = $db->prepare($sql);

    $signInData = [
        ':email' => $email,
        ':pass' => encryptPass($pass),
        ':id' => $id
    ];

    return $stmt->execute($signInData);
}

//  Получаем все статусы юзера
function getUserStatus() {
    $db = connectDb();

    $sql = "SELECT status_key, status_val FROM user_status";
    $stmt = $db->prepare($sql);
    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Ищем фото юзера
function getUserPhoto($id) {
    $db = connectDb();

    $sql = "SELECT photo FROM users WHERE `users`.`id` = :id";
    $stmt = $db->prepare($sql);

    $userPhoto = [
        ':id' => $id
    ];

    $stmt->execute($userPhoto);
    $photo = $stmt->fetch(PDO::FETCH_ASSOC);

    return $photo['photo'];
}

// Удаляем файл
function removeFile($file) {
    unlink($file);
}

// Удаляем юзера
function removeUser($id) {
    $db = connectDb();

    $sql = "DELETE FROM users WHERE `users`.`id` = :id";
    $stmt = $db->prepare($sql);

    $user = [
        ':id' => $id
    ];

    return $stmt->execute($user);
}

// Авторизация
function signIn($email, $password) {
    $user = getUserByEmail($email);
    
    if (empty($user)) {
        setMessage('danger', "Пользователь с таким эл. адресом не зарегистрирован");
        return false;
    }
    
    if (!password_verify($password, $user['password'])) {
        setMessage('danger', "Неверный пароль");
        return false;
    }
    
    if (!empty($_POST['is_remember']) || $_POST['is_remember'] == 1) {
        setcookie("id", $user['id'], time() + 604800);
    } else {
        setcookie("id", '', time() - 604800);
    }
    
    $_SESSION['id'] = $user['id'];
    $_SESSION['is_logged_in'] = 1;
    
    if ($user['role'] == 777) {
        $_SESSION['is_admin'] = 1;
    } else {
        $_SESSION['is_admin'] = 0;
    }
    
    return true;
}

// Метод проверки авторизации
function notSignedIn() {
    return !isset($_SESSION['is_logged_in']) && $_SESSION['is_logged_in'] != 1;
}

// Выводим сообщения
function showMessage($status) {
    if (isset($_SESSION['status'])) {
        echo "<div class='alert alert-{$status} text-dark' role='alert'>{$_SESSION['status_mess']}</div>";
        unset($_SESSION['status']);
        unset($_SESSION['status_mess']);
    }
}

// Задаем сообщения
function setMessage($status, $mess) {
    $_SESSION['status'] = $status;
    $_SESSION['status_mess'] = $mess;
}

// Преобразуем спец. символы и удаляем пробелы
function checkData($data) {
    return trim(htmlspecialchars($data));
}

// Шифруем пасс
function encryptPass($data) {
    return password_hash($data, PASSWORD_DEFAULT);
}

// Редиректим
function redirect($page) {
    header('Location: '.$page.'.php');
    exit();
}

// Проверяем юзер админ или нет
function checkAdmin() {
    return isset($_SESSION['is_admin']) && $_SESSION['is_admin'] == 1;
}

// Проверяем свой ли профиль редачит юзер
function checkCurrentUser($id, $changeId) {
    return $id == $changeId;
}