<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>{{ $title }} - SIPosting</title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="/theme/plugins/fontawesome-free/css/all.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="/theme/dist/css/adminlte.min.css">
  <!-- Toastr -->
  <link rel="stylesheet" href="/theme/plugins/toastr/toastr.min.css">
  <!-- SweetAlert -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.28/dist/sweetalert2.min.css">
  <!-- DataTables -->
  <link rel="stylesheet" href="/theme/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
  <link rel="stylesheet" href="/theme/plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
  <link rel="stylesheet" href="/theme/plugins/datatables-buttons/css/buttons.bootstrap4.min.css">
</head>

<body class="hold-transition sidebar-mini layout-navbar-fixed layout-fixed">
<!-- Site wrapper -->
<div class="wrapper">
  <!-- Navbar -->
  <nav class="main-header navbar navbar-expand navbar-dark navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
      </li>
    </ul>

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
        <li class="nav-item">
            <a class="nav-link" data-widget="fullscreen" href="#" role="button">
                <i class="fas fa-expand-arrows-alt"></i>
            </a>
        </li>
        <!-- Accout Dropdown Menu -->
        <li class="nav-item dropdown">
          <a class="nav-link" data-toggle="dropdown" href="#">
            <i class="far fa-user"></i>
          </a>
          <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
            <span class="dropdown-item dropdown-header">{{ auth()->user()->name }}</span>
            <div class="dropdown-divider"></div>
            <a href="#" class="dropdown-item">
              <i class="fas fa-user mr-2"></i> Edit Profile
            </a>
            <div class="dropdown-divider"></div>
            <form action="/logout" method="post">
                @csrf
                <button type="submit" class="dropdown-item"><i class="fas fa-arrow-right mr-2"></i> Logout</button>
            </form>
            <div class="dropdown-divider"></div>
          </div>
        </li>
    </ul>
  </nav>
  <!-- /.navbar -->

  <!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="home" class="brand-link">
      {{-- <img src="/theme/dist/img/AdminLTELogo.png" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8"> --}}
      <span class="font-weight-bold d-flex justify-content-center"><span class="text-danger">SI</span>Posting</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar user (optional) -->
      <div class="user-panel pb-3 mt-3 mb-3 d-flex align-items-center">
        <div class="image">
          <img src="/theme/dist/img/avatar5.png" class="img-circle elevation-2" alt="User Image">
        </div>
        <div class="info">
          <span class="d-block text-white text-capitalize">{{ auth()->user()->name }}<br><small>{{ (session('level') == 'petugas') ? 'Petugas Posyandu' : (session('level') == 'admin' ? 'Petugas Puskesmas' : 'Pimpinan')}}</small></span>
        </div>
      </div>

      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <li class="nav-item">
            <a href="/home" class="nav-link {{ Request::is('home') ? 'active' : '' }}">
              <i class="nav-icon fas fa-home"></i>
              <p>
                Home
                {{-- <span class="right badge badge-danger">New</span> --}}
              </p>
            </a>
          </li>
          <li class="nav-item {{ Request::is('balita') || Request::is('balita/new') || Request::is('balita/history') ? 'menu-open' : '' }}">
            <a href="#" class="nav-link {{ Request::is('balita') || Request::is('balita/new') || Request::is('balita/history') ? 'active' : '' }}">
              <i class="nav-icon fas fa-child"></i>
              <p>
                Balita
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              @if (session('level') != 'pimpinan')
              <li class="nav-item">
                <a href="/balita/new" class="nav-link {{ Request::is('balita/new') ? 'active' : '' }}">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Tambah Data Balita</p>
                </a>
              </li>
              @endif
              <li class="nav-item">
                <a href="/balita" class="nav-link {{ Request::is('balita') ? 'active' : '' }}">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Daftar Balita</p>
                </a>
              </li>
              @if (session('level') == 'admin')
              <li class="nav-item">
                <a href="/balita/history" class="nav-link {{ Request::is('balita/history') ? 'active' : '' }}">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Histori Balita</p>
                </a>
              </li>
              @endif
            </ul>
          </li>

          @if (session('level') != 'pimpinan')
          <li class="nav-item">
            <a href="/pelayanan" class="nav-link {{ Request::is('pelayanan') ? 'active' : '' }}">
              <i class="nav-icon fas fa-book"></i>
              <p>
                Pelayanan
              </p>
            </a>
          </li>
          @endif

          @if (session('level') != 'petugas')
          <li class="nav-item {{ Request::is('verifikasi') || Request::is('status') ? 'menu-open' : '' }}">
            <a href="#" class="nav-link {{ Request::is('verifikasi') ? 'active' : '' }}">
              <i class="nav-icon fas fa-user-md"></i>
              <p>
                Stunting
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              @if (session('level') == 'admin')
              <li class="nav-item">
                <a href="/verifikasi" class="nav-link {{ Request::is('verifikasi') ? 'active' : '' }}">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Verifikasi Pendataan</p>
                </a>
              </li>
              @endif
              <li class="nav-item">
                <a href="/status" class="nav-link {{ Request::is('status') ? 'active' : '' }}">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Hasil Pendataan</p>
                </a>
              </li>
            </ul>
          </li>
          @endif

          @if (session('level') != 'petugas')
          <li class="nav-item">
            <a href="/laporan" class="nav-link">
              <i class="nav-icon fas fa-file-pdf"></i>
              <p>
                Laporan
              </p>
            </a>
          </li>
          @endif

        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">

    @yield('content')
  
  </div>
  <!-- /.content-wrapper -->

  <footer class="main-footer">
    <div class="float-right d-none d-sm-block">
      <a href="http://vaard.site" class="text-bold">&copy; Vard <span class="text-dark">Project</span></a>
    </div>
    <strong>SIPosting</strong>. Kota Pekalongan
  </footer>

  <!-- Control Sidebar -->
  <aside class="control-sidebar control-sidebar-dark">
    <!-- Control sidebar content goes here -->
  </aside>
  <!-- /.control-sidebar -->
</div>
<!-- ./wrapper -->

<!-- jQuery -->
<script src="/theme/plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="/theme/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE App -->
<script src="/theme/dist/js/adminlte.min.js"></script>
<!-- SweetAlert -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.28/dist/sweetalert2.all.min.js"></script>
<!-- Toastr -->
<script src="/theme/plugins/toastr/toastr.min.js"></script>
<!-- DataTables  & Plugins -->
<script src="/theme/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="/theme/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
<script src="/theme/plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
<script src="/theme/plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
<script src="/theme/plugins/datatables-buttons/js/dataTables.buttons.min.js"></script>
<script src="/theme/plugins/datatables-buttons/js/buttons.bootstrap4.min.js"></script>
<script src="/theme/plugins/jszip/jszip.min.js"></script>
<script src="/theme/plugins/pdfmake/pdfmake.min.js"></script>
<script src="/theme/plugins/pdfmake/vfs_fonts.js"></script>
<script src="/theme/plugins/datatables-buttons/js/buttons.html5.min.js"></script>
<script src="/theme/plugins/datatables-buttons/js/buttons.print.min.js"></script>
<script src="/theme/plugins/datatables-buttons/js/buttons.colVis.min.js"></script>


@if(
  Request::is('balita') ||
  Request::is('balita/history') ||
  Request::is('verifikasi') ||
  Request::is('status') ||
  Request::is('status/1') ||
  Request::is('status/2') ||
  Request::is('status/3') ||
  Request::is('status/4') ||
  Request::is('status/5') ||
  Request::is('status/6') ||
  Request::is('status/7') ||
  Request::is('status/8') ||
  Request::is('status/9') ||
  Request::is('status/10') ||
  Request::is('status/11') ||
  Request::is('status/12')
)
<script>
  $(function () {
    var table = $("#balita").DataTable({
      "columnDefs": [{targets:[0], orderable: false, searchable: false}],
      "responsive": true, "lengthChange": false, "autoWidth": false,
      "buttons": [
        {
            extend: 'colvis',
            className: 'btn btn-info',
            text: 'Kolom'
        },
        {
            extend: 'pdf',
            className: 'btn btn-danger',
            exportOptions: {
              columns: [ 0, 1, 2, 3, 4, 5, 6, 7 ]
            }
        },
        {
            extend: 'excel',
            className: 'btn btn-success',
            exportOptions: {
              columns: [ 0, 1, 2, 3, 4, 5, 6, 7 ]
            }
        },
        {
            extend: 'print',
            className: 'btn btn-dark',
            exportOptions: {
              columns: [ 0, 1, 2, 3, 4, 5, 6, 7 ]
            }
        }
    ],
    });

    table.buttons().container().appendTo('#balita_wrapper .col-md-6:eq(0)');
    table.on('order.dt search.dt', function () {
      table.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
        cell.innerHTML = i+1;
        table.cell(cell).invalidate('dom');
      });
    }).draw();
  });
