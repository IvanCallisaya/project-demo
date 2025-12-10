<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <a href="/" class="brand-link text-center">
        <span class="brand-text font-weight-light">Agiliza Tech</span>
    </a>

    <div class="sidebar">
        <nav>
            <ul class="nav nav-pills nav-sidebar flex-column">
                <li class="nav-item">
                    <a href="{{ route('dashboard') }}" class="nav-link">
                        <i class="nav-icon fas fa-home"></i>
                        <p>Dashboard</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('cliente_empresa.index') }}" class="nav-link">
                        <i class="fas fa-user-tie"></i>
                        Clientes
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('laboratorio.index') }}" class="nav-link">
                        <i class="fa-solid fa-flask-vial"></i>
                        Laboratorios
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('producto.index') }}" class="nav-link">
                        <i class="fas fa-boxes"></i>
                        Productos
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('subcategoria.index') }}" class="nav-link">
                        <i class="fas fa-tags"></i>
                        Subcategorías
                    </a>
                </li>
                @role('admin')
                <li class="nav-item">
                    <a href="{{ route('users.index') }}" class="nav-link">
                        <i class="nav-icon fas fa-users"></i>
                        <p>Usuarios</p>
                    </a>
                </li>
                @endrole

            </ul>
        </nav>
    </div>
</aside>