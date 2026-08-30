import './bootstrap';
import '../css/app.css';
import 'flowbite';

// ✅ SWIPER CORE + MODULE
import Swiper from 'swiper';
import { Autoplay, Pagination } from 'swiper/modules';

// ✅ SWIPER CSS
import 'swiper/css';
import 'swiper/css/pagination';

import './pages/AdminManageUser';


// ================= MODAL =================


window.openApprove = function () {
    document.getElementById('approveModal').classList.remove('hidden');
    document.getElementById('approveModal').classList.add('flex');
}

window.closeApprove = function () {
    document.getElementById('approveModal').classList.add('hidden');
}

window.openReject = function () {
    document.getElementById('rejectModal').classList.remove('hidden');
    document.getElementById('rejectModal').classList.add('flex');
}

window.closeReject = function () {
    document.getElementById('rejectModal').classList.add('hidden');
}

window.openUnpublish = function () {
    document.getElementById('unpublishModal').classList.remove('hidden');
    document.getElementById('unpublishModal').classList.add('flex');
}

window.closeUnpublish = function () {
    document.getElementById('unpublishModal').classList.add('hidden');
}

// ================= SWIPER =================
document.addEventListener('DOMContentLoaded', function () {
    const el = document.querySelector('.myHeroSwiper');
    if (!el) return;

    new Swiper(el, {
        modules: [Autoplay, Pagination],

        loop: true,

        autoplay: {
            delay: 3000,
            disableOnInteraction: false,
        },

        pagination: {
            el: '.swiper-pagination',
            clickable: true,
        },

        speed: 800,
    });
});


const ROLE_CONFIG = {
    Admin:     { badge: 'bg-blue-500/10 text-blue-400',     avatar: 'bg-blue-500/20 text-blue-400' },
    Organizer: { badge: 'bg-indigo-500/10 text-indigo-400', avatar: 'bg-indigo-500/20 text-indigo-400' },
    Customer:  { badge: 'bg-gray-500/10 text-gray-300',     avatar: 'bg-gray-500/20 text-gray-300' },
};

function getInitials(name) {
    const parts = name.trim().split(' ');
    return (parts[0][0] + (parts[1] ? parts[1][0] : '')).toUpperCase();
}

window.openModal = function (id) {
    const el = document.getElementById(id);
    el.classList.remove('hidden');
    el.classList.add('flex');
};

window.closeModal = function (id) {
    const el = document.getElementById(id);
    el.classList.add('hidden');
    el.classList.remove('flex');
};

window.openEdit = function (name, email, role) {
    const cfg = ROLE_CONFIG[role] ?? ROLE_CONFIG.Customer;

    const avatar = document.getElementById('editAvatar');
    avatar.textContent = getInitials(name);
    avatar.className   = `w-10 h-10 rounded-xl flex items-center justify-center text-sm font-bold flex-shrink-0 ${cfg.avatar}`;

    document.getElementById('editBadgeName').textContent  = name;
    document.getElementById('editBadgeEmail').textContent = email;

    const badge = document.getElementById('editBadgeRole');
    badge.textContent = role;
    badge.className   = `ml-auto px-3 py-1 rounded-full text-xs font-semibold flex-shrink-0 ${cfg.badge}`;

    document.getElementById('editName').value  = name;
    document.getElementById('editEmail').value = email;
    document.getElementById('editRole').value  = role;

    window.openModal('editModal');
};

window.openDelete = function (name, email) {
    document.getElementById('deleteAvatar').textContent    = getInitials(name);
    document.getElementById('deleteUserName').textContent  = name;
    document.getElementById('deleteUserEmail').textContent = email;

    window.openModal('deleteModal');
};

// Tutup modal klik backdrop
document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('[id$="Modal"]').forEach(overlay => {
        overlay.addEventListener('click', function (e) {
            if (e.target === this) window.closeModal(this.id);
        });
    });
});

