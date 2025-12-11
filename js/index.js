// index.js - Ana sayfa malzeme arama fonksiyonları

let malzemeler = [];

function addMalzeme() {
    const input = document.getElementById('malzemeInput');
    const malzeme = input.value.trim();
    
    if (malzeme && !malzemeler.includes(malzeme.toLowerCase())) {
        malzemeler.push(malzeme.toLowerCase());
        updateMalzemeList();
        input.value = '';
    }
}

function removeMalzeme(index) {
    malzemeler.splice(index, 1);
    updateMalzemeList();
}

function updateMalzemeList() {
    const listesi = document.getElementById('malzemeListesi');
    const hidden = document.getElementById('malzemelerHidden');
    
    listesi.innerHTML = '';
    malzemeler.forEach((malzeme, index) => {
        const tag = document.createElement('div');
        tag.className = 'malzeme-tag';
        tag.innerHTML = `
            <span>${malzeme}</span>
            <span class="remove" onclick="removeMalzeme(${index})">×</span>
        `;
        listesi.appendChild(tag);
    });
    
    hidden.value = JSON.stringify(malzemeler);
}

// Enter tuşu ile malzeme ekleme
document.addEventListener('DOMContentLoaded', function() {
    const malzemeInput = document.getElementById('malzemeInput');
    if (malzemeInput) {
        malzemeInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                addMalzeme();
            }
        });
    }
    
    // Form submit kontrolü
    const searchForm = document.getElementById('searchForm');
    if (searchForm) {
        searchForm.addEventListener('submit', function(e) {
            const recipeName = document.getElementById('recipeName').value.trim();
            const authorName = document.getElementById('authorName').value.trim();
            
            if (malzemeler.length === 0 && !recipeName && !authorName) {
                e.preventDefault();
                alert('Lütfen en az bir arama kriteri girin (tarif adı, yazar veya malzeme)!');
            }
        });
    }
});
