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
                <div class="input-group mb-4 mr-2" style="width: 150px">
                  <div class="input-group-prepend">
                    <span class="input-group-text">Tahun</span>
                  </div>
                  <select id="tahun" class="form-control">
                    @php
                      $thisYear = date('Y');
                      $dataTahun = [$thisYear, $thisYear-1, $thisYear-2, $thisYear-3, $thisYear-4];
                    @endphp
                    @foreach ($dataTahun as $i)
                      <option value="{{ $i }}" @if($tahun == $i) selected @endif>{{ $i }}</option>
                    @endforeach
                  </select>
                </div>
                <div class="input-group mb-4" style="width: 190px">
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
                    <a href="/status/{{ $tahun }}/{{ $bulan[1]-1 }}">&#9664;</a> 
                  @endif
                  &nbsp;Data Bulan {{ $bulan[0] }}&nbsp;
                  @if($bulan[0] != 'Desember')
                    <a href="/status/{{ $tahun }}/{{ $bulan[1]+1 }}">&#9654;</a>
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
                  <!-- Data akan diisi oleh DataTables -->
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

    // Inisialisasi DataTable dengan server-side processing
    var table = $("#balita").DataTable({
      ajax: {
        url: "/data-status",
        data: function(d) {
          d.tahun = $("#tahun").val();
          d.bulan = $("#bulan").val();
        }
      },
      processing: true,
      serverSide: true,
      columns: [
        { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
        { data: 'tgl_pendataan', name: 'tgl_pelayanan' },
        { data: 'nama_balita', name: 'balita.nama' },
        { data: 'usia_balita', name: 'usia' },
        { data: 'jenis_kelamin', name: 'balita.jenis_kelamin' },
        @if ((session('level') == 'pimpinan' && auth()->user()->area == 'all') || (session('level') == 'admin'))
        { data: 'kelurahan', name: 'balita.kelurahan' },
        @endif
        { data: 'posyandu', name: 'balita.posyanduRelation.name' },
        { data: 'orang_tua', name: 'balita.nama_ibu', orderable: false },
        { 
          data: 'bb_display', 
          name: 'bb_display',
          orderable: true,
          createdCell: function (td, cellData, rowData, row, col) {
            // Ekstrak kelas dari cellData (yang berisi HTML)
            var match = cellData.match(/class='([^']*)'/);
            if (match && match[1]) {
              $(td).addClass(match[1]);
              $(td).html($(cellData).html());
            } else {
              $(td).html(cellData);
            }
          }
        },
        { 
          data: 'tb_display', 
          name: 'tb_display',
          orderable: true,
          createdCell: function (td, cellData, rowData, row, col) {
            // Ekstrak kelas dari cellData (yang berisi HTML)
            var match = cellData.match(/class='([^']*)'/);
            if (match && match[1]) {
              $(td).addClass(match[1]);
              $(td).html($(cellData).html());
            } else {
              $(td).html(cellData);
            }
          }
        }
      ],
      lengthChange: true,
      pageLength: 20,
      responsive: true,
      autoWidth: true,
      dom: "<'row'<'col-sm-12 col-md-6'B><'col-sm-12 col-md-6'f>>" + // Baris untuk Tombol dan Filter
            "<'row'<'col-sm-12'tr>>" + // Baris untuk Tabel (tr = table + processing)
            "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>", // Baris untuk Info dan Paginasi
      buttons: [
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
            columns: ':visible'
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
      initComplete: function(settings, json) {
        var api = this.api(); // Dapatkan instance API DataTables

        // Buat container utama dengan flex display
        var mainContainer = document.createElement('div');
        mainContainer.style.display = 'flex';
        mainContainer.style.justifyContent = 'space-between'; // Untuk memberi ruang antara kiri dan kanan
        mainContainer.style.alignItems = 'center'; // Menyelaraskan item secara vertikal
        mainContainer.style.marginTop = '10px';
        mainContainer.style.marginBottom = '10px';

        // Buat container untuk teks di sebelah kiri
        var textContainer = document.createElement('div');
        var notes = document.createElement('small');
        notes.className = 'text-danger text-bold d-block';
        notes.innerHTML = '*Aktifkan "Tampilkan Semua Data" sebelum export/print jika ingin semua data terambil.';
        textContainer.appendChild(notes);

        // Buat container untuk slider di sebelah kanan
        var sliderContainer = document.createElement('div');
        sliderContainer.style.display = 'flex';
        var sliderLabel = document.createElement('span');
        sliderLabel.innerHTML = 'Tampilkan Semua Data';
        sliderLabel.className = 'text-bold';
        var sliderCheckbox = document.createElement('input');
        sliderCheckbox.type = 'checkbox';
        sliderCheckbox.className = 'form-check-input';
        sliderCheckbox.style.width = '1em';
        sliderCheckbox.style.height = '1em';

        // Event listener untuk slider
        sliderCheckbox.addEventListener('change', function() {
          if (this.checked) {
            api.page.len(-1).draw(); // Tampilkan semua data
          } else {
            api.page.len(20).draw(); // Kembalikan ke default (misalnya 20, atau nilai dari lengthMenu)
          }
        });

        sliderContainer.appendChild(sliderLabel);
        sliderContainer.appendChild(sliderCheckbox);

        // Tambahkan textContainer dan sliderContainer ke mainContainer
        mainContainer.appendChild(textContainer);
        mainContainer.appendChild(sliderContainer);

        // Sisipkan mainContainer ke dalam DOM
        var wrapper = document.getElementById('balita_wrapper');
        if (wrapper) {
          var rowsInWrapper = $(wrapper).find('> .row');
          if (rowsInWrapper.length > 1) {
            $(rowsInWrapper[1]).before(mainContainer);
          } else if (rowsInWrapper.length === 1) {
            $(rowsInWrapper[0]).after(mainContainer);
          } else {
            $(wrapper).prepend(mainContainer);
          }
        }
      }
    });

    table.on('preXhr.dt', function ( e, settings, data ) {
      console.log('preXhr.dt: Requesting data from server...');
      Swal.fire({
        title: 'Memperbarui Data',
        text: 'Mohon tunggu sebentar...', // Opsional
        allowOutsideClick: false,
        didOpen: () => {
          Swal.showLoading();
        }
      });
    });

    table.on('draw.dt', function (e, settings) {
      console.log('draw.dt: Table redrawn.');
      Swal.close();
    });

    // debounce search
    var searchInput = $('div.dataTables_filter input');
    var debounceTimer;
    searchInput.off('keyup.DT input.DT'); // Hapus event listener default
    searchInput.on('keyup input', function() {
      clearTimeout(debounceTimer);
      var that = this;
      debounceTimer = setTimeout(function() {
          table.search($(that).val()).draw();
      }, 1000); // Delay 1 detik (1000 ms)
    });
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