<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">

    <title>Electronics Configurations App</title>

    <!-- Custom fonts for this template-->
    <link href="{{ asset('conquer/vendor/fontawesome-free/css/all.min.css') }}" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="{{ asset('conquer/css/sb-admin-2.min.css') }}" rel="stylesheet">

    @yield('javascript')
</head>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">
        @php
            // Get all menu items that the user has access to
            use App\Http\Controllers\MenuController;
            
            // Check if the current user is an active employee
            $isActiveEmployee = false;
            if (Auth::check()) {
                $user = Auth::user();
                // Check employee status directly from the users table or from the employees table
                $isActiveEmployee = $user->status_active == 1;
            }
            
            // Only get authorized menus if the employee is active
            $menuItems = $isActiveEmployee ? MenuController::getAuthorizedMenus() : collect([]);
            $hasConfigAccess = $isActiveEmployee ? MenuController::hasConfigAccess() : false;

            // Define a helper function to check if current route is active
            function isRouteActive($routeName)
            {
                return request()->routeIs($routeName) ? 'active' : '';
            }
        @endphp
        <!-- Sidebar -->
        <ul style="background: rgb(70, 5, 5); padding-top: 20px;"
            class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

            <!-- Sidebar - Brand -->
            <a class="sidebar-brand d-flex align-items-center justify-content-center" href="/">
                <div class="sidebar-brand-icon rotate-n-15">
                    <i class="fas fa-laugh-wink"></i>
                </div>
                <div class="sidebar-brand-text mx-3">Electronics Configurations App</div>
            </a>

            @if($isActiveEmployee)
                <!-- Display POS Cashier option -->
                @if ($menuItems->contains('id', 1))
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('pos.index') }}">
                            <i class="fas fa-fw fa-cart-plus"></i>
                            <span>POS Cashier</span>
                        </a>
                    </li>
                @endif

                <!-- Divider -->
                <hr class="sidebar-divider my-0">

                <!-- Display Sales Module options -->
                @if ($menuItems->contains('id', 2))
                    <li class="nav-item">
                        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#salesModuleMenu"
                            aria-expanded="false" aria-controls="salesModuleMenu">
                            <i class="fas fa-fw fa-cash-register"></i>
                            <span>Sales Module</span>
                        </a>
                        <div id="salesModuleMenu" class="collapse">
                            <div class="bg-white py-2 collapse-inner rounded">
                                <a class="collapse-item" href="{{ url('sales') }}">
                                    <i class="fas fa-fw fa-file-invoice-dollar"></i>
                                    <span>Transaction Sales</span>
                                </a>

                                <a class="collapse-item" href="{{ url('salesShipping') }}">
                                    <i class="fas fa-fw fa-truck"></i>
                                    <span>Shipping</span>
                                </a>

                                @if($hasConfigAccess)
                                <a class="collapse-item" href="{{ url('salesKonfigurasi') }}">
                                    <i class="fas fa-fw fa-cogs"></i>
                                    <span>Sales Configurations</span>
                                </a>
                                @endif

                                <a class="collapse-item" href="{{ url('salesRetur') }}">
                                    <i class="fas fa-fw fa-undo-alt"></i>
                                    <span>Sales Return</span>
                                </a>
                            </div>
                        </div>
                    </li>
                @endif

                <!-- Display Purchase Module options -->
                @if ($menuItems->contains('id', 3))
                    <li class="nav-item">
                        <a class="nav-link collapsed" href="#" data-toggle="collapse"
                            data-target="#purchaseModuleMenu" aria-expanded="false" aria-controls="purchaseModuleMenu">
                            <i class="fas fa-fw fa-shopping-cart"></i>
                            <span>Purchase Module</span>
                        </a>

                        <div id="purchaseModuleMenu" class="collapse">
                            <div class="bg-white py-2 collapse-inner rounded">
                                <a class="collapse-item" href="{{ url('purchase') }}">
                                    <i class="fas fa-fw fa-file-invoice"></i>
                                    <span>Transaction Purchase</span>
                                </a>

                                <a class="collapse-item" href="{{ url('purchaseShipping') }}">
                                    <i class="fas fa-fw fa-truck"></i>
                                    <span>Receive</span>
                                </a>

                                @if($hasConfigAccess)
                                <a class="collapse-item" href="{{ url('purchaseKonfigurasi') }}">
                                    <i class="fas fa-fw fa-cogs"></i>
                                    <span>Purchase Configuration</span>
                                </a>
                                @endif

                                <a class="collapse-item" href="{{ url('purchaseRetur') }}">
                                    <i class="fas fa-fw fa-undo-alt"></i>
                                    <span>Purchase Return</span>
                                </a>
                            </div>
                        </div>
                    </li>
                @endif

                <!-- Display Inventory Module options -->
                @if ($menuItems->contains('id', 4))
                    <li class="nav-item">
                        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#inventoriesMenu"
                            aria-expanded="false" aria-controls="inventoriesMenu">
                            <i class="fas fa-fw fa-warehouse"></i>
                            <span>Inventory Module</span>
                        </a>
                        <div id="inventoriesMenu" class="collapse">
                            <div class="bg-white py-2 collapse-inner rounded">
                                <a class="collapse-item" href="{{ url('product') }}">
                                    <i class="fas fa-fw fa-box-open"></i>
                                    <span>Product Management</span>
                                </a>

                                @if($hasConfigAccess)
                                <a class="collapse-item" href="{{ url('profitLoss') }}">
                                    <i class="fas fa-fw fa-chart-line"></i>
                                    <span>Profit Loss</span>
                                </a>
                                @endif

                                <a class="collapse-item" href="{{ route('categories.index') }}">
                                    <i class="fas fa-fw fa-tags"></i>
                                    <span>Category</span>
                                </a>
                                <a class="collapse-item" href="{{ route('warehouse.index') }}">
                                    <i class="fas fa-fw fa-warehouse"></i>
                                    <span>Warehouse</span>
                                </a>
                                <a class="collapse-item" href="{{ route('suppliers.index') }}">
                                    <i class="fas fa-fw fa-truck"></i>
                                    <span>Suppliers</span>
                                </a>

                                @if($hasConfigAccess)
                                <a class="collapse-item" href="{{ route('warehouse.konfigurasi') }}">
                                    <i class="fas fa-fw fa-cogs"></i>
                                    <span>Inventory Configurations</span>
                                </a>
                                @endif
                            </div>
                        </div>
                    </li>
                @endif

                <!-- Display Settings options -->
                @if ($menuItems->contains('id', 5))
                    <li class="nav-item">
                        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#settingsMenu"
                            aria-expanded="false" aria-controls="settingsMenu">
                            <i class="fas fa-fw fa-cog"></i>
                            <span>Settings</span>
                        </a>
                        <div id="settingsMenu" class="collapse">
                            <div class="bg-white py-2 collapse-inner rounded">
                                <a class="collapse-item" href="{{ route('customer.index') }}">
                                    <i class="fas fa-fw fa-user"></i>
                                    <span>Customer's Data</span>
                                </a>
                                <a class="collapse-item" href="{{ route('employe.index') }}">
                                    <i class="fas fa-fw fa-users"></i>
                                    <span>Employee's Data</span>
                                </a>
                                <a class="collapse-item" href="{{ route('companies.index') }}">
                                    <i class="fas fa-fw fa-building"></i>
                                    <span>Companies</span>
                                </a>
                            </div>
                        </div>
                    </li>
                @endif
            @else
                <!-- Show a message for inactive employees -->
                <div class="text-center text-white p-3">
                    <i class="fas fa-exclamation-triangle mb-3" style="font-size: 2rem;"></i>
                    <p>Your account is currently inactive. Please contact an administrator.</p>
                </div>
            @endif

            <!-- Divider -->
            <hr class="sidebar-divider">

            <!-- Display user role information -->
            <div class="text-center mb-2 text-white">
                <small>Logged in as: {{ Auth::user()->username ?? 'Guest' }}</small><br>
                <small>Role:
                    {{ DB::table('roles')->where('id', Auth::user()->roles_id ?? 0)->value('name') ?? 'Unknown' }}</small>
                @if(Auth::check())
                    <br>
                    <small>Status: {{ Auth::user()->status_active == 1 ? 'Active' : 'Inactive' }}</small>
                @endif
            </div>

            <div class="text-center mb-2">
                <form action="{{ route('logout') }}" method="post">
                    @csrf
                    <button type="submit" class="btn btn-danger">Logout</button>
                </form>
            </div>

            <!-- Sidebar Toggler (Sidebar) -->
            <div class="text-center d-none d-md-inline">
                <button class="rounded-circle border-0" id="sidebarToggle"></button>
            </div>
        </ul>
        <!-- End of Sidebar -->

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content" style="padding-top: 20px;">

                <!-- Begin Page Content -->
                <div class="container-fluid">
                    @if(!$isActiveEmployee && Auth::check())
                        <div class="alert alert-danger text-center">
                            <h4><i class="fas fa-exclamation-circle"></i> Account Inactive</h4>
                            <p>Your account is currently inactive. You have limited access to the system.</p>
                            <p>Please contact an administrator for assistance.</p>
                        </div>
                    @endif
                    
                    @yield('content')
                </div>
                <!-- End of Main Content -->

            </div>
            <!-- End of Content Wrapper -->

        </div>
        <!-- End of Page Wrapper -->

        <!-- Scroll to Top Button-->
        <a class="scroll-to-top rounded" href="#page-top">
            <i class="fas fa-angle-up"></i>
        </a>

        <!-- Bootstrap core JavaScript-->
        <script src="{{ asset('conquer/vendor/jquery/jquery.min.js') }}"></script>
        <script src="{{ asset('conquer/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>

        <!-- Core plugin JavaScript-->
        <script src="{{ asset('conquer/vendor/jquery-easing/jquery.easing.min.js') }}"></script>

        <!-- Custom scripts for all pages-->
        <script src="{{ asset('conquer/js/sb-admin-2.min.js') }}"></script>

        <!-- Page level plugins -->
        <script src="{{ asset('conquer/vendor/chart.js/Chart.min.js') }}"></script>

        <!-- Page level custom scripts -->
        <script src="{{ asset('conquer/js/demo/chart-area-demo.js') }}"></script>
        <script src="{{ asset('conquer/js/demo/chart-pie-demo.js') }}"></script>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>


        @if (session('success'))
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: '{{ session('success') }}',
                        confirmButtonColor: '#3085d6',
                        confirmButtonText: 'OK'
                    });
                });
            </script>
        @endif

        @if (session('error'))
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: '{{ session('error') }}',
                        confirmButtonColor: '#d33',
                        confirmButtonText: 'OK'
                    });
                });
            </script>
        @endif

        @if ($errors->any())
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    let errorMessages = '';
                    @foreach ($errors->all() as $error)
                        errorMessages += '<p>{{ $error }}</p>';
                    @endforeach

                    Swal.fire({
                        icon: 'error',
                        title: 'Validation Errors',
                        html: errorMessages,
                        confirmButtonColor: '#d33',
                        confirmButtonText: 'OK'
                    });
                });
            </script>
        @endif

</body>

</html>