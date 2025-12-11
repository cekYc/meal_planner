<?php
require_once 'auth.php';
require_once 'functions.php';
require_once 'user_interactions.php';

startSession();

if (!isLoggedIn()) {
    header('Location: login.php');
    exit();
}

$current_user_id = getCurrentUserId();
$user_info = getUserInfo($current_user_id);

// Kullanıcı tarifleri
$user_recipes = getUserRecipes($current_user_id);

// Favoriler
$favorites = getUserFavorites($current_user_id);
$favorite_recipes = [];
foreach ($favorites as $fav) {
    $recipe = getTarifDetay($fav['recipe_id']);
    if ($recipe) {
        $favorite_recipes[] = $recipe;
    }
}

// Ben de yaptım
$made_recipes = getUserMadeRecipes($current_user_id);
$made_recipes_with_details = [];
foreach ($made_recipes as $made) {
    $recipe = getTarifDetay($made['recipe_id']);
    if ($recipe) {
        $made['recipe'] = $recipe;
        $made_recipes_with_details[] = $made;
    }
}

// Yorumlar
$user_comments = getUserComments($current_user_id);
$comments_with_recipes = [];
foreach ($user_comments as $comment) {
    $recipe = getTarifDetay($comment['recipe_id']);
    if ($recipe) {
        $comment['recipe'] = $recipe;
        $comments_with_recipes[] = $comment;
    }
}
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profilim - Yemek Tarifi Bulucu</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/global.css">
    <link rel="stylesheet" href="css/profile.css">
