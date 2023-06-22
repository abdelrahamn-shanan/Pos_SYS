<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="index3.html" class="brand-link">
        <img src="{{asset('assets/admin/dist/img/AdminLTELogo.png')}}" alt="AdminLTE Logo"
            class="brand-image img-circle elevation-3" style="opacity: .8">
        <span class="brand-text font-weight-light">Admin</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user panel (optional) -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
                <img src="{{asset('assets/admin/dist/img/user2-160x160.jpg')}}" class="img-circle elevation-2"
                    alt="User Image">
            </div>
            <div class="info">
                <a href="#" class="d-block">{{auth() -> user() -> name}}</a>
            </div>
        </div>

        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->

                <li
                    class="nav-item has-treeview {{(request()-> is('admin/Panel_Settings*')||request()-> is('admin/Treasuries*') )?'menu-open' : '' }}">
                    <a href="#"
                        class="nav-link  {{(request()-> is('admin/Panel_Settings*')||request()-> is('admin/Treasuries*') )?'active' : '' }}">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>
                            الضبط العام
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">

                        <li class="nav-item">
                            <a href="{{route('settings')}}"
                                class="nav-link {{(request()-> is('admin/Panel_Settings*'))?'active' : '' }}">
                                <p>الضبط
                                    العام</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{route('Treasureies_index')}}"
                                class="nav-link {{(request()-> is('admin/Treasuries*'))?'active' : '' }}">
                                <p> بيانات
                                    الخزن</p>
                            </a>
                        </li>
                    </ul>

                </li>

                <li
                    class="nav-item has-treeview  {{(request()-> is('admin/Accounts_types*') || request()-> is('admin/Accounts*')|| request()-> is('admin/Customer*') || request()-> is('admin/SuppliersCategories*') || request()-> is('admin/Suppliers*')|| request()-> is('admin/collect_transaction*') || request()-> is('admin/Exchange_transaction*')) ?'menu-open' : '' }} ">
                    <a href="#"
                        class="nav-link {{(request()-> is('admin/Accounts_types*') || request()-> is('admin/Accounts*') || request()-> is('admin/Customer*') || request()-> is('admin/SuppliersCategories*') || request()-> is('admin/Suppliers*') || request()-> is('admin/collect_transaction*') || request()-> is('admin/Exchange_transaction*')) ?'active' : '' }}">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>
                            الحسابات
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">

                        <li class="nav-item">

                            <a href="{{route('admin.accountTypes.index')}}"
                                class="nav-link {{(request()-> is('admin/Accounts_types*'))?'active' : '' }}">
                                <p>أنواع الحسابات المالية</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{route('admin.accounts.index')}}"
                                class="nav-link {{(request()-> is('admin/Accounts*') and !request()->is('admin/Accounts_types*'))?'active' : '' }}">
                                <p> الحسابات المالية</p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="{{route('Customer.index')}}"
                                class="nav-link {{(request()-> is('admin/Customer*'))?'active' : '' }}">
                                <p> حسابات العملاء</p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="{{route('SuppliersCategories.index')}}"
                                class="nav-link {{(request()-> is('admin/SuppliersCategories*'))?'active' : '' }}">
                                <p> فئات الموردين</p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="{{route('Suppliers.index')}}"
                                class="nav-link {{(request()-> is('admin/Suppliers*') and !request()->is('admin/SuppliersCategories*'))?'active' : '' }}">
                                <p> حسابات الموردين</p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="{{route('collect_transaction.index')}}"
                                class="nav-link {{(request()-> is('admin/collect_transaction*')) ?'active' : '' }}">
                                <p> شاشة تحصيل النقدية</p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="{{route('Exchange_transaction.index')}}"
                                class="nav-link {{(request()-> is('admin/Exchange_transaction*')) ?'active' : '' }}">
                                <p> شاشة صرف النقدية</p>
                            </a>
                        </li>

                    </ul>

                </li>

                <li
                    class="nav-item has-treeview {{(request()-> is('admin/Sales_Materials_Types*')||request()-> is('admin/Stores*') ||request()-> is('admin/Measures_Units*') ||request()-> is('admin/ItemCard_Categories*') ||request()-> is('admin/ItemCard*') )?'menu-open' : '' }}">
                    <a href="#"
                        class="nav-link {{(request()-> is('admin/Sales_Materials_Types*')||request()-> is('admin/Stores*') ||request()-> is('admin/Measures_Units*') ||request()-> is('admin/ItemCard_Categories*') ||request()-> is('admin/ItemCard*') )?'active' : '' }}">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>
                            ضبط المخازن
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{route('SalesMaterialsTypesindex')}}"
                                class="nav-link {{(request()-> is('admin/Sales_Materials_Types*'))?'active' : '' }}">

                                <p>
                                    بيانات فئات الفواتير
                                </p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="{{route('admin.stores.index')}}"
                                class="nav-link {{(request()-> is('admin/Stores*'))?'active' : '' }}">

                                <p>
                                    بيانات المخازن
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{route('admin.uoms.index')}}"
                                class="nav-link {{(request()-> is('admin/Measures_Units*'))?'active' : '' }}">

                                <p>
                                    بيانات الوحدات
                                </p>
                            </a>
                        </li>


                        <li class="nav-item">
                            <a href="{{route('ItemCard_Categories.index')}}"
                                class="nav-link {{(request()-> is('admin/ItemCard_Categories*'))?'active' : '' }}">

                                <p>
                                    فئات الأصناف
                                </p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="{{route('ItemCard.index')}}"
                                class="nav-link {{(request()-> is('admin/ItemCard*') and !request()->is('admin/ItemCard_Categories*') )?'active' : '' }}">

                                <p>
                                    الأصناف
                                </p>
                            </a>
                        </li>


                    </ul>

                </li>

                <li
                    class="nav-item has-treeview {{(request()-> is('admin/Supplier_with_orders*') )?'menu-open' : '' }}">

                    <a href="#" class="nav-link  {{(request()-> is('admin/Supplier_with_orders*'))?'active' : '' }}">

                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>
                            حركات مخزنية
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">

                        <li class="nav-item">
                            <a href="{{route('Supplier_with_orders.index')}}"
                                class="nav-link {{(request()-> is('admin/Supplier_with_orders*') and !request()->is('admin/Suppliers*'))?'active' : '' }}">

                                <p>
                                    فواتير المشتريات
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">

                        </li>
                    </ul>

                </li>

                <li class="nav-item has-treeview {{(request()-> is('admin/Sales*') )?'menu-open' : '' }}">

                    <a href="#" class="nav-link  {{(request()-> is('admin/Sales*'))?'active' : '' }}">

                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>
                            المبيعات
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">

                        <li class="nav-item">
                            <a href="{{route('Sales.index')}}"
                                class="nav-link {{(request()-> is('admin/Sales*'))?'active' : '' }}">

                                <p>
                                    فواتير المبيعات
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">

                        </li>
                    </ul>

                </li>


                <li class="nav-item has-treeview ">
                    <a href="#" class="nav-link ">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>
                            خدمات داخلية وخارجية
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">

                        <li class="nav-item">

                            </a>
                        </li>
                        <li class="nav-item">

                        </li>
                    </ul>

                </li>

                <li class="nav-item has-treeview {{(request()-> is('admin/shifts*') )?'menu-open' : '' }}">

                    <a href="#" class="nav-link  {{(request()-> is('admin/shifts*'))?'active' : '' }}">

                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>
                            حركة شفت الخزينة
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">

                        <li class="nav-item">
                            <a href="{{route('shifts.index')}}"
                                class="nav-link {{(request()-> is('admin/shifts*') ) ? 'active' : '' }}">

                                <p>
                                    شفتات الخزن
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">

                        </li>
                    </ul>

                </li>


                <li class="nav-item has-treeview {{(request()-> is('admin/Admin_accounts*') )?'menu-open' : '' }}">

                    <a href="#" class="nav-link  {{(request()-> is('admin/Admin_accounts*'))?'active' : '' }}">

                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>
                            الصلاحيات
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">

                        <li class="nav-item">
                            <a href="{{route('Admin_accounts.index')}}"
                                class="nav-link {{(request()-> is('admin/Admin_accounts*') ) ? 'active' : '' }}">

                                <p>
                                    المستخدمين
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">

                        </li>
                    </ul>

                </li>
                <li class="nav-item has-treeview ">
                    <a href="#" class="nav-link ">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>
                            التقارير
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">

                        <li class="nav-item">

                            </a>
                        </li>
                        <li class="nav-item">

                        </li>
                    </ul>

                </li>

                <li class="nav-item has-treeview ">
                    <a href="#" class="nav-link ">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>
                            المراقبة والدعم الفني
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">

                        <li class="nav-item">

                            </a>
                        </li>
                        <li class="nav-item">

                        </li>
                    </ul>

                </li>




            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>