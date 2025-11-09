<?php
/**
 * Created by PhpStorm.
 * User: Abdul Rohim
 * Date: 8/13/2023
 * Time: 11:04 PM
 */
?>
<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="" class="brand-link">
        <img src="{{ asset('assets') }}/dist/img/AdminLogo.png" alt="Mas Cargo Express"
            class="brand-image img-circle elevation-3" style="opacity: .8">
        <span class="brand-text font-weight-light">My Invoice</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user panel (optional) -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
                <img src="{{asset('assets')}}/dist/img/users.jpeg" class="img-circle elevation-2" alt="User Image"> 
            </div>
            <div class="info">
                <a href="#" class="d-block">{{ ucfirst(Auth()->user()->full_name) }}</a>
            </div>
        </div>

        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column nav-collapse-hide-child" data-widget="treeview"
                role="menu" data-accordion="false">
                @if (in_array(Auth()->user()->role, ['admin']))
                    <li class="nav-item">
                        <a href="{{ route('dashboard') }}"
                            class="nav-link {{ request()->is('dashboard') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-tachometer-alt"></i>
                            <p>Dashboard</p>
                        </a>
                    </li>
                    <!-- <li class="nav-item">
                        <a href="{{ route('dashboard-vendor') }}"
                            class="nav-link {{ request()->is('dashboard/vendor-invoice') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-tachometer-alt"></i>
                            <p>Dashboard Vendor</p>
                        </a>
                    </li>
                    @if (Auth()->user()->role == 'admin')
                        <li class="nav-item">
                            <a href="{{ route('dashboard-gudang') }}"
                                class="nav-link {{ request()->is('dashboard-gudang') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-tachometer-alt"></i>
                                <p>Dashboard Gudang</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('dashboard-tracking') }}"
                                class="nav-link {{ request()->is('dashboard-tracking') ? 'active' : '' }}">
                                <i class="nav-icon fas fa-tachometer-alt"></i>
                                <p>Dashboard Tracking</p>
                            </a>
                        </li>
                    @endif -->
                    <!-- <li class="nav-item">
                        <a href="{{ route('asset') }}" class="nav-link {{ request()->is('asset') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-credit-card"></i>
                            <p>Aset</p>
                        </a>
                    </li>
                    <li
                        class="nav-item {{ request()->is('manage/office-spending') || request()->is('manage/vendor-spending') ? 'menu-open' : '' }}">
                        <a href="#"
                            class="nav-link {{ request()->is('manage/office-spending') || request()->is('manage/vendor-spending') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-outdent"></i>
                            <p>
                                Pengeluaran
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{ route('office-spending.index') }}"
                                    class="nav-link {{ request()->is('manage/office-spending') ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Pengeluaran Office</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('vendor-spending.index') }}"
                                    class="nav-link {{ request()->is('manage/vendor-spending') ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Pengeluaran Vendor</p>
                                </a>
                            </li>
                        </ul>
                    </li> -->
                    <!-- <li class="nav-item">
                        <a href=""
                            class="nav-link">
                            <i class="nav-icon fas fa-shipping-fast"></i>
                            <p>Pre-Order</p>
                        </a>
                    </li> -->
                    <li class="nav-item">
                        <a href="{{ route('invoices.index') }}"
                            class="nav-link {{ request()->is('invoices') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-file"></i>
                            <p>Invoice</p>
                        </a>
                    </li>
                    <li class="nav-item {{ request()->is('manage/office-spending') || request()->is('manage/vendor-spending') ? 'menu-open' : '' }}">
                        <a href="#"
                            class="nav-link {{ request()->is('manage/office-spending') || request()->is('manage/vendor-spending') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-outdent"></i>
                            <p>
                                Master
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{ route('master.customers') }}"
                                    class="nav-link {{ request()->is('manage/master/customers') ? 'active' : '' }}">
                                    <i class="fa fa-user nav-icon"></i>
                                    <p>Customers</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('master.products.index') }}"
                                    class="nav-link {{ request()->is('manage/master/products') ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Product</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('settings.index') }}"
                                    class="nav-link {{ request()->is('settings') ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Mark Up</p>
                                </a>
                            </li>
                        </ul>
                    </li>
                    <!-- <li class="nav-item">
                        <a href="{{ route('manifest-barang') }}"
                            class="nav-link {{ request()->is('manifest-barang') ? 'active' : '' }}">
                            <i class="nav-icon fa fa-file-archive"></i>
                            <p>Data Manifest</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('outbond.index') }}"
                            class="nav-link {{ request()->is('outbond') ? 'active' : '' }}">
                            <i class="nav-icon fa fa-file-archive"></i>
                            <p>Data Manifest Outbond</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('invoice.index') }}"
                            class="nav-link {{ request()->is('vendor/invoice') ? 'active' : '' }}">
                            <i class="nav-icon fa fa-file"></i>
                            <p>Invoice Vendor</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('payment-history.index') }}"
                            class="nav-link {{ request()->is('vendor/payment-history') ? 'active' : '' }}">
                            <i class="nav-icon fa fa-file"></i>
                            <p>PH Invoice</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('master.vendors.index') }}"
                            class="nav-link {{ request()->is('manage/master/vendors') ? 'active' : '' }}">
                            <i class="fa fa-building nav-icon"></i>
                            <p>Master Vendors</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('master.bank.index') }}"
                            class="nav-link {{ request()->is('manage/master/bank') ? 'active' : '' }}">
                            <i class="fa fa-dollar-sign nav-icon"></i>
                            <p>Master Bank</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('master.destination.index') }}"
                            class="nav-link {{ request()->is('manage/master/destination') ? 'active' : '' }}">
                            <i class="fa fa-dollar-sign nav-icon"></i>
                            <p>Master Tujuan</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('master.customers') }}"
                            class="nav-link {{ request()->is('manage/master/customers') ? 'active' : '' }}">
                            <i class="fa fa-database nav-icon"></i>
                            <p>Database Customers</p>
                        </a>
                    </li> -->
                @endif
                @if (in_array(Auth()->user()->role, ['superadmin']))
                    <li class="nav-item">
                        <a href="{{ route('user-management.index') }}"
                            class="nav-link {{ request()->is('user-management') ? 'active' : '' }}">
                            <i class="fa fa-user nav-icon"></i>
                            <p>Management User</p>
                        </a>
                    </li>
                @endif
                <!-- @if (Auth()->user()->role == 'admin')
                    <li class="nav-item">
                        <a href="{{ route('report-profit.index') }}"
                            class="nav-link {{ request()->is('report-profit') ? 'active' : '' }}">
                            <i class="fa fa-money-bill nav-icon"></i>
                            <p>Laporan Profit</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('user-complain') }}"
                            class="nav-link {{ request()->is('user-complain') ? 'active' : '' }}">
                            <i class="fa fa-user nav-icon"></i>
                            <p>Laporan Keluhan</p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="{{ route('tracking.index') }}"
                            class="nav-link {{ request()->is('tracking') ? 'active' : '' }}">
                            <i class="nav-icon fa fa-file"></i>
                            <p>Data Pengiriman</p>
                        </a>
                    </li>
                @endif -->
            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>
