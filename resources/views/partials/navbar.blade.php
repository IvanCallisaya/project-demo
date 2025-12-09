<nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <ul class="navbar-nav">
        </ul>
    
    <ul class="navbar-nav ml-auto">
        
        <li class="nav-item d-block d-lg-none">
            <a class="nav-link" data-widget="pushmenu" href="#" role="button">
                <i class="fas fa-bars"></i>
            </a>
        </li>

        <li class="nav-item">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button class="btn btn-danger btn-sm">Cerrar sesión</button>
            </form>
        </li>
    </ul>
</nav>