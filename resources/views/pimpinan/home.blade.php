<?php 
$thisYear = date('Y');
$dataTahun = [$thisYear, $thisYear-1, $thisYear-2, $thisYear-3, $thisYear-4];
?>

@extends('layout.main')

@section('content')

  <!-- Main content -->
  <section class="content">
    <div class="container-fluid">
      <div class="row">
        <div class="col-12 mt-3">
          <!-- Default box -->
          <div class="row">
            <div class="col-md-12">
              <div class="card">
                <div class="card-body">
                  {{-- GiziBuruk Chart --}}
                  {!! $giziBuruk->container() !!}
                  {{-- Select Year & Month --}}
                  @if (session('level') == 'pimpinan' && auth()->user()->area == 'all')    
                  <div class="d-flex justify-content-center">
                    <div class="input-group mt-3 mr-2" style="width: 150px">
                      <div class="input-group-prepend">
                        <span class="input-group-text">Tahun</span>
                      </div>
                      <select id="tahun" class="form-control">
                        @foreach ($dataTahun as $i)
                          <option value="{{ $i }}" @if($tahun == $i) selected @endif>{{ $i }}</option>
                        @endforeach
                      </select>
                    </div>
                    <div class="input-group mt-3" style="width: 190px">
                      <div class="input-group-prepend">
                        <span class="input-group-text">Bulan</span>
                      </div>
                      <select id="bulan" class="form-control">
                        @foreach ($listBulan as $i=>$val)
                        <option value="{{ $i+1 }}" @if($bulan[0] == $val) selected @endif>{{ $val }}</option>
                        @endforeach
                      </select>
                    </div>
                  </div>
                  @endif
                </div>
              </div>
            </div>

            <div class="col-md-6">
              <div class="card">
                <div class="card-body">
                  {!! $utaraBalita->container() !!}
                </div>
              </div>
            </div>
            <div class="col-md-6">
              <div class="card">
                <div class="card-body">
                  {!! $utaraLastMonth->container() !!}
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>  
  <!-- /.content -->

@endsection
  
@section('script')
  <script src="{{ $giziBuruk->cdn() }}"></script>
  <script src="{{ $utaraLastMonth->cdn() }}"></script>
  <script src="{{ $utaraBalita->cdn() }}"></script>
  
  {{ $giziBuruk->script() }}
  {{ $utaraLastMonth->script() }}
  {{ $utaraBalita->script() }}

  <script>
    // redirect with year and month data
    var tahun = document.getElementById('tahun');
    var bulan = document.getElementById('bulan');

    tahun.addEventListener('change', function() {
        window.location = '/home/' + tahun.value;
    });
    bulan.addEventListener('change', function() {
        window.location = '/home/' + <?= $tahun ?> + '/' + bulan.value;
    });
  </script>
@endsection