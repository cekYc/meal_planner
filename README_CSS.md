# Yemek Sitesi - Modern CSS Yapısı

## 📁 CSS Dosya Yapısı

Tüm inline CSS'ler ayrı dosyalara taşındı ve modern, şık bir tasarım uygulandı.

### CSS Dosyaları:

```
css/
├── global.css      # Genel stiller, değişkenler, butonlar, formlar
├── index.css       # Ana sayfa ve arama kutusu stilleri
├── tarif.css       # Tarif detay sayfası stilleri
├── profile.css     # Profil sayfası stilleri
├── auth.css        # Giriş/Kayıt sayfası stilleri
├── arama.css       # Arama sonuçları sayfası stilleri
└── form.css        # Form sayfaları (Tarif Ekle, Ben de Yaptım)
```

## 🎨 Tasarım Özellikleri

### Modern Tasarım Elementleri:
- ✅ **CSS Variables (Custom Properties)**: Renk ve stil yönetimi için
- ✅ **Google Fonts (Inter)**: Modern ve okunabilir font ailesi
- ✅ **Gradient Backgrounds**: Dinamik ve çekici arka planlar
- ✅ **Box Shadows**: Derinlik ve katman efektleri
- ✅ **Smooth Transitions**: Yumuşak geçişler ve animasyonlar
- ✅ **Responsive Design**: Mobil uyumlu tasarım
- ✅ **Hover Effects**: İnteraktif hover animasyonları
- ✅ **Backdrop Blur**: Glassmorphism efektleri
- ✅ **Modern Card Layout**: Şık kart tasarımları
- ✅ **Gradient Buttons**: Renkli gradient butonlar

### Renk Paleti:
```css
--primary-color: #667eea      (Mor-Mavi)
--primary-dark: #5568d3       (Koyu Mor-Mavi)
--secondary-color: #764ba2     (Mor)
--accent-color: #48bb78        (Yeşil)
--warning-color: #f39c12       (Turuncu)
--danger-color: #e74c3c        (Kırmızı)
```

## 📄 Sayfa-CSS Eşleşmeleri

| Sayfa | CSS Dosyaları |
|-------|---------------|
| **index.php** | global.css + index.css |
| **tarif.php** | global.css + tarif.css |
| **profile.php** | global.css + profile.css |
| **login.php** | global.css + auth.css |
| **add_recipe.php** | global.css + form.css |
| **arama.php** | global.css + index.css + arama.css |
| **i_made_it.php** | global.css + form.css |

## 🚀 Kullanım

Her sayfada gerekli CSS dosyaları zaten bağlantılı durumda:

```html
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
<link rel="stylesheet" href="css/global.css">
<link rel="stylesheet" href="css/[sayfa-özel].css">
```

## 🎯 Ana Özellikler

### Global (global.css):
- CSS değişkenleri
- Reset stilleri
- Tipografi
- Butonlar (primary, secondary, success, outline)
- Form elementleri
- Mesaj kutuları (success, error, warning)
- Animasyonlar

### Ana Sayfa (index.css):
- Modern arama kutusu
- Tag sistemi (malzeme etiketleri)
- Grid layout
- Responsive tarif kartları
- Hover efektleri

### Tarif Detay (tarif.css):
- Hero header gradient
- Action butonları (beğen, favorile, ben de yaptım)
- Yıldız rating sistemi
- Malzeme listesi
- Yorum sistemi
- Fotoğraf galerisi

### Profil (profile.css):
- Avatar sistemi
- Tab navigasyonu
- Grid layout
- Kullanıcı içerik kartları

## 🔧 Özelleştirme

Renkleri değiştirmek için `css/global.css` dosyasındaki CSS değişkenlerini düzenleyin:

```css
:root {
    --primary-color: #667eea;
    --accent-color: #48bb78;
    /* ... diğer değişkenler */
}
```

## 📱 Responsive Breakpoints

- **Desktop**: > 768px (Varsayılan)
- **Tablet/Mobile**: ≤ 768px

Tüm sayfalar mobil cihazlarda da mükemmel görünür!

---

**Not**: Tüm inline CSS'ler kaldırıldı ve ayrı dosyalara taşındı. Bu sayede:
- ✅ Kod daha temiz ve organize
- ✅ Bakım daha kolay
- ✅ Performans daha iyi (tarayıcı cache)
- ✅ Stil değişiklikleri tek yerden yapılabilir
