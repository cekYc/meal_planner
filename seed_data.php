<?php
require_once 'functions.php';

// Örnek tarifleri ekle

// 1. Menemen
$malzemeler = [
    ['ad' => 'yumurta', 'miktar' => '3 adet'],
    ['ad' => 'domates', 'miktar' => '2 adet'],
    ['ad' => 'biber', 'miktar' => '1 adet'],
    ['ad' => 'soğan', 'miktar' => '1 adet'],
    ['ad' => 'zeytinyağı', 'miktar' => '2 yemek kaşığı'],
    ['ad' => 'tuz', 'miktar' => 'Bir tutam'],
    ['ad' => 'karabiber', 'miktar' => 'Bir tutam']
];
addTarif(
    'Menemen',
    'Türk mutfağının klasik kahvaltılık yemeği. Pratik ve lezzetli.',
    "1. Soğanı ince doğrayın ve zeytinyağında kavurun.\n2. Biberleri ekleyip 2-3 dakika kavurun.\n3. Domatesleri küp küp doğrayıp ekleyin.\n4. Domatesler suyunu salıp çekene kadar pişirin.\n5. Yumurtaları çırpıp karışımın üzerine dökün.\n6. Tuz ve karabiber ekleyin.\n7. Yumurtalar pişene kadar karıştırarak pişirin.\n8. Sıcak servis edin.",
    $malzemeler,
    15,
    2
);

// 2. Tavuk Sote
$malzemeler = [
    ['ad' => 'tavuk göğsü', 'miktar' => '500 gr'],
    ['ad' => 'domates', 'miktar' => '2 adet'],
    ['ad' => 'biber', 'miktar' => '2 adet'],
    ['ad' => 'soğan', 'miktar' => '1 adet'],
    ['ad' => 'tereyağı', 'miktar' => '2 yemek kaşığı'],
    ['ad' => 'sıvı yağ', 'miktar' => '2 yemek kaşığı'],
    ['ad' => 'tuz', 'miktar' => 'Bir tutam'],
    ['ad' => 'karabiber', 'miktar' => 'Bir tutam'],
    ['ad' => 'kırmızı pul biber', 'miktar' => '1 tatlı kaşığı']
];
addTarif(
    'Tavuk Sote',
    'Pratik ve lezzetli bir akşam yemeği. Pilav veya makarna ile servis edilebilir.',
    "1. Tavuk göğsünü küp küp doğrayın.\n2. Tavukları tereyağı ve sıvı yağda kavurun.\n3. Tavuklar pembeliğini kaybedince soğanı ekleyin.\n4. Soğan pembeleşince biberleri ekleyin.\n5. 2-3 dakika sonra domatesleri ekleyin.\n6. Tuz, karabiber ve pul biber ekleyin.\n7. Kısık ateşte 15 dakika pişirin.\n8. Pilav veya makarna ile servis edin.",
    $malzemeler,
    30,
    4
);

// 3. Makarna
$malzemeler = [
    ['ad' => 'makarna', 'miktar' => '500 gr'],
    ['ad' => 'domates', 'miktar' => '3 adet'],
    ['ad' => 'sarımsak', 'miktar' => '3 diş'],
    ['ad' => 'zeytinyağı', 'miktar' => '3 yemek kaşığı'],
    ['ad' => 'tuz', 'miktar' => 'Bir tutam'],
    ['ad' => 'fesleğen', 'miktar' => '5-6 yaprak'],
    ['ad' => 'kaşar peyniri', 'miktar' => '100 gr']
];
addTarif(
    'Fesleğenli Domates Soslu Makarna',
    'İtalyan mutfağının vazgeçilmezi. Basit ama çok lezzetli.',
    "1. Makarnayı tuzlu suda haşlayın.\n2. Domatesleri rendeleyin veya blenderden geçirin.\n3. Sarımsağı ince doğrayın.\n4. Zeytinyağında sarımsağı kavurun.\n5. Domates püresini ekleyin.\n6. Tuz ve fesleğen ekleyip 10 dakika pişirin.\n7. Haşlanmış makarnayı sosla karıştırın.\n8. Üzerine rendelenmiş kaşar serpin.\n9. Sıcak servis edin.",
    $malzemeler,
    25,
    4
);

// 4. Patates Kızartması
$malzemeler = [
    ['ad' => 'patates', 'miktar' => '4 adet'],
    ['ad' => 'sıvı yağ', 'miktar' => 'Kızartmak için'],
    ['ad' => 'tuz', 'miktar' => 'Bir tutam']
];
addTarif(
    'Çıtır Patates Kızartması',
    'Hem çocukların hem de yetişkinlerin favorisi. Yanında ketçap veya mayonezle servis edilir.',
    "1. Patatesleri soyun ve çubuk çubuk doğrayın.\n2. Soğuk suda bekletin (15 dakika).\n3. İyice kurulayın.\n4. Bol sıvı yağda kızartın.\n5. İlk kızartmada patatesleri hafif pembeleşene kadar kızartın.\n6. Çıkarıp dinlendirin.\n7. Yağı tekrar kızdırın ve ikinci kez kızartın.\n8. Altın sarısı olunca çıkarın.\n9. Kağıt havlu üzerinde yağını süzdürün.\n10. Tuzlayıp sıcak servis edin.",
    $malzemeler,
    30,
    4
);

