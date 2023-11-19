@extends('layout.main')

@section('content')

  <!-- Main content -->
  <section class="content">
    <div class="container-fluid">
      <div class="row">
        <div class="col-12 mt-3">
          <div class="row">
            <div class="col-md-6">
              <div class="small-box bg-secondary">
                <div class="inner text-center py-4">
                  <span class="h1">{{ $balitas }}</span>
                  <p>Jumlah Balita</p>
                </div>
                <a href="balita" class="small-box-footer">Selengkapnya <i class="fas fa-arrow-circle-right"></i></a>
              </div>
            </div>
            <div class="col-md-6">
              <div class="small-box bg-danger">
                <div class="inner text-center py-4">
                  <span class="h1">{{ $terdata }}</span><span class="h2">/{{ $balitas }}</span>
                  <p>Balita Terdata Bulan Ini</p>
                </div>
                <a href="pelayanan" class="small-box-footer">Pendataan <i class="fas fa-arrow-circle-right"></i></a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
  <!-- /.content -->

@endsection