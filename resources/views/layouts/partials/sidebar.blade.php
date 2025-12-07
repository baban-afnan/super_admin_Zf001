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
    
    <div class="modern-profile p-3 pb-0">
        <div class="text-center rounded bg-light p-3 mb-4 user-profile">
            <div class="avatar avatar-lg online mb-3">
                <img src="{{ asset('assets/img/profiles/avatar-02.jpg') }}" alt="Img" class="img-fluid rounded-circle">
            </div>
            <h6 class="fs-12 fw-normal mb-1">{{ Auth::user()->first_name }} {{ Auth::user()->last_name }}</h6>
            <p class="fs-10">{{ ucfirst(Auth::user()->role ?? 'User') }}</p>
        </div>
        <div class="sidebar-nav mb-3">
            <ul class="nav nav-tabs nav-tabs-solid nav-tabs-rounded nav-justified bg-transparent" role="tablist">
                <li class="nav-item"><a class="nav-link active border-0" href="#">Menu</a></li>
                <li class="nav-item"><a class="nav-link border-0" href="#">Chats</a></li>
                <li class="nav-item"><a class="nav-link border-0" href="#">Inbox</a></li>
            </ul>
        </div>
    </div>
    
    <div class="sidebar-header p-3 pb-0 pt-2">
        <div class="text-center rounded bg-light p-2 mb-4 sidebar-profile d-flex align-items-center">
            <div class="avatar avatar-md online">
                <img src="{{ asset('assets/img/profiles/avatar-02.jpg') }}" alt="Img" class="img-fluid rounded-circle">
            </div>
            <div class="text-start sidebar-profile-info ms-2">
                <h6 class="fs-12 fw-normal mb-1">{{ Auth::user()->first_name }} {{ Auth::user()->last_name }}</h6>
                <p class="fs-10">{{ ucfirst(Auth::user()->role ?? 'User') }}</p>
            </div>
        </div>
        
        <div class="input-group input-group-flat d-inline-flex mb-4">
            <span class="input-icon-addon">
                <i class="ti ti-search"></i>
            </span>
            <input type="text" class="form-control" placeholder="Search in HRMS">
            <span class="input-group-text">
                <kbd>CTRL + / </kbd>
            </span>
        </div>
        
        <div class="d-flex align-items-center justify-content-between menu-item mb-3">
            <div class="me-3">
                <a href="#" class="btn btn-menubar">
                    <i class="ti ti-layout-grid-remove"></i>
                </a>
            </div>
            <div class="me-3">
                <a href="#" class="btn btn-menubar position-relative">
                    <i class="ti ti-brand-hipchat"></i>
                    <span class="badge bg-info rounded-pill d-flex align-items-center justify-content-center header-badge">5</span>
                </a>
            </div>
            <div class="me-3 notification-item">
                <a href="#" class="btn btn-menubar position-relative me-1">
                    <i class="ti ti-bell"></i>
                    <span class="notification-status-dot"></span>
                </a>
            </div>
            <div class="me-0">
                <a href="#" class="btn btn-menubar">
                    <i class="ti ti-message"></i>
                </a>
            </div>
        </div>
    </div>
    
    <div class="sidebar-inner slimscroll">
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
                        <li><a href="#">BVN Search</a></li>
                        <li><a href="#">Approval Request</a></li>
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
                        <li><a href="#">NIN Validation</a></li>
                        <li><a href="#">NIN Self Service</a></li>
                        <li><a href="#">NIN IFE</a></li>
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
                    <a href="#">
                        <i class="ti ti-message"></i><span>Support</span>
                    </a>
                </li>
                
                <li>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <a href="{{ route('logout') }}" onclick="event.preventDefault(); this.closest('form').submit();">
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