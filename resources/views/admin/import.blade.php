@extends('layout.main')

@section('content')

  <!-- Main content -->
  <section class="content">
    <div class="container-fluid">
      <div class="row">
        <div class="col-6 mt-3">
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">Import Data Balita</h3>
                </div>
                <div class="card-body">
                    @error('file')
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <strong>Gagal!</strong> {{ $message }}.
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><small>x</small></button>
                      </div>
                    @enderror
                    <form method="post" enctype="multipart/form-data">
                        @csrf
                        <input type="file" name="file" accept=".csv,.xls,.xlsx" required>
                        <button type="submit" class="mt-2 btn btn-primary w-100">Import Data</button>
                    </form>
                </div>
            </div>
        </div>
      </div>
    </div>
  </section>

  <!-- /.content -->
@endsection