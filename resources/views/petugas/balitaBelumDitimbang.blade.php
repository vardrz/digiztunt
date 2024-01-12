<?php
$thisYear = date('Y');
$dataTahun = [$thisYear, $thisYear-1, $thisYear-2, $thisYear-3, $thisYear-4];
?>

@extends('layout.main')

@section('head')
<style>
  @media print {
    body {
      -webkit-print-color-adjust: exact;
    }
  }
</style>
@endsection

@section('content')
  <!-- Main content -->
  <section class="content">
    <div class="container-fluid">
      <div class="row">
        <div class="col-12 mt-3">
          <div class="card">
            <div class="card-body">
                <section id="tabs" class="project-tab">
                    <div class="container">
                        <div class="row">
                            <div class="col-md-12">
                                <nav>
                                    <div class="nav nav-tabs nav-fill" id="nav-tab" role="tablist">
                                        <a class="nav-item nav-link active" id="nav-belum-tab" data-toggle="tab" onclick="titleChange('belum')" href="#nav-belum" role="tab" aria-controls="nav-belum" aria-selected="true"><span id="btn-belum" class="w-100 btn btn-danger">Belum Ditimbang</span></a>
                                        <a class="nav-item nav-link" id="nav-sudah-tab" data-toggle="tab" onclick="titleChange('sudah')" href="#nav-sudah" role="tab" aria-controls="nav-sudah" aria-selected="false"><span id="btn-sudah" class="w-100 btn btn-success">Sudah Ditimbang</span></a>
                                    </div>
                                </nav>
                                <div class="tab-content" id="nav-tabContent">
                                    <div class="tab-pane fade show active" id="nav-belum" role="tabpanel" aria-labelledby="nav-belum-tab">
                                        <h5 id="titleBelum" class="mt-4 mb-3 text-center">{{ $title }}</h5>
                                        
                                        <div class="d-flex justify-content-center">
                                            <div class="input-group mb-4 mr-2" style="width: 150px">
                                              <div class="input-group-prepend">
                                                <span class="input-group-text">Tahun</span>
                                              </div>
                                              <select id="tahun1" class="form-control">
                                                @foreach ($dataTahun as $i)
                                                  <option value="{{ $i }}" @if($tahun == $i) selected @endif>{{ $i }}</option>
                                                @endforeach
                                              </select>
                                            </div>
                                            <div class="input-group mb-4" style="width: 190px">
                                              <div class="input-group-prepend">
                                                <span class="input-group-text">Bulan</span>
                                              </div>
                                              <select id="bulan1" class="form-control">
                                                @foreach ($listBulan as $i=>$val)
                                                <option value="{{ $i+1 }}" @if($bulan[0] == $val) selected @endif>{{ $val }}</option>
                                                @endforeach
                                              </select>
                                            </div>
                                        </div>

                                        <table id="belum" class="table table-bordered table-striped">
                                            <thead>
                                                <tr>
                                                    <th class="text-center">No.</th>
                                                    <th>NIK</th>
                                                    <th>Nama</th>
                                                    <th>Jenis Kelamin</th>
                                                    <th>Tanggal Lahir</th>
                                                    @if ((session('level') == 'pimpinan' && auth()->user()->area == 'all') || (session('level') == 'admin'))<th>Kelurahan</th>@endif
                                                    <th>Posyandu</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($belumDitimbang as $data)
                                                <tr>
                                                    <td class="text-center"></td>
                                                    <td>@if($data->nik == '-')<small>Belum memiliki NIK<small>@else{{ $data->nik }}@endif</td>
                                                    <td>{{ $data->nama }}</td>
                                                    <td>{{ $data->jenis_kelamin == 'lk' ? 'Laki-laki' : 'Perempuan' }}</td>
                                                    <td>{{ date('d-m-Y', strtotime ($data->tgl_lahir)) }}</td>
                                                    @if ((session('level') == 'pimpinan' && auth()->user()->area == 'all') || (session('level') == 'admin'))
                                                        <td>{{ $data->kelurahan }}</td>
                                                    @endif
                                                    <td>{{ $data->posyandu()->first()->name }}</td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="tab-pane fade" id="nav-sudah" role="tabpanel" aria-labelledby="nav-sudah-tab">
                                        <h5 id="titleSudah" class="mt-4 mb-3 text-center">{{ $title }}</h5>
                                        
                                        <div class="d-flex justify-content-center">
                                            <div class="input-group mb-4 mr-2" style="width: 150px">
                                              <div class="input-group-prepend">
                                                <span class="input-group-text">Tahun</span>
                                              </div>
                                              <select id="tahun2" class="form-control">
                                                @foreach ($dataTahun as $i)
                                                  <option value="{{ $i }}" @if($tahun == $i) selected @endif>{{ $i }}</option>
                                                @endforeach
                                              </select>
                                            </div>
                                            <div class="input-group mb-4" style="width: 190px">
                                              <div class="input-group-prepend">
                                                <span class="input-group-text">Bulan</span>
                                              </div>
                                              <select id="bulan2" class="form-control">
                                                @foreach ($listBulan as $i=>$val)
                                                <option value="{{ $i+1 }}" @if($bulan[0] == $val) selected @endif>{{ $val }}</option>
                                                @endforeach
                                              </select>
                                            </div>
                                        </div>

                                        <table id="sudah" class="table table-bordered table-striped">
                                            <thead>
                                                <tr>
                                                    <th class="text-center">No.</th>
                                                    <th>NIK</th>
                                                    <th>Nama</th>
                                                    <th>Jenis Kelamin</th>
                                                    <th>Usia</th>
                                                    @if ((session('level') == 'pimpinan' && auth()->user()->area == 'all') || (session('level') == 'admin'))<th>Kelurahan</th>@endif
                                                    <th>Posyandu</th>
                                                    <th>Tgl Penimbangan</th>
                                                    <th>BB</th>
                                                    <th>TB</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($sudahDitimbang as $data)
                                                <tr>
                                                    <td class="text-center"></td>
                                                    <td>@if($data->nik == '-')<small>Belum memiliki NIK<small>@else{{ $data->nik }}@endif</td>
                                                    <td>{{ $data->nama }}</td>
                                                    <td>{{ $data->jenis_kelamin == 'lk' ? 'Laki-laki' : 'Perempuan' }}</td>
                                                    <td>{{ $data->pelayanan()->whereBetween('tgl_pelayanan', $between)->orderBy('tgl_pelayanan', 'desc')->first()->usia }} Bulan</td>
                                                    @if ((session('level') == 'pimpinan' && auth()->user()->area == 'all') || (session('level') == 'admin'))
                                                        <td>{{ $data->kelurahan }}</td>
                                                    @endif
                                                    <td>{{ $data->posyandu()->first()->name }}</td>
                                                    <td>{{ date('d-m-Y', strtotime($data->pelayanan()->whereBetween('tgl_pelayanan', $between)->orderBy('tgl_pelayanan', 'desc')->first()->tgl_pelayanan)) }}</td>
                                                    <td>{{ $data->pelayanan()->whereBetween('tgl_pelayanan', $between)->orderBy('tgl_pelayanan', 'desc')->first()->bb }}</td>
                                                    <td>{{ $data->pelayanan()->whereBetween('tgl_pelayanan', $between)->orderBy('tgl_pelayanan', 'desc')->first()->tb }}</td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
            <!-- /.card-body -->
          </div>
        </div>
      </div>
    </div>
  </section>
  <!-- /.content -->
