@extends('layout.main')

@section('content')

  <!-- Main content -->
  <section class="content">
    <div class="container-fluid">
      <div class="row">
        <div class="col-12 mt-3">
          <!-- Default box -->
          <div class="row">
            <div class="col-md-4">
              <div class="small-box bg-secondary">
                <div class="inner text-center py-4">
                  <p>Jumlah Balita</p>
                  <span class="h1">{{ $balitas }}</span>
                </div>
                <a href="balita" class="small-box-footer">Selengkapnya <i class="fas fa-arrow-circle-right"></i></a>
              </div>
            </div>
            <div class="col-md-4">
              <div class="small-box bg-danger">
                <div class="inner text-center py-4">
                  <p>Total perlu diverifikasi</p>
                  <span class="h1">{{ $unverif }}</span>
                </div>
                <a href="verifikasi" class="small-box-footer">Verifikasi <i class="fas fa-arrow-circle-right"></i></a>
              </div>
            </div>
            <div class="col-md-4">
              <div class="small-box bg-success">
                <div class="inner text-center py-4">
                  <p>Sudah diverifikasi bulan ini</p>
                  <span class="h1">{{ $verif }}</span>
                </div>
                <a href="status" class="small-box-footer">Hasil <i class="fas fa-arrow-circle-right"></i></a>
              </div>
            </div>
            <div class="col-md-12">
              <div class="card">
                <div class="card-body">
                  {!! $giziBuruk->container() !!}
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <script src="{{ $giziBuruk->cdn() }}"></script>
  {{ $giziBuruk->script() }}

  <!-- /.content -->
@endsection