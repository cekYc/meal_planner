<?php
require_once 'database.php';

// Yeni tarif ekle
function addTarif($ad, $aciklama, $talimatlar, $malzemeler, $sure = 30, $porsiyon = 4, $resim = '', $user_id = null) {
    $tarifler = loadTarifler();
    
    // Yeni ID oluştur
    $max_id = 0;
    foreach ($tarifler as $tarif) {
        if ($tarif['id'] > $max_id) {
            $max_id = $tarif['id'];
        }
    }
    $new_id = $max_id + 1;
    
    // Yeni tarif
    $yeni_tarif = [
        'id' => $new_id,
        'ad' => $ad,
        'aciklama' => $aciklama,
        'talimatlar' => $talimatlar,
        'sure' => $sure,
        'porsiyon' => $porsiyon,
        'resim' => $resim,
        'malzemeler' => $malzemeler,
        'user_id' => $user_id,
        'created_at' => date('Y-m-d H:i:s')
    ];
    
    $tarifler[] = $yeni_tarif;
    saveTarifler($tarifler);
    
    return $new_id;
}

// Malzemelere göre tarif ara
function searchTariflerByMalzemeler($malzeme_listesi) {
    if (empty($malzeme_listesi)) {
        return [];
    }
    
    $tarifler = loadTarifler();
    $malzeme_listesi = array_map('strtolower', array_map('trim', $malzeme_listesi));
    
    $sonuclar = [];
    
    foreach ($tarifler as $tarif) {
        // Tarifteki malzemelerin isimlerini al
        $tarif_malzemeleri = array_map(function($m) {
            return strtolower(trim($m['ad']));
        }, $tarif['malzemeler']);
        
        // Eşleşen malzeme sayısı
        $eslesen = count(array_intersect($malzeme_listesi, $tarif_malzemeleri));
        
        if ($eslesen > 0) {
            $tarif['eslesen_malzeme'] = $eslesen;
            $tarif['toplam_malzeme'] = count($tarif_malzemeleri);
            $tarif['eslesen_oran'] = ($eslesen / $tarif['toplam_malzeme']) * 100;
            
            // Malzemeleri string olarak birleştir
            $malzeme_str = implode(', ', array_map(function($m) {
                return $m['ad'];
            }, $tarif['malzemeler']));
            $tarif['malzemeler_str'] = $malzeme_str;
            
            $sonuclar[] = $tarif;
        }
    }
    
    // Eşleşme oranına göre sırala
    usort($sonuclar, function($a, $b) {
        if ($a['eslesen_oran'] == $b['eslesen_oran']) {
            return $b['eslesen_malzeme'] - $a['eslesen_malzeme'];
        }
        return $b['eslesen_oran'] - $a['eslesen_oran'];
    });
    
    return $sonuclar;
}

// Tarif detaylarını getir
function getTarifDetay($tarif_id) {
    $tarifler = loadTarifler();
    
    foreach ($tarifler as $tarif) {
        if ($tarif['id'] == $tarif_id) {
            return $tarif;
        }
    }
    
    return null;
}

// Kullanıcının tariflerini getir
function getUserRecipes($user_id) {
    $tarifler = loadTarifler();
    return array_filter($tarifler, function($tarif) use ($user_id) {
        return isset($tarif['user_id']) && $tarif['user_id'] == $user_id;
    });
}

