<style>
    #sidebar-menu > ul > li > a > span {
    font-size: 13px;
    }
</style>
<!-- ========== Left Sidebar Start ========== -->
<div class="left side-menu">
    <div class="slimscroll-menu" id="remove-scroll">
        <!--- Side Menu -->
        <div id="sidebar-menu">
            <!-- Left Menu Start -->
            <ul class="metismenu" id="side-menu">
                <li class="menu-title"></li>
                @can('Dashboard')
                <li>
                    <a href="{{ route('admin.dashboard') }}" class="">
                        <i class="flaticon-dashboard"></i><span> {{ __('custom.dashboard') }} </span>
                    </a>
                </li>
                @endcan
                <li class="menu-title"></li>
                <!-- <li class="menu-title">{{ __('custom.components') }}</li> -->
                @canany('Order')
                <li class="{{ request()->is('admin/services/*') ? 'mm-active' : '' }}">
                    <a href="{{route('admin.services.index')}}" class=""><i class="mdi mdi-book"></i><span> {{
                            __('custom.list_of_services') }}
                            <!-- <span class="float-right menu-arrow"> -->
                                <!-- <svg class="svg-icon iq-arrow-right arrow-active" width="20" height="20"
                                    xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round">
                                    <polyline points="10 15 15 20 20 15"></polyline>
                                    <path d="M4 4h7a4 4 0 0 1 4 4v12"></path>
                                </svg> -->
                            </span>
                        </span>
                    </a>
                </li>
                @endcanany
                @canany('Order')
                <li class="{{ request()->is('admin/merchant/*') ? 'mm-active' : '' }}">
                    <a href="{{route('admin.merchant.index')}}" class=""><i class="mdi mdi-book"></i><span> {{
                            __('Merchant') }}
                            <!-- <span class="float-right menu-arrow"> -->
                                <!-- <svg class="svg-icon iq-arrow-right arrow-active" width="20" height="20"
                                    xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round">
                                    <polyline points="10 15 15 20 20 15"></polyline>
                                    <path d="M4 4h7a4 4 0 0 1 4 4v12"></path>
                                </svg> -->
                            </span>
                        </span>
                    </a>
                </li>
                @endcanany
                @canany('Category')
                <li class="{{ request()->is('admin/category/*') ? 'mm-active' : '' }}">
                    <a href="{{route('admin.category.index')}}" class=""><i class="mdi mdi-book"></i><span> {{
                            __('Category') }}
                            <!-- <span class="float-right menu-arrow"> -->
                                <!-- <svg class="svg-icon iq-arrow-right arrow-active" width="20" height="20"
                                    xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round">
                                    <polyline points="10 15 15 20 20 15"></polyline>
                                    <path d="M4 4h7a4 4 0 0 1 4 4v12"></path>
                                </svg> -->
                            </span>
                        </span>
                    </a>
                </li>
                @endcanany
                @canany('Order-Management')
                <li class="{{ request()->is('admin/medicine') ? 'mm-active' : '' }}">
                    <a href="#" class=""><i class="mdi mdi-book"></i><span> {{
                            __('Medicine') }}
                        
                            <span class="float-right menu-arrow">
                                <svg class="svg-icon iq-arrow-right arrow-active" width="20" height="20"
                                    xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round">
                                    <polyline points="10 15 15 20 20 15"></polyline>
                                    <path d="M4 4h7a4 4 0 0 1 4 4v12"></path>
                                </svg>
                            </span>
                        </span>
                    </a>
                    <ul class="submenu">
                        @can('User')
                        <li class="{{ request()->is('admin/medicine/*') ? 'mm-active' : '' }}"><a
                                href="{{route('admin.medicine.index')}}">{{ __('Medicine Entry') }}</a>
                        </li>
                        @endcan
                        @can('User')
                        <li class="{{ request()->is('admin/group/*') ? 'mm-active' : '' }}"><a
                                href="{{route('admin.group.index')}}">{{ __('Group') }}</a>
                        </li>
                        @endcan
                        @can('Role')
                        <li class="{{ request()->is('admin/brand/*') ? 'mm-active' : '' }}"><a
                                href="{{route('admin.brand.index')}}">{{ __('Brand') }}</a></li>
                        @endcan
                        @can('Role')
                        <li class="{{ request()->is('admin/type/*') ? 'mm-active' : '' }}"><a
                                href="{{route('admin.type.index')}}">{{ __('Type') }}</a></li>
                        @endcan
                        @can('User')
                        <li class="{{ request()->is('admin/suplier/*') ? 'mm-active' : '' }}"><a
                                href="{{route('admin.suplier.index')}}">{{ __('Suplier') }}</a>
                        </li>
                        @endcan
                    </ul>
                </li>
                @endcanany

                @canany('Order-Management')
                <li class="{{ request()->is('admin/order/due/list') ? 'mm-active' : '' }}">
                    <a href="#" class=""><i class="mdi mdi-book"></i><span> {{
                            __('Staff') }}
                        
                            <span class="float-right menu-arrow">
                                <svg class="svg-icon iq-arrow-right arrow-active" width="20" height="20"
                                    xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round">
                                    <polyline points="10 15 15 20 20 15"></polyline>
                                    <path d="M4 4h7a4 4 0 0 1 4 4v12"></path>
                                </svg>
                            </span>
                        </span>
                    </a>
                    <ul class="submenu">
                        @can('User')
                        <li class="{{ request()->is('admin/staff/*') ? 'mm-active' : '' }}"><a
                                href="{{route('admin.staff.index')}}">{{ __('custom.list') }}</a>
                        </li>
                        @endcan
                        @can('Role')
                        <li class="{{ request()->is('admin/staff/create/*') ? 'mm-active' : '' }}"><a
                                href="{{route('admin.staff.create')}}">{{ __('custom.add') }}</a></li>
                        @endcan
                        @can('Role')
                        <li class="{{ request()->is('admin//staff/list/staff-salary/*') ? 'mm-active' : '' }}"><a
                                href="{{route('admin.staff.salary.list')}}">{{ __('Staff Salary') }}</a></li>
                        @endcan
                    </ul>
                </li>
                @endcanany

                @can('Customer')
                <li class="{{ request()->is('admin/customers') ? 'mm-active' : '' }}">
                    <a href="{{ route('admin.customers.index') }}" class=""><i class="mdi mdi-book"></i><span> {{
                            __('custom.customers') }}
                        </span>
                    </a>
                </li>
                @endcanany

                @canany('Order-Management')
                <li class="{{ request()->is('admin/order/due/list') ? 'mm-active' : '' }}">
                    <a href="#" class=""><i class="mdi mdi-book"></i><span> {{
                            __('Accounts') }}
                        
                            <span class="float-right menu-arrow">
                                <svg class="svg-icon iq-arrow-right arrow-active" width="20" height="20"
                                    xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round">
                                    <polyline points="10 15 15 20 20 15"></polyline>
                                    <path d="M4 4h7a4 4 0 0 1 4 4v12"></path>
                                </svg>
                            </span>
                        </span>
                    </a>
                    <ul class="submenu">
                        @can('User')
                        <li class="{{ request()->is('admin/account/*') ? 'mm-active' : '' }}"><a
                                href="{{route('admin.account.credited')}}">{{ __('Total Credit') }}</a>
                        </li>
                        @endcan
                        @can('Role')
                        <li class="{{ request()->is('admin/offline-record/create/*') ? 'mm-active' : '' }}"><a
                                href="{{route('admin.account.dedited')}}">{{ __('Debit by Staff&CEO') }}</a></li>
                        @endcan
                        @can('Role')
                        <li class="{{ request()->is('admin/account/*') ? 'mm-active' : '' }}"><a
                                href="{{route('admin.account.balance')}}">{{ __('Balance') }}</a></li>
                        @endcan
                    </ul>
                </li>
                @endcanany

                @canany('Order-Management')
                <li class="{{ request()->is('admin/order/due/list') ? 'mm-active' : '' }}">
                    <a href="#" class=""><i class="mdi mdi-book"></i><span> {{
                            __('Reports') }}
                        
                            <span class="float-right menu-arrow">
                                <svg class="svg-icon iq-arrow-right arrow-active" width="20" height="20"
                                    xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round">
                                    <polyline points="10 15 15 20 20 15"></polyline>
                                    <path d="M4 4h7a4 4 0 0 1 4 4v12"></path>
                                </svg>
                            </span>
                        </span>
                    </a>
                    <ul class="submenu">
                        @can('User')
                        <li class="{{ request()->is('admin/reports/*') ? 'mm-active' : '' }}"><a
                                href="{{route('admin.reports.daywise')}}">{{ __('Day Wise') }}</a>
                        </li>
                        @endcan
                        @can('Role')
                        <li class="{{ request()->is('admin/reports/monthwise/') ? 'mm-active' : '' }}"><a
                                href="{{route('admin.reports.monthwise')}}">{{ __('Monthly') }}</a></li>
                        @endcan
                    </ul>
                </li>
                @endcanany
                
                @canany(['User', 'Role'])
                <li>
                    <a href="#" class=""><i class="flaticon-working"></i><span> {{
                            __('custom.administration') }}
                            <span class="float-right menu-arrow">
                                <svg class="svg-icon iq-arrow-right arrow-active" width="20" height="20"
                                    xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round">
                                    <polyline points="10 15 15 20 20 15"></polyline>
                                    <path d="M4 4h7a4 4 0 0 1 4 4v12"></path>
                                </svg>
                            </span>
                        </span>
                    </a>
                    <ul class="submenu">
                        @can('User')
                        <li class="{{ request()->is('admin/users/*') ? 'mm-active' : '' }}"><a
                                href="{{ route('admin.users.index') }}">{{ __('custom.users') }}</a>
                        </li>
                        @endcan
                        @can('Role')
                        <li class="{{ request()->is('admin/roles/*') ? 'mm-active' : '' }}"><a
                                href="{{ route('admin.roles.index') }}">{{ __('custom.roles') }}</a></li>
                        @endcan
                        @can('Role')
                        <li class="{{ request()->is('admin/permissions/*') ? 'mm-active' : '' }}"><a
                                href="{{ route('admin.permissions.index') }}">Permissions</a></li>
                        @endcan
                    </ul>
                </li>
                @endcanany

                @canany(['Weight Unit', 'Measurement Unti', 'Attribute'])

                @endcanany

                @can('Settings')
                <li>
                    <a href="{{ route('admin.system-settings.edit') }}" class="">
                        <i class="ti-settings"></i><span> {{ __('custom.settings') }} </span>
                    </a>
                </li>
                @endcan

            </ul>
        </div>
        <!-- Sidebar -->
        <div class="clearfix"></div>

    </div>
    <!-- Sidebar -left -->

</div>
<!-- Left Sidebar End -->
