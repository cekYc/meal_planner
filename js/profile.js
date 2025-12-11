// profile.js - Profil sayfası tab yönetimi

function showTab(tabName) {
    // Tüm tab'ları gizle
    document.querySelectorAll('.tab-content').forEach(content => {
        content.classList.remove('active');
    });
    
    // Tüm tab butonlarını pasif yap
    document.querySelectorAll('.tab').forEach(btn => {
        btn.classList.remove('active');
    });
    
    // Seçilen tab'ı göster
    document.getElementById(tabName).classList.add('active');
    
    // Seçilen butonu aktif yap
    if (event && event.target) {
        event.target.classList.add('active');
    }
}
