<div class="navbar-fixed">
    <nav class="indigo darken-4">
        <div class="container">
            <div class="nav-wrapper">

                <a href="{{ route('home') }}" class="brand-logo">
                    @if(isset($navbarsettings[0]) && $navbarsettings[0]['name'])
                    {{ $navbarsettings[0]['name'] }}
                    @else
                    Real Estate
                    @endif
                    {{-- <i class="material-icons left">location_city</i> --}}
                </a>
                <a href="#" data-target="mobile-demo" class="sidenav-trigger">
                    <i class="material-icons">menu</i>
                </a>

                <ul class="right hide-on-med-and-down">
                    <li class="{{ Request::is('/') ? 'active' : '' }}">
                        {{-- <a href="{{ route('home') }}">Home</a> --}}
                        <a href="{{ route('home') }}">{{ __('views.navbar.home') }}</a>
                    </li>

                    <li class="{{ Request::is('property*') ? 'active' : '' }}">
                        <a href="{{ route('property') }}">{{ __('views.navbar.properties') }}</a>
                    </li>

                    <li class="{{ Request::is('agents*') ? 'active' : '' }}">
                        <a href="{{ route('agents') }}">{{ __('views.navbar.agents') }}</a>
                    </li>

                    <li class="{{ Request::is('gallery') ? 'active' : '' }}">
                        <a href="{{ route('gallery') }}">{{ __('views.navbar.gallery') }}</a>
                    </li>

                    <li class="{{ Request::is('blog*') ? 'active' : '' }}">
                        <a href="{{ route('blog') }}">{{ __('views.navbar.news') }}</a>
                    </li>

                    <li class="{{ Request::is('contact') ? 'active' : '' }}">
                        <a href="{{ route('contact') }}">{{ __('views.navbar.contact') }}</a>
                    </li>

                    @guest
                    <li><a href="{{ route('login') }}"><i class="material-icons">input</i></a></li>
                    <li><a href="{{ route('register') }}"><i class="material-icons">person_add</i></a></li>
                    @else
                    <li>
                        <a class="dropdown-trigger" href="#!" data-target="dropdown-auth-frontend">
                            {{ ucfirst(Auth::user()->username) }}
                            <i class="material-icons right">arrow_drop_down</i>
                        </a>
                    </li>

                    <ul id="dropdown-auth-frontend" class="dropdown-content">
                        <li>
                            @if(Auth::user()->role->id == 1)
                            <a href="{{ route('admin.dashboard') }}" class="indigo-text">
                                <i class="material-icons">person</i>{{ __('views.navbar.user.profile') }}
                            </a>
                            @elseif(Auth::user()->role->id == 2)
                            <a href="{{ route('editor.dashboard') }}" class="indigo-text">
                                <i class="material-icons">person</i>{{ __('views.navbar.user.profile') }}
                            </a>
                            @elseif(Auth::user()->role->id == 3)
                            <a href="{{ route('agent.dashboard') }}" class="indigo-text">
                                <i class="material-icons">person</i>{{ __('views.navbar.user.profile') }}
                            </a>
                            @elseif(Auth::user()->role->id == 4)
                            <a href="{{ route('user.dashboard') }}" class="indigo-text">
                                <i class="material-icons">person</i>{{ __('views.navbar.user.profile') }}
                            </a>
                            @endif
                        </li>
                        <li>
                            <a class="dropdownitem indigo-text" href="{{ route('logout') }}" onclick="event.preventDefault();
                                    document.getElementById('logout-form').submit();">
                                <i class="material-icons">power_settings_new</i>{{ __('default.logout') }}
                            </a>

                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                @csrf
                            </form>
                        </li>
                    </ul>

                    @endguest
                    <li>
                        <a class="dropdown-trigger" href="#!" data-target="dropdown-language">
                            {{-- {{ __('views.navbar.language') }} --}}
                            <i class="material-icons">language</i>
                            {{-- <i class="material-icons right">arrow_drop_down</i> --}}
                        </a>
                    </li>
                    <ul id="dropdown-language" class="dropdown-content">
                        <li>
                            <a href="{{ route('lang', 'vi') }}" class="indigo-text">
                                {{-- <i class="material-icons">language</i>Tiếng Việt --}}
                                Tiếng Việt
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('lang', 'en') }}" class="indigo-text">
                                English
                            </a>
                        </li>
                    </ul>
                </ul>
            </div>
        </div>
    </nav>

    <ul class="sidenav" id="mobile-demo">
        <li class="{{ Request::is('/') ? 'active' : '' }}">
            <a href="{{ route('home') }}">Home</a>
        </li>

        <li class="{{ Request::is('property*') ? 'active' : '' }}">
            <a href="{{ route('property') }}">Properties</a>
        </li>

        <li class="{{ Request::is('agents*') ? 'active' : '' }}">
            <a href="{{ route('agents') }}">Agents</a>
        </li>

        <li class="{{ Request::is('gallery') ? 'active' : '' }}">
            <a href="{{ route('gallery') }}">Gallery</a>
        </li>

        <li class="{{ Request::is('blog*') ? 'active' : '' }}">
            <a href="{{ route('blog') }}">Blog</a>
        </li>

        <li class="{{ Request::is('contact') ? 'active' : '' }}">
            <a href="{{ route('contact') }}">Contact</a>
        </li>
    </ul>

</div>