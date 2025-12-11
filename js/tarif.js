// tarif.js - Tarif detay sayfası etkileşim fonksiyonları

const recipeId = window.recipeId || 0;
const isLoggedIn = window.isLoggedIn || false;

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