</head>
<body>
    <div class="container">
        <a href="index.php" class="back-link">← Ana Sayfaya Dön</a>
        
        <div class="profile-header">
            <div class="profile-avatar">👤</div>
            <h1 class="profile-name"><?php echo htmlspecialchars($user_info['name']); ?></h1>
            <p class="profile-email"><?php echo htmlspecialchars($user_info['email']); ?></p>
        </div>
        
        <div class="tabs">
            <button class="tab active" onclick="showTab('my-recipes')">📝 Tariflerim (<?php echo count($user_recipes); ?>)</button>
            <button class="tab" onclick="showTab('favorites')">⭐ Favorilerim (<?php echo count($favorite_recipes); ?>)</button>
            <button class="tab" onclick="showTab('made')">🎉 Yaptıklarım (<?php echo count($made_recipes_with_details); ?>)</button>
            <button class="tab" onclick="showTab('comments')">💬 Yorumlarım (<?php echo count($comments_with_recipes); ?>)</button>
        </div>
        
        <!-- Tariflerim -->
        <div id="my-recipes" class="tab-content active">
            <?php if (empty($user_recipes)): ?>
                <div class="empty-state">
                    <div class="empty-state-icon">📝</div>
                    <p class="empty-state-text">Henüz tarif eklemediniz</p>
                    <a href="add_recipe.php" class="empty-state-link">İlk tarifini ekle →</a>
                </div>
            <?php else: ?>
                <div class="recipes-grid">
                    <?php foreach ($user_recipes as $recipe): ?>
                        <a href="tarif.php?id=<?php echo $recipe['id']; ?>" class="recipe-card">
                            <div class="recipe-img">
                                <?php if (!empty($recipe['resim'])): ?>
                                    <img src="<?php echo htmlspecialchars($recipe['resim']); ?>" alt="<?php echo htmlspecialchars($recipe['ad']); ?>" style="width: 100%; height: 100%; object-fit: cover;">
                                <?php else: ?>
                                    🍳
                                <?php endif; ?>
                            </div>
                            <div class="recipe-content">
                                <h3 class="recipe-title"><?php echo htmlspecialchars($recipe['ad']); ?></h3>
                                <p class="recipe-desc"><?php echo htmlspecialchars(substr($recipe['aciklama'], 0, 100)); ?>...</p>
                                <div class="recipe-meta">
                                    <span>⏱️ <?php echo $recipe['sure']; ?> dk</span>
                                    <span>👥 <?php echo $recipe['porsiyon']; ?> kişi</span>
                                </div>
                            </div>
                        </a>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
        
        <!-- Favorilerim -->
        <div id="favorites" class="tab-content">
            <?php if (empty($favorite_recipes)): ?>
                <div class="empty-state">
                    <div class="empty-state-icon">⭐</div>
                    <p class="empty-state-text">Favori tarifiniz yok</p>
                    <a href="index.php" class="empty-state-link">Tariflere göz at →</a>
                </div>
            <?php else: ?>
                <div class="recipes-grid">
                    <?php foreach ($favorite_recipes as $recipe): ?>
                        <a href="tarif.php?id=<?php echo $recipe['id']; ?>" class="recipe-card">
                            <div class="recipe-img">
                                <?php if (!empty($recipe['resim'])): ?>
                                    <img src="<?php echo htmlspecialchars($recipe['resim']); ?>" alt="<?php echo htmlspecialchars($recipe['ad']); ?>" style="width: 100%; height: 100%; object-fit: cover;">
                                <?php else: ?>
                                    🍳
                                <?php endif; ?>
                            </div>
                            <div class="recipe-content">
                                <h3 class="recipe-title"><?php echo htmlspecialchars($recipe['ad']); ?></h3>
                                <p class="recipe-desc"><?php echo htmlspecialchars(substr($recipe['aciklama'], 0, 100)); ?>...</p>
                                <div class="recipe-meta">
                                    <span>⏱️ <?php echo $recipe['sure']; ?> dk</span>
                                    <span>👥 <?php echo $recipe['porsiyon']; ?> kişi</span>
                                </div>
                            </div>
                        </a>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
        
        <!-- Yaptıklarım -->
        <div id="made" class="tab-content">
            <?php if (empty($made_recipes_with_details)): ?>
                <div class="empty-state">
                    <div class="empty-state-icon">🎉</div>
                    <p class="empty-state-text">Henüz hiç tarif yapmadınız</p>
                    <a href="index.php" class="empty-state-link">Tariflere göz at →</a>
                </div>
            <?php else: ?>
                <div class="recipes-grid">
                    <?php foreach ($made_recipes_with_details as $made): ?>
                        <a href="tarif.php?id=<?php echo $made['recipe']['id']; ?>" class="recipe-card">
                            <div class="recipe-img">
                                <?php if (!empty($made['photo_path'])): ?>
                                    <img src="<?php echo htmlspecialchars($made['photo_path']); ?>" alt="Benim Fotoğrafım" style="width: 100%; height: 100%; object-fit: cover;">
                                <?php elseif (!empty($made['recipe']['resim'])): ?>
                                    <img src="<?php echo htmlspecialchars($made['recipe']['resim']); ?>" alt="<?php echo htmlspecialchars($made['recipe']['ad']); ?>" style="width: 100%; height: 100%; object-fit: cover;">
                                <?php else: ?>
                                    🍳
                                <?php endif; ?>
                                <div style="position: absolute; top: 10px; right: 10px; background: rgba(255,255,255,0.9); padding: 5px 10px; border-radius: 20px; font-size: 0.8em; font-weight: 600; color: var(--success-color);">
                                    ✓ Yaptım
                                </div>
                            </div>
                            <div class="recipe-content">
                                <h3 class="recipe-title"><?php echo htmlspecialchars($made['recipe']['ad']); ?></h3>
                                <?php if (!empty($made['note'])): ?>
                                    <p class="recipe-desc" style="font-style: italic; color: var(--text-secondary);">"<?php echo htmlspecialchars(substr($made['note'], 0, 80)); ?>..."</p>
                                <?php else: ?>
                                    <p class="recipe-desc"><?php echo htmlspecialchars(substr($made['recipe']['aciklama'], 0, 100)); ?>...</p>
                                <?php endif; ?>
                                <div class="recipe-meta">
                                    <span>⏱️ <?php echo $made['recipe']['sure']; ?> dk</span>
                                    <span>👥 <?php echo $made['recipe']['porsiyon']; ?> kişi</span>
                                    <span>📅 <?php echo date('d.m.Y', strtotime($made['created_at'])); ?></span>
                                </div>
                            </div>
                        </a>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
        
        <!-- Yorumlarım -->
        <div id="comments" class="tab-content">
            <?php if (empty($comments_with_recipes)): ?>
                <div class="empty-state">
                    <div class="empty-state-icon">💬</div>
                    <p class="empty-state-text">Henüz yorum yapmadınız</p>
                    <a href="index.php" class="empty-state-link">Tariflere göz at →</a>
                </div>
            <?php else: ?>
                <div class="recipes-grid">
                    <?php foreach ($comments_with_recipes as $comment): ?>
                        <a href="tarif.php?id=<?php echo $comment['recipe']['id']; ?>" class="recipe-card">
                            <div class="recipe-img">
                                <?php if (!empty($comment['recipe']['resim'])): ?>
                                    <img src="<?php echo htmlspecialchars($comment['recipe']['resim']); ?>" alt="<?php echo htmlspecialchars($comment['recipe']['ad']); ?>" style="width: 100%; height: 100%; object-fit: cover;">
                                <?php else: ?>
                                    🍳
                                <?php endif; ?>
                                <div style="position: absolute; bottom: 10px; left: 10px; right: 10px; background: rgba(0,0,0,0.7); padding: 8px; border-radius: 8px; font-size: 0.85em; color: white;">
                                    💬 <?php echo date('d.m.Y', strtotime($comment['created_at'])); ?>
                                </div>
                            </div>
                            <div class="recipe-content">
                                <h3 class="recipe-title"><?php echo htmlspecialchars($comment['recipe']['ad']); ?></h3>
                                <p class="recipe-desc" style="font-style: italic; color: var(--text-secondary);">
                                    "<?php echo htmlspecialchars(substr($comment['comment'], 0, 100)); ?><?php echo strlen($comment['comment']) > 100 ? '...' : ''; ?>"
                                </p>
                                <div class="recipe-meta">
                                    <span>⏱️ <?php echo $comment['recipe']['sure']; ?> dk</span>
                                    <span>👥 <?php echo $comment['recipe']['porsiyon']; ?> kişi</span>
                                </div>
                            </div>
                        </a>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
    
    <script src="js/profile.js"></script>
</body>
</html>
