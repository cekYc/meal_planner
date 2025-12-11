<?php
require_once 'functions.php';
require_once 'auth.php';

startSession();

// Giriş yapıp yapmadığını kontrol et
if (!isLoggedIn()) {
    header('Location: login.php');
    exit();
}

$current_user_id = getCurrentUserId();
$current_user = getUserInfo($current_user_id);

$success_message = '';
$error_message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $ad = $_POST['ad'] ?? '';
    $aciklama = $_POST['aciklama'] ?? '';
    $talimatlar = $_POST['talimatlar'] ?? '';
    $sure = (int)($_POST['sure'] ?? 30);
    $porsiyon = (int)($_POST['porsiyon'] ?? 4);
    $resim = $_POST['resim'] ?? '';
    
    // Malzemeleri parse et
    $malzeme_str = $_POST['malzemeler'] ?? '';
    $malzemeler = [];
    
    if (!empty($malzeme_str)) {
        $lines = explode("\n", $malzeme_str);
        foreach ($lines as $line) {
            $line = trim($line);
            if (!empty($line)) {
                // Format: "malzeme adı - miktar" veya sadece "malzeme adı"
                if (strpos($line, '-') !== false) {
                    list($ad_m, $miktar) = explode('-', $line, 2);
                    $malzemeler[] = [
                        'ad' => trim($ad_m),
                        'miktar' => trim($miktar)
                    ];
                } else {
                    $malzemeler[] = [
                        'ad' => $line,
                        'miktar' => ''
                    ];
                }
            }
        }
    }
    
    // Validasyon
    if (empty($ad)) {
        $error_message = 'Tarif adı gerekli';
    } elseif (empty($talimatlar)) {
        $error_message = 'Hazırlanış talimatları gerekli';
    } elseif (empty($malzemeler)) {
        $error_message = 'En az bir malzeme gerekli';
    } else {
        try {
            $tarif_id = addTarif($ad, $aciklama, $talimatlar, $malzemeler, $sure, $porsiyon, $resim, $current_user_id);
            $success_message = 'Tarif başarıyla eklendi!';
            // Formu temizle
            $_POST = [];
        } catch (Exception $e) {
            $error_message = 'Tarif eklenirken hata: ' . $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tarif Ekle - Yemek Tarifi Bulucu</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/global.css">
    <link rel="stylesheet" href="css/form.css">
</head>
<body>
    <div class="container">
        <header>
            <div>
                <h1>➕ Tarif Ekle</h1>
            </div>
            <div class="user-menu">
                <a href="profile.php" style="color: white; text-decoration: none; display: flex; align-items: center; gap: 5px; padding: 8px 15px; background: rgba(255, 255, 255, 0.1); border-radius: 8px;">
                    <span>👤</span>
                    <span><?php echo htmlspecialchars($current_user['name']); ?></span>
                </a>
                <a href="logout.php" class="logout-btn">Çıkış</a>
            </div>
        </header>
        
        <a href="index.php" class="back-link">← Ana Sayfaya Dön</a>
        
        <div class="form-card">
            <?php if (!empty($success_message)): ?>
                <div class="message success"><?php echo htmlspecialchars($success_message); ?></div>
            <?php endif; ?>
            
            <?php if (!empty($error_message)): ?>
                <div class="message error"><?php echo htmlspecialchars($error_message); ?></div>
            <?php endif; ?>
            
            <form method="POST">
                <div class="form-group">
                    <label for="ad">Tarif Adı *</label>
                    <input type="text" id="ad" name="ad" required value="<?php echo htmlspecialchars($_POST['ad'] ?? ''); ?>">
                </div>
                
                <div class="form-group">
                    <label for="aciklama">Kısa Açıklama</label>
                    <input type="text" id="aciklama" name="aciklama" placeholder="Örn: Türk mutfağının klasik kahvaltılığı" value="<?php echo htmlspecialchars($_POST['aciklama'] ?? ''); ?>">
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="sure">Hazırlanma Süresi (dakika) *</label>
                        <input type="number" id="sure" name="sure" min="1" required value="<?php echo $_POST['sure'] ?? 30; ?>">
                    </div>
                    
                    <div class="form-group">
                        <label for="porsiyon">Kişi Sayısı *</label>
                        <input type="number" id="porsiyon" name="porsiyon" min="1" required value="<?php echo $_POST['porsiyon'] ?? 4; ?>">
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="resim">Resim URL</label>
                    <input type="url" id="resim" name="resim" placeholder="https://example.com/image.jpg" value="<?php echo htmlspecialchars($_POST['resim'] ?? ''); ?>">
                </div>
                
                <div class="form-group">
                    <label for="malzemeler">Malzemeler *</label>
                    <textarea id="malzemeler" name="malzemeler" required placeholder="Her satıra bir malzeme yazın&#10;Örn:&#10;Yumurta - 3 adet&#10;Domates - 2 adet&#10;Soğan - 1 adet"><?php echo htmlspecialchars($_POST['malzemeler'] ?? ''); ?></textarea>
                    <div class="form-hint">Her satırda bir malzeme. Miktar için çizgi (–) kullanın: "Malzeme - Miktar"</div>
                </div>
                
                <div class="form-group">
                    <label for="talimatlar">Hazırlanış Talimatları *</label>
                    <textarea id="talimatlar" name="talimatlar" required placeholder="Adım adım tarifi yazın&#10;Örn:&#10;1. Soğanı ince doğrayın&#10;2. Zeytinyağında kavurun&#10;3. ..."><?php echo htmlspecialchars($_POST['talimatlar'] ?? ''); ?></textarea>
                    <div class="form-hint">Adım adım hazırlanış talimatlarını yazın</div>
                </div>
                
                <div class="button-group">
                    <button type="submit" class="btn btn-primary">✅ Tarifi Ekle</button>
                    <a href="index.php" class="btn btn-secondary" style="text-decoration: none; display: flex; align-items: center; justify-content: center;">İptal</a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
