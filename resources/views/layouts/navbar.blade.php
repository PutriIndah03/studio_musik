        <nav class="navbar navbar-expand-lg navbar-light bg-light shadow-sm px-3">
            <div class="container-fluid">
                <button class="navbar-toggler d-block" type="button" id="toggleSidebar">
                    <span class="navbar-toggler-icon"></span>
                </button>
                
                <form class="d-flex mx-auto">
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-search"></i></span>
                        <input class="form-control" type="search" placeholder="Search" aria-label="Search">
                    </div>
                </form>
                
                <div class="dropdown">
                    <button class="btn d-flex align-items-center" id="profileDropdown" data-bs-toggle="dropdown" aria-expanded="false">

                        <span class="ms-2">{{ ucfirst(auth()->user()->nama) }}</span>
                        <i class="bi bi-chevron-down ms-1"></i>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end shadow">
                        <li>
                            <a class="dropdown-item" href="#"> <i class="fa fa-user me-2"></i> Profile</a>
                        </li>
                        <li><hr class="dropdown-divider"></li> <!-- Divider untuk pemisah -->
                        <li>
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="submit" class="dropdown-item text-danger">
                                    <i class="fa fa-sign-out-alt me-2"></i> Logout
                                </button>
                            </form>
                        </li>
                    </ul>
                    
                </div>
            </div>
        </nav>