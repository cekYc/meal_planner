<?php
require_once 'auth.php';
require_once 'user_interactions.php';

startSession();

header('Content-Type: application/json');

if (!isLoggedIn()) {
    echo json_encode(['success' => false, 'message' => 'Giriş yapmalısınız']);
    exit();
}

$user_id = getCurrentUserId();
$action = $_POST['action'] ?? $_GET['action'] ?? '';

switch ($action) {
    case 'toggle_favorite':
        $recipe_id = (int)($_POST['recipe_id'] ?? 0);
        if ($recipe_id > 0) {
            if (isFavorite($user_id, $recipe_id)) {
                removeFromFavorites($user_id, $recipe_id);
                echo json_encode(['success' => true, 'is_favorite' => false]);
            } else {
                addToFavorites($user_id, $recipe_id);
                echo json_encode(['success' => true, 'is_favorite' => true]);
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'Geçersiz tarif']);
        }
        break;
        
    case 'toggle_like':
        $recipe_id = (int)($_POST['recipe_id'] ?? 0);
        if ($recipe_id > 0) {
            $is_liked = toggleLike($user_id, $recipe_id);
            $like_count = getRecipeLikeCount($recipe_id);
            echo json_encode([
                'success' => true, 
                'is_liked' => $is_liked, 
                'like_count' => $like_count
            ]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Geçersiz tarif']);
        }
        break;
        
    case 'rate':
        $recipe_id = (int)($_POST['recipe_id'] ?? 0);
        $rating = (int)($_POST['rating'] ?? 0);
        if ($recipe_id > 0 && $rating >= 1 && $rating <= 5) {
            rateRecipe($user_id, $recipe_id, $rating);
            $avg_rating = getRecipeAverageRating($recipe_id);
            $rating_count = getRecipeRatingCount($recipe_id);
            echo json_encode([
                'success' => true, 
                'average_rating' => $avg_rating,
                'rating_count' => $rating_count
            ]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Geçersiz değerler']);
        }
        break;
        
    case 'add_comment':
        $recipe_id = (int)($_POST['recipe_id'] ?? 0);
        $comment = trim($_POST['comment'] ?? '');
        if ($recipe_id > 0 && !empty($comment)) {
            addComment($user_id, $recipe_id, $comment);
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Yorum boş olamaz']);
        }
        break;
        
    case 'delete_comment':
        $comment_id = $_POST['comment_id'] ?? '';
        deleteComment($comment_id, $user_id);
        echo json_encode(['success' => true]);
        break;
        
    case 'check_favorite':
        $recipe_id = (int)($_GET['recipe_id'] ?? 0);
        echo json_encode([
            'is_favorite' => isFavorite($user_id, $recipe_id)
        ]);
        break;
        
    case 'check_like':
        $recipe_id = (int)($_GET['recipe_id'] ?? 0);
        echo json_encode([
            'is_liked' => isLiked($user_id, $recipe_id),
            'like_count' => getRecipeLikeCount($recipe_id)
        ]);
        break;
        
    default:
        echo json_encode(['success' => false, 'message' => 'Geçersiz işlem']);
        break;
}
?>
