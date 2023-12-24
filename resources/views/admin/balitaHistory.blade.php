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
              <h3 class="card-title">{{ $title }}</h3>
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
                    @if ((session('level') == 'pimpinan' && auth()->user()->area == 'all') || (session('level') == 'admin'))<th>Kelurahan</th>@endif
                    <th>Posyandu</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach ($data as $d)
                  <tr>
                    <td></td>
                    <td>{{ $d->nik }}</td>
                    <td style="cursor: pointer;" onclick="dataModal('{{ $d->id }}','{{ $d->nama }}','{{ $d->kelurahan }}','{{ $d->posyandu()->first()->name }}','{{ $d->nama_ibu }}','{{ $d->nik_ibu }}','{{ $d->nama_ayah }}','{{ $d->nik_ayah }}','{{ $d->no_kk }}')">{{ $d->nama }}</td>
                    <td>{{ $d->jenis_kelamin == 'lk' ? 'Laki-laki' : 'Perempuan' }}</td>
                    <td>{{ date('d-m-Y', strtotime ($d->tgl_lahir)) }}</td>
                    <td>{{ umur($d->tgl_lahir) }}</td>
                    @if ((session('level') == 'pimpinan' && auth()->user()->area == 'all') || (session('level') == 'admin'))
                      <td>{{ $d->kelurahan }}</td>
                    @endif
                    <td>{{ $d->posyandu()->first()->name }}</td>
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
<div class="modal fade" id="dataModal">
  <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
    <div class="modal-content">
      <div class="modal-header">
        <div id="btnPDF">
          <button type="button" class="btn btn-sm btn-danger mr-1" onclick="savePDF()">PDF</button>
        </div>
        <button type="button" class="btn btn-sm btn-success" onclick="print()">Print</button>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
      </div>
      <div class="modal-body" id="modalPrint">
        <h2 class="text-center">Data Balita</h2>
        <table class="table table-bordered">
          <thead>
            <tr>
              <th>Nama</th>
              <th>Kelurahan</th>
              <th>Posyandu</th>
              <th>Ibu</th>
              <th>Ayah</th>
              <th>No. KK</th>
            </tr>
          </thead>
          <tbody id="data_balita"></tbody>
        </table>
        <h4 class="text-center mt-5 @if(session('level') == 'petugas') d-none @endif">Status Gizi Terakhir</h4>
        <div class="text-center mb-4 @if(session('level') == 'petugas') d-none @endif" id="gizi"></div>
        <h4 class="text-center">Riwayat Pendataan</h4>
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

<script src="/theme/plugins/printThis/printThis.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/1.5.3/jspdf.min.js"></script>
<script src="http://html2canvas.hertzen.com/dist/html2canvas.min.js"></script>
<script>
  // Func export modal data to pdf
  function savePDF(nama, kelurahan){
    const modal = document.getElementById('modalPrint');
    $('#modalPrint').show().scrollTop(0);
    html2canvas(modal, {
        windowWidth: document.documentElement.offsetWidth,
        windowHeight: modal.scrollHeight + 100,
      }).then(function(canvas) {
        var doc = new jsPDF('p', 'mm', 'a4');
        var imgData = canvas.toDataURL('image/png');
        var imgWidth = doc.internal.pageSize.getWidth();
        var pageHeight = doc.internal.pageSize.getHeight();
        var imgHeight = canvas.height * imgWidth / canvas.width;
        var heightLeft = imgHeight;
        var position = 10; // give some top padding to first page

        doc.addImage(imgData, 'PNG', 0, position, imgWidth, imgHeight);
        heightLeft -= pageHeight;

        while (heightLeft >= 0) {
          position = heightLeft - imgHeight + 10
          doc.addPage();
          doc.addImage(imgData, 'PNG', 0, position, imgWidth, imgHeight);
          heightLeft -= pageHeight;
        }
        doc.save(nama + '_' + kelurahan +'.pdf');
      }
    );
  }

  // Print status gizi
  function print(){
    $("#modalPrint").printThis({ 
      importCSS: true,
      importStyle: true,
      printContainer: true,
    });
  }

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
  function dataModal(id, nama, kelurahan, posyandu, nama_ibu, nik_ibu, nama_ayah, nik_ayah, no_kk){
    const myModal = new bootstrap.Modal(document.getElementById('dataModal')); // creating modal object
    myModal.show();
    
    // Button PDF
    document.getElementById('btnPDF').innerHTML =
      `<button type="button" class="btn btn-sm btn-danger mr-1" onclick="savePDF('${nama}','${kelurahan}')">PDF</button>`;

    // Data Balita
    document.getElementById('data_balita').innerHTML =
      `<tr><td>` + nama + `</td>` +
      `<td>` + kelurahan + `</td>` +
      `<td>` + posyandu + `</td>` +
      `<td>` + nama_ibu + `<br><small>NIK : ` + nik_ibu + `</small>` + `</td>` +
      `<td>` + nama_ayah + `<br><small>NIK : ` + nik_ayah + `</small>` + `</td>` +
      `<td>` + no_kk + `</td></tr>`;

    // Pendataan
    var url = window.location.origin + '/pelayanan/find/' + id;
    console.log('/pelayanan/find/' + id)
    fetch(url)
    .then(
        response => response.json()
    ).then(
        data => {
            if(data.length > 0){
              if(data.length > 1){
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
                // Status Gizi
                if(data[0]['verif'] == 'y'){
                  var gizi = statusGizi(data[0]['tbu'], data[0]['bbu'], data[0]['tb'], data[0]['bb']);
                }else{
                  var gizi = "<button class='btn btn-md btn-secondary mt'><b>Status gizi belum di proses</b></button>";
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
        // console.log(wrapper);
        wrapper.insertBefore(notes, wrapper.querySelectorAll('.row')[1]);
      }, 1);
  }
</script>
@endsection