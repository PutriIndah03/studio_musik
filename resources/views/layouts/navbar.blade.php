        <nav class="navbar navbar-expand-lg navbar-light bg-light shadow-sm px-3">
            <div class="container-fluid">
                <button class="navbar-toggler d-block" type="button" id="toggleSidebar">
                    <span class="navbar-toggler-icon"></span>
                </button>
                
                @if(auth()->user()->role === 'mahasiswa')
                <form class="d-flex mx-auto" action="{{ route('search') }}" method="GET">
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-search"></i></span>
                        <input class="form-control" type="search" name="query" placeholder="Cari" aria-label="Search">
                    </div>
                </form>
                @endif
                
                <div class="dropdown">
                    <button class="btn d-flex align-items-center" id="profileDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                        @php
                        $user = auth()->user();
                        $role = ucfirst($user->role);
                        $foto = null;
                
                        if ($user->role === 'mahasiswa') {
                            $mahasiswa = \App\Models\Mahasiswa::where('nim', $user->username)->first();
                            $foto = $mahasiswa && $mahasiswa->foto ? asset('storage/' . $mahasiswa->foto) : null;
                        } elseif ($user->role === 'staf') {
                            $staf = \App\Models\Staf::where('nim', $user->username)->first();
                            $foto = $staf && $staf->foto ? asset('storage/' . $staf->foto) : null;
                        } else {
                            $foto = $user->image ? asset('storage/' . $user->image) : null;
                        }
                
                        $initials = strtoupper(substr($user->nama, 0, 1)); // Inisial Nama
                        @endphp
                                <div class="d-flex align-items-center justify-content-start profile-box">
                                    <!-- Foto Profil -->
                                    @if($foto)
                                        <img src="{{ $foto }}" class="profile-img me-2" alt="Profile">
                                    @else
                                        <div class="profile-placeholder me-2">{{ $initials }}</div>
                                    @endif
                                </div>

                        <span class="ms-2">{{ ucfirst(auth()->user()->nama) }}</span>
                        <i class="bi bi-chevron-down ms-1"></i>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end shadow">
                        @if(auth()->user()->role === 'mahasiswa' || auth()->user()->role === 'staf')
                        <li>
                            <a class="dropdown-item" href="/profile"> <i class="fa fa-user me-2"></i> Profile</a>
                        </li>
                        <li><hr class="dropdown-divider"></li> <!-- Divider untuk pemisah -->
                        @endif
                        <li>
                            <form action="{{ route('logout') }}" method="POST" onsubmit="localStorage.removeItem('activeMenu')">
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