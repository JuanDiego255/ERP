@inject('request', 'Illuminate\Http\Request')
<!-- Main Header -->
<header class="main-header no-print">
    <a href="{{ route('home') }}" class="logo">
        <div class="col-xs-12">
            <img src="/images/logo_ag_cor.png" class="img-rounded" alt="Logo" id="logo_ag" width="150"
                style="margin-bottom: 30px;">
            {{-- <span id="logo_ag_text" class="text-left text-success" style="display: none; margin-left:0; margin-right:20px; font-size: 18px; font-weight: bold;">AG</span> --}}
        </div>
    </a>

    <!-- Header Navbar -->
    <nav class="navbar navbar-static-top" role="navigation">
        <!-- Sidebar toggle button-->
        <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
            &#9776;
            <span class="sr-only">Toggle navigation</span>
        </a>

        @if (Module::has('Superadmin'))
            @includeIf('superadmin::layouts.partials.active_subscription')
        @endif

        <!-- Navbar Right Menu -->
        <div class="navbar-custom-menu">

            {{-- <div class="m-8 pull-left mt-15 hidden-xs" style="color: #fff;">Ambiente: 
          <strong>
            {{auth()->user()->business->ambiente == 2 ? 'Homologado' : 'Produção'}}
          </strong>
        </div> --}}

            @if (Module::has('Essentials'))
                @includeIf('essentials::layouts.partials.header_part')
            @endif

            {{--  <button id="btnCalculator" title="@lang('lang_v1.calculator')" type="button" class="btn btn-success btn-flat pull-left m-8 hidden-xs btn-sm mt-10 popover-default" data-toggle="popover" data-trigger="click" data-content='@include("layouts.partials.calculator")' data-html="true" data-placement="bottom">
            <strong><i class="fa fa-calculator fa-lg" aria-hidden="true"></i></strong>
        </button> --}}

            @if ($request->segment(1) == 'pos')
                <button type="button" id="register_details" title="{{ __('cash_register.register_details') }}"
                    data-toggle="tooltip" data-placement="bottom"
                    class="btn btn-success btn-flat pull-left m-8 hidden-xs btn-sm mt-10 btn-modal"
                    data-container=".register_details_modal"
                    data-href="{{ action('CashRegisterController@getRegisterDetails') }}">
                    <strong><i class="fa fa-briefcase fa-lg" aria-hidden="true"></i></strong>
                </button>
                <button type="button" id="close_register" title="{{ __('cash_register.close_register') }}"
                    data-toggle="tooltip" data-placement="bottom"
                    class="btn btn-danger btn-flat pull-left m-8 hidden-xs btn-sm mt-10 btn-modal"
                    data-container=".close_register_modal"
                    data-href="{{ action('CashRegisterController@getCloseRegister') }}">
                    <strong><i class="fa fa-window-close fa-lg"></i></strong>
                </button>
            @endif

            {{-- @if (in_array('pos_sale', $enabled_modules))
                @can('sell.create')
                    <a href="{{ action('SellPosController@create') }}" title="PDV" data-toggle="tooltip"
                        data-placement="bottom" class="btn btn-success btn-flat pull-left m-8 hidden-xs btn-sm mt-10">
                        <strong><i class="fa fa-th-large"></i> &nbsp; PDV</strong>
                    </a>
                @endcan
            @endif --}}
            {{-- @can('profit_loss_report.view')
                <button type="button" id="view_todays_profit" title="{{ __('home.todays_profit') }}" data-toggle="tooltip"
                    data-placement="bottom" class="btn btn-success btn-flat pull-left m-8 hidden-xs btn-sm mt-10">
                    <strong><i class="fas fa-money-bill-alt fa-lg"></i></strong>
                </button>
            @endcan --}}

            <!-- Help Button -->
            @if (auth()->user()->hasRole('Admin#' . auth()->user()->business_id))
                <button type="button" id="start_tour" title="@lang('lang_v1.application_tour')" data-toggle="tooltip"
                    data-placement="bottom" class="btn btn-success btn-flat pull-left m-8 hidden-xs btn-sm mt-10">
                    <strong><i class="fa fa-question-circle fa-lg" aria-hidden="true"></i></strong>
                </button>
            @endif

            <div class="m-8 pull-left mt-15 hidden-xs" style="color: #fff;"><strong>{{ @format_date('now') }}</strong>
            </div>

            <ul class="nav navbar-nav">
                @include('layouts.partials.header-notifications')
                <!-- User Account Menu -->
                <li class="dropdown user user-menu">
                    <!-- Menu Toggle Button -->
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <!-- The user image in the navbar-->
                        @php
                            $profile_photo = auth()->user()->media;
                        @endphp
                        @if (!empty($profile_photo))
                            <img src="{{ $profile_photo->display_url }}" class="user-image" alt="User Image">
                        @endif
                        <!-- hidden-xs hides the username on small devices so only the image appears. -->
                        <span>{{ Auth::User()->first_name }} {{ Auth::User()->last_name }}</span>
                    </a>
                    <ul class="dropdown-menu">
                        <!-- The user image in the menu -->
                        <li class="user-header">
                            @if (!empty(Session::get('business.logo')))
                                <img src="{{ url('uploads/business_logos/' . Session::get('business.logo')) }}"
                                    alt="Logo"></span>
                            @endif
                            <p>
                                {{ Auth::User()->first_name }} {{ Auth::User()->last_name }}
                            </p>
                        </li>
                        <!-- Menu Body -->
                        <!-- Menu Footer-->
                        <li class="user-footer">
                            <div class="pull-left">
                                <a href="{{ action('UserController@getProfile') }}"
                                    class="btn btn-default btn-flat">@lang('lang_v1.profile')</a>
                            </div>
                            <div class="pull-right">
                                <a href="{{ action('Auth\LoginController@logout') }}"
                                    class="btn btn-default btn-flat">@lang('lang_v1.sign_out')</a>
                            </div>
                        </li>
                    </ul>
                </li>
                <!-- Control Sidebar Toggle Button -->
            </ul>
        </div>
    </nav>
</header>
