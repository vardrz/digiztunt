@php
$thisYear = date('Y');
$dataTahun = [$thisYear, $thisYear-1, $thisYear-2, $thisYear-3, $thisYear-4];
@endphp

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
                                              <select id="tahun1" class="form-control filter-change">
                                                @foreach ($dataTahun as $i)
                                                  <option value="{{ $i }}" @if($tahun == $i) selected @endif>{{ $i }}</option>
                                                @endforeach
                                              </select>
                                            </div>
                                            <div class="input-group mb-4" style="width: 190px">
                                              <div class="input-group-prepend">
                                                <span class="input-group-text">Bulan</span>
                                              </div>
                                              <select id="bulan1" class="form-control filter-change" onchange="titleChange('belum')">
                                                @foreach ($listBulan as $index => $val)
                                                <option value="{{ $index + 1 }}" @if($bulan[1] == ($index + 1)) selected @endif>{{ $val }}</option>
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
                                                {{-- Data akan dimuat oleh DataTables --}}
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
                                              <select id="tahun2" class="form-control filter-change">
                                                @foreach ($dataTahun as $i)
                                                  <option value="{{ $i }}" @if($tahun == $i) selected @endif>{{ $i }}</option>
                                                @endforeach
                                              </select>
                                            </div>
                                            <div class="input-group mb-4" style="width: 190px">
                                              <div class="input-group-prepend">
                                                <span class="input-group-text">Bulan</span>
                                              </div>
                                              <select id="bulan2" class="form-control filter-change" onchange="titleChange('sudah')">
                                                @foreach ($listBulan as $index => $val)
                                                <option value="{{ $index + 1 }}" @if($bulan[1] == ($index + 1)) selected @endif>{{ $val }}</option>
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
                                               {{-- Data akan dimuat oleh DataTables --}}
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
    var tabelBelum, tabelSudah;
    var selectedTahun = {{ $tahun }};
    var selectedBulan = {{ $bulan[1] }};

    $(function () {
        // Table Belum Ditimbang
        tabelBelum = $("#belum").DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "/data/balita-belum-ditimbang",
                data: function (d) {
                    d.tahun = selectedTahun;
                    d.bulan = selectedBulan;
                }
            },
            columns: [
                {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false, className: 'text-center'},
                {data: 'nik_display', name: 'nik'},
                {data: 'nama', name: 'nama'},
                {data: 'jenis_kelamin_display', name: 'jenis_kelamin'},
                {data: 'tgl_lahir_display', name: 'tgl_lahir'},
                @if ((session('level') == 'pimpinan' && auth()->user()->area == 'all') || (session('level') == 'admin'))
                {data: 'kelurahan', name: 'balitas.kelurahan'},
                @endif
                {data: 'posyandu_name', name: 'posyanduRelation.name'}
            ],
            pageLength: 20,
            responsive: true, lengthChange: false, autoWidth: false,
            dom: "<'row'<'col-sm-12 col-md-6'B><'col-sm-12 col-md-6'f>>" + // Baris untuk Tombol dan Filter
                "<'row'<'col-sm-12'tr>>" + // Baris untuk Tabel (tr = table + processing)
                "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>", // Baris untuk Info dan Paginasi
            buttons: [
                { extend: 'colvis', className: 'btn btn-info', text: 'Kolom' },
                { extend: 'pdf', className: 'btn btn-danger', exportOptions: { columns: ':visible' } },
                { extend: 'excel', className: 'btn btn-success', exportOptions: { columns: ':visible' }, customizeData: function (data) {
                    for (var i = 0; i < data.body.length; i++) {
                        if(data.body[i][1]) data.body[i][1] = '\u200C' + data.body[i][1]; // NIK
                    }
                } },
                { extend: 'print', className: 'btn btn-dark', exportOptions: { columns: ':visible' } },
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
                var notes = document.createElement('span');
                notes.className = 'text-sm text-danger text-bold d-block';
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
                var wrapper = document.getElementById('belum_wrapper');
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
        tabelBelum.buttons().container().appendTo('#belum_wrapper .col-md-6:eq(0)');
        tabelBelum.on('preXhr.dt', function ( e, settings, data ) {
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
        tabelBelum.on('draw.dt', function (e, settings) {
            console.log('draw.dt: Table redrawn.');
            Swal.close();
        });
        
        // Table Sudah Ditimbang
        tabelSudah = $("#sudah").DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "/data/balita-sudah-ditimbang",
                data: function (d) {
                    d.tahun = selectedTahun;
                    d.bulan = selectedBulan;
                }
            },
            columns: [
                {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false, className: 'text-center'},
                {data: 'nik_display', name: 'nik'},
                {data: 'nama', name: 'nama'},
                {data: 'jenis_kelamin_display', name: 'jenis_kelamin', visible: false},
                {data: 'usia_saat_timbang', name: 'usia_saat_timbang_db', searchable: false},
                @if ((session('level') == 'pimpinan' && auth()->user()->area == 'all') || (session('level') == 'admin'))
                {data: 'kelurahan', name: 'kelurahan'},
                @endif
                {data: 'posyandu_name', name: 'posyanduRelation.name'},
                {data: 'tgl_penimbangan_display', name: 'tgl_penimbangan_db', searchable: false},
                {data: 'bb_saat_timbang', name: 'bb_saat_timbang_db', searchable: false},
                {data: 'tb_saat_timbang', name: 'tb_saat_timbang_db', searchable: false}
            ],
            pageLength: 20,
            responsive: true, lengthChange: false, autoWidth: false,
            dom: "<'row'<'col-sm-12 col-md-6'B><'col-sm-12 col-md-6'f>>" + // Baris untuk Tombol dan Filter
                "<'row'<'col-sm-12'tr>>" + // Baris untuk Tabel (tr = table + processing)
                "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>", // Baris untuk Info dan Paginasi
            buttons: [
                { extend: 'colvis', className: 'btn btn-info', text: 'Kolom' },
                { extend: 'pdf', className: 'btn btn-danger', exportOptions: { columns: ':visible' } },
                { extend: 'excel', className: 'btn btn-success', exportOptions: { columns: ':visible' }, customizeData: function (data) {
                    for (var i = 0; i < data.body.length; i++) {
                        if(data.body[i][1]) data.body[i][1] = '\u200C' + data.body[i][1]; // NIK
                    }
                } },
                { extend: 'print', className: 'btn btn-dark', exportOptions: { columns: ':visible' } },
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
                var notes = document.createElement('span');
                notes.className = 'text-sm text-danger text-bold d-block';
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
                var wrapper = document.getElementById('sudah_wrapper');
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
        tabelSudah.buttons().container().appendTo('#sudah_wrapper .col-md-6:eq(0)');
        tabelSudah.on('preXhr.dt', function ( e, settings, data ) {
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
        tabelSudah.on('draw.dt', function (e, settings) {
            console.log('draw.dt: Table redrawn.');
            Swal.close();
        });

        // debounce search
        var searchInput = $('div.dataTables_filter input');
        var debounceTimer;
        searchInput.off('keyup.DT input.DT');
        searchInput.on('keyup input', function() {
            clearTimeout(debounceTimer);
            var that = this;
            debounceTimer = setTimeout(function() {
                let activeTab = $('.nav-tabs .active').attr('id');
                if (activeTab === 'nav-sudah-tab') {
                    tabelSudah.search($(that).val()).draw();
                } else if (activeTab === 'nav-belum-tab') {
                    tabelBelum.search($(that).val()).draw();
                }
            }, 1000); // Delay 1 detik (1000 ms)
        });

        // Handler untuk perubahan filter tahun dan bulan
        $('.filter-change').on('change', function(){
            // Ambil nilai dari filter yang aktif (berdasarkan tab yang aktif)
            var activeTab = $('.nav-tabs .active').attr('id');
            if (activeTab === 'nav-belum-tab') {
                selectedTahun = $('#tahun1').val();
                selectedBulan = $('#bulan1').val();
                tabelBelum.ajax.reload();
            } else if (activeTab === 'nav-sudah-tab') {
                selectedTahun = $('#tahun2').val();
                selectedBulan = $('#bulan2').val();
                tabelSudah.ajax.reload();
            }
            // Update URL browser tanpa reload halaman penuh
            var newUrl = '/belum-ditimbang/' + selectedTahun + '/' + selectedBulan;
            history.pushState(null, '', newUrl);
            // Update judul juga jika perlu
            // Anda mungkin perlu AJAX call kecil untuk mendapatkan judul baru dari server atau merekonstruksinya di client-side
        });

        // Sinkronisasi filter saat tab diganti
        $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
            var targetTab = $(e.target).attr("href") // activated tab
            if (targetTab === '#nav-belum'){
                $('#tahun1').val(selectedTahun).trigger('change.select2'); // Jika menggunakan select2
                $('#bulan1').val(selectedBulan).trigger('change.select2');
                // tabelBelum.ajax.reload(); // Tidak perlu reload jika filter sudah sinkron
            } else if (targetTab === '#nav-sudah'){
                $('#tahun2').val(selectedTahun).trigger('change.select2');
                $('#bulan2').val(selectedBulan).trigger('change.select2');
                // tabelSudah.ajax.reload(); // Tidak perlu reload jika filter sudah sinkron
            }
        });
    });

    // function to change title 'belum ditimbang' / 'sudah ditimbang'
    function titleChange(status){
        // Logika titleChange Anda bisa disederhanakan atau disesuaikan
        // karena judul utama sekarang mungkin tidak sepenuhnya bergantung pada tab saja
        // jika filter tahun/bulan juga mengubahnya.
        var baseTitle = "Balita " + (status == 'belum' ? "Belum" : "Sudah") + " Ditimbang";
        var area = "{{ $area }}";
        var bulanNama = status == 'belum' ? $('#bulan1 option:selected').text() : $('#bulan2 option:selected').text(); // Ambil nama bulan dari dropdown
        var tahunNama = selectedTahun;

        document.title = baseTitle + " " + area + " " + bulanNama + " " + tahunNama + " - DIGIZTUNT";
        if(status == 'belum'){
            $('#titleBelum').text(baseTitle + " " + area + " " + bulanNama + " " + tahunNama);
            $('#btn-belum').addClass('text-bold');
            $('#btn-sudah').removeClass('text-bold');
        }else{
            $('#titleSudah').text(baseTitle + " " + area + " " + bulanNama + " " + tahunNama);
            $('#btn-sudah').addClass('text-bold');
            $('#btn-belum').removeClass('text-bold');
        }
    }

    // Inisialisasi judul saat halaman pertama kali dimuat
    $(document).ready(function(){
        titleChange($('.nav-tabs .active').attr('id') === 'nav-belum-tab' ? 'belum' : 'sudah');
    });
</script>
@endsection