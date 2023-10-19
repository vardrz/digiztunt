<?php 
$no = 1;
function month($tanggalLahir) {
    $tanggalLahirObj = new DateTime($tanggalLahir);
    $hariIni = new DateTime();

    $selisihTahun = $hariIni->format('Y') - $tanggalLahirObj->format('Y');
    $selisihBulan = $hariIni->format('m') - $tanggalLahirObj->format('m');
    $selisihHari = $tanggalLahirObj->diff($hariIni)->format('%a');
    
    $totalBulan = $selisihTahun * 12 + $selisihBulan;

    if($selisihHari > 30){
      return $totalBulan . ' Bulan';
    }else{
      return $selisihHari . ' Hari';
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
              <h3 class="card-title">Daftar Balita</h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
              <table id="balita" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th class="text-center">No.</th>
                  <th>NIK</th>
                  <th>Nama</th>
                  <th>Jenis Kelamin</th>
                  <th>Tanggal Lahir</th>
                  <th>Usia</th>
                  <th>Kecamatan</th>
                  <th>Kelurahan</th>
                  @if (session('level') == 'admin')<th>Aksi</th>@endif
                </tr>
                </thead>
                <tbody>
                @foreach ($balitas as $balita)
                <tr>
                  <td class="text-center">{{ $no }}</td>
                  <td>{{ $balita->nik }}</td>
                  <td style="cursor: pointer;" onclick="dataModal('{{ $balita->nik }}','{{ $balita->nama_ibu }}','{{ $balita->nik_ibu }}','{{ $balita->nama_ayah }}','{{ $balita->nik_ayah }}','{{ $balita->no_kk }}')">{{ $balita->nama }}</td>
                  <td>{{ $balita->jenis_kelamin == 'lk' ? 'Laki-laki' : 'Perempuan' }}</td>
                  <td>{{ date('d-m-Y', strtotime ($balita->tgl_lahir)) }}</td>
                  <td>{{ month($balita->tgl_lahir) }}</td>
                  <td>{{ $balita->kecamatan }}</td>
                  <td>{{ $balita->kelurahan }}</td>
                  @if (session('level') == 'admin')<td class="text-center align-middle">
                    <a href="/balita/edit/{{ $balita->nik }}" class="btn btn-lg py-0 px-1 text-primary"><i class="fas fa-pen"></i></a>
                    <button type="button" onclick="confirm({{ $balita->nik }})" class="btn btn-lg py-0 px-1 text-danger"><i class="fas fa-trash"></i></button>
                    <form action="/balita/delete/{{ $balita->nik }}" method="post" id="{{ $balita->nik }}">@csrf</form>
                  </td>@endif
                </tr>
                <?php $no++; ?>
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

<script>
  // Func Status Gizi
  function statusGizi(tbu, bbu, tb, bb){
    if(bbu < -3){
      var bbuHasil = "<button class='btn btn-md btn-danger mt-1'><b>Berat badan sangat kurang</b> (" + bb + " kg)</button>";
    }else if(bbu >= -3 && bbu < -2) {
      var bbuHasil = "<button class='btn btn-md btn-warning mt-1'><b>Berat badan kurang</b> (" + bb + " kg)</button>";
    }else if(bbu >= -2 && bbu <= 1) {
      var bbuHasil = "<button class='btn btn-md btn-success mt-1'><b>Berat badan normal</b> (" + bb + " kg)</button>";
    }else if(bbu > 1) {
      var bbuHasil = "<button class='btn btn-md btn-warning mt-1'><b>Berat badan lebih</b> (" + bb + " kg)</button>";
    }

    if(tbu < -3){
      var tbuHasil = "<button class='btn btn-md btn-danger mt-1'><b>Tinggi badan sangat pendek</b> (" + tb + " cm)</button>";
    }else if(tbu >= -3 && tbu < -2) {
      var tbuHasil = "<button class='btn btn-md btn-warning mt-1'><b>Tinggi badan pendek</b> (" + tb + " cm)</button>";
    }else if(tbu >= -2 && tbu <= 3) {
      var tbuHasil = "<button class='btn btn-md btn-success mt-1'><b>Tinggi badan normal</b> (" + tb + " cm)</button>";
    }else if(tbu > 3) {
      var tbuHasil = "<button class='btn btn-md btn-success mt-1'><b>Tinggi badan lebih</b> (" + tb + " cm)</button>";
    }

    return bbuHasil + " &nbsp; " + tbuHasil
  }

  // Func Data Lengkap
  function dataModal(nik, nama_ibu, nik_ibu, nama_ayah, nik_ayah, no_kk){
    const myModal = new bootstrap.Modal(document.getElementById('dataModal')); // creating modal object
    myModal.show();

    // Data Ortu
    document.getElementById('data').innerHTML = 
      `<div class='col-sm-4'><table><tr><td width='65'><b>Ibu</b><br/><span class='text-xs text-bold'>(NIK)</span></td><td>` + nama_ibu + `<br/><span class='text-xs'>` + nik_ibu + `</span></td></tr></table></div>` +
      `<div class='col-sm-4'><table><tr><td width='65'><b>Ayah</b><br/><span class='text-xs text-bold'>(NIK)</span></td><td>` + nama_ayah + `<br/><span class='text-xs'>` + nik_ayah + `</span></td></tr></table></div>` +
      `<div class='col-sm-4'><table><tr><td width='65'><b>No. KK</b></td><td>` + no_kk + `</td></tr></table></div>`;

    // Pendataan
    var url = window.location.origin + '/pelayanan/find/' + nik;
    fetch(url)
    .then(
        response => response.json()
    ).then(
        data => {
            if(data.length > 0){
              // Status Gizi
              if(data[0]['verif'] == 'y'){
                var gizi = statusGizi(data[0]['tbu'], data[0]['bbu'], data[0]['tb'], data[0]['bb']);
              }else{
                var gizi = statusGizi(data[1]['tbu'], data[1]['bbu'], data[1]['tb'], data[1]['bb']);
              }

              // Riwayat Pendataan
              document.getElementById('riwayat').innerHTML = "";
              for(var i = 0; i < data.length; i++){
                document.getElementById('riwayat').innerHTML +=
                  `<tr><td>` + data[i]['tgl_pelayanan'] + `</td>` +
                  `<td>` + data[i]['usia'] + ` Bulan</td>` +
                  `<td>` + data[i]['tb'] + `</td>` +
                  `<td>` + data[i]['bb'] + `</td>` +
                  `<td>` + data[i]['lingkar_kepala'] + `</td></tr>`;
              }
            }else{
              var gizi = "<button class='btn btn-md btn-secondary mt'><b>Belum ada data</b></button>";
              document.getElementById('riwayat').innerHTML = "<td colspan='6'><center><i>Belum ada data.</i></center></td>";
            }

            document.getElementById('gizi').innerHTML = gizi;
        }
    );
  }
  
  window.onload = (event) => {
    setTimeout(
      function() {
        var notes = document.createElement('small');
        notes.className = 'text-primary';
        notes.innerHTML = '*Klik nama balita untuk info lebih lengkap.';

        var wrapper = document.getElementById('balita_wrapper');
        wrapper.insertBefore(notes, wrapper.querySelectorAll('.row')[1]);
      }, 1);
  }
</script>

<div class="modal fade" id="dataModal">
  <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-body">
        <h4 class="text-center">Status Gizi Terakhir</h4>
        <div class="text-center mb-4" id="gizi"></div>
        <h4>Data Orang Tua</h4>
        <div class="row" id="data"></div>
        <h4 class="mt-4">Riwayat Pendataan</h4>
        <table class="table table-bordered">
          <thead>
            <tr>
              <th>TANGGAL</th>
              <th>USIA</th>
              <th>TB (Cm)</th>
              <th>BB (Kg)</th>
              <th>LK (Cm)</th>
            </tr>
          </thead>
          <tbody id="riwayat"></tbody>
        </table>
      </div>
    </div>
  </div>
</div>
@endsection