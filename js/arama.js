// arama.js - Arama sayfası dinamik filtreleme fonksiyonları

let malzemeler = [];
const tumTarifler = window.tumTarifler || [];
let recipeName = window.recipeName || '';
let authorName = window.authorName || '';
const users = window.users || [];

// Yazar adından user ID'si bulma
function findUserIdByName(name) {
    if (!name) return null;
    const nameLower = name.toLowerCase();
    for (let user of users) {
        if (user.name.toLowerCase().includes(nameLower) || user.email.toLowerCase().includes(nameLower)) {
            return user.id;
        }
    }
    return null;
}

// Sayfa yüklendiğinde çalış
document.addEventListener('DOMContentLoaded', function() {
    malzemeler = window.initialMalzemeler || [];
    updateMalzemeList();
    updateTarifler();
});

function malzemeEkle() {
    const input = document.getElementById('yeniMalzeme');
    const malzeme = input.value.trim();
    
    if (malzeme && !malzemeler.includes(malzeme.toLowerCase())) {
        malzemeler.push(malzeme.toLowerCase());
        updateMalzemeList();
        updateTarifler();
        input.value = '';
    }
}

function removeMalzeme(index) {
    malzemeler.splice(index, 1);
    updateMalzemeList();
    updateTarifler();
}

function updateMalzemeList() {
    const listesi = document.getElementById('malzemeListesi');
    
    if (malzemeler.length === 0) {
        listesi.innerHTML = '<div style="color: var(--text-secondary); font-style: italic;">Henüz malzeme eklenmedi. Yukarıdan malzeme ekleyin.</div>';
        return;
    }
    
    listesi.innerHTML = '';
    malzemeler.forEach((malzeme, index) => {
        const tag = document.createElement('div');
        tag.className = 'malzeme-tag';
        tag.style.cursor = 'pointer';
        tag.innerHTML = `
            <span>${malzeme}</span>
            <span class="remove" onclick="removeMalzeme(${index})">×</span>
        `;
        listesi.appendChild(tag);
    });
}

function updateTarifler() {
    const resultsDiv = document.getElementById('tarifResults');
    
    if (malzemeler.length === 0 && !recipeName && !authorName) {
        resultsDiv.innerHTML = `
            <div class="no-results">
                <div class="no-results-icon">🔍</div>
                <h2>Malzeme Ekleyin</h2>
                <p>Yukarıdaki kutudan malzeme ekleyerek tarifleri görüntüleyin.</p>
            </div>
        `;
        return;
    }
    
    // Yazar ID'sini bul
    const authorId = authorName ? findUserIdByName(authorName) : null;
    
    // Malzemeleri küçük harfe dönüştür
    const normalizedMalzemeler = malzemeler.map(m => m.toLowerCase().trim());
    
    // Eşleşen tarifleri filtrele
    const eşleşenler = tumTarifler.map(tarif => {
        let match = true;
        let eslesen = 0;
        
        // Yazar adı filtresi
        if (authorName) {
            if (authorId === null) {
                // Yazar bulunamadı
                match = false;
            } else if (!tarif.user_id || tarif.user_id != authorId) {
                // Tarif bu yazara ait değil
                match = false;
            }
        }
        
        // Tarif adı filtresi
        if (match && recipeName && tarif.ad.toLowerCase().indexOf(recipeName.toLowerCase()) === -1) {
            match = false;
        }
        
        // Malzeme filtresi
        if (match && normalizedMalzemeler.length > 0) {
            const tarifMalzemeleri = tarif.malzemeler.map(m => m.ad.toLowerCase().trim());
            eslesen = normalizedMalzemeler.filter(m => tarifMalzemeleri.includes(m)).length;
            
            if (eslesen === 0) {
                match = false;
            }
        }
        
        if (!match) {
            return null;
        }
        
        // Malzeme string'i oluştur
        const malzeme_isimleri = tarif.malzemeler.map(m => m.ad);
        const malzemeler_str = malzeme_isimleri.join(', ');
        
        if (normalizedMalzemeler.length > 0) {
            const tarifMalzemeleri = tarif.malzemeler.map(m => m.ad.toLowerCase().trim());
            return {
                ...tarif,
                eslesen_malzeme: eslesen,
                toplam_malzeme: tarifMalzemeleri.length,
                eslesen_oran: (eslesen / tarifMalzemeleri.length) * 100,
                malzemeler_str: malzemeler_str
            };
        } else {
            return {
                ...tarif,
                eslesen_malzeme: 0,
                toplam_malzeme: tarif.malzemeler.length,
                eslesen_oran: 0,
                malzemeler_str: malzemeler_str
            };
        }
    }).filter(t => t !== null);
    
    // Eşleşme oranına göre sırala (sadece malzeme filtresi varsa)
    if (normalizedMalzemeler.length > 0) {
        eşleşenler.sort((a, b) => {
            if (a.eslesen_oran === b.eslesen_oran) {
                return b.eslesen_malzeme - a.eslesen_malzeme;
            }
            return b.eslesen_oran - a.eslesen_oran;
        });
    }
    
    // Sonuç sayısını güncelle
    const sonucDiv = document.getElementById('sonucSayisi');
    if (sonucDiv) {
        sonucDiv.textContent = `${eşleşenler.length} tarif bulundu`;
    }
    
    // Sonuçları render et
    if (eşleşenler.length === 0) {
        resultsDiv.innerHTML = `
            <div class="no-results">
                <div class="no-results-icon">😕</div>
                <h2>Sonuç Bulunamadı</h2>
                <p>Bu aramanıza uygun tarif bulunamadı.</p>
            </div>
        `;
    } else {
        resultsDiv.innerHTML = eşleşenler.map(tarif => {
            let infoText = '';
            if (normalizedMalzemeler.length > 0) {
                infoText = `✓ ${tarif.eslesen_malzeme}/${tarif.toplam_malzeme} malzeme eşleşti (${Math.round(tarif.eslesen_oran)}%)`;
            }
            
            return `
                <a href="tarif.php?id=${tarif.id}" class="tarif-card">
                    <div class="tarif-img">
                        ${tarif.resim ? `<img src="${escapeHtml(tarif.resim)}" alt="${escapeHtml(tarif.ad)}" style="width: 100%; height: 100%; object-fit: cover;">` : '🍽️'}
                    </div>
                    <div class="tarif-content">
                        ${infoText ? `<div class="eslesen-info">${infoText}</div>` : ''}
                        <h3 class="tarif-title">${escapeHtml(tarif.ad)}</h3>
                        <p class="tarif-desc">${escapeHtml(tarif.aciklama)}</p>
                        <div class="tarif-meta">
                            <span>⏱️ ${tarif.sure} dk</span>
                            <span>👥 ${tarif.porsiyon} kişilik</span>
                        </div>
                        <div style="font-size: 0.8em; color: #999;">
                            <strong>Malzemeler:</strong> ${escapeHtml(tarif.malzemeler_str.substring(0, 80))}...
                        </div>
                    </div>
                </a>
            `;
        }).join('');
    }
}

function escapeHtml(text) {
    const map = {
        '&': '&amp;',
        '<': '&lt;',
        '>': '&gt;',
        '"': '&quot;',
        "'": '&#039;'
    };
    return text.replace(/[&<>"']/g, m => map[m]);
}

// Enter tuşu ile malzeme ekleme
document.addEventListener('DOMContentLoaded', function() {
    const yeniMalzemeInput = document.getElementById('yeniMalzeme');
    if (yeniMalzemeInput) {
        yeniMalzemeInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                malzemeEkle();
            }
        });
    }
});
