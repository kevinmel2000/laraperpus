<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Styles -->
    <link rel="stylesheet" href="{{ asset( 'css/font-awesome.min.css' ) }}" type="text/css">
    <link rel="stylesheet" href="{{ asset( 'css/bootstrap.min.css' ) }}" type="text/css">
    <link rel="stylesheet" href="{{ asset( 'css/app.css' ) }}" type="text/css">
    <link rel="stylesheet" href="{{ asset( 'css/jquery.dataTables.css' ) }}" type="text/css">
    <link rel="stylesheet" href="{{ asset( 'css/dataTables.bootstrap.css' ) }}" type="text/css">
    <link rel="stylesheet" href="{{ asset( 'css/selectize.css' ) }}" type="text/css">
    <link rel="stylesheet" href="{{ asset( 'css/selectize.bootstrap3.css' ) }}" type="text/css">


    <!-- Scripts -->
    <script>
        window.Laravel = <?php echo json_encode([
            'csrfToken' => csrf_token(),
        ]); ?>
    </script>
</head>
<body>
    <nav class="navbar navbar-default navbar-static-top">
        <div class="container">
            <div class="navbar-header">

                <!-- Collapsed Hamburger -->
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#app-navbar-collapse">
                    <span class="sr-only">Toggle Navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>

                <!-- Branding Image -->
                <a class="navbar-brand" href="{{ url('/') }}">
                    {{ config('app.name', 'Laravel') }}
                </a>
            </div>

            <div class="collapse navbar-collapse" id="app-navbar-collapse">
                <!-- Left Side Of Navbar -->
                <ul class="nav navbar-nav">
                    @if ( Auth::check() )
                        {!! Html::smartNav(url('/home'), 'Dashboard') !!}
                    @endif
                    @role( ['admin'] )
                        {!! Html::smartNav(route('authors.index'), 'Penulis') !!}
                        {!! Html::smartNav(route('books.index'), 'Buku') !!}
                        {!! Html::smartNav(route('members.index'), 'Member') !!}
                        {!! Html::smartNav(route('statistics.index'), 'Peminjaman') !!}
                    @endrole
                    @if ( auth()->check() )
                        {!! Html::smartNav(url('/settings/profile'), 'Profil') !!}
                    @endif
                </ul>

                <!-- Right Side Of Navbar -->
                <ul class="nav navbar-nav navbar-right">
                    <!-- Authentication Links -->
                    @if (Auth::guest())
                        <li><a href="{{ url('/login') }}">Login</a></li>
                        <li><a href="{{ url('/register') }}">Daftar</a></li>
                    @else
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                                {{ Auth::user()->name }} <span class="caret"></span>
                            </a>

                            <ul class="dropdown-menu" role="menu">
                                <li><a href="{{ url( '/settings/password' ) }}"><i class="fa fa-btn fa-lock"></i> Ubah Password</a></li>
                                <li>
                                    <a href="{{ url('/logout') }}"
                                        onclick="event.preventDefault();
                                                 document.getElementById('logout-form').submit();">
                                        <i class="fa fa-btn fa-power-off"></i> Logout
                                    </a>

                                    <form id="logout-form" action="{{ url('/logout') }}" method="POST" style="display: none;">
                                        {{ csrf_field() }}
                                    </form>
                                </li>
                            </ul>
                        </li>
                    @endif
                </ul>
            </div>
        </div>
    </nav>

    {{-- Flash Notification partial Blade --}}
    @include( 'layouts._flash' )
    {{-- Konten Blade --}}
    @yield('content')

    <!-- Scripts -->
    <script src="{{ asset( 'js/jquery-3.1.0.min.js' ) }}"></script>
    <script src="{{ asset( 'js/bootstrap.min.js' ) }}"></script>
    <script src="{{ asset( 'js/jquery.dataTables.min.js' ) }}"></script>
    <script src="{{ asset( 'js/dataTables.bootstrap.min.js' ) }}"></script>
    <script src="{{ asset( 'js/selectize.min.js' ) }}"></script>
    <script src="{{ asset( 'js/custom.js' ) }}"></script>
    @yield( 'scripts' )
</body>
</html>