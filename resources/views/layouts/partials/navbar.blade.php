<nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
    <div class="container-fluid d-flex justify-content-between align-items-center px-4">
        <!-- Kiri: Brand -->
        <a class="navbar-brand" href="{{ url('/') }}">
            K-JOBS
        </a>

        <!-- Kanan: Auth -->
        @auth
            @if (!request()->routeIs('home'))
                <div class="d-flex align-items-center gap-3">
                    <span class="badge-user">
                        <i class="bi bi-person-fill me-1"></i>
                        {{ Auth::user()->name }}
                    </span>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="btn-logout btn-outline-danger">
                            <i class="fas fa-sign-out-alt"></i> Logout
                        </button>
                    </form>
                </div>
            @else
                <a class="btn btn-login me-2" href="{{ route('dashboard') }}">
                    Go to Dashboard
                </a>
            @endif
        @endauth
    </div>
</nav>
