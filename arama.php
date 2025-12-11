<?php
require_once 'functions.php';
require_once 'auth.php';

startSession();

// POST ile gelen parametreleri al
$malzemeler = [];
if (isset($_POST['malzemeler'])) {
    $malzemeler = json_decode($_POST['malzemeler'], true);
}

$recipe_name = $_POST['recipe_name'] ?? '';
$author_name = $_POST['author_name'] ?? '';

// Tarifleri ara
$tarifler = advancedSearchRecipes($malzemeler, $recipe_name, $author_name);

// JavaScript için TÜM tarifleri al (dinamik filtreleme için)
$tum_tarifler = loadTarifler();

// Kullanıcı listesini al
$users = loadUsers();
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Arama Sonuçları - Yemek Tarifi Bulucu</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/global.css">
    <link rel="stylesheet" href="css/index.css">
    <link rel="stylesheet" href="css/arama.css">
</head>
<body>
    <div class="container">
        <a href="index.php" class="back-link">← Ana Sayfaya Dön</a>
        
        <header>
            <div>
                <h1>🔍 Arama Sonuçları</h1>
            </div>
            <div class="user-menu">
                <?php if (isLoggedIn()): ?>
                    <a href="profile.php" class="btn btn-secondary">
                        <span>👤</span>
                        <span>Profilim</span>
                    </a>
                <?php endif; ?>
            </div>
        </header>
        
        <div class="search-info">
            <?php if (!empty($recipe_name) || !empty($author_name)): ?>
                <div class="search-criteria">
                    <?php if (!empty($recipe_name)): ?>
                        <div class="criteria-item">
                            <span class="criteria-label">Tarif Adı:</span>
                            <span><?php echo htmlspecialchars($recipe_name); ?></span>
                        </div>
                    <?php endif; ?>
                    <?php if (!empty($author_name)): ?>
                        <div class="criteria-item">
                            <span class="criteria-label">Yazar:</span>
                            <span><?php echo htmlspecialchars($author_name); ?></span>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
            
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">
                <h2 style="margin: 0;">Malzemeler:</h2>
                <div id="sonucSayisi" style="color: var(--primary-color); font-weight: 600;"></div>
            </div>
            <div class="malzeme-listesi" id="malzemeListesi">
                <?php foreach ($malzemeler as $malzeme): ?>
                    <div class="malzeme-tag"><?php echo htmlspecialchars($malzeme); ?></div>
                <?php endforeach; ?>
            </div>
            
            <div style="margin-top: 20px; padding-top: 20px; border-top: 1px solid var(--border-color);">
                <h3 style="color: var(--primary-color); margin-bottom: 15px;">Malzeme Ekle/Çıkar</h3>
                <div class="malzeme-input-group">
                    <input type="text" id="yeniMalzeme" class="malzeme-input" placeholder="Yeni malzeme ekle...">
                    <button type="button" class="btn btn-primary" onclick="malzemeEkle()">➕ Ekle</button>
                </div>
            </div>
        </div>
        
        <div class="results" id="tarifResults">
            <!-- JavaScript tarafından doldurulacak -->
        </div>
    </div>
    
    <script>
        window.tumTarifler = <?php echo json_encode($tum_tarifler); ?>;
        window.initialMalzemeler = <?php echo json_encode($malzemeler); ?>;
        window.recipeName = <?php echo json_encode($recipe_name); ?>;
        window.authorName = <?php echo json_encode($author_name); ?>;
        window.users = <?php echo json_encode($users); ?>;
        window.isLoggedIn = <?php echo isLoggedIn() ? 'true' : 'false'; ?>;
    </script>
    <script src="js/arama.js"></script>
</body>
</html>
