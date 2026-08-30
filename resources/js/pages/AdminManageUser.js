window.openModal = function (id) {
    const modal = document.getElementById(id);

    if (modal) {
        modal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }
};

window.closeModal = function (id) {
    const modal = document.getElementById(id);

    if (modal) {
        modal.classList.add('hidden');
        document.body.style.overflow = '';
    }
};

window.openEditUser = function (
    id,
    name,
    nim,
    phone,
    role
) {
    document.getElementById('editName').value = '';
    document.getElementById('editNim').value = '';
    document.getElementById('editPhone').value = '';
    document.getElementById('editRole').value = '';

    document.getElementById('editForm').action =
        `/admin/users/${id}`;

    openModal('editModal');
};

window.openDeleteUser = function (id, name) {
    document.getElementById('deleteUserName').textContent = name;

    document.getElementById('deleteForm').action =
        `/admin/users/${id}`;

    openModal('deleteModal');
};

window.switchTab = function (active) {
    const tabs = [
        'pembeli',
        'panitia',
        'deleted'
    ];

    tabs.forEach(tab => {
        const panel = document.getElementById(`panel-${tab}`);
        const button = document.getElementById(`tab-${tab}`);

        if (tab === active) {
            panel?.classList.remove('hidden');

            button.className =
                'px-5 py-2.5 rounded-xl text-sm font-bold transition-all bg-blue-600 text-white';
        } else {
            panel?.classList.add('hidden');

            button.className =
                'px-5 py-2.5 rounded-xl text-sm font-bold transition-all bg-gray-100 text-gray-500 hover:bg-gray-200';
        }
    });

    document
        .getElementById('toolbar-search')
        ?.classList.toggle(
            'hidden',
            active === 'deleted'
        );
};

document.addEventListener('DOMContentLoaded', () => {

    const modals = [
        'editModal',
        'deleteModal'
    ];

    modals.forEach(id => {
        const modal = document.getElementById(id);

        modal?.addEventListener('click', function (e) {
            if (e.target === this) {
                closeModal(id);
            }
        });
    });

    document.addEventListener('keydown', e => {
        if (e.key === 'Escape') {
            closeModal('editModal');
            closeModal('deleteModal');
        }
    });

    const toast = document.getElementById('toast');

    if (toast) {
        setTimeout(() => {
            toast.style.opacity = '0';
            toast.style.transition = 'opacity .5s ease';

            setTimeout(() => {
                toast.remove();
            }, 500);
        }, 3000);
    }
});
