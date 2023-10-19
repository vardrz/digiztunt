<?php 
function umur($tanggalLahir) {
    $tanggalLahirObj = new DateTime($tanggalLahir);
    $hariIni = new DateTime();

    $selisihTahun = $hariIni->format('Y') - $tanggalLahirObj->format('Y');
    $selisihBulan = $hariIni->format('m') - $tanggalLahirObj->format('m');
    // $totalBulan = $selisihTahun * 12 + $selisihBulan;

    $umur = $selisihTahun . ' Tahun ' . $selisihBulan . ' Bulan';

    return $umur;
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
              <h3 class="card-title">History Balita</h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
              <table id="balita" class="table table-bordered table-striped">
                <thead>
                  <tr>
                    <th>No.</th>
                    <th>NIK</th>
                    <th>Nama</th>
                    <th>Jenis Kelamin</th>
                    <th>Tanggal Lahir</th>
                    <th>Usia</th>
                    <th>Kecamatan</th>
                    <th>Kelurahan</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach ($data as $d)
                  <tr>
                    <td></td>
                    <td>{{ $d->nik }}</td>
                    <td style="cursor: pointer;" onclick="dataModal('{{ $d->nik }}','{{ $d->nama_ibu }}','{{ $d->nik_ibu }}','{{ $d->nama_ayah }}','{{ $d->nik_ayah }}','{{ $d->no_kk }}')">{{ $d->nama }}</td>
                    <td>{{ $d->jenis_kelamin == 'lk' ? 'Laki-laki' : 'Perempuan' }}</td>
                    <td>{{ date('d-m-Y', strtotime ($d->tgl_lahir)) }}</td>
                    <td>{{ umur($d->tgl_lahir) }}</td>
                    <td>{{ $d->kecamatan }}</td>
                    <td>{{ $d->kelurahan }}</td>
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

<script>
  function dataModal(nik, nama_ibu, nik_ibu, nama_ayah, nik_ayah, no_kk){
    const myModal = new bootstrap.Modal(document.getElementById('dataModal')); // creating modal object
    myModal.show();

    document.getElementById('data').innerHTML = 
      `<div class='col-sm-4'><table><tr><td width='65'><b>Ibu</b><br/><span class='text-xs text-bold'>(NIK)</span></td><td>` + nama_ibu + `<br/><span class='text-xs'>` + nik_ibu + `</span></td></tr></table></div>` +
      `<div class='col-sm-4'><table><tr><td width='65'><b>Ayah</b><br/><span class='text-xs text-bold'>(NIK)</span></td><td>` + nama_ayah + `<br/><span class='text-xs'>` + nik_ayah + `</span></td></tr></table></div>` +
      `<div class='col-sm-4'><table><tr><td width='65'><b>No. KK</b></td><td>` + no_kk + `</td></tr></table></div>`;

    var url = window.location.origin + '/pelayanan/find/' + nik;
    fetch(url)
    .then(
        response => response.json()
    ).then(
        data => {
            console.log(data.length);
            if(data.length > 0){
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
              document.getElementById('riwayat').innerHTML = "<td colspan='5'><center><i>Belum ada data.</i></center></td>";
            }
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
        // console.log(wrapper);
        wrapper.insertBefore(notes, wrapper.querySelectorAll('.row')[1]);
      }, 1);
  }
</script>

<div class="modal fade" id="dataModal">
  <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-body">
        <h4>Data Orang Tua</h4>
        <div class="row" id="data">
        </div>
        <h4 class="mt-4">Riwayat Pelayanan</h4>
        <table class="table table-bordered">
          <thead>
            <tr>
              <th>TANGGAL</th>
              <th>USIA</th>
              <th>TB</th>
              <th>BB</th>
              <th>LK</th>
            </tr>
          </thead>
          <tbody id="riwayat">
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
@endsection