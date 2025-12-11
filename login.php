<?php
require_once 'auth.php';

startSession();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['form_type'])) {
        if ($_POST['form_type'] === 'login') {
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';
            
            $result = loginUser($email, $password);
            
            if ($result['success']) {
                $_SESSION['user_id'] = $result['user_id'];
                $_SESSION['user_name'] = $result['name'];
                header('Location: index.php');
                exit();
            } else {
                $login_error = $result['message'];
            }
        } elseif ($_POST['form_type'] === 'register') {
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';
            $confirm_password = $_POST['confirm_password'] ?? '';
            $name = $_POST['name'] ?? '';
            
            if ($password !== $confirm_password) {
                $register_error = 'Şifreler eşleşmiyor';
            } elseif (strlen($password) < 6) {
                $register_error = 'Şifre en az 6 karakter olmalı';
            } else {
                $result = registerUser($email, $password, $name);
                
                if ($result['success']) {
                    $_SESSION['user_id'] = $result['user_id'];
                    $_SESSION['user_name'] = $name;
                    header('Location: index.php');
                    exit();
                } else {
                    $register_error = $result['message'];
                }
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Giriş Yap / Kayıt Ol - Yemek Tarifi Bulucu</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/global.css">
    <link rel="stylesheet" href="css/auth.css">
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🍳 Yemek Tarifi Bulucu</h1>
            <p style="font-size: 1.1em; opacity: 0.95;">Giriş Yap veya Kayıt Ol</p>
        </div>
        
        <div class="forms-container">
            <!-- Giriş Formu -->
            <div class="form-card">
                <h2>🔐 Giriş Yap</h2>
                <?php if (isset($login_error)): ?>
                    <div class="error"><?php echo htmlspecialchars($login_error); ?></div>
                <?php endif; ?>
                
                <form method="POST">
                    <input type="hidden" name="form_type" value="login">
                    
                    <div class="form-group">
                        <label for="login_email">Email</label>
                        <input type="email" id="login_email" name="email" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="login_password">Şifre</label>
                        <input type="password" id="login_password" name="password" required>
                    </div>
                    
                    <button type="submit" class="btn">Giriş Yap</button>
                </form>
            </div>
            
            <!-- Kayıt Formu -->
            <div class="form-card">
                <h2>✨ Kayıt Ol</h2>
                <?php if (isset($register_error)): ?>
                    <div class="error"><?php echo htmlspecialchars($register_error); ?></div>
                <?php endif; ?>
                
                <form method="POST">
                    <input type="hidden" name="form_type" value="register">
                    
                    <div class="form-group">
                        <label for="register_name">Ad Soyad</label>
                        <input type="text" id="register_name" name="name" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="register_email">Email</label>
                        <input type="email" id="register_email" name="email" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="register_password">Şifre</label>
                        <input type="password" id="register_password" name="password" required minlength="6">
                    </div>
                    
                    <div class="form-group">
                        <label for="register_confirm">Şifreyi Onayla</label>
                        <input type="password" id="register_confirm" name="confirm_password" required minlength="6">
                    </div>
                    
                    <button type="submit" class="btn">Kayıt Ol</button>
                </form>
            </div>
        </div>
        
        <div style="text-align: center; margin-top: 40px;">
            <a href="index.php" class="back-link">← Ana Sayfaya Dön</a>
        </div>
    </div>
</body>
</html>
