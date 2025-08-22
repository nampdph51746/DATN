<style>
    .profile-menu {
    width: 250px;
    background: #ffffff;
    border-radius: 12px 0 0 12px;
    padding: 1.5rem;
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.07);
    margin: 0;
    transition: all 0.3s ease;
}

.profile-menu:hover {
    box-shadow: 0 12px 28px rgba(0, 0, 0, 0.12);
}

.menu-title {
    font-size: 18px;
    font-weight: bold;
    margin-bottom: 1rem;
    color: #111827;
}

.menu-list {
    list-style: none;
    padding: 0;
    margin: 0;
}

.menu-item {
    display: block;
    padding: 0.6rem 0.75rem;
    color: #374151;
    text-decoration: none;
    font-weight: 500;
    border-radius: 8px;
    transition: all 0.25s ease;
}

.menu-item:hover {
    background-color: #3b82f6;
    color: white;
    padding-left: 1rem;
}

[data-theme="dark"] .profile-menu {
    background-color: #1f2937;
}

[data-theme="dark"] .menu-title {
    color: #f9fafb;
}

[data-theme="dark"] .menu-item {
    color: #d1d5db;
}

[data-theme="dark"] .menu-item:hover {
    background-color: #2563eb;
}

</style>

<div class="profile-menu">
    <h3 class="menu-title">Tài khoản CineVN</h3>
    <ul class="menu-list">
        <li><a href="/profile/general" class="menu-item">Thông tin chung</a></li>
        <li><a href="/profile/detail" class="menu-item">Chi tiết tài khoản</a></li>
        <li><a href="/profile/membership" class="menu-item">Thẻ thành viên</a></li>
        <li><a href="/profile/voucher" class="menu-item">Voucher</a></li>
        <li><a href="/profile/history" class="menu-item">Lịch sử giao dịch</a></li>
        <li><a href="/profile/change-password" class="menu-item">Thay đổi mật khẩu</a></li>
    </ul>
</div>
