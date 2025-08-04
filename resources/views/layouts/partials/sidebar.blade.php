<!-- resources/views/layouts/partials/sidebar.blade.php -->
<div class="sidebar-wrapper">
    <div class="sidebar-toggle" onclick="toggleSidebar()">
        <i class="fas fa-chevron-left toggle-icon"></i>
    </div>
    <div class="sidebar">
        <div class="sidebar-menu">
            <li class="sidebar-section-title">
                <span>Main Menu</span>
            </li>
            <ul class="sidebar-list">
                <li class="sidebar-item {{ request()->routeIs('dashboard.*') ? 'active' : '' }}">
                    <a href="{{ route('dashboard.index') }}" class="sidebar-link">
                        <i class="fas fa-home me-2"></i>
                        <span>Dashboard</span>
                    </a>
                </li>
                <!-- Menu Users -->
                <li class="sidebar-item {{ request()->routeIs('users.*') ? 'active' : '' }}">
                    <a href="{{ route('users.index') }}" class="sidebar-link">
                        <i class="fas fa-users me-2"></i>
                        <span>Users</span>
                    </a>
                </li>
                
                <!-- Menu Employee -->
                <li class="sidebar-item {{ request()->routeIs('divisions.*') ? 'active' : '' }}">
                    <a href="{{ route('divisions.index') }}" class="sidebar-link">
                        <i class="fas fa-building me-2"></i>
                        <span>Division</span>
                    </a>
                </li>
                <!-- Menu Employee -->
                <li class="sidebar-item {{ request()->routeIs('employees.*') ? 'active' : '' }}">
                    <a href="{{ route('employees.index') }}" class="sidebar-link">
                        <i class="fas fa-user-tie me-2"></i>
                        <span>Employee</span>
                    </a>
                </li>
                
                <!-- Recruitment Header -->
<li class="sidebar-section-title mt-4">
    <span>Recruitment</span>
</li>

<li class="sidebar-item {{ request()->routeIs('jobs.*') ? 'active' : '' }}">
    <a href="{{ route('jobs.index') }}" class="sidebar-link">
        <i class="fas fa-briefcase me-2"></i>
        <span>Job Post</span>
    </a>
</li>
<li class="sidebar-item {{ request()->routeIs('applications.*') ? 'active' : '' }}">
    <a href="{{ route('applications.index') }}" class="sidebar-link">
        <i class="fas fa-file-alt me-2"></i>
        <span>Applications</span>
    </a>
</li>
<li class="sidebar-item {{ request()->routeIs('interviews.*') ? 'active' : '' }}">
    <a href="{{ route('interviews.index') }}" class="sidebar-link">
        <i class="fas fa-comments me-2"></i>
        <span>Interviews</span>
    </a>
</li>

<!-- Scoring Dropdown -->
<li class="sidebar-item has-submenu {{ request()->routeIs('interview-scores.*') ? 'active' : '' }}">
    <a href="#" class="sidebar-link">
        <i class="fas fa-star-half-alt me-2"></i>
        <span>Scoring</span>
        <i class="fas fa-chevron-right dropdown-icon"></i>
    </a>
    <ul class="submenu">
        <li class="submenu-item {{ request()->routeIs('interview-scores.unscored') ? 'active' : '' }}">
            <a href="{{ route('interview-scores.unscored') }}">Belum Dinilai</a>
        </li>
        <li class="submenu-item {{ request()->routeIs('interview-scores.hired') ? 'active' : '' }}">
            <a href="{{ route('interview-scores.hired') }}">Kandidat Hired</a>
        </li>
        <li class="submenu-item {{ request()->routeIs('interview-scores.unhired') ? 'active' : '' }}">
            <a href="{{ route('interview-scores.unhired') }}">Kandidat Unhired</a>
        </li>
        <li class="submenu-item {{ request()->routeIs('interview-scores.undecided') ? 'active' : '' }}">
            <a href="{{ route('interview-scores.undecided') }}">Belum Diputuskan</a>
        </li>
    </ul>
</li>

            </ul>
        </div>
    </div>
</div>

<script>
    function toggleSidebar() {
        const sidebar = document.querySelector('.sidebar-wrapper');
        const mainContent = document.querySelector('.main-content');
        
        sidebar.classList.toggle('collapsed');
        
        // Adjust main content margin
        if (sidebar.classList.contains('collapsed')) {
            mainContent.style.marginLeft = '70px';
            localStorage.setItem('sidebarCollapsed', 'true');
        } else {
            mainContent.style.marginLeft = '250px';
            localStorage.setItem('sidebarCollapsed', 'false');
        }
    }

    // Fungsi untuk menangani dropdown submenu
    document.addEventListener('DOMContentLoaded', function() {
        // Toggle submenu saat menu recruitment diklik
        document.querySelectorAll('.sidebar-item.has-submenu .sidebar-link, .submenu-item.has-submenu .submenu-link').forEach(item => {
            item.addEventListener('click', function(e) {
                // Hanya proses jika bukan di mode collapsed
                if (!document.querySelector('.sidebar-wrapper').classList.contains('collapsed')) {
                    e.preventDefault();
                    const parent = this.closest('.has-submenu');
                    parent.classList.toggle('active');
                    
                    // Tutup submenu lainnya yang terbuka pada level yang sama
                    const siblings = parent.parentElement.querySelectorAll('.has-submenu');
                    siblings.forEach(otherItem => {
                        if (otherItem !== parent) {
                            otherItem.classList.remove('active');
                        }
                    });
                }
            });
        });

        // Tutup submenu saat klik di luar
        document.addEventListener('click', function(e) {
            if (!e.target.closest('.sidebar-item.has-submenu') && !e.target.closest('.submenu-item.has-submenu')) {
                document.querySelectorAll('.sidebar-item.has-submenu, .submenu-item.has-submenu').forEach(item => {
                    item.classList.remove('active');
                });
            }
        });
    });

    // Initialize on load
    document.addEventListener('DOMContentLoaded', function() {
        const sidebar = document.querySelector('.sidebar-wrapper');
        const mainContent = document.querySelector('.main-content');
        const isCollapsed = localStorage.getItem('sidebarCollapsed') === 'true';
        
        if (isCollapsed) {
            sidebar.classList.add('collapsed');
            mainContent.style.marginLeft = '70px';
        }
        
        // Responsive handling
        function handleResponsive() {
            if (window.innerWidth <= 768) {
                sidebar.classList.add('collapsed');
                mainContent.style.marginLeft = '70px';
            } else {
                if (localStorage.getItem('sidebarCollapsed') === 'true') {
                    sidebar.classList.add('collapsed');
                    mainContent.style.marginLeft = '70px';
                } else {
                    sidebar.classList.remove('collapsed');
                    mainContent.style.marginLeft = '250px';
                }
            }
        }
        
        handleResponsive();
        window.addEventListener('resize', handleResponsive);
    });
</script>