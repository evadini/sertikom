// =============================================
// ADMIN — Modal Unpublish dengan Alasan
// =============================================
function openUnpublishModal(eventId) {
    const form = document.getElementById('unpublishForm');
    if (!form) return;
    form.action = `/admin/events/${eventId}/unpublish`;
    document.getElementById('unpublishReason').value = '';
    document.getElementById('reasonCount').textContent = '0';
    document.getElementById('unpublishReasonError').classList.add('hidden');

    const modal = document.getElementById('unpublishModal');
    modal.classList.remove('hidden');
    modal.classList.add('flex');
}

function closeUnpublishModal() {
    const modal = document.getElementById('unpublishModal');
    modal.classList.add('hidden');
    modal.classList.remove('flex');
}

// =============================================
// PANITIA — Modal Lihat Alasan Reject
// =============================================
function showRejectReason(reason, date) {
    document.getElementById('rejectReasonText').textContent = reason || 'Tidak ada alasan.';
    document.getElementById('rejectReasonDate').textContent = date || '-';

    const modal = document.getElementById('rejectReasonModal');
    modal.classList.remove('hidden');
    modal.classList.add('flex');
}

function closeRejectModal() {
    const modal = document.getElementById('rejectReasonModal');
    modal.classList.add('hidden');
    modal.classList.remove('flex');
}

// =============================================
// Event Listeners (dijalankan saat DOM siap)
// =============================================
document.addEventListener('DOMContentLoaded', function () {

    // Counter karakter textarea alasan
    const textarea = document.getElementById('unpublishReason');
    if (textarea) {
        textarea.addEventListener('input', function () {
            document.getElementById('reasonCount').textContent = this.value.length;
            if (this.value.length >= 10) {
                document.getElementById('unpublishReasonError').classList.add('hidden');
            }
        });
    }

    // Validasi form sebelum submit
    const form = document.getElementById('unpublishForm');
    if (form) {
        form.addEventListener('submit', function (e) {
            const reason = document.getElementById('unpublishReason').value.trim();
            if (reason.length < 10) {
                e.preventDefault();
                document.getElementById('unpublishReasonError').classList.remove('hidden');
            }
        });
    }

    // Tutup modal kalau klik background
    ['unpublishModal', 'rejectReasonModal'].forEach(function (id) {
        const el = document.getElementById(id);
        if (el) {
            el.addEventListener('click', function (e) {
                if (e.target === this) {
                    this.classList.add('hidden');
                    this.classList.remove('flex');
                }
            });
        }
    });
});
