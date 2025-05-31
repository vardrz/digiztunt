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
                  {{-- Data akan diisi oleh DataTables server-side --}}
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
        <div id="btnPDF"></div>
        <button type="button" class="btn btn-sm btn-success" onclick="printModal()">Print</button>
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
        <div class="text-center mb-5 @if(session('level') == 'petugas') d-none @endif" id="gizi"></div>
        
        <h4 class="text-center">Riwayat Penimbangan</h4>
        <table class="table table-bordered">
          <thead>
            <tr>
              <th>TANGGAL</th>
              <th>USIA</th>
              <th>BB (Kg)</th>
              <th>TB (Cm)</th>
              <th>LK (Cm)</th>
              <th>VERIF</th>
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
        scrollY: -window.scrollY // Atasi masalah scroll
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
          position = heightLeft - imgHeight + 10;
          doc.addPage();
          doc.addImage(imgData, 'PNG', 0, position, imgWidth, imgHeight);
          heightLeft -= pageHeight;
        }
        doc.save(nama + '_' + kelurahan +'.pdf');
      }
    );
  }

  // Print status gizi
  function printModal(){
    $("#modalPrint").printThis({ 
      importCSS: true,
      importStyle: true,
      printContainer: true,
    });
  }

  // Func Status Gizi
  function statusGizi(tbu, bbu, tb, bb){
    // Menentukan status dan kelas untuk berat badan
    let bbStatus, bbClass;
    if(bbu < -3){
      bbStatus = "Sangat kurang";
      bbClass = "danger";
      bbTextColor = "white";
    }else if(bbu >= -3 && bbu < -2) {
      bbStatus = "Kurang";
      bbClass = "warning";
      bbTextColor = "black";
    }else if(bbu >= -2 && bbu <= 1) {
      bbStatus = "Normal";
      bbClass = "success";
      bbTextColor = "white";
    }else if(bbu > 1) {
      bbStatus = "Lebih";
      bbClass = "warning";
      bbTextColor = "black";
    }
    
    // Menentukan status dan kelas untuk tinggi badan
    let tbStatus, tbClass;
    if(tbu < -3){
      tbStatus = "Sangat pendek";
      tbClass = "danger";
      tbTextColor = "white";
    }else if(tbu >= -3 && tbu < -2) {
      tbStatus = "Pendek";
      tbClass = "warning";
      tbTextColor = "black";
    }else if(tbu >= -2 && tbu <= 3) {
      tbStatus = "Normal";
      tbClass = "success";
      tbTextColor = "white";
    }else if(tbu > 3) {
      tbStatus = "Tinggi";
      tbClass = "success";
      tbTextColor = "white";
    }
    
    // Mengembalikan data dalam format tabel
    return `
      <table class="table table-bordered">
        <tbody>
          <tr>
            <td class="text-center text-bold" style="width: 50%">Berat Badan</td>
            <td class="text-center text-bold" style="width: 50%">Tinggi Badan</td>
          </tr>
          <tr>
            <td class="p-1">
              <div class="bg-${bbClass} py-3 text-center">
                <span class="text-${bbTextColor} text-bold">${bbStatus}</span>
                <span class="text-${bbTextColor} ml-2">${bb} kg</span>
              </div>
            </td>
            <td class="p-1">
              <div class="bg-${tbClass} py-3 text-center">
                <span class="text-${tbTextColor} text-bold">${tbStatus}</span>
                <span class="text-${tbTextColor} ml-2">${tb} cm</span>
              </div>
            </td>
          </tr>
        </tbody>
      </table>
    `;
  }

  // Func Data Lengkap
  function dataModal(id, nama, kelurahan, posyandu, nama_ibu, nik_ibu, nama_ayah, nik_ayah, no_kk){
    const myModal = new bootstrap.Modal(document.getElementById('dataModal'));
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
                let verifiedData = null;
                for(let i = 0; i < data.length; i++) {
                  if(data[i]['verif'] == 'y') {
                    verifiedData = data[i];
                    break;
                  }
                }
                
                var gizi = verifiedData ? 
                  statusGizi(verifiedData['tbu'], verifiedData['bbu'], verifiedData['tb'], verifiedData['bb']) :
                  "<span class='text-secondary mt-2'><i>Status gizi belum di verifikasi</i></span>";
  
                // Riwayat Pendataan
                document.getElementById('riwayat').innerHTML = "";
                for(var i = 0; i < data.length; i++){
                  document.getElementById('riwayat').innerHTML +=
                    `<tr><td>` + data[i]['tgl_pelayanan'] + `</td>` +
                    `<td>` + data[i]['usia'] + ` Bulan</td>` +
                    `<td>` + data[i]['bb'] + `</td>` +
                    `<td>` + data[i]['tb'] + `</td>` +
                    `<td>` + data[i]['lingkar_kepala'] + `</td>` +
                    `<td>` + (data[i]['verif'] === "y" ? "<span class='text-success'>Sudah</span>" : "Belum") + `</td></tr>`;
                }
              }else{
                // Status Gizi
                if(data[0]['verif'] == 'y'){
                  var gizi = statusGizi(data[0]['tbu'], data[0]['bbu'], data[0]['tb'], data[0]['bb']);
                }else{
                  var gizi = "<span class='text-secondary mt-2'><i>Status gizi belum di verifikasi</i></span>";
                }
  
                // Riwayat Pendataan
                document.getElementById('riwayat').innerHTML = "";
                for(var i = 0; i < data.length; i++){
                  document.getElementById('riwayat').innerHTML +=
                    `<tr><td>` + data[i]['tgl_pelayanan'] + `</td>` +
                    `<td>` + data[i]['usia'] + ` Bulan</td>` +
                    `<td>` + data[i]['bb'] + `</td>` +
                    `<td>` + data[i]['tb'] + `</td>` +
                    `<td>` + data[i]['lingkar_kepala'] + `</td>` +
                    `<td>` + (data[i]['verif'] === "y" ? "<span class='text-success'>Sudah</span>" : "Belum") + `</td></tr>`;
                }
              }
            }else{
              var gizi = "<span class='text-secondary mt-2'><i>Belum ada data</i></span>";
              document.getElementById('riwayat').innerHTML = "<td colspan='6'><center><i>Belum ada data.</i></center></td>";
            }

            document.getElementById('gizi').innerHTML = gizi;
        }
    );
  }
</script>
@endsection