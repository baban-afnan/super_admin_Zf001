<!-- Main Wrapper -->
<div class="main-wrapper">

<!-- Sidebar -->
<div class="sidebar" id="sidebar">
    <!-- Logo -->
    <div class="sidebar-logo">
        <a href="{{ route('dashboard') }}" class="logo logo-normal">
            <img src="{{ asset('assets/img/logo/logo-small.png') }}" alt="Logo" class="img-fluid sidebar-logo-img">
        </a>
        <a href="{{ route('dashboard') }}" class="logo-small">
            <img src="{{ asset('assets/img/logo/logo-small.png') }}" alt="Logo" class="img-fluid sidebar-logo-img-small">
        </a>
        <a href="{{ route('dashboard') }}" class="dark-logo">
            <img src="{{ asset('assets/img/logo/logo-small.png') }}" alt="Logo" class="img-fluid sidebar-logo-img">
        </a>
    </div>
    <!-- /Logo -->
    
    <div class="sidebar-inner">
        <div id="sidebar-menu" class="sidebar-menu">
            <ul>
                <!-- Main Menu -->
                <li class="menu-title"><span>Main Menu</span></li>
                
                <li>
                    <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">
                        <i class="ti ti-home"></i><span>Dashboard</span>
                    </a>
                </li>


                 <!-- Services -->
                <li class="submenu">
                    <a href="javascript:void(0);" class="{{ request()->routeIs(['services.*', 'admin.data-variations.*', 'admin.sme-data.*']) ? 'active subdrop' : '' }}">
                        <i class="ti ti-server"></i>
                        <span>Services</span>
                        <span class="menu-arrow"></span>
                    </a>
                    <ul>
                        <li><a href="{{ route('services.index') }}" class="{{ request()->routeIs('services.*') ? 'active' : '' }}">Services</a></li>
                        <li><a href="{{ route('admin.data-variations.index') }}" class="{{ request()->routeIs('admin.data-variations.*') ? 'active' : '' }}">VTpass Data</a></li>
                        <li><a href="{{ route('admin.sme-data.index') }}" class="{{ request()->routeIs('admin.sme-data.*') ? 'active' : '' }}">SME Data</a></li>
                    </ul>
                </li>
                <!-- /Services -->

                <!-- Wallets Services -->
                <li class="submenu">
                    <a href="javascript:void(0);" class="{{ request()->routeIs('admin.wallet.*') ? 'active subdrop' : '' }}">
                        <i class="ti ti-wallet"></i>
                        <span>Wallets</span>
                        <span class="menu-arrow"></span>
                    </a>
                    <ul>
                        <li><a href="{{ route('admin.wallet.index') }}" class="{{ request()->routeIs('admin.wallet.index') ? 'active' : '' }}">Wallets</a></li>
                        <li><a href="{{ route('admin.wallet.summary') }}" class="{{ request()->routeIs('admin.wallet.summary') ? 'active' : '' }}">Summary</a></li>
                    </ul>
                </li>
                <!-- /Wallets Services -->
                

                <!-- BVN Request  -->
                <li class="submenu">
                    <a href="javascript:void(0);">
                        <i class="ti ti-credit-card"></i>
                        <span>BVN Services</span>
                        <span class="menu-arrow"></span>
                    </a>
                    <ul>
                        <li><a href="{{ route('bvnmod.index') }}" class="{{ request()->routeIs('bvnmod.*') ? 'active' : '' }}">BVN Modification</a></li>
                        <li><a href="{{ route('crm.index') }}" class="{{ request()->routeIs('crm.*') ? 'active' : '' }}">CRM</a></li>
                        <li><a href="{{ route('bvnuser.index') }}" class="{{ request()->routeIs('bvnuser.*') ? 'active' : '' }}">BVN User</a></li>
                        <li><a href="{{ route('vnin-nibss.index') }}" class="{{ request()->routeIs('vnin-nibss.*') ? 'active' : '' }}">VNIN to NIBSS</a></li>
                        <li><a href="{{ route('bvn-search.index') }}">BVN Search</a></li>
                         <li><a href="{{ route('enrollments.index') }}" class="{{ request()->routeIs('enrollments.*') ? 'active' : '' }}">Upload Enrolment</a></li>
                    </ul>
                </li>
                <!-- /end agency service -->

                <!-- NIN Services -->
                <li class="submenu">
                    <a href="javascript:void(0);">
                        <i class="ti ti-id"></i>
                        <span>NIN Services</span>
                        <span class="menu-arrow"></span>
                    </a>
                    <ul>
                        <li><a href="{{ route('validation.index') }}" class="{{ request()->routeIs('validation.*') ? 'active' : '' }}">NIN Validation</a></li>
                        <li><a href="{{ route('ninipe.index') }}" class="{{ request()->routeIs('ninipe.*') ? 'active' : '' }}">NIN IPE</a></li>
                        <li><a href="{{ route('ninmod.index') }}" class="{{ request()->routeIs('ninmod.*') ? 'active' : '' }}">NIN Modification</a></li>
                    </ul>
                </li>
                <!-- /NIN Services -->

                <!-- Agency Services -->
                <li class="submenu">
                    <a href="javascript:void(0);">
                        <i class="ti ti-user-check"></i>
                        <span>Agency Services</span>
                        <span class="menu-arrow"></span>
                    </a>
                    <ul>
                        <li><a href="{{ route('cac-registration.index') }}" class="{{ request()->routeIs('cac-registration.*') ? 'active' : '' }}">CAC Registration</a></li>
                        <li><a href="{{ route('affidavit.index') }}" class="{{ request()->routeIs('affidavit.*') ? 'active' : '' }}">Affidavit</a></li>
                        <li><a href="#">VAS</a></li>
                    </ul>
                </li>
                <!-- /NIN Services -->


                <!-- Admin Section -->
                <li class="menu-title"><span>Admin</span></li>
                 <li>
                    <a href="{{ route('admin.users.index') }}" class="{{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                        <i class="ti ti-users"></i><span>User Management</span>
                    </a>
                </li>
               

                 <!-- Api services -->
                <li class="submenu">
                    <a href="javascript:void(0);">
                        <i class="ti ti-user-check"></i>
                        <span>API Service</span>
                        <span class="menu-arrow"></span>
                    </a>
                    <ul>
                        <li><a href="{{ route('admin.api-applications.index') }}">API request</a></li>
                        <li><a href="{{ route('admin.transactions.index', ['source' => 'api']) }}" class="{{ request('source') == 'api' ? 'active' : '' }}">Transactions</a></li>
                        <li><a href="{{ route('admin.website-clients.index') }}" class="{{ request()->routeIs('admin.website-clients.*') ? 'active' : '' }}">Website Service</a></li>
                    </ul>
                </li>
                <!-- /Api Services -->



                <!-- Account Section -->
                <li class="menu-title"><span>Account</span></li>
                
                <li>
                    <a href="{{ route('profile.edit') }}" class="{{ request()->routeIs('profile.edit') ? 'active' : '' }}">
                        <i class="ti ti-settings"></i><span>Settings</span>
                    </a>
                </li>
                
                <li>
                    <a href="{{ route('admin.transactions.index') }}" class="{{ request()->routeIs('admin.transactions.*') ? 'active' : '' }}">
                        <i class="ti ti-receipt"></i><span>Transactions</span>
                    </a>
                </li>
                
                <li>
                    <a href="{{ route('admin.notification.index') }}" class="{{ request()->routeIs('admin.notification.*') ? 'active' : '' }}">
                        <i class="ti ti-user-star"></i><span>Notification</span>
                    </a>
                </li>
                
                <li>
                    <a href="{{ route('admin.support.index') }}" class="{{ request()->routeIs('admin.support.*') ? 'active' : '' }}">
                        <i class="ti ti-message"></i><span>Support</span>
                    </a>
                </li>
                
                <li>
                    <form method="POST" action="{{ route('logout') }}" id="logout-form-sidebar">
                        @csrf
                        <a href="javascript:void(0);" onclick="confirmLogout('logout-form-sidebar')" class="btn btn-outline-danger w-100 text-start border-0 fw-semibold">
                            <i class="ti ti-logout me-2"></i><span>Logout</span>
                        </a>
                    </form>
                </li>
            </ul>
        </div>
    </div>
