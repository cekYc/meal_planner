<?php
require_once 'functions.php';
require_once 'auth.php';
require_once 'user_interactions.php';

startSession();

// Tarif ID'sini al
$tarif_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Tarif detaylarını getir
$tarif = getTarifDetay($tarif_id);

if (!$tarif) {
    header('Location: index.php');
    exit();
}

$is_logged_in = isLoggedIn();
$current_user_id = $is_logged_in ? getCurrentUserId() : null;

// Kullanıcı bilgisi
$author_name = 'Anonim';
if (isset($tarif['user_id']) && $tarif['user_id']) {
    $author_info = getUserInfo($tarif['user_id']);
    if ($author_info) {
        $author_name = htmlspecialchars($author_info['name']);
    }
}

// İstatistikler
$like_count = getRecipeLikeCount($tarif_id);
$is_liked = $is_logged_in ? isLiked($current_user_id, $tarif_id) : false;
$is_favorite = $is_logged_in ? isFavorite($current_user_id, $tarif_id) : false;
$avg_rating = getRecipeAverageRating($tarif_id);
$rating_count = getRecipeRatingCount($tarif_id);
$user_rating = $is_logged_in ? getUserRating($current_user_id, $tarif_id) : 0;
$made_count = getRecipeMadeCount($tarif_id);
$comments = getRecipeComments($tarif_id);
$made_photos = getRecipeMadePhotos($tarif_id);
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($tarif['ad']); ?> - Yemek Tarifi</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/global.css">
    <link rel="stylesheet" href="css/tarif.css">