</script>
@endif

@if(Request::is('balita') && session('level') == 'admin')
<script>
  function confirm(nik) {
    Swal.fire({
      html: "<h2>Hapus data ini?</h2>",
      showConfirmButton: false,
      showDenyButton: true,
      showCancelButton: true,
      denyButtonText: 'Hapus',
      cancelButtonText: 'Batal',
    }).then((result) => {
      if (result.isDenied) {
        Swal.fire({
          showConfirmButton: false,
          icon: 'success',
          text: 'Data dihapus.',
        });
        setTimeout(function() {
          document.getElementById(nik).submit();
        }, 2000);
      }
    })
  }
</script>
@endif

@if(Request::is('verifikasi'))
<script>
  function verif(nik) {
    Swal.fire({
      html: "<h2>Verifikasi data ini?</h2>",
      showConfirmButton: true,
      showDenyButton: false,
      showCancelButton: true,
      ConfirmButtonText: 'Ya',
      cancelButtonText: 'Batal',
    }).then((result) => {
      if (result.isConfirmed) {
        Swal.fire({
          showConfirmButton: false,
          icon: 'success',
          text: 'Berhasil.',
        });
        setTimeout(function() {
          document.getElementById(nik).submit();
        }, 2000);
      }
    })
  }
</script>
@endif

@if (session()->has('success'))
<?= "<script>window.onload = (event) => {toastr.success("; ?>
<?= "'" . session()->get('success') . "'"; ?>
<?= ")};</script> "; ?>
@endif

@if (session()->has('error'))
<?= "<script>window.onload = (event) => {toastr.error("; ?>
<?= "'" . session()->get('error') . "'"; ?>
<?= ")};</script> "; ?>
@endif

</body>
</html>
