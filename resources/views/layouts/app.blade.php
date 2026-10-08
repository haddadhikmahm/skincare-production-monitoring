<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Skincare Production Monitoring — PT. Sinar Alfa Omega</title>
    <meta name="description" content="Sistem Informasi Manajemen Produksi Maklon Kosmetik PT. Sinar Alfa Omega">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body data-theme="{{ session('theme', 'light') }}">
    @auth
        <div class="app-layout">
            <!-- Sidebar -->
            <aside class="sidebar">
                <div class="sidebar-header">
                    <i class="fa-solid fa-industry" style="font-size: 1.5rem;"></i>
                    <div>
                        <h2>PT. Sinar Alfa Omega</h2>
                        <span>{{ __('Sistem Informasi Manajemen') }}<br>{{ __('Produksi Maklon Kosmetik') }}</span>
                    </div>
                </div>
                <div class="sidebar-nav">
                    @can('view_dashboard')
                        <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}"><i class="fa-solid fa-house"></i> {{ __('Dashboard') }}</a>
                    @endcan
                    @can('manage_work_orders')
                        <a href="{{ route('work-orders.index') }}" class="nav-link {{ request()->routeIs('work-orders.*') ? 'active' : '' }}"><i class="fa-solid fa-file-invoice"></i> {{ __('Work Order') }}</a>
                    @endcan
                    @can('manage_produksi')
                        <a href="{{ route('produksi.index') }}" class="nav-link {{ request()->routeIs('produksi.*') ? 'active' : '' }}"><i class="fa-solid fa-gear"></i> {{ __('Produksi') }}</a>
                    @endcan
                    @can('manage_qc')
                        <a href="{{ route('qc.index') }}" class="nav-link {{ request()->routeIs('qc.*') ? 'active' : '' }}"><i class="fa-solid fa-flask"></i> {{ __('QC / Quality Release') }}</a>
                    @endcan
                    @can('manage_waste')
                        <a href="{{ route('waste.index') }}" class="nav-link {{ request()->routeIs('waste.*') ? 'active' : '' }}"><i class="fa-solid fa-triangle-exclamation"></i> {{ __('Waste & Downtime') }}</a>
                    @endcan
                    @can('view_vsm')
                        <a href="{{ route('vsm.index') }}" class="nav-link {{ request()->routeIs('vsm.*') ? 'active' : '' }}"><i class="fa-solid fa-chart-line"></i> {{ __('Analisis VSM') }}</a>
                    @endcan
                    @can('view_laporan')
                        <a href="{{ route('laporan.index') }}" class="nav-link {{ request()->routeIs('laporan.*') ? 'active' : '' }}"><i class="fa-solid fa-file-lines"></i> {{ __('Laporan') }}</a>
                    @endcan
                    @can('manage_users')
                        <a href="{{ route('users.index') }}" class="nav-link {{ request()->routeIs('users.*') ? 'active' : '' }}"><i class="fa-solid fa-users"></i> {{ __('Manajemen Pengguna') }}</a>
                    @endcan
                    <a href="{{ route('settings.index') }}" class="nav-link {{ request()->routeIs('settings.*') ? 'active' : '' }}"><i class="fa-solid fa-sliders"></i> {{ __('Pengaturan') }}</a>

                    <div style="margin-top: auto;"></div>
                </div>
                <div style="padding: 1.25rem 1.5rem; border-top: 1px solid rgba(255,255,255,0.05);">
                    <form method="POST" action="{{ route('logout') }}" style="margin: 0;">
                        @csrf
                        <button type="submit" class="nav-link" style="width:100%; background: rgba(239,68,68,0.08); border: 1px solid rgba(239,68,68,0.2); text-align: left; cursor: pointer; color: #fca5a5; border-radius: 8px; justify-content: center;">
                            <i class="fa-solid fa-arrow-right-from-bracket"></i> {{ __('Logout') }}
                        </button>
                    </form>
                </div>
            </aside>

            <!-- Main Content Wrapper -->
            <div class="main-wrapper">
                <header class="topbar">
                    @php
                        $routeName = request()->route() ? request()->route()->getName() : '';
                        $pageTitle = __('Dashboard');
                        $pageIcon = 'fa-house';

                        if (str_starts_with($routeName, 'work-orders.')) {
                            $pageTitle = __('Work Order');
                            $pageIcon = 'fa-file-invoice';
                        } elseif (str_starts_with($routeName, 'produksi.')) {
                            $pageTitle = __('Produksi');
                            $pageIcon = 'fa-gear';
                        } elseif (str_starts_with($routeName, 'qc.')) {
                            $pageTitle = __('QC / Quality Release');
                            $pageIcon = 'fa-flask';
                        } elseif (str_starts_with($routeName, 'waste.')) {
                            $pageTitle = __('Waste & Downtime');
                            $pageIcon = 'fa-triangle-exclamation';
                        } elseif (str_starts_with($routeName, 'vsm.')) {
                            $pageTitle = __('Analisis VSM');
                            $pageIcon = 'fa-chart-line';
                        } elseif (str_starts_with($routeName, 'laporan.')) {
                            $pageTitle = __('Laporan');
                            $pageIcon = 'fa-file-lines';
                        } elseif (str_starts_with($routeName, 'users.')) {
                            $pageTitle = __('Manajemen Pengguna');
                            $pageIcon = 'fa-users';
                        } elseif (str_starts_with($routeName, 'settings.')) {
                            $pageTitle = __('Pengaturan');
                            $pageIcon = 'fa-sliders';
                        } elseif (str_starts_with($routeName, 'notifications.')) {
                            $pageTitle = __('Semua Notifikasi');
                            $pageIcon = 'fa-bell';
                        }
                    @endphp
                    <div class="topbar-left">
                        <i class="fa-solid fa-bars" id="sidebar-toggle"></i>
                        <span style="font-size: 0.95rem; font-weight: 600; color: #0f172a;">
                            <i class="fa-solid {{ $pageIcon }}" style="margin-right: 0.4rem; color: #94a3b8; font-size: 0.85rem;"></i>
                            {{ $pageTitle }}
                        </span>
                    </div>
                    <div class="topbar-right">
                        <!-- Notification Dropdown -->
                        <div class="dropdown" id="notifDropdown">
                            <i class="fa-regular fa-bell" id="bellIcon" style="font-size: 1.2rem; color: #64748b; position: relative; cursor: pointer; transition: 0.2s; padding: 0.5rem;" onclick="toggleDropdown('notifMenu')">
                                <span id="notifBadge" style="position: absolute; top: 0; right: 0; background: #ef4444; color: white; border-radius: 50%; font-size: 0.6rem; width: 16px; height: 16px; display: none; align-items: center; justify-content: center; font-weight: bold; animation: pulse 2s infinite;">0</span>
                            </i>
                            <div class="dropdown-menu" id="notifMenu" style="width: 350px;">
                                <div class="dropdown-header">
                                    <span>{{ __('Notifikasi') }}</span>
                                    <span style="font-size: 0.75rem; color: var(--primary); cursor: pointer;"><i class="fa-solid fa-check-double"></i>{{ __('Tandai dibaca') }}</span>
                                </div>
                                <div id="notifList" style="max-height: 300px; overflow-y: auto;">
                                    <div style="padding: 2rem; text-align: center; color: #94a3b8; font-size: 0.85rem;">{{ __('Memuat...') }}</div>
                                </div>
                                <div class="dropdown-footer">
                                    <a href="{{ route('notifications.index') }}">{{ __('Lihat Semua Notifikasi &rarr;') }}</a>
                                </div>
                            </div>
                        </div>

                        <!-- Profile Dropdown -->
                        <div class="dropdown" id="profileDropdown">
                            <div class="user-profile" onclick="toggleDropdown('profileMenu')" style="cursor: pointer;">
                                <div style="text-align: right;">
                                    <div class="user-name">{{ Auth::user()->name }}</div>
                                    <div class="user-role">{{ Auth::user()->roles->pluck('name')->first() ?? 'User' }}</div>
                                </div>
                                <img src="{{ Auth::user()->avatar ? asset('storage/'.Auth::user()->avatar) : 'https://ui-avatars.com/api/?length=1&name='.urlencode(preg_replace('/[^a-zA-Z0-9\s]/', '', Auth::user()->name)).'&background=e0e7ff&color=4338ca&bold=true' }}" style="object-fit: cover;" alt="User avatar">
                            </div>
                            <div class="dropdown-menu" id="profileMenu" style="width: 250px;">
                                <div style="padding: 1.25rem; border-bottom: 1px solid var(--border-color); display: flex; align-items: center; gap: 1rem;">
                                    <img src="{{ Auth::user()->avatar ? asset('storage/'.Auth::user()->avatar) : 'https://ui-avatars.com/api/?length=1&name='.urlencode(preg_replace('/[^a-zA-Z0-9\s]/', '', Auth::user()->name)).'&background=e0e7ff&color=4338ca&bold=true' }}" style="width: 45px; height: 45px; border-radius: 50%; object-fit: cover;" alt="">
                                    <div>
                                        <div style="font-weight: 700; color: #1e293b;">{{ Auth::user()->name }}</div>
                                        <div style="font-size: 0.75rem; color: #64748b;">{{ Auth::user()->email }}</div>
                                    </div>
                                </div>
                                <a href="{{ route('profile.index') }}" class="dropdown-item">
                                    <div style="color: #64748b; width: 20px;"><i class="fa-regular fa-user"></i></div>
                                    <div>{{ __('Profil Saya') }}</div>
                                </a>
                                <a href="{{ route('settings.index') }}" class="dropdown-item">
                                    <div style="color: #64748b; width: 20px;"><i class="fa-solid fa-sliders"></i></div>
                                    <div>{{ __('Pengaturan') }}</div>
                                </a>
                                <div style="border-top: 1px solid var(--border-color);">
                                    <form method="POST" action="{{ route('logout') }}" style="margin:0;">
                                        @csrf
                                        <button type="submit" class="dropdown-item" style="width: 100%; border: none; background: none; text-align: left; color: #ef4444;">
                                            <div style="width: 20px;"><i class="fa-solid fa-arrow-right-from-bracket"></i></div>
                                            <div>{{ __('Logout') }}</div>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </header>

                <main class="main-content anim-fade-in">
                    
                    {{-- Global Alerts --}}
                    @if(session('success'))
                        <div style="background: #ecfdf5; color: #065f46; padding: 1rem 1.25rem; border-radius: 12px; margin-bottom: 1.5rem; border: 1px solid #a7f3d0; display: flex; align-items: center; gap: 0.75rem; font-weight: 600; box-shadow: 0 4px 6px -1px rgba(16, 185, 129, 0.1);">
                            <i class="fa-solid fa-circle-check" style="font-size: 1.25rem; color: #10b981;"></i> 
                            <div>{{ session('success') }}</div>
                        </div>
                    @endif

                    @if(session('error'))
                        <div style="background: #fef2f2; color: #991b1b; padding: 1rem 1.25rem; border-radius: 12px; margin-bottom: 1.5rem; border: 1px solid #fecaca; display: flex; align-items: center; gap: 0.75rem; font-weight: 600; box-shadow: 0 4px 6px -1px rgba(239, 68, 68, 0.1);">
                            <i class="fa-solid fa-triangle-exclamation" style="font-size: 1.25rem; color: #ef4444;"></i> 
                            <div>{{ session('error') }}</div>
                        </div>
                    @endif

                    @if($errors->any())
                        <div style="background: #fef2f2; color: #991b1b; padding: 1rem 1.25rem; border-radius: 12px; margin-bottom: 1.5rem; border: 1px solid #fecaca; box-shadow: 0 4px 6px -1px rgba(239, 68, 68, 0.1);">
                            <div style="display: flex; align-items: center; gap: 0.5rem; font-weight: 700; margin-bottom: 0.5rem;">
                                <i class="fa-solid fa-circle-xmark" style="color: #ef4444;"></i> {{ __('Validasi Gagal:') }}
                            </div>
                            <ul style="margin: 0; padding-left: 2rem; font-size: 0.9rem; color: #7f1d1d;">
                                @foreach($errors->all() as $err)
                                    <li>{{ $err }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    @yield('content')
                </main>
            </div>
        </div>

        <script>
            // Responsive & Desktop Sidebar Toggle
            const sidebarToggle = document.getElementById('sidebar-toggle');
            if(sidebarToggle) {
                sidebarToggle.addEventListener('click', function() {
                    const sidebar = document.querySelector('.sidebar');
                    if (window.innerWidth <= 768) {
                        sidebar.classList.toggle('active'); // Slide in on mobile
                    } else {
                        sidebar.classList.toggle('collapsed'); // Slide out on desktop
                    }
                });
            }
        </script>
    @else
        @yield('content')
    @endauth

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script>
        $(document).ready(function() {
            $('table[id^="table-"]').each(function() {
                if (!$.fn.DataTable.isDataTable(this)) {
                    $(this).DataTable({
                        "dom": '<"top"lf>rt<"bottom"ip><"clear">',
                        "language": {
                            "search": '<i class="fa-solid fa-magnifying-glass"></i>',
                            "searchPlaceholder": "{{ __('Cari data...') }}",
                            "lengthMenu": "{{ __('Tampilkan _MENU_ baris') }}",
                            "info": "{!! __('Menampilkan _START_–_END_ dari <strong>_TOTAL_</strong> data') !!}",
                            "infoEmpty": "{{ __('Tidak ada data tersedia') }}",
                            "infoFiltered": "{{ __('(difilter dari _MAX_ total)') }}",
                            "emptyTable": '<div class="table-empty" style="text-align:center; padding: 3.5rem 2rem; color: #94a3b8;"><i class="fa-solid fa-folder-open" style="font-size: 2.5rem; opacity: 0.5; margin-bottom: 1rem; display: block;"></i><strong>{{ __('Belum ada data') }}</strong><p style="font-size: 0.9rem; margin-top: 0.5rem;">{{ __('Data belum tersedia untuk saat ini.') }}</p></div>',
                            "zeroRecords": '<div style="text-align:center; padding: 2rem; color: #94a3b8;"><i class="fa-solid fa-magnifying-glass" style="font-size: 1.5rem; opacity: 0.4; display: block; margin-bottom: 0.5rem;"></i>{{ __('Data tidak ditemukan') }}</div>',
                            "paginate": {
                                "first": '<i class="fa-solid fa-angles-left"></i>',
                                "last": '<i class="fa-solid fa-angles-right"></i>',
                                "next": '<i class="fa-solid fa-angle-right"></i>',
                                "previous": '<i class="fa-solid fa-angle-left"></i>'
                            }
                        },
                        "pageLength": 10,
                        "lengthMenu": [[5, 10, 25, 50, 100], [5, 10, 25, 50, 100]],
                        "autoWidth": false,
                        "order": [],
                        "drawCallback": function(settings) {
                            // Re-apply striping classes after each draw
                        }
                    });
                }
            });
        });

        // Dropdown toggle function
        function toggleDropdown(menuId) {
            document.querySelectorAll('.dropdown-menu').forEach(el => {
                if(el.id !== menuId) el.classList.remove('show');
            });
            document.getElementById(menuId).classList.toggle('show');
        }

        // Close dropdowns when clicking outside
        document.addEventListener('click', function(e) {
            if (!e.target.closest('.dropdown')) {
                document.querySelectorAll('.dropdown-menu').forEach(el => el.classList.remove('show'));
            }
        });

        // Realtime Notifications Fetcher
        function fetchNotifications() {
            fetch('/api/notifications')
                .then(res => res.json())
                .then(data => {
                    const badge = document.getElementById('notifBadge');
                    const list = document.getElementById('notifList');
                    const dashboardList = document.getElementById('dashboardNotifList');
                    
                    if (data.count > 0) {
                        badge.style.display = 'flex';
                        badge.textContent = data.count;
                        
                        let html = '';
                        let dashboardHtml = '';
                        
                        data.notifications.forEach(item => {
                            const colorClass = item.type === 'success' ? '#16a34a' : (item.type === 'danger' ? '#dc2626' : '#ea580c');
                            const bgClass = item.type === 'success' ? '#dcfce7' : (item.type === 'danger' ? '#fee2e2' : '#ffedd5');
                            
                            html += `
                                <a href="${item.link ? item.link : '#'}" class="dropdown-item" style="align-items: flex-start;">
                                    <div class="dropdown-icon" style="background: ${bgClass}; color: ${colorClass};">
                                        <i class="fa-solid ${item.icon}"></i>
                                    </div>
                                    <div style="flex: 1;">
                                        <div style="font-weight: 600; font-size: 0.85rem; margin-bottom: 0.2rem;">${item.title}</div>
                                        <div style="font-size: 0.75rem; color: #64748b; line-height: 1.4;">${item.desc}</div>
                                        <div style="font-size: 0.7rem; color: #94a3b8; margin-top: 0.3rem;"><i class="fa-regular fa-clock"></i> ${item.time}</div>
                                    </div>
                                </a>
                            `;
                            
                            // Color mapping for dashboard icons
                            const dbIconColor = item.type === 'success' ? 'green' : (item.type === 'danger' ? 'red' : 'orange');
                            dashboardHtml += `
                                <div class="notif-item">
                                    <div class="notif-icon ${dbIconColor}"><i class="fa-solid ${item.icon}"></i></div>
                                    <div class="notif-content">
                                        <div class="notif-title">${item.title}</div>
                                        <div class="notif-desc">${item.desc}</div>
                                    </div>
                                    <div class="notif-time">${item.time}</div>
                                </div>
                            `;
                        });
                        list.innerHTML = html;
                        if (dashboardList) { dashboardList.innerHTML = dashboardHtml; }
                    } else {
                        badge.style.display = 'none';
                        const emptyMsg = '<div style="padding: 2rem; text-align: center; color: #94a3b8; font-size: 0.85rem;">{{ __('Tidak ada notifikasi baru') }}</div>';
                        list.innerHTML = emptyMsg;
                        if (dashboardList) { dashboardList.innerHTML = emptyMsg; }
                    }
                })
                .catch(err => console.error('Error fetching notifications:', err));
        }

        // Initial fetch and poll every 10 seconds
        fetchNotifications();
        setInterval(fetchNotifications, 10000);

    </script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // Global Delete Confirmation using SweetAlert2 (Click Interception)
        document.addEventListener('click', function(e) {
            const btn = e.target.closest('.btn-delete-trigger');
            if (btn && btn.form && btn.form.classList.contains('form-delete')) {
                e.preventDefault(); // Stop just in case, though it's type="button" now
                
                const form = btn.form;
                const message = form.getAttribute('data-message') || '{{ __('Apakah Anda yakin ingin menghapus data ini?') }}';
                
                Swal.fire({
                    title: '<i class="fa-solid fa-triangle-exclamation"></i> Konfirmasi Hapus',
                    text: message,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#ef4444',
                    cancelButtonColor: '#94a3b8',
                    confirmButtonText: '{{ __('Ya, Hapus') }}',
                    cancelButtonText: '{{ __('Batal') }}',
                    reverseButtons: true,
                    background: '#ffffff',
                    customClass: {
                        title: 'swal2-title-custom'
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Use prototype to bypass any JS submit listeners (like DataTables/jQuery)
                        HTMLFormElement.prototype.submit.call(form);
                    }
                });
            }
        });
    </script>
    @stack('scripts')
</body>
</html>

