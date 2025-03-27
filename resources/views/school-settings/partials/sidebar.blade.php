<div class="col-md-3 col-lg-2 d-md-block bg-light sidebar collapse">
    <div class="position-sticky pt-3">
        <h6 class="sidebar-heading d-flex justify-content-between align-items-center px-3 mt-2 mb-1 text-muted">
            <span>Paramètres de l'école</span>
        </h6>
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('school.settings') ? 'active text-primary-custom fw-bold' : '' }}" href="{{ route('school.settings') }}">
                    <i class="fas fa-cog me-2"></i>
                    Général
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('school.settings.widgets') ? 'active text-primary-custom fw-bold' : '' }}" href="{{ route('school.settings.widgets') }}">
                    <i class="fas fa-th-large me-2"></i>
                    Widgets
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('school.settings.reports.*') ? 'active text-primary-custom fw-bold' : '' }}" href="{{ route('school.settings.reports.index') }}">
                    <i class="fas fa-file-alt me-2"></i>
                    Rapports
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('school.settings.appearance') ? 'active text-primary-custom fw-bold' : '' }}" href="{{ route('school.settings.appearance') }}">
                    <i class="fas fa-palette me-2"></i>
                    Apparence
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('school.settings.terminology') ? 'active text-primary-custom fw-bold' : '' }}" href="{{ route('school.settings.terminology') }}">
                    <i class="fas fa-language me-2"></i>
                    Terminologie
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('school.settings.notifications') ? 'active text-primary-custom fw-bold' : '' }}" href="{{ route('school.settings.notifications') }}">
                    <i class="fas fa-bell me-2"></i>
                    Notifications
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('school.settings.documents') ? 'active text-primary-custom fw-bold' : '' }}" href="{{ route('school.settings.documents') }}">
                    <i class="fas fa-file-pdf me-2"></i>
                    Documents
                </a>
            </li>
        </ul>
    </div>
</div> 