</head>
<body>
    <div class="container">
        <a href="index.php" class="back-link">← Ana Sayfaya Dön</a>

        <div class="tarif-container">
            <div class="tarif-header">
                <h1><?php echo htmlspecialchars($tarif['ad']); ?></h1>
                <p><?php echo htmlspecialchars($tarif['aciklama']); ?></p>
                <div class="tarif-meta">
                    <div class="tarif-meta-item">
                        <span>⏱️</span>
                        <span><?php echo $tarif['sure']; ?> dakika</span>
                    </div>
                    <div class="tarif-meta-item">
                        <span>��</span>
                        <span><?php echo $tarif['porsiyon']; ?> kişilik</span>
                    </div>
                    <div class="tarif-meta-item">
                        <span>❤️</span>
                        <span id="likeCount"><?php echo $like_count; ?> beğeni</span>
                    </div>
                    <div class="tarif-meta-item">
                        <span>⭐</span>
                        <span><?php echo $avg_rating > 0 ? number_format($avg_rating, 1) : 'Puanlanmadı'; ?></span>
                    </div>
                    <div class="tarif-meta-item">
                        <span>🎉</span>
                        <span><?php echo $made_count; ?> kişi yaptı</span>
                    </div>
                </div>
                <div class="author-info">
                    👨‍🍳 Yazar: <?php echo $author_name; ?>
                </div>
            </div>

            <?php if ($is_logged_in): ?>
            <div class="action-buttons">
                <button class="action-btn btn-like <?php echo $is_liked ? 'liked' : ''; ?>" onclick="toggleLike()">
                    <span>❤️</span>
                    <span id="likeText"><?php echo $is_liked ? 'Beğendin' : 'Beğen'; ?></span>
                </button>

                <button class="action-btn btn-favorite <?php echo $is_favorite ? 'favorited' : ''; ?>" onclick="toggleFavorite()">
                    <span>⭐</span>
                    <span id="favoriteText"><?php echo $is_favorite ? 'Favorilerden Çıkar' : 'Favorilere Ekle'; ?></span>
                </button>

                <a href="i_made_it.php?recipe_id=<?php echo $tarif_id; ?>" class="action-btn btn-made">
                    <span>🎉</span>
                    <span>Ben de Yaptım!</span>
                </a>
            </div>

            <div class="rating-section">
                <div class="rating-display">
                    Ortalama: <strong><?php echo $avg_rating > 0 ? number_format($avg_rating, 1) : '0'; ?>/5</strong>
                    (<?php echo $rating_count; ?> değerlendirme)
                </div>
                <div>Puanınız:</div>
                <div class="stars" id="ratingStars">
                    <?php for ($i = 1; $i <= 5; $i++): ?>
                        <span class="star <?php echo $i <= $user_rating ? 'filled' : ''; ?>" data-rating="<?php echo $i; ?>" onclick="rateRecipe(<?php echo $i; ?>)">★</span>
                    <?php endfor; ?>
                </div>
            </div>
            <?php else: ?>
            <div class="login-prompt">
                Bu tarifi beğenmek, favorilere eklemek veya puanlamak için <a href="login.php">giriş yapın</a>.
            </div>
            <?php endif; ?>

            <div class="tarif-content">
                <div class="section">
                    <h2>📝 Malzemeler</h2>
                    <ul class="malzemeler-list">
                        <?php foreach ($tarif['malzemeler'] as $malzeme): ?>
                            <li>
                                <span><?php echo htmlspecialchars($malzeme['ad']); ?></span>
                                <span class="miktar"><?php echo htmlspecialchars($malzeme['miktar']); ?></span>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>

                <div class="section">
                    <h2>👨‍🍳 Hazırlanışı</h2>
                    <div class="talimatlar"><?php echo htmlspecialchars($tarif['talimatlar']); ?></div>
                </div>
            </div>
        </div>

        <?php if (!empty($made_photos)): ?>
        <div class="made-photos">
            <h2>📸 Yapanların Fotoğrafları</h2>
            <div class="photos-grid">
                <?php foreach ($made_photos as $photo):
                    $photo_user = getUserInfo($photo['user_id']);
                ?>
                    <div class="photo-card">
                        <img src="<?php echo htmlspecialchars($photo['photo_path']); ?>" alt="Yemek fotoğrafı">
                        <div class="photo-info">
                            <div class="photo-author"><?php echo htmlspecialchars($photo_user['name']); ?></div>
                            <?php if (!empty($photo['note'])): ?>
                                <div class="photo-note"><?php echo htmlspecialchars($photo['note']); ?></div>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endif; ?>

        <div class="comments-section">
            <h2>💬 Yorumlar (<?php echo count($comments); ?>)</h2>

            <?php if ($is_logged_in): ?>
            <div class="comment-form">
                <textarea id="commentText" placeholder="Yorumunuzu yazın..."></textarea>
                <button onclick="addComment()">Yorum Yap</button>
            </div>
            <?php else: ?>
            <div class="login-prompt" style="margin-bottom: 20px;">
                Yorum yapmak için <a href="login.php">giriş yapın</a>.
            </div>
            <?php endif; ?>

            <div id="commentsList">
                <?php foreach ($comments as $comment):
                    $comment_user = getUserInfo($comment['user_id']);
                ?>
                    <div class="comment">
                        <div class="comment-header">
                            <span class="comment-author"><?php echo htmlspecialchars($comment_user['name']); ?></span>
                            <span class="comment-date"><?php echo date('d.m.Y H:i', strtotime($comment['created_at'])); ?></span>
                        </div>
                        <div class="comment-text"><?php echo nl2br(htmlspecialchars($comment['comment'])); ?></div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <script>
        const recipeId = <?php echo $tarif_id; ?>;
        const isLoggedIn = <?php echo $is_logged_in ? 'true' : 'false'; ?>;

        function toggleLike() {
            if (!isLoggedIn) {
                alert('Beğenmek için giriş yapmalısınız');
                return;
            }

            fetch('api.php', {
                method: 'POST',
                headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                body: 'action=toggle_like&recipe_id=' + recipeId
            })
            .then(r => r.json())
            .then(data => {
                if (data.success) {
                    const btn = document.querySelector('.btn-like');
                    const text = document.getElementById('likeText');
                    const count = document.getElementById('likeCount');

                    if (data.is_liked) {
                        btn.classList.add('liked');
                        text.textContent = 'Beğendin';
                    } else {
                        btn.classList.remove('liked');
                        text.textContent = 'Beğen';
                    }

                    count.textContent = data.like_count + ' beğeni';
                }
            });
        }

        function toggleFavorite() {
            if (!isLoggedIn) {
                alert('Favorilere eklemek için giriş yapmalısınız');
                return;
            }

            fetch('api.php', {
                method: 'POST',
                headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                body: 'action=toggle_favorite&recipe_id=' + recipeId
            })
            .then(r => r.json())
            .then(data => {
                if (data.success) {
                    const btn = document.querySelector('.btn-favorite');
                    const text = document.getElementById('favoriteText');

                    if (data.is_favorite) {
                        btn.classList.add('favorited');
                        text.textContent = 'Favorilerden Çıkar';
                    } else {
                        btn.classList.remove('favorited');
                        text.textContent = 'Favorilere Ekle';
                    }
                }
            });
        }

        function rateRecipe(rating) {
            if (!isLoggedIn) {
                alert('Puanlamak için giriş yapmalısınız');
                return;
            }

            fetch('api.php', {
                method: 'POST',
                headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                body: 'action=rate&recipe_id=' + recipeId + '&rating=' + rating
            })
            .then(r => r.json())
            .then(data => {
                if (data.success) {
                    // Yıldızları güncelle
                    document.querySelectorAll('.star').forEach(star => {
                        const starRating = parseInt(star.dataset.rating);
                        if (starRating <= rating) {
                            star.classList.add('filled');
                        } else {
                            star.classList.remove('filled');
                        }
                    });

                    // Ortalamayı güncelle
                    document.querySelector('.rating-display strong').textContent =
                        data.average_rating + '/5';
                    document.querySelector('.rating-display').innerHTML =
                        'Ortalama: <strong>' + data.average_rating + '/5</strong> (' + data.rating_count + ' değerlendirme)';
                }
            });
        }

        function addComment() {
            if (!isLoggedIn) {
                alert('Yorum yapmak için giriş yapmalısınız');
                return;
            }

            const commentText = document.getElementById('commentText').value.trim();
            if (!commentText) {
                alert('Yorum boş olamaz');
                return;
            }

            fetch('api.php', {
                method: 'POST',
                headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                body: 'action=add_comment&recipe_id=' + recipeId + '&comment=' + encodeURIComponent(commentText)
            })
            .then(r => r.json())
            .then(data => {
                if (data.success) {
                    alert('Yorumunuz eklendi!');
                    location.reload();
                } else {
                    alert(data.message || 'Hata oluştu');
                }
            });
        }
    </script>
    
    <script>
        window.recipeId = <?php echo $tarif_id; ?>;
        window.isLoggedIn = <?php echo $is_logged_in ? 'true' : 'false'; ?>;
    </script>
    <script src="js/tarif.js"></script>
</body>
</html>
