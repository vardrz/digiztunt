<?php 
function bbu($bbu_zscore, $bb){
  if ($bbu_zscore < -3) {
    echo "<td class='bg-danger'><b>Berat badan sangat kurang</b><br/>(" . $bb . " kg)</td>";
  } elseif ($bbu_zscore >= -3 && $bbu_zscore < -2) {
    echo "<td class='bg-warning'><b>Berat badan kurang</b><br/>(" . $bb . " kg)</td>";
  } elseif ($bbu_zscore >= -2 && $bbu_zscore <= 1) {
    echo "<td class='bg-success'><b>Berat badan normal</b><br/>(" . $bb . " kg)</td>";
  } elseif ($bbu_zscore > 1) {
    echo "<td class='bg-warning'><b>Berat badan lebih</b><br/>(" . $bb . " kg)</td>";
  }
}

function tbu($tbu_zscore, $tb){
  if ($tbu_zscore < -3) {
    echo "<td class='bg-danger'><b>Sangat Pendek</b><br/>(" . $tb . " cm)</td>";
  } elseif ($tbu_zscore >= -3 && $tbu_zscore < -2) {
    echo "<td class='bg-warning'><b>Pendek</b><br/>(" . $tb . " cm)</td>";
  } elseif ($tbu_zscore >= -2 && $tbu_zscore <= 3) {
    echo "<td class='bg-success'><b>Normal</b><br/>(" . $tb . " cm)</td>";
  } elseif ($tbu_zscore > 3) {
    echo "<td class='bg-success'><b>Tinggi</b><br/>(" . $tb . " cm)</td>";
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
              <table id="balita" class="table table-bordered table-striped">
                <thead>
                  <tr>
                    <th>No.</th>
                    <th>Tanggal</th>
                    <th>Nama</th>
                    <th>Usia</th>
                    <th>Jenis Kelamin</th>
                    <th>Berat Badan</th>
                    <th>Tinggi Badan</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach ($data as $d)
                  <tr>
                    <td></td>
                    <td>{{ $d->tgl_pelayanan }}</td>
                    <td>{{ $d->balita->nama }}</td>
                    <td>{{ $d->usia }} Bulan</td>
                    <td>{{ ($d->balita->jenis_kelamin == 'lk') ? 'Laki-laki' : 'Perempuan' }}</td>
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