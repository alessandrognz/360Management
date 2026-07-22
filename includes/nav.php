<?php
$layout_part = $layout_part ?? 'nav';
$current_page = basename($_SERVER['PHP_SELF']);

if ($layout_part === 'nav'):
?>
<aside class="sidebar">
    <div class="sidebar-brand">
        <img src="assets/icons/logo.png" alt="">
        <span>360Management</span>
    </div>
    <div class="sidebar-profile">
        <img src="assets/icons/profile.png" alt="" class="profile">
        <span><?php echo htmlspecialchars($_SESSION['nombre'] ?? ''); ?></span>
    </div>
    <p class="sidebar-section-label">Menu</p>
    <nav class="sidebar-nav">
        <a href="session.php" class="sidebar-item<?= $current_page === 'session.php' ? ' sidebar-item--active' : '' ?>">
            <svg class="sidebar-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                <path d="m3 9 9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/>
                <polyline points="9 22 9 12 15 12 15 22"/>
            </svg>
            <span>Inicio</span>
        </a>
        <a href="admin.php" class="sidebar-item<?= $current_page === 'admin.php' ? ' sidebar-item--active' : '' ?>">
            <svg class="sidebar-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
            </svg>
            <span>Admin</span>
        </a>
        <a href="tasks.php" class="sidebar-item<?= $current_page === 'tasks.php' ? ' sidebar-item--active' : '' ?>">
            <img src="assets/icons/Edit.svg" class="sidebar-icon" alt="" aria-hidden="true">
            <span>Tasks</span>
        </a>
        <a href="inbox.php" class="sidebar-item<?= $current_page === 'inbox.php' ? ' sidebar-item--active' : '' ?>">
            <img src="assets/icons/Info.svg" class="sidebar-icon" alt="" aria-hidden="true">
            <span>Inbox</span>
        </a>
        <a href="settings.php" class="sidebar-item<?= $current_page === 'settings.php' ? ' sidebar-item--active' : '' ?>">
            <img src="assets/icons/Setting_line.svg" class="sidebar-icon" alt="" aria-hidden="true">
            <span>Ajustes</span>
        </a>
    </nav>
    <div class="sidebar-footer-area">
        <a href="includes/logout.php" class="sidebar-item sidebar-item--logout">
            <img src="assets/icons/On_button.svg" class="sidebar-icon" alt="" aria-hidden="true">
            <span>Salir</span>
        </a>
    </div>
</aside>
<?php elseif ($layout_part === 'footer'): ?>
<footer class="footer-publico">
    <span>© 2026 360Management</span>
    <a href="#">Privacidad</a>
    <a href="#">Contacto</a>
</footer>
<?php endif; ?>
