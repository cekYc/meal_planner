<?php
require_once 'auth.php';
require_once 'functions.php';
require_once 'user_interactions.php';

startSession();

// Sıralama türünü al
$sort_by = $_GET['sort_by'] ?? 'trending';

// Tarifleri sıralaı
$tarifler = getRecipesSorted($sort_by, 12);
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Yemek Tarifi Bulucu</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/global.css">
    <link rel="stylesheet" href="css/index.css">
</head>
<body>
    <div class="container">
        <header>
            <div>
                <h1>🍳 Yemek Tarifi Bulucu</h1>
                <p class="subtitle">Elindeki malzemelerle ne pişirebileceğini keşfet!</p>
            </div>
            <div class="user-menu">
                <?php if (isLoggedIn()): 
                    $user_info = getUserInfo(getCurrentUserId());
                ?>
                    <a href="profile.php" class="btn btn-secondary">
                        <div class="user-avatar"><?php echo strtoupper(substr($user_info['name'], 0, 1)); ?></div>
                        <span><?php echo htmlspecialchars($user_info['name']); ?></span>
                    </a>
                    <a href="add_recipe.php" class="btn btn-success">+ Tarif Ekle</a>
                    <a href="logout.php" class="btn btn-outline">Çıkış</a>
                <?php else: ?>
                    <a href="login.php" class="btn btn-primary">Giriş Yap</a>
                <?php endif; ?>
            </div>
        </header>
        
        <div class="search-box">
            <form method="POST" action="arama.php" id="searchForm">
                <div class="search-filters">
                    <div>
                        <label class="filter-label" for="recipeName">📖 Tarif Adı</label>
                        <input type="text" id="recipeName" name="recipe_name" class="filter-input" placeholder="Örn: Mercimek çorbası">
                    </div>
                    <div>
                        <label class="filter-label" for="authorName">👨‍🍳 Yazar</label>
                        <input type="text" id="authorName" name="author_name" class="filter-input" placeholder="Örn: Ahmet">
                    </div>
                </div>
                
                <div style="margin-bottom: 10px;">
                    <label class="filter-label">🥘 Malzemeler</label>
                </div>
                <div class="malzeme-input-group">
                    <input type="text" id="malzemeInput" class="malzeme-input" placeholder="Malzeme ekle (örn: domates, soğan, tavuk...)">
                    <button type="button" class="btn btn-primary" onclick="addMalzeme()">➕ Ekle</button>
                </div>
                
                <div class="malzeme-listesi" id="malzemeListesi">
                    <!-- Malzemeler buraya eklenecek -->
                </div>
                
                <input type="hidden" name="malzemeler" id="malzemelerHidden">
                <button type="submit" class="btn btn-success" style="width: 100%;">🔍 Tarif Ara</button>
            </form>
        </div>
        
        <div class="results" id="results">
            <div class="no-results">
                <div class="no-results-icon">🔍</div>
                <h2>Arama Yapın</h2>
                <p>Tarif adı, yazar veya malzemelerle arama yaparak size uygun tarifleri bulabilirsiniz.</p>
            </div>
        </div>
        
        <!-- Trending Tarifleri -->
        <section style="margin-top: 40px;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                <h2 style="margin: 0; color: var(--primary-color);">📌 Popüler Tarifler</h2>
                <div class="sort-buttons" style="display: flex; gap: 10px; flex-wrap: wrap;">
                    <a href="?sort_by=trending" class="btn btn-secondary" style="padding: 8px 12px; font-size: 0.9em; <?php echo $sort_by === 'trending' ? 'background: var(--primary-color); color: white;' : ''; ?>">
                        🔥 Trending
                    </a>
                    <a href="?sort_by=popular" class="btn btn-secondary" style="padding: 8px 12px; font-size: 0.9em; <?php echo $sort_by === 'popular' ? 'background: var(--primary-color); color: white;' : ''; ?>">
                        ❤️ Beğenilen
                    </a>
                    <a href="?sort_by=favorite" class="btn btn-secondary" style="padding: 8px 12px; font-size: 0.9em; <?php echo $sort_by === 'favorite' ? 'background: var(--primary-color); color: white;' : ''; ?>">
                        ⭐ Kaydedilenler
                    </a>
                    <a href="?sort_by=made" class="btn btn-secondary" style="padding: 8px 12px; font-size: 0.9em; <?php echo $sort_by === 'made' ? 'background: var(--primary-color); color: white;' : ''; ?>">
                        👨‍🍳 Yapılanlar
                    </a>
                    <a href="?sort_by=rated" class="btn btn-secondary" style="padding: 8px 12px; font-size: 0.9em; <?php echo $sort_by === 'rated' ? 'background: var(--primary-color); color: white;' : ''; ?>">
                        🌟 En İyi Yorumlanan
                    </a>
                </div>
            </div>
            
            <div class="tarif-cards-grid" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 20px;">
                <?php foreach ($tarifler as $tarif): ?>
                    <a href="tarif.php?id=<?php echo $tarif['id']; ?>" class="tarif-card">
                        <div class="tarif-img">
                            <?php if (!empty($tarif['resim'])): ?>
                                <img src="<?php echo htmlspecialchars($tarif['resim']); ?>" alt="<?php echo htmlspecialchars($tarif['ad']); ?>" style="width: 100%; height: 100%; object-fit: cover;">
                            <?php else: ?>
                                🍽️
                            <?php endif; ?>
                        </div>
                        <div class="tarif-content">
                            <h3 class="tarif-title"><?php echo htmlspecialchars($tarif['ad']); ?></h3>
                            <p class="tarif-desc"><?php echo htmlspecialchars(substr($tarif['aciklama'], 0, 100)); ?></p>
                            <div class="tarif-meta">
                                <span>⏱️ <?php echo $tarif['sure']; ?> dk</span>
                                <span>👥 <?php echo $tarif['porsiyon']; ?> kişilik</span>
                            </div>
                            <div style="display: flex; gap: 15px; margin-top: 10px; font-size: 0.85em; color: #666;">
                                <span title="Beğeni">❤️ <?php echo $tarif['like_count'] ?? 0; ?></span>
                                <span title="Kaydedilme">⭐ <?php echo $tarif['favorite_count'] ?? 0; ?></span>
                                <span title="Yapıldı">👨‍🍳 <?php echo $tarif['made_count'] ?? 0; ?></span>
                                <span title="Rating">🌟 <?php echo number_format($tarif['avg_rating'] ?? 0, 1); ?></span>
                            </div>
                        </div>
                    </a>
                <?php endforeach; ?>
            </div>
        </section>
    </div>
    
    <script src="js/index.js"></script>
</body>
</html>