@endsection

@section('script')
<script>
    $(function () {
        // Table Sudah Ditimbang
        var belum = $("#belum").DataTable({
            "pageLength": 20,
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
                        columns: <?= ((session('level') == 'pimpinan' && auth()->user()->area == 'all') || (session('level') == 'admin'))
                            ? '[ 0, 1, 2, 3, 4, 5, 6 ]'
                            : '[ 0, 1, 2, 3, 4, 5 ]'
                        ?>
                    }
                },
                {
                    extend: 'excel',
                    className: 'btn btn-success',
                    exportOptions: {
                        columns: <?= ((session('level') == 'pimpinan' && auth()->user()->area == 'all') || (session('level') == 'admin'))
                            ? '[ 0, 1, 2, 3, 4, 5, 6 ]'
                            : '[ 0, 1, 2, 3, 4, 5 ]'
                        ?>
                    }
                },
                {
                    extend: 'print',
                    className: 'btn btn-dark',
                    exportOptions: {
                        columns: <?= ((session('level') == 'pimpinan' && auth()->user()->area == 'all') || (session('level') == 'admin'))
                            ? '[ 0, 1, 2, 3, 4, 5, 6 ]'
                            : '[ 0, 1, 2, 3, 4, 5 ]'
                        ?>
                    }
                },
            ],
        });

        belum.buttons().container().appendTo('#belum_wrapper .col-md-6:eq(0)');
        belum.on('order.dt search.dt', function () {
            belum.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
                cell.innerHTML = i+1;
                belum.cell(cell).invalidate('dom');
            });
        }).draw();
        
        // Table Sudah Ditimbang
        var sudah = $("#sudah").DataTable({
            "pageLength": 20,
            "columnDefs": [
                {targets:[0], orderable: false, searchable: false},
                {targets:[3], visible: false}
            ],
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
                        columns: <?= ((session('level') == 'pimpinan' && auth()->user()->area == 'all') || (session('level') == 'admin'))
                            ? '[ 0, 1, 2, 3, 4, 5, 6, 7, 8, 9 ]'
                            : '[ 0, 1, 2, 3, 4, 5, 6, 7, 8 ]'
                        ?>
                    }
                },
                {
                    extend: 'excel',
                    className: 'btn btn-success',
                    exportOptions: {
                        columns: <?= ((session('level') == 'pimpinan' && auth()->user()->area == 'all') || (session('level') == 'admin'))
                            ? '[ 0, 1, 2, 3, 4, 5, 6, 7, 8, 9 ]'
                            : '[ 0, 1, 2, 3, 4, 5, 6, 7, 8 ]'
                        ?>
                    }
                },
                {
                    extend: 'print',
                    className: 'btn btn-dark',
                    exportOptions: {
                        columns: <?= ((session('level') == 'pimpinan' && auth()->user()->area == 'all') || (session('level') == 'admin'))
                            ? '[ 0, 1, 2, 3, 4, 5, 6, 7, 8, 9 ]'
                            : '[ 0, 1, 2, 3, 4, 5, 6, 7, 8 ]'
                        ?>
                    }
                },
            ],
        });

        sudah.buttons().container().appendTo('#sudah_wrapper .col-md-6:eq(0)');
        sudah.on('order.dt search.dt', function () {
            sudah.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
                cell.innerHTML = i+1;
                sudah.cell(cell).invalidate('dom');
            });
        }).draw();
    });

    // redirect with year and month data
    var tahun1 = document.getElementById('tahun1');
    var tahun2 = document.getElementById('tahun2');
    var bulan1 = document.getElementById('bulan1');
    var bulan2 = document.getElementById('bulan2');

    tahun1.addEventListener('change', function() {
        window.location = '/belum-ditimbang/' + tahun1.value;
    });
    tahun2.addEventListener('change', function() {
        window.location = '/belum-ditimbang/' + tahun2.value;
    });
    bulan1.addEventListener('change', function() {
        window.location = '/belum-ditimbang/' + <?= $tahun ?> + '/' + bulan1.value;
    });
    bulan2.addEventListener('change', function() {
        window.location = '/belum-ditimbang/' + <?= $tahun ?> + '/' + bulan2.value;
    });

    // function to change title 'belum ditimbang' / 'sudah ditimbang'
    function titleChange(status){
        if(status == 'belum'){
            document.title = document.title.replace('Sudah', 'Belum');
            document.getElementById('btn-belum').classList.add('text-bold');
            document.getElementById('btn-sudah').classList.remove('text-bold');
            document.getElementById('titleBelum').innerHTML = document.title.replace('Sudah', 'Belum').replace(' - DIGIZTUNT', '');
        }else{
            document.title = document.title.replace('Belum', 'Sudah');
            document.getElementById('btn-sudah').classList.add('text-bold');
            document.getElementById('btn-belum').classList.remove('text-bold');
            document.getElementById('titleSudah').innerHTML = document.title.replace('Belum', 'Sudah').replace(' - DIGIZTUNT', '');
        }
    }
</script>
@endsection