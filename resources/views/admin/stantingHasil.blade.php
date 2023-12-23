<?php 
$thisYear = date('Y');
$dataTahun = [$thisYear, $thisYear-1, $thisYear-2, $thisYear-3, $thisYear-4];

function bbu($bbu_zscore, $bb){
  if ($bbu_zscore < -3) {
    echo "<td width='100' class='bg-danger'><b>Berat badan sangat kurang </b><br/>(" . $bb . " kg)</td>";
  } elseif ($bbu_zscore >= -3 && $bbu_zscore < -2) {
    echo "<td width='100' class='bg-warning'><b>Berat badan kurang </b><br/>(" . $bb . " kg)</td>";
  } elseif ($bbu_zscore >= -2 && $bbu_zscore <= 1) {
    echo "<td width='100' class='bg-success'><b>Berat badan normal </b><br/>(" . $bb . " kg)</td>";
  } elseif ($bbu_zscore > 1) {
    echo "<td width='100' class='bg-warning'><b>Berat badan lebih </b><br/>(" . $bb . " kg)</td>";
  }
}

function tbu($tbu_zscore, $tb){
  if ($tbu_zscore < -3) {
    echo "<td width='100' class='bg-danger'><b>Sangat Pendek </b><br/>(" . $tb . " cm)</td>";
  } elseif ($tbu_zscore >= -3 && $tbu_zscore < -2) {
    echo "<td width='100' class='bg-warning'><b>Pendek </b><br/>(" . $tb . " cm)</td>";
  } elseif ($tbu_zscore >= -2 && $tbu_zscore <= 3) {
    echo "<td width='100' class='bg-success'><b>Normal </b><br/>(" . $tb . " cm)</td>";
  } elseif ($tbu_zscore > 3) {
    echo "<td width='100' class='bg-success'><b>Tinggi </b><br/>(" . $tb . " cm)</td>";
  }
}
?>

@extends('layout.main')

@section('content')
  <!-- Main content -->
  <section class="content">
    <div class="container-fluid">
      <div class="row">
        <div class="col-12 mt-3">
          <div class="card">
            <div class="card-header">
              <h3 class="card-title">{{ $title }}</h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
              <div class="d-flex justify-content-center">
                <div class="input-group mb-5 mr-2" style="width: 150px">
                  <div class="input-group-prepend">
                    <span class="input-group-text">Tahun</span>
                  </div>
                  <select id="tahun" class="form-control">
                    @foreach ($dataTahun as $i)
                      <option value="{{ $i }}" @if($tahun == $i) selected @endif>{{ $i }}</option>
                    @endforeach
                  </select>
                </div>
                <div class="input-group mb-5" style="width: 190px">
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

              <div class="text-center">
                <h4 class="mb-3">
                  @if($bulan[0] != 'Januari')
                    <a href="/status/{{ $tahun }}/{{ $bulan[1] }}">&#9664;</a> 
                  @endif
                  &nbsp;Data Bulan {{ $bulan[0] }}&nbsp;
                  @if($bulan[0] != 'Desember')
                    <a href="/status/{{ $tahun }}/{{ $bulan[1]+2 }}">&#9654;</a>
                  @endif
                </h4>
              </div>

              <table id="balita" class="table table-bordered table-striped">
                <thead>
                  <tr>
                    <th>No.</th>
                    <th>Tanggal Pendataan</th>
                    <th>Nama</th>
                    <th>Usia</th>
                    <th>Jenis Kelamin</th>
                    @if ((session('level') == 'pimpinan' && auth()->user()->area == 'all') || (session('level') == 'admin'))<th>Kelurahan</th>@endif
                    <th>Posyandu</th>
                    <th>Orang Tua <br><small>(Ibu & Bapak)</small></th>
                    <th>Berat Badan</th>
                    <th>Tinggi Badan</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach ($data as $d)
                  <tr>
                    <td></td>
                    <td>{{ date('d-m-Y', strtotime ($d->tgl_pelayanan)) }}</td>
                    <td>{{ $d->balita->nama }}</td>
                    <td>{{ $d->usia }} Bulan</td>
                    <td>{{ ($d->balita->jenis_kelamin == 'lk') ? 'Laki-laki' : 'Perempuan' }}</td>
                    @if ((session('level') == 'pimpinan' && auth()->user()->area == 'all') || (session('level') == 'admin'))
                      <td>{{ $d->balita->kelurahan }}</td>
                    @endif
                    <td>{{ $d->balita->posyandu()->first()->name }}</td>
                    <td width='100'>{{ $d->balita->nama_ibu }} & {{ $d->balita->nama_ayah }}</td>
                    {{ bbu($d->bbu, $d->bb) }}
                    {{ tbu($d->tbu, $d->tb) }}
                  </tr>
                  @endforeach
                </tbody>
              </table>
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
    var title = [document.title.split(' ')];
    var title1 = '';
    for(var i=0; i < title[0].length-4; i++){
      title1 += title[0][i] + ' ';
    }
    var title2 = title[0][title[0].length-4] + ' ' + title[0][title[0].length-3] + ' ' + title[0][title[0].length-2] + ' ' + title[0][title[0].length-1];
    console.log(title1);
    console.log(title2);

    var table = $("#balita").DataTable({
      "columnDefs": [
          {targets:[0], orderable: false, searchable: false, visible: false},
          {targets:[3,4,7], orderable: false},
      ],
      "responsive": true, "lengthChange": false, "autoWidth": true,
      "buttons": [
        {
            extend: 'colvis',
            className: 'btn btn-info',
            text: 'Kolom'
        },
        {
            extend: 'pdf',
            title: `${title1}\n${title2}\n`,
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
            title: `${title1}\n${title2}\n`,
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
            title: `<center>${title1}<br>${title2}</center><br>`,
            className: 'btn btn-dark',
            exportOptions: {columns: [ 0, 1, 2, 3, 4, 5, 6, 7, 8, 9 ]},
            exportOptions: {stripHtml: false}
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

<script>
  var tahun = document.getElementById('tahun');
  tahun.addEventListener('change', function() {
    window.location = '/status/' + tahun.value;
  });
  var bulan = document.getElementById('bulan');
  bulan.addEventListener('change', function() {
    window.location = '/status/' + <?= $tahun ?> + '/' + bulan.value;
  });
</script>
@endsection