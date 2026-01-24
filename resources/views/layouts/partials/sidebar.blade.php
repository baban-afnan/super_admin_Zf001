<!-- Main Wrapper -->
<div class="main-wrapper">

<!-- Sidebar -->
<div class="sidebar" id="sidebar">
    <!-- Logo -->
    <div class="sidebar-logo">
        <a href="{{ route('dashboard') }}" class="logo logo-normal">
            <img src="{{ asset('assets/img/logo/logo-small.png') }}" alt="Logo" width="150" height="150">
        </a>
        <a href="{{ route('dashboard') }}" class="logo-small">
            <img src="{{ asset('assets/img/logo/logo-small.png') }}" alt="Logo">
        </a>
        <a href="{{ route('dashboard') }}" class="dark-logo">
            <img src="{{ asset('assets/img/logo/logo-small.png') }}" alt="Logo">
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

                <li>
                    <a href="{{ route('services.index') }}" class="{{ request()->routeIs('services.*') ? 'active' : '' }}">
                        <i class="ti ti-server"></i><span>Services</span>
                    </a>
                </li>
                
                <li class="{{ request()->routeIs('admin.wallet.*') ? 'active' : '' }}">
                    <a href="{{ route('admin.wallet.index') }}">
                        <i class="ti ti-wallet"></i><span>Wallet</span>
                    </a>
                </li>

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
                        <li><a href="{{ route('tin.index', ['type' => 'individual']) }}" class="{{ request('type') == 'individual' ? 'active' : '' }}">TIN Individual</a></li>
                        <li><a href="{{ route('tin.index', ['type' => 'corporate']) }}" class="{{ request('type') == 'corporate' ? 'active' : '' }}">TIN Corporate</a></li>
                        <li><a href="#">CAC Registration</a></li>
                        <li><a href="#">Affidavit</a></li>
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
                        <li><a href="#">Website</a></li>
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
                        <a href="javascript:void(0);" onclick="confirmLogout('logout-form-sidebar')">
                            <i class="ti ti-logout"></i><span>Logout</span>
                        </a>
                    </form>
                </li>
            </ul>
        </div>
    </div>
</div>
<!-- /Sidebar -->


 <style>
/* Sidebar Scrolling Fix */
.sidebar {
    display: flex !important;
    flex-direction: column !important;
    height: 100vh !important;
    position: fixed !important;
    top: 0;
    bottom: 0;
    left: 0;
    z-index: 1001; /* Ensure it stays on top */
    padding-bottom: 0 !important; /* Remove bottom padding if likely to cause issues */
}

/* Prevent static sections from shrinking */
.sidebar-logo, 
.modern-profile, 
.sidebar-header {
    flex-shrink: 0 !important;
}

/* Make the inner menu scrollable */
.sidebar-inner {
    flex: 1 !important;
    overflow-y: auto !important;
    overflow-x: hidden !important;
    height: auto !important; /* Override any potential JS fixed height */
    width: 100%;
}

/* Custom Scrollbar */
.sidebar-inner::-webkit-scrollbar {
    width: 5px;
}
.sidebar-inner::-webkit-scrollbar-track {
    background: transparent;
}
.sidebar-inner::-webkit-scrollbar-thumb {
    background: #ccc;
    border-radius: 5px;
}
.sidebar-inner::-webkit-scrollbar-thumb:hover {
    background: #bbb;
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
    background: #e4d48eff;
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
 </style>