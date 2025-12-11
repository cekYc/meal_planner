<?php
require_once 'auth.php';
require_once 'user_interactions.php';

startSession();

if (!isLoggedIn()) {
    header('Location: login.php');
    exit();
}

$user_id = getCurrentUserId();
$recipe_id = (int)($_POST['recipe_id'] ?? $_GET['recipe_id'] ?? 0);

if ($recipe_id == 0) {
    header('Location: index.php');
    exit();
}

$success = false;
$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $note = trim($_POST['note'] ?? '');
    $photo_path = null;
    
    // Fotoğraf yükleme
    if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
        $allowed_types = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'];
        $file_type = $_FILES['photo']['type'];
        
        if (in_array($file_type, $allowed_types)) {
            $extension = pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION);
            $filename = 'made_' . $user_id . '_' . $recipe_id . '_' . time() . '.' . $extension;
            $upload_path = __DIR__ . '/uploads/' . $filename;
            
            if (move_uploaded_file($_FILES['photo']['tmp_name'], $upload_path)) {
                $photo_path = 'uploads/' . $filename;
            } else {
                $message = 'Fotoğraf yüklenirken hata oluştu';
            }
        } else {
            $message = 'Sadece JPG, PNG ve GIF formatları desteklenir';
        }
    }
    
    if (empty($message)) {
        addToMadeList($user_id, $recipe_id, $photo_path, $note);
        $success = true;
        $message = 'Başarıyla eklendi!';
    }
}

require_once 'functions.php';
$tarif = getTarifDetay($recipe_id);

if (!$tarif) {
    header('Location: index.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ben de Yaptım - <?php echo htmlspecialchars($tarif['ad']); ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/global.css">
    <link rel="stylesheet" href="css/form.css">
</head>
<body>
    <div class="container">
        <a href="tarif.php?id=<?php echo $recipe_id; ?>" class="back-link">← Tarife Dön</a>
        
        <div class="card">
            <h1>🎉 Ben de Yaptım!</h1>
            <p class="recipe-name"><?php echo htmlspecialchars($tarif['ad']); ?></p>
            
            <?php if ($success): ?>
                <div class="message success">
                    <?php echo htmlspecialchars($message); ?>
                    <br><br>
                    <a href="tarif.php?id=<?php echo $recipe_id; ?>">Tarife geri dön</a>
                </div>
            <?php else: ?>
                <?php if (!empty($message)): ?>
                    <div class="message error"><?php echo htmlspecialchars($message); ?></div>
                <?php endif; ?>
                
                <form method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="recipe_id" value="<?php echo $recipe_id; ?>">
                    
                    <div class="form-group">
                        <label for="photo">Fotoğraf (İsteğe bağlı)</label>
                        <input type="file" id="photo" name="photo" accept="image/*">
                        <p class="form-hint">JPG, PNG veya GIF formatında fotoğraf yükleyebilirsiniz</p>
                    </div>
                    
                    <div class="form-group">
                        <label for="note">Notunuz (İsteğe bağlı)</label>
                        <textarea id="note" name="note" placeholder="Tarifle ilgili deneyimlerinizi paylaşın..."><?php echo isset($_POST['note']) ? htmlspecialchars($_POST['note']) : ''; ?></textarea>
                    </div>
                    
                    <button type="submit" class="btn btn-primary">Paylaş</button>
                </form>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
