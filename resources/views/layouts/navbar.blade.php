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
                        <img src="profile.jpg" class="rounded-circle" width="40" height="40" alt="Profile">
                        <span class="ms-2">John Doe</span>
                        <i class="bi bi-chevron-down ms-1"></i>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item" href="#">Profile</a></li>
                        <li><a class="dropdown-item" href="#">Logout</a></li>
                    </ul>
                </div>
            </div>
        </nav>