// 5. Mercimek Çorbası
$malzemeler = [
    ['ad' => 'kırmızı mercimek', 'miktar' => '1 su bardağı'],
    ['ad' => 'soğan', 'miktar' => '1 adet'],
    ['ad' => 'havuç', 'miktar' => '1 adet'],
    ['ad' => 'patates', 'miktar' => '1 adet'],
    ['ad' => 'tereyağı', 'miktar' => '2 yemek kaşığı'],
    ['ad' => 'tuz', 'miktar' => 'Bir tutam'],
    ['ad' => 'su', 'miktar' => '6 su bardağı'],
    ['ad' => 'limon', 'miktar' => '1 adet']
];
addTarif(
    'Mercimek Çorbası',
    'Türk mutfağının en sevilen çorbalarından biri. Besleyici ve doyurucu.',
    "1. Soğanı ince doğrayıp tereyağında kavurun.\n2. Havuç ve patatesi küp şeklinde doğrayın.\n3. Mercimek, havuç ve patatesi soğana ekleyin.\n4. Üzerini geçecek kadar su ekleyin.\n5. Sebzeler yumuşayana kadar pişirin (20-25 dakika).\n6. Blenderdan geçirin.\n7. Kıvamını su ile ayarlayın.\n8. Tuz ekleyin.\n9. 5 dakika daha kaynatın.\n10. Limon sıkarak sıcak servis edin.",
    $malzemeler,
    35,
    4
);

// 6. Omlet
$malzemeler = [
    ['ad' => 'yumurta', 'miktar' => '3 adet'],
    ['ad' => 'süt', 'miktar' => '2 yemek kaşığı'],
    ['ad' => 'kaşar peyniri', 'miktar' => '50 gr'],
    ['ad' => 'tereyağı', 'miktar' => '1 yemek kaşığı'],
    ['ad' => 'tuz', 'miktar' => 'Bir tutam'],
    ['ad' => 'karabiber', 'miktar' => 'Bir tutam']
];
addTarif(
    'Peynirli Omlet',
    'Hızlı ve pratik bir kahvaltılık. 5 dakikada hazır!',
    "1. Yumurtaları bir kaseye kırın.\n2. Süt, tuz ve karabiberi ekleyin.\n3. Çırpıcı ile iyice çırpın.\n4. Peyniri rendeleyin.\n5. Tavayı kızdırın ve tereyağını eritin.\n6. Yumurta karışımını dökün.\n7. Üzerine rendelenmiş peyniri serpin.\n8. Alt tarafı pişince spatula ile katına.\n9. Her iki tarafı da pişene kadar bekleyin.\n10. Sıcak servis edin.",
    $malzemeler,
    10,
    2
);

// 7. Salata
$malzemeler = [
    ['ad' => 'marul', 'miktar' => '1 baş'],
    ['ad' => 'domates', 'miktar' => '2 adet'],
    ['ad' => 'salatalık', 'miktar' => '1 adet'],
    ['ad' => 'limon', 'miktar' => '1 adet'],
    ['ad' => 'zeytinyağı', 'miktar' => '3 yemek kaşığı'],
    ['ad' => 'tuz', 'miktar' => 'Bir tutam']
];
addTarif(
    'Yeşil Salata',
    'Taze ve sağlıklı. Her öğünün yanında servis edilebilir.',
    "1. Marulu yıkayıp doğrayın.\n2. Domatesleri küp küp doğrayın.\n3. Salatalığı dilimleyin.\n4. Tüm sebzeleri geniş bir kaseye alın.\n5. Üzerine limon sıkın.\n6. Zeytinyağı ve tuz ekleyin.\n7. Karıştırıp servis edin.",
    $malzemeler,
    10,
    4
);

// 8. Kuru Fasulye
$malzemeler = [
    ['ad' => 'kuru fasulye', 'miktar' => '2 su bardağı'],
    ['ad' => 'soğan', 'miktar' => '1 adet'],
    ['ad' => 'domates', 'miktar' => '2 adet'],
    ['ad' => 'biber', 'miktar' => '1 adet'],
    ['ad' => 'sıvı yağ', 'miktar' => '3 yemek kaşığı'],
    ['ad' => 'salça', 'miktar' => '1 yemek kaşığı'],
    ['ad' => 'tuz', 'miktar' => 'Bir tutam'],
    ['ad' => 'karabiber', 'miktar' => 'Bir tutam'],
    ['ad' => 'su', 'miktar' => '4 su bardağı']
];
addTarif(
    'Kuru Fasulye',
    'Türk mutfağının en sevilen yemeklerinden. Pilav ve turşu ile servis edilir.',
    "1. Fasulyeleri bir gece önceden ıslak mendilde ıslatın.\n2. Haşlamak için tencereye alın, üzerini geçecek kadar su ekleyin.\n3. Yumuşayana kadar haşlayın (45-60 dakika).\n4. Soğanı doğrayıp yağda kavurun.\n5. Salçayı ekleyip kavurun.\n6. Domates ve biberleri ekleyin.\n7. Haşlanmış fasulyeleri süzüp ekleyin.\n8. Tuz ve karabiber ekleyin.\n9. Üzerine sıcak su ekleyin.\n10. Kısık ateşte 30 dakika pişirin.\n11. Pilav ile servis edin.",
    $malzemeler,
    90,
    4
);

echo "Örnek tarifler başarıyla eklendi!\n";
echo "Toplam 8 tarif eklendi.\n";
?>