// Gelişmiş arama (malzeme, isim, yazar)
function advancedSearchRecipes($malzemeler = [], $recipe_name = '', $author_name = '') {
    $tarifler = loadTarifler();
    $results = [];
    
    // Eğer kullanıcı adı varsa, önce kullanıcıyı bul
    $author_id = null;
    $author_not_found = false;
    if (!empty($author_name)) {
        require_once 'auth.php';
        $users = loadUsers();
        foreach ($users as $user) {
            if (stripos($user['name'], $author_name) !== false || stripos($user['email'], $author_name) !== false) {
                $author_id = $user['id'];
                break;
            }
        }
        // Eğer yazar adı aranmış ama bulunamamışsa, sonuç döndürme
        if ($author_id === null) {
            $author_not_found = true;
        }
    }
    
    // Yazar bulunamamışsa boş sonuç dön
    if ($author_not_found) {
        return [];
    }
    
    foreach ($tarifler as $tarif) {
        $match = true;
        
        // Yazar filtresi
        if ($author_id !== null && (!isset($tarif['user_id']) || $tarif['user_id'] != $author_id)) {
            $match = false;
        }
        
        // İsim filtresi
        if (!empty($recipe_name) && stripos($tarif['ad'], $recipe_name) === false) {
            $match = false;
        }
        
        // Malzeme filtresi
        if (!empty($malzemeler)) {
            $malzemeler_normalized = array_map('strtolower', array_map('trim', $malzemeler));
            $tarif_malzemeleri = array_map(function($m) {
                return strtolower(trim($m['ad']));
            }, $tarif['malzemeler']);
            
            $eslesen = count(array_intersect($malzemeler_normalized, $tarif_malzemeleri));
            
            if ($eslesen == 0) {
                $match = false;
            } else {
                $tarif['eslesen_malzeme'] = $eslesen;
                $tarif['toplam_malzeme'] = count($tarif_malzemeleri);
                $tarif['eslesen_oran'] = ($eslesen / $tarif['toplam_malzeme']) * 100;
            }
        }
        
        if ($match) {
            $results[] = $tarif;
        }
    }
    
    // Malzeme araması varsa eşleşme oranına göre sırala
    if (!empty($malzemeler)) {
        usort($results, function($a, $b) {
            $a_oran = $a['eslesen_oran'] ?? 0;
            $b_oran = $b['eslesen_oran'] ?? 0;
            
            if ($a_oran == $b_oran) {
                return ($b['eslesen_malzeme'] ?? 0) - ($a['eslesen_malzeme'] ?? 0);
            }
            return $b_oran - $a_oran;
        });
    }
    
    return $results;
}

// Tarifleri farklı kriterlere göre sırala
function getRecipesSorted($sort_by = 'trending', $limit = 12) {
    require_once 'user_interactions.php';
    
    $tarifler = loadTarifler();
    
    // İstatistikleri ekle
    foreach ($tarifler as &$tarif) {
        $tarif['like_count'] = getRecipeLikeCount($tarif['id']);
        $tarif['favorite_count'] = count(array_filter(loadJsonFile(USER_FAVORITES_FILE), function($f) use ($tarif) {
            return $f['recipe_id'] == $tarif['id'];
        }));
        $tarif['made_count'] = getRecipeMadeCount($tarif['id']);
        $tarif['avg_rating'] = getRecipeAverageRating($tarif['id']);
        $tarif['comment_count'] = count(getRecipeComments($tarif['id']));
    }
    
    // Sırala
    usort($tarifler, function($a, $b) use ($sort_by) {
        switch ($sort_by) {
            case 'popular':
                // En çok beğenilen
                return $b['like_count'] - $a['like_count'];
            case 'favorite':
                // En çok kaydedilen
                return $b['favorite_count'] - $a['favorite_count'];
            case 'made':
                // En çok yapılan
                return $b['made_count'] - $a['made_count'];
            case 'rated':
                // En yüksek yorumlanan (rating)
                if ($b['avg_rating'] == $a['avg_rating']) {
                    return $b['comment_count'] - $a['comment_count'];
                }
                return ($b['avg_rating'] <=> $a['avg_rating']);
            case 'trending':
            default:
                // Trending: beğeni + kaydedilme + yapılma kombinasyonu
                $a_score = ($a['like_count'] * 2) + ($a['favorite_count'] * 3) + $a['made_count'];
                $b_score = ($b['like_count'] * 2) + ($b['favorite_count'] * 3) + $b['made_count'];
                return $b_score - $a_score;
        }
    });
    
    // Limit uygula
    return array_slice($tarifler, 0, $limit);
}

// Trending tarifleri al (default)
function getTrendingRecipes($limit = 12) {
    return getRecipesSorted('trending', $limit);
}
?>
