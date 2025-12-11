<?php
// Kullanıcı etkileşimleri için helper fonksiyonlar

define('USER_FAVORITES_FILE', __DIR__ . '/user_favorites.json');
define('USER_MADE_FILE', __DIR__ . '/user_made.json');
define('RECIPE_LIKES_FILE', __DIR__ . '/recipe_likes.json');
define('RECIPE_RATINGS_FILE', __DIR__ . '/recipe_ratings.json');
define('RECIPE_COMMENTS_FILE', __DIR__ . '/recipe_comments.json');
define('USER_MADE_PHOTOS_FILE', __DIR__ . '/user_made_photos.json');

// JSON dosyalarını yükle
function loadJsonFile($file) {
    if (!file_exists($file)) {
        return [];
    }
    $content = file_get_contents($file);
    return json_decode($content, true) ?: [];
}

// JSON dosyasına kaydet
function saveJsonFile($file, $data) {
    file_put_contents($file, json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
}

// ============ FAVORİLER ============
function addToFavorites($user_id, $recipe_id) {
    $favorites = loadJsonFile(USER_FAVORITES_FILE);
    
    // Zaten favorilerde mi?
    foreach ($favorites as $fav) {
        if ($fav['user_id'] == $user_id && $fav['recipe_id'] == $recipe_id) {
            return false;
        }
    }
    
    $favorites[] = [
        'user_id' => $user_id,
        'recipe_id' => $recipe_id,
        'created_at' => date('Y-m-d H:i:s')
    ];
    
    saveJsonFile(USER_FAVORITES_FILE, $favorites);
    return true;
}

function removeFromFavorites($user_id, $recipe_id) {
    $favorites = loadJsonFile(USER_FAVORITES_FILE);
    $favorites = array_filter($favorites, function($fav) use ($user_id, $recipe_id) {
        return !($fav['user_id'] == $user_id && $fav['recipe_id'] == $recipe_id);
    });
    
    saveJsonFile(USER_FAVORITES_FILE, array_values($favorites));
    return true;
}

function isFavorite($user_id, $recipe_id) {
    $favorites = loadJsonFile(USER_FAVORITES_FILE);
    foreach ($favorites as $fav) {
        if ($fav['user_id'] == $user_id && $fav['recipe_id'] == $recipe_id) {
            return true;
        }
    }
    return false;
}

function getUserFavorites($user_id) {
    $favorites = loadJsonFile(USER_FAVORITES_FILE);
    return array_filter($favorites, function($fav) use ($user_id) {
        return $fav['user_id'] == $user_id;
    });
}

// ============ BENDE YAPTIM ============
function addToMadeList($user_id, $recipe_id, $photo_path = null, $note = '') {
    $made = loadJsonFile(USER_MADE_FILE);
    
    $made[] = [
        'id' => time() . rand(1000, 9999),
        'user_id' => $user_id,
        'recipe_id' => $recipe_id,
        'photo_path' => $photo_path,
        'note' => $note,
        'created_at' => date('Y-m-d H:i:s')
    ];
    
    saveJsonFile(USER_MADE_FILE, $made);
    return true;
}

function getUserMadeRecipes($user_id) {
    $made = loadJsonFile(USER_MADE_FILE);
    return array_filter($made, function($m) use ($user_id) {
        return $m['user_id'] == $user_id;
    });
}

function getRecipeMadeCount($recipe_id) {
    $made = loadJsonFile(USER_MADE_FILE);
    return count(array_filter($made, function($m) use ($recipe_id) {
        return $m['recipe_id'] == $recipe_id;
    }));
}

function getRecipeMadePhotos($recipe_id) {
    $made = loadJsonFile(USER_MADE_FILE);
    return array_filter($made, function($m) use ($recipe_id) {
        return $m['recipe_id'] == $recipe_id && !empty($m['photo_path']);
    });
}

// ============ BEĞENİLER ============
function toggleLike($user_id, $recipe_id) {
    $likes = loadJsonFile(RECIPE_LIKES_FILE);
    
    // Zaten beğenmiş mi?
    $already_liked = false;
    foreach ($likes as $key => $like) {
        if ($like['user_id'] == $user_id && $like['recipe_id'] == $recipe_id) {
            unset($likes[$key]);
            $already_liked = true;
            break;
        }
    }
    
    if (!$already_liked) {
        $likes[] = [
            'user_id' => $user_id,
            'recipe_id' => $recipe_id,
            'created_at' => date('Y-m-d H:i:s')
        ];
    }
    
    saveJsonFile(RECIPE_LIKES_FILE, array_values($likes));
    return !$already_liked;
}

function isLiked($user_id, $recipe_id) {
    $likes = loadJsonFile(RECIPE_LIKES_FILE);
    foreach ($likes as $like) {
        if ($like['user_id'] == $user_id && $like['recipe_id'] == $recipe_id) {
            return true;
        }
    }
    return false;
}

function getRecipeLikeCount($recipe_id) {
    $likes = loadJsonFile(RECIPE_LIKES_FILE);
    return count(array_filter($likes, function($like) use ($recipe_id) {
        return $like['recipe_id'] == $recipe_id;
    }));
}

// ============ PUANLAMA ============
function rateRecipe($user_id, $recipe_id, $rating) {
    $ratings = loadJsonFile(RECIPE_RATINGS_FILE);
    
    // Önceki puanı kaldır
    $ratings = array_filter($ratings, function($r) use ($user_id, $recipe_id) {
        return !($r['user_id'] == $user_id && $r['recipe_id'] == $recipe_id);
    });
    
    $ratings[] = [
        'user_id' => $user_id,
        'recipe_id' => $recipe_id,
        'rating' => max(1, min(5, (int)$rating)),
        'created_at' => date('Y-m-d H:i:s')
    ];
    
    saveJsonFile(RECIPE_RATINGS_FILE, array_values($ratings));
    return true;
}

function getUserRating($user_id, $recipe_id) {
    $ratings = loadJsonFile(RECIPE_RATINGS_FILE);
    foreach ($ratings as $rating) {
        if ($rating['user_id'] == $user_id && $rating['recipe_id'] == $recipe_id) {
            return $rating['rating'];
        }
    }
    return 0;
}

function getRecipeAverageRating($recipe_id) {
    $ratings = loadJsonFile(RECIPE_RATINGS_FILE);
    $recipe_ratings = array_filter($ratings, function($r) use ($recipe_id) {
        return $r['recipe_id'] == $recipe_id;
    });
    
    if (empty($recipe_ratings)) {
        return 0;
    }
    
    $sum = array_sum(array_column($recipe_ratings, 'rating'));
    return round($sum / count($recipe_ratings), 1);
}

function getRecipeRatingCount($recipe_id) {
    $ratings = loadJsonFile(RECIPE_RATINGS_FILE);
    return count(array_filter($ratings, function($r) use ($recipe_id) {
        return $r['recipe_id'] == $recipe_id;
    }));
}

// ============ YORUMLAR ============
function addComment($user_id, $recipe_id, $comment) {
    $comments = loadJsonFile(RECIPE_COMMENTS_FILE);
    
    $comments[] = [
        'id' => time() . rand(1000, 9999),
        'user_id' => $user_id,
        'recipe_id' => $recipe_id,
        'comment' => $comment,
        'created_at' => date('Y-m-d H:i:s')
    ];
    
    saveJsonFile(RECIPE_COMMENTS_FILE, $comments);
    return true;
}

function getRecipeComments($recipe_id) {
    $comments = loadJsonFile(RECIPE_COMMENTS_FILE);
    $recipe_comments = array_filter($comments, function($c) use ($recipe_id) {
        return $c['recipe_id'] == $recipe_id;
    });
    
    // En yeni yorumlar önce
    usort($recipe_comments, function($a, $b) {
        return strtotime($b['created_at']) - strtotime($a['created_at']);
    });
    
    return $recipe_comments;
}

function getUserComments($user_id) {
    $comments = loadJsonFile(RECIPE_COMMENTS_FILE);
    return array_filter($comments, function($c) use ($user_id) {
        return $c['user_id'] == $user_id;
    });
}

function deleteComment($comment_id, $user_id) {
    $comments = loadJsonFile(RECIPE_COMMENTS_FILE);
    $comments = array_filter($comments, function($c) use ($comment_id, $user_id) {
        return !($c['id'] == $comment_id && $c['user_id'] == $user_id);
    });
    
    saveJsonFile(RECIPE_COMMENTS_FILE, array_values($comments));
    return true;
}
?>
