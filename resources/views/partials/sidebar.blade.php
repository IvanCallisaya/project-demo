<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <a href="/" class="brand-link text-center">
        <span class="brand-text font-weight-light">Agiliza Tech</span>
    </a>

    <div class="sidebar">
        <nav>
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
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
                    <a href="{{ route('sucursal.index') }}" class="nav-link">
                        <i class="fa-solid fa-building"></i>
                        Sucursales
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('laboratorio.index') }}" class="nav-link">
                        <i class="fa-solid fa-flask-vial"></i>
                        Laboratorios
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('presolicitud.index') }}" class="nav-link">
                        <i class="fa-solid fa-file"></i>
                        Pre-solicitudes de tramites
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
                <li class="nav-item has-treeview {{ request()->is('reporte*') ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link {{ request()->is('reporte*') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-chart-bar"></i>
                        <p>
                            Reportes
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('reporte.producto') }}" class="nav-link {{ request()->routeIs('reporte.producto') ? 'active' : '' }}">
                                <i class="fas fa-file-medical nav-icon"></i>
                                <p>Producto Veterinario</p>
                            </a>
                        </li>
                    </ul>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('reporte.presolicitud') }}" class="nav-link {{ request()->routeIs('reporte.presolicitud') ? 'active' : '' }}">
                                <i class="fas fa-file-medical nav-icon"></i>
                                <p>PreSolicitud</p>
                            </a>
                        </li>
                    </ul>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('reporte.documento.index') }}" class="nav-link {{ request()->routeIs('reporte.documento.index') ? 'active' : '' }}">
                                <i class="fas fa-file-medical nav-icon"></i>
                                <p>Documento</p>
                            </a>
                        </li>
                    </ul>
            </ul>

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