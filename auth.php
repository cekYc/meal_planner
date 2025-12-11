<?php

// Kullanıcılar için JSON dosyası
define('USERS_FILE', __DIR__ . '/users.json');

// Kullanıcıları yükle
function loadUsers() {
    if (!file_exists(USERS_FILE)) {
        return [];
    }
    $content = file_get_contents(USERS_FILE);
    return json_decode($content, true) ?: [];
}

// Kullanıcıları kaydet
function saveUsers($users) {
    file_put_contents(USERS_FILE, json_encode($users, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
}

// Oturum başlat
function startSession() {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
}

// Kullanıcı kaydı yap
function registerUser($email, $password, $name) {
    $users = loadUsers();
    
    // Email zaten kayıtlı mı?
    foreach ($users as $user) {
        if ($user['email'] === $email) {
            return ['success' => false, 'message' => 'Bu email zaten kayıtlı'];
        }
    }
    
    $newUser = [
        'id' => time(),
        'email' => $email,
        'password' => password_hash($password, PASSWORD_BCRYPT),
        'name' => $name,
        'created_at' => date('Y-m-d H:i:s')
    ];
    
    $users[] = $newUser;
    saveUsers($users);
    
    return ['success' => true, 'message' => 'Kayıt başarılı', 'user_id' => $newUser['id']];
}

// Kullanıcı girişi
function loginUser($email, $password) {
    $users = loadUsers();
    
    foreach ($users as $user) {
        if ($user['email'] === $email && password_verify($password, $user['password'])) {
            return ['success' => true, 'user_id' => $user['id'], 'name' => $user['name']];
        }
    }
    
    return ['success' => false, 'message' => 'Email veya şifre yanlış'];
}

// Kullanıcı bilgisi al
function getUserInfo($user_id) {
    $users = loadUsers();
    
    foreach ($users as $user) {
        if ($user['id'] == $user_id) {
            return $user;
        }
    }
    
    return null;
}

// Kullanıcı çıkış yap
function logoutUser() {
    startSession();
    session_destroy();
}

// Giriş durumunu kontrol et
function isLoggedIn() {
    startSession();
    return isset($_SESSION['user_id']);
}

// Kullanıcı ID'si al
function getCurrentUserId() {
    startSession();
    return $_SESSION['user_id'] ?? null;
}

?>
