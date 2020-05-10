<!-- Loader -->
<div id="preloader">
    <div id="status">
        <div class="spinner"></div>
    </div>
</div>


<!-- Begin page -->
<div id="wrapper">

    <!-- ========== Left Sidebar Start ========== -->
    <div class="left side-menu">

        <!-- LOGO -->
        <div class="topbar-left">
            <div class="">
                <a href="" class="logo"><img src="{{ URL::asset('assets/images/resources/f.jpg')}}" height="45" alt="logo"></a>
            </div>
        </div>

        <div class="sidebar-inner slimscrollleft">
            <div id="sidebar-menu">

                <ul>


                    <li>
                        <a href="{{ url('/') }}" class="waves-effect"><i class="dripicons-device-desktop"></i><span>{{ __('Dashboard') }} </span></a>
                    </li>

                    {{--<li class="menu-title">SALES & BILLING</li>--}}
                    {{--<li class="has_sub">--}}
                        {{--<a href="javascript:void(0);" class="waves-effect"><i class="dripicons-suitcase"></i><span>Invoice<span--}}
                                        {{--class="pull-right"><i class="mdi mdi-chevron-right"></i></span> </span></a>--}}
                        {{--<ul class="list-unstyled">--}}
                            {{--<li><a href="{{route('createInvoice')}}">Create Invoice</a></li>--}}
                            {{--<li><a href="{{route('invoiceHistory')}}">Invoice History</a></li>--}}
                            {{--<li><a href="{{route('cancelledInvoices')}}">Cancelled Invoices</a></li>--}}
                            {{--<li><a href="{{route('invoiceReturnHistory')}}">Invoice Return History</a></li>--}}
                        {{--</ul>--}}
                    {{--</li>--}}

                    {{--<li class="has_sub">--}}
                        {{--<a href="javascript:void(0);" class="waves-effect"><i class="dripicons-suitcase"></i><span>Quotation<span--}}
                                        {{--class="pull-right"><i class="mdi mdi-chevron-right"></i></span> </span></a>--}}
                        {{--<ul class="list-unstyled">--}}
                            {{--<li><a href="{{route('createQuotation')}}">Create Quotation</a></li>--}}
                            {{--<li><a href="{{route('quotationHistory')}}">Quotation History</a></li>--}}
                        {{--</ul>--}}
                    {{--</li>--}}
                    {{--<li class="has_sub">--}}
                        {{--<a href="javascript:void(0);" class="waves-effect"><i class="dripicons-suitcase"></i><span>Gift Vouchers<span--}}
                                        {{--class="pull-right"><i class="mdi mdi-chevron-right"></i></span> </span></a>--}}
                        {{--<ul class="list-unstyled">--}}
                            {{--<li><a href="{{route('giftVoucher')}}">Gift Vouchers</a></li>--}}
                            {{--<li><a href="{{route('issuedGiftVoucher')}}">Issued Gift Vouchers</a></li>--}}
                            {{--<li><a href="{{route('claimedGiftVoucher')}}">Claimed Gift Vouchers</a></li>--}}
                        {{--</ul>--}}
                    {{--</li>--}}

                    {{--<li class="menu-title">CRM MANAGEMENT</li>--}}
                    {{--<li class="has_sub">--}}
                        {{--<a href="javascript:void(0);" class="waves-effect"><i class="ion-person-stalker"></i><span>Sales Orders <span--}}
                                        {{--class="pull-right"><i class="mdi mdi-chevron-right"></i></span> </span></a>--}}
                        {{--<ul class="list-unstyled">--}}
                            {{--<li><a href="{{route('createSO')}}">Create Sales Order</a></li>--}}
                            {{--<li><a href="{{route('viewSO')}}">Sales Orders History</a></li>--}}
                            {{--<li><a href="{{route('cancelledSo')}}">Cancelled Sales Orders</a></li>--}}

                        {{--</ul>--}}
                    {{--</li>--}}
                    {{--<li class="has_sub">--}}
                        {{--<a href="javascript:void(0);" class="waves-effect"><i class="ion-person-stalker"></i><span>Customers <span--}}
                                        {{--class="pull-right"><i class="mdi mdi-chevron-right"></i></span> </span></a>--}}
                        {{--<ul class="list-unstyled">--}}
                            {{--<li><a href="{{route('addCustomer')}}">Add Customer</a></li>--}}
                            {{--<li><a href="{{route('viewCustomer')}}">View Customers</a></li>--}}
                        {{--</ul>--}}
                    {{--</li>--}}

                    {{--<li class="menu-title">PURCHASING & INVENTORY</li>--}}
                    {{--<li class="has_sub">--}}
                        {{--<a href="javascript:void(0);" class="waves-effect"><i--}}
                                    {{--class="dripicons-suitcase"></i><span>GRN<span--}}
                                        {{--class="pull-right"><i class="mdi mdi-chevron-right"></i></span> </span></a>--}}
                        {{--<ul class="list-unstyled">--}}
                            {{--<li><a href="{{route('grn_management')}}">Add GRN</a></li>--}}
                            {{--<li><a href="{{route('grnSearch')}}">GRN History</a></li>--}}

                        {{--</ul>--}}
                    {{--</li>--}}


                    {{--<li class="has_sub">--}}
                        {{--<a href="javascript:void(0);" class="waves-effect"><i class="dripicons-suitcase"></i><span>Stock<span--}}
                                        {{--class="pull-right"><i class="mdi mdi-chevron-right"></i></span> </span></a>--}}
                        {{--<ul class="list-unstyled">--}}
                            {{--<li><a href="{{route('openingStock')}}">Opening Stock</a></li>--}}
                            {{--<li><a href="{{route('activeStock')}}">Active Stock</a></li>--}}
                            {{--<li><a href="{{route('deactivateStock')}}">Deactivated Stock</a></li>--}}
                            {{--<li><a href="{{route('lowStock')}}">Low Stock</a></li>--}}
                            {{--<li><a href="{{route('maxStock')}}">Max Stock</a></li>--}}
                            {{--<li><a href="{{route('unUsedStock')}}">Unused Stock</a></li>--}}
                            {{--<li><a href="{{route('storeChange')}}">Store Change</a></li>--}}
                            {{--<li><a href="{{route('stores')}}">Stores</a></li>--}}
                            {{--<li><a href="{{route('stockOverview')}}">Stock Overview</a></li>--}}
                            {{--<li><a href="{{route('openingHistory')}}">Opening Stock History</a></li>--}}
                            {{--<li><a href="{{route('binCard')}}">Bin Card</a></li>--}}


                        {{--</ul>--}}
                    {{--</li>--}}

                    {{--<li class="has_sub">--}}
                        {{--<a href="javascript:void(0);" class="waves-effect"><i class="ion-person-stalker"></i><span>Purchase Orders <span--}}
                                        {{--class="pull-right"><i class="mdi mdi-chevron-right"></i></span> </span></a>--}}
                        {{--<ul class="list-unstyled">--}}
                            {{--<li><a href="{{route('createPO')}}">Create PO</a></li>--}}
                            {{--<li><a href="{{route('POHistory')}}">PO History</a></li>--}}
                            {{--<li><a href="{{route('cancelledPo')}}">Cancelled PO</a></li>--}}

                        {{--</ul>--}}
                    {{--</li>--}}
                    {{--<li class="has_sub">--}}
                        {{--<a href="javascript:void(0);" class="waves-effect"><i class="dripicons-suitcase"></i><span>Products<span--}}
                                        {{--class="pull-right"><i class="mdi mdi-chevron-right"></i></span> </span></a>--}}
                        {{--<ul class="list-unstyled">--}}
                            {{--<li><a href="{{route('addProduct')}}">Add Product</a></li>--}}
                            {{--<li><a href="products">View Products</a></li>--}}
                            {{--<li><a href="{{route('category')}}">Categories</a></li>--}}
                            {{--<li><a href="{{route('brands')}}">Brands</a></li>--}}
                            {{--<li><a href="measurements">Measurements</a></li>--}}


                        {{--</ul>--}}
                    {{--</li>--}}


                    {{--<li class="has_sub">--}}
                        {{--<a href="javascript:void(0);" class="waves-effect"><i class="ion-person-stalker"></i><span>Suppliers <span--}}
                                        {{--class="pull-right"><i class="mdi mdi-chevron-right"></i></span> </span></a>--}}
                        {{--<ul class="list-unstyled">--}}
                            {{--<li><a href="{{route('add_suppliers')}}">Add Supplier</a></li>--}}
                            {{--<li><a href="{{route('view_suppliers')}}">View Suppliers</a></li>--}}

                        {{--</ul>--}}
                    {{--</li>--}}


                    {{--<li class="menu-title">ACCOUNTS & PAYMENTS</li>--}}
                    {{--<li class="has_sub">--}}
                        {{--<a href="javascript:void(0);" class="waves-effect"><i class="dripicons-suitcase"></i><span>Payment<span--}}
                                        {{--class="pull-right"><i class="mdi mdi-chevron-right"></i></span> </span></a>--}}
                        {{--<ul class="list-unstyled">--}}
                            {{--<li><a href="{{route('bankAccounts')}}">Bank Accounts</a></li>--}}


                        {{--</ul>--}}
                    {{--</li>--}}






                    {{--<li class="has_sub">--}}
                    {{--<a href="javascript:void(0);" class="waves-effect"><i class="mdi mdi-clipboard-text"></i><span>Voucher<span--}}
                    {{--class="pull-right"><i class="mdi mdi-chevron-right"></i></span> </span></a>--}}
                    {{--<ul class="list-unstyled">--}}
                    {{--<li>--}}
                    {{--<a href="create_voucher" class="waves-effect"><span>Create Voucher</span></a>--}}
                    {{--</li>--}}
                    {{--<li>--}}
                    {{--<a href="voucher_history" class="waves-effect"><span>Voucher History</span></a>--}}
                    {{--</li>--}}
                    {{--</ul>--}}
                    {{--</li>--}}
                    <li class="menu-title">{{ __('ADMINISTRATION') }}</li>

                    <li class="has_sub">
                        <a href="javascript:void(0);" class="waves-effect"><i class="dripicons-suitcase"></i><span>{{ __('Payments') }}<span
                                        class="pull-right"><i class="mdi mdi-chevron-right"></i></span> </span></a>
                        <ul class="list-unstyled">
                            <li><a href="{{route('addPayment')}}">{{ __('Add Payment') }}</a></li>
                            <li><a href="{{route('viewCategory')}}">{{ __('Outstanding Payments') }}</a></li>
                            <li><a href="{{route('viewPayments')}}">{{ __('View Payments') }}</a></li>
                        </ul>
                    </li>
                    <li class="has_sub">
                        <a href="javascript:void(0);" class="waves-effect"><i class="dripicons-suitcase"></i><span>{{ __('Category') }}<span
                                        class="pull-right"><i class="mdi mdi-chevron-right"></i></span> </span></a>
                        <ul class="list-unstyled">
                            <li><a href="{{route('addCategory')}}">{{ __('Add Category') }}</a></li>
                            <li><a href="{{route('viewCategory')}}">{{ __('View Category') }}</a></li>
                        </ul>
                    </li>

                    <li class="has_sub">
                        <a href="javascript:void(0);" class="waves-effect"><i class="dripicons-suitcase"></i><span>{{ __('Office') }}<span
                                        class="pull-right"><i class="mdi mdi-chevron-right"></i></span> </span></a>
                        <ul class="list-unstyled">
                            <li><a href="{{route('addOffice')}}">{{ __('Add Office') }}</a></li>
                            <li><a href="{{route('viewOffice')}}">{{ __('View Office') }}</a></li>
                        </ul>
                    </li>

                    <li class="has_sub">
                        <a href="javascript:void(0);" class="waves-effect"><i class="dripicons-suitcase"></i><span>{{ __('Users') }}<span
                                        class="pull-right"><i class="mdi mdi-chevron-right"></i></span> </span></a>
                        <ul class="list-unstyled">
                            <li><a href="{{route('addUser')}}">{{ __('Add Users') }}</a></li>
                            <li><a href="{{route('viewUser')}}">{{ __('View Users') }}</a></li>
                        </ul>
                    </li>

                    {{--<li class="has_sub">--}}
                    {{--<a href="javascript:void(0);" class="waves-effect"><i class="dripicons-suitcase"></i><span>Setting <span--}}
                    {{--class="pull-right"><i class="mdi mdi-chevron-right"></i></span> </span></a>--}}
                    {{--<ul class="list-unstyled">--}}

                    {{----}}

                    {{--</ul>--}}
                    {{--</li>--}}


                </ul>
            </div>
            <div class="clearfix"></div>
        </div> <!-- end sidebarinner -->
    </div>
    <!-- Left Sidebar End -->