@auth
    <li class="nav-item d-flex align-items-center mr-2">
        @if (auth()->user()->hasRole('admin'))
            <span class="role-badge-topbar role-admin" aria-label="Peran pengguna saat ini: Admin">Admin</span>
        @elseif (auth()->user()->hasRole('staf'))
            <span class="role-badge-topbar role-staf" aria-label="Peran pengguna saat ini: Staf">Staf</span>
        @endif
    </li>
@endauth
