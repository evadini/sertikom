C
// ============================================================
// resources/js/pages/AdminEventCategories.js
// ============================================================

// ── Modal Helpers ────────────────────────────────────────────
function openModal(id) {
    const el = document.getElementById(id);
    el.classList.remove('hidden');
    el.classList.add('flex');
}

function closeModal(id) {
    const el = document.getElementById(id);
    el.classList.add('hidden');
    el.classList.remove('flex');
}

// Tutup modal saat klik backdrop
['addModal', 'editModal'].forEach(id => {
    document.getElementById(id)?.addEventListener('click', function (e) {
        if (e.target === this) closeModal(id);
    });
});

// Tutup modal saat tekan Escape
document.addEventListener('keydown', function (e) {
    if (e.key === 'Escape') {
        closeModal('addModal');
        closeModal('editModal');
    }
});

// ── Add Modal ────────────────────────────────────────────────
function openAddModal() {
    openModal('addModal');
}

// Live preview icon saat mengetik di Add Modal
document.getElementById('addIconInput')?.addEventListener('input', function () {
    const icon = document.getElementById('addIconPreview')?.querySelector('i');
    if (icon) icon.className = 'fa-solid ' + (this.value || 'fa-tag');
});

// ── Edit Modal ───────────────────────────────────────────────
function openEditModal(id, name, icon, color) {
    // Set action form
    const form = document.getElementById('editForm');
    if (form) form.action = `/admin/categories/${id}`;

    // Set nilai input
    const nameInput = document.getElementById('editName');
    if (nameInput) nameInput.value = name;

    const iconInput = document.getElementById('editIconInput');
    if (iconInput) iconInput.value = icon;

    // Set preview icon
    const iconPreview = document.getElementById('editIconPreview')?.querySelector('i');
    if (iconPreview) iconPreview.className = 'fa-solid ' + icon;

    // Set radio color
    document.querySelectorAll('.edit-color-radio').forEach(r => {
        r.checked = r.value === color;
    });

    openModal('editModal');
}

// Live preview icon saat mengetik di Edit Modal
document.getElementById('editIconInput')?.addEventListener('input', function () {
    const icon = document.getElementById('editIconPreview')?.querySelector('i');
    if (icon) icon.className = 'fa-solid ' + (this.value || 'fa-tag');
});

// ── Ekspor fungsi agar bisa dipanggil dari inline onclick di blade ──
window.openAddModal  = openAddModal;
window.openEditModal = openEditModal;
window.closeModal    = closeModal;
