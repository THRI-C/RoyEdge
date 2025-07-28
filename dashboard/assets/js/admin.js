// Prevent reloading of pages
document.addEventListener('submit', (e) => {
    e.preventDefault();
})

// Modal open/close for admin suspend/activate
window.openSuspendModal = function (userId, action) {
    var url = 'functions/suspend-user.php?id=' + userId + (action === 'activate' ? '&activate=1' : '');
    var msg = action === 'activate' ? 'Activate this user?' : 'Suspend this user?';
    document.getElementById('modalBody').textContent = msg;
    document.getElementById('modalConfirmBtn').setAttribute('href', url);
    document.getElementById('confirmModal').style.display = 'flex';
}
window.closeModal = function () {
    document.getElementById('confirmModal').style.display = 'none';
}