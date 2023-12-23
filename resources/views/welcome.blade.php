<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>DIGIZTUNT - Digital Analisis Z-Score Status Gizi dan Kontrol Stunting</title>
  <link rel="icon" href="/theme/dist/img/favicon.png" type="image/x-icon" />

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="theme/plugins/fontawesome-free/css/all.min.css">
  <!-- icheck bootstrap -->
  <link rel="stylesheet" href="theme/plugins/icheck-bootstrap/icheck-bootstrap.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="theme/dist/css/adminlte.min.css">
  <!-- Toastr -->
  <link rel="stylesheet" href="theme/plugins/toastr/toastr.min.css">
</head>
<body class="hold-transition login-page">
<div class="login-box">
  <!-- /.login-logo -->
  <div class="card card-outline card-primary">
    <div class="card-header text-center pb-0">
      <a href="/" class="h1 text-bold">DIGIZTUNT</a>
      <p class="text-sm">Digital Analisis Z-Score Status Gizi dan<br>Kontrol Stunting</p>
    </div>
    <div class="card-body">
      {{-- <p class="login-box-msg">Masuk untuk mengakses menu.</p> --}}

      <form action="/" method="post">
        @csrf
        <div class="input-group mb-2">
          <input type="text" name="email" class="form-control" placeholder="Username / Email" required>
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-envelope"></span>
            </div>
          </div>
        </div>
        <div class="input-group mb-2">
          <input type="password" name="password" class="form-control" placeholder="Password" required>
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-lock"></span>
            </div>
          </div>
        </div>
        {{-- <div class="input-group mb-3">
            <select name="level" class="form-control" id="">
                <option value="petugas">Petugas</option>
                <option value="admin">Admin</option>
                <option value="pimpinan">Pimpinan</option>
            </select>
        </div> --}}
        <button type="submit" class="btn btn-primary btn-block">Masuk</button>
      </form>

      <hr>
      <div class="social-auth-links text-center mt-2 mb-3">
        <a href="http://wa.me/628100200300" class="btn btn-block btn-danger">
            <i class="fa fa-key mr-2"></i> Lupa Password?
        </a>
      </div>
      <!-- /.social-auth-links -->
    </div>
    <!-- /.card-body -->
  </div>
  <!-- /.card -->
</div>
<!-- /.login-box -->


<!-- jQuery -->
<script src="theme/plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="theme/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE App -->
<script src="theme/dist/js/adminlte.min.js"></script>
<!-- Toastr -->
<script src="theme/plugins/toastr/toastr.min.js"></script>

@if (session()->has('error'))
<script>
  window.onload = (event) => {
    toastr.error('Username atau password salah!')
  };
</script>
@endif

@error('email')
<script>
  window.onload = (event) => {
    toastr.error('Email tidak valid!')
  };
</script>
@enderror
</body>
</html>
