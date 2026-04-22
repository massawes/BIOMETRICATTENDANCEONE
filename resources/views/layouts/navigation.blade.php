<nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm px-3">

    <div class="container-fluid">

        <!-- LEFT -->
        <span class="navbar-brand fw-bold">
            🎓 ATC Dashboard
        </span>

        <!-- RIGHT -->
        <div class="ms-auto d-flex align-items-center">

            <!-- USER -->
            <span class="me-3 fw-semibold">
                👤 {{ auth()->user()->name }}
            </span>

            <!-- DROPDOWN -->
            <div class="dropdown">
                <button class="btn btn-light dropdown-toggle"
                        type="button"
                        data-bs-toggle="dropdown">
                    Settings
                </button>

                <ul class="dropdown-menu dropdown-menu-end">

                    <!-- PROFILE -->
                    <li>
                        <a class="dropdown-item" href="{{ route('profile.edit') }}">
                            👤 Profile
                        </a>
                    </li>

                    <li><hr class="dropdown-divider"></li>

                    <!-- LOGOUT -->
                    <li>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="dropdown-item text-danger">
                                🚪 Logout
                            </button>
                        </form>
                    </li>

                </ul>
            </div>

        </div>

    </div>
</nav>