</div>
<!-- /Sidebar -->


<style>

/* Make the sidebar scrollable while keeping logo fixed on top */
.sidebar {
    display: flex;
    flex-direction: column;
    height: 100vh;
}

.sidebar-inner {
    flex: 1;
    overflow-y: auto;
    overflow-x: hidden;
}

/* Customize the scrollbar for webkit browsers */
.sidebar-inner::-webkit-scrollbar {
    width: 6px;
}
.sidebar-inner::-webkit-scrollbar-track {
    background: transparent;
}
.sidebar-inner::-webkit-scrollbar-thumb {
    background: #d1d1d1;
    border-radius: 4px;
}

  /* Better icon and text spacing */
.sidebar-menu li a {
    display: flex;
    align-items: center;
    padding: 10px 15px;
}

.sidebar-menu li a i {
    margin-right: 10px;
    width: 20px;
    text-align: center;
}

.sidebar-logo {
    position: sticky;
    top: 0;
    z-index: 9999;
    background-color: #fff; 
    flex-shrink: 0;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 10px;
    min-height: 70px;
}

.sidebar-logo-img {
    max-height: 50px;
    max-width: 100%;
    width: auto;
    object-fit: contain;
}

.sidebar-logo-img-small {
    max-height: 35px;
    max-width: 100%;
    width: auto;
    object-fit: contain;
}

/* Ensure logo resizes gracefully on smaller screens */
@media (max-width: 768.98px) {
    .sidebar-logo-img {
        max-height: 40px;
    }
}

/* Submenu styling */
.sidebar-menu .submenu ul {
    background: rgba(0, 0, 0, 0.02);
}

.sidebar-menu .submenu ul li a {
    padding-left: 45px;
    font-size: 13px;
}

/* Active state */
.sidebar-menu li a.active {
    background: #f1cfbfff;
    color: white;
}

/* Menu title spacing */
.menu-title {
    padding: 15px 15px 5px 15px;
    font-size: 12px;
    text-transform: uppercase;
    color: #6c757d;
    font-weight: 600;
}
@keyframes pulse-red-sidebar {
    0% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(220, 53, 69, 0.7); }
    70% { transform: scale(1); box-shadow: 0 0 0 6px rgba(220, 53, 69, 0); }
    100% { transform: scale(0.95); box-shadow: 0 0 0 0 rgba(220, 53, 69, 0); }
}
 </style>