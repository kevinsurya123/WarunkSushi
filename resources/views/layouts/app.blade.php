<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>Kasir WSI - @yield('title','Dashboard')</title>

  <!-- Bootstrap 5 CDN -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">

  <!-- Bootstrap Icons CDN -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

  <style>
    :root {
      --accent: #f0c14b;
      --accent-dark: #e0ad36;
      --muted: #6b7280;
      --bg: #f4f6f8;
      --card-radius: 12px;
    }
    html,body { height:100%; background:var(--bg); font-family: Inter, system-ui, -apple-system, "Segoe UI", Roboto, "Helvetica Neue", Arial; }
    .app-shell { display:flex; min-height:100vh; }
    .sidebar {
      width:240px;
      background:var(--accent);
      padding:28px 20px;
      position:fixed;
      top:0; left:0; bottom:0;
      box-shadow: 2px 0 8px rgba(0,0,0,0.04);
    }
    .sidebar .brand { color:#c93f2d; font-weight:700; font-size:20px; margin-bottom:6px; }
    .sidebar .role { color:rgba(0,0,0,0.45); margin-bottom:14px; }
    .sidebar a.nav-link { color:#0b3a66; padding:10px 6px; display:block; border-radius:8px; }
    .sidebar a.nav-link:hover { background:rgba(0,0,0,0.03); color:#07283a; text-decoration:none; }
    .sidebar .logout-btn { margin-top:18px; }

    .main { margin-left:260px; padding:28px; flex:1; }
    .topbar { display:flex; justify-content:space-between; align-items:center; margin-bottom:18px; }
    .card-spot { border-radius:var(--card-radius); box-shadow: 0 6px 18px rgba(15,23,42,0.06); }
    .stat-title { color:var(--muted); font-weight:600; margin-bottom:6px; }
    .stat-value { font-size:32px; font-weight:700; margin:0; }

    /* responsive */
    @media(max-width:900px){
      .sidebar { position:static; width:100%; display:flex; gap:12px; overflow:auto; padding:12px; }
      .main { margin-left:0; padding:12px; }
      .topbar { flex-direction:column; align-items:flex-start; gap:10px; }
    }
  </style>
</head>
<body>
  <div class="app-shell">
    <aside class="sidebar">
      <div class="brand">Warunk Sushi</div>
      <div class="role small text-muted">Owner</div>

      <nav class="nav flex-column mb-3">
        <a href="{{ route('home') }}" class="nav-link"> <i class="bi bi-speedometer2 me-2"></i> Dashboard</a>
        <a href="{{ route('users.index') }}" class="nav-link"> <i class="bi bi-people me-2"></i> Pegawai</a>
        <a href="{{ route('menus.index') }}" class="nav-link"> <i class="bi bi-list-ul me-2"></i> Menu</a>
        <a href="{{ route('customers.index') }}" class="nav-link"> <i class="bi bi-people-fill me-2"></i> Customers</a>
        <a href="{{ route('shifts.index') }}" class="nav-link"> <i class="bi bi-clock me-2"></i> Shift</a>
        <a href="{{ route('transactions.index') }}" class="nav-link"> <i class="bi bi-receipt me-2"></i> Live Menu</a>
        <a href="{{ route('reports.index') }}" class="nav-link">Report</a>
        <li><a href="{{ route('kitchen.index') }}">
    🍳 Kitchen Order
  </a>
</li>


      </nav>

      <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button class="btn btn-light w-100 logout-btn">Logout</button>
      </form>
    </aside>

    <main class="main">
      <div class="topbar">
        <h2 class="m-0">@yield('page_title','Dashboard')</h2>
        <div class="text-end small text-muted">Hi, {{ auth()->check() ? auth()->user()->nama_user : 'Guest' }}</div>
      </div>

      <div class="content-area">
        @yield('content')
      </div>

      <footer class="mt-5 small text-muted">
        Laravel v{{ Illuminate\Foundation\Application::VERSION }} (PHP v{{ phpversion() }})
      </footer>
    </main>
  </div>

     {{-- script bawaan --}}
    <script src="{{ asset('js/app.js') }}"></script>

    {{-- tempat script tambahan per-halaman (termasuk Chart.js) --}}
    @yield('scripts')
  </body>
</html>

</html>