// Tutup modal tombol Escape
document.addEventListener('keydown', e => {
    if (e.key !== 'Escape') return;
    document.querySelectorAll('[id$="Modal"]:not(.hidden)')
        .forEach(el => window.closeModal(el.id));
});

// ── Modal Utilities ─────────────────────────────────────────
// resources/js/modal.js

export function openModal(id) {
    const modal = document.getElementById(id);
    if (!modal) return;
    modal.classList.remove('hidden');
    modal.classList.add('flex');
}

export function closeModal(id) {
    const modal = document.getElementById(id);
    if (!modal) return;
    modal.classList.add('hidden');
    modal.classList.remove('flex');
}

// ── User Modal Helpers ───────────────────────────────────────

export function openEditUser(name, email, role) {
    const badgeClasses = {
        Admin:     'bg-blue-500/10 text-blue-400',
        Organizer: 'bg-indigo-500/10 text-indigo-400',
        Customer:  'bg-gray-500/10 text-gray-300',
    };
    const avatarClasses = {
        Admin:     'bg-blue-500/20 text-blue-400',
        Organizer: 'bg-indigo-500/20 text-indigo-400',
        Customer:  'bg-gray-500/20 text-gray-300',
    };

    // Initials
    const parts    = name.trim().split(' ');
    const initials = (parts[0][0] + (parts[1]?.[0] ?? '')).toUpperCase();

    const avatar = document.getElementById('editAvatar');
    avatar.textContent  = initials;
    avatar.className    = `w-10 h-10 rounded-xl flex items-center justify-center text-sm font-bold flex-shrink-0 ${avatarClasses[role] ?? avatarClasses.Customer}`;

    document.getElementById('editBadgeName').textContent  = name;
    document.getElementById('editBadgeEmail').textContent = email;

    const badge = document.getElementById('editBadgeRole');
    badge.textContent = role;
    badge.className   = `ml-auto px-3 py-1 rounded-full text-xs font-semibold flex-shrink-0 ${badgeClasses[role] ?? badgeClasses.Customer}`;

    document.getElementById('editName').value  = name;
    document.getElementById('editEmail').value = email;
    document.getElementById('editRole').value  = role;

    openModal('editModal');
}

export function openDeleteUser(name, email) {
    const parts    = name.trim().split(' ');
    const initials = (parts[0][0] + (parts[1]?.[0] ?? '')).toUpperCase();

    document.getElementById('deleteAvatar').textContent    = initials;
    document.getElementById('deleteUserName').textContent  = name;
    document.getElementById('deleteUserEmail').textContent = email;

    openModal('deleteModal');
}

// ── Category Modal Helpers ───────────────────────────────────

export function openEditCategory(name) {
    document.getElementById('editBadgeName').textContent  = name;
    document.getElementById('editCategoryName').value     = name;
    openModal('editModal');
}

export function openDeleteCategory(name) {
    document.getElementById('deleteCategoryName').textContent = name;
    openModal('deleteModal');
}

// ── Backdrop click to close ──────────────────────────────────

function initBackdropClose() {
    document.querySelectorAll('[id$="Modal"]').forEach(modal => {
        modal.addEventListener('click', function (e) {
            if (e.target === this) closeModal(this.id);
        });
    });
}

// ── Auto-init on DOM ready ───────────────────────────────────

document.addEventListener('DOMContentLoaded', () => {
    initBackdropClose();

    // Expose ke window agar bisa dipanggil dari blade via onclick="..."
    window.openModal          = openModal;
    window.closeModal         = closeModal;
    window.openEdit           = openEditUser;        // alias untuk users.blade
    window.openDelete         = openDeleteUser;      // alias untuk users.blade
    window.openEditCategory   = openEditCategory;
    window.openDeleteCategory = openDeleteCategory;
});

function openDetailFromElement(el) {
    let event = JSON.parse(el.dataset.event);

    console.log(event); //

    document.getElementById('detailPoster').src =
        event.banner && event.banner !== ''
            ? `/images/events/${event.banner}`
            : `/images/events/banner_1779635248.jpg`;
}
