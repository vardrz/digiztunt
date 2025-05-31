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
                    <th>Tanggal Pendataan</th>
                    <th>Nama</th>
                    <th>Kelurahan</th>
                    <th>Posyandu</th>
                    <th>Usia</th>
                    <th>Jenis Kelamin</th>
                    <th>Berat Badan</th>
                    <th>Tinggi Badan</th>
                    <th>Lingkar Kepala</th>
                    <th>Verifikasi</th>
                  </tr>
                </thead>
                <tbody>
                  {{-- Data akan dimuat oleh DataTables --}}
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
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
      <div class="modal-content">
        <div class="h4 pt-4 pb-3 text-center w-100" style="border-bottom: solid 1px #b4b4b4" id="updateNama"></div>
        <div class="modal-body">
          <form method="post" action="/verifikasi/update">
            @csrf
            <input type="hidden" name="id" value="" id="updateID">
            <div class="form-group">
              <label for="bb">Berat Badan</label>
              <input type="text" name="bb" value="" class="form-control" id="updateBB" required>
            </div>
            <div class="form-group">
              <label for="tb">Tinggi Badan</label>
              <input type="text" name="tb" value="" class="form-control" id="updateTB" required>
            </div>
            <div class="form-group">
              <label for="lingkar_kepala">Lingkar Kepala</label>
              <input type="text" name="lingkar_kepala" value="" class="form-control" id="updateLK" required>
            </div>
            <div class="row">
              <div class="col-md-6">
                <input type="submit" id="submit" class="d-none">
                <button type="button" onclick="confirm()" class="btn btn-primary w-100 mb-1">Perbarui Data</button>
              </div>
              <div class="col-md-6">
                <button type="button" class="btn btn-danger w-100" data-dismiss="modal" id="closeModal">Batal</button>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>

  <script>
    const myModal = new bootstrap.Modal(document.getElementById('dataModal'));
    
    // Fill update modal form
    function update(id, nama, bb, tb, lingkar_kepala){
      myModal.show();
      document.getElementById('updateNama').innerHTML = nama;
      document.getElementById('updateID').value = id;
      document.getElementById('updateBB').value = bb;
      document.getElementById('updateTB').value = tb;
      document.getElementById('updateLK').value = lingkar_kepala;
    }

    // DataTable
    $(function () {
      var titleArr = [document.title.split(' ')];
      var title = '';
      for(var i=1; i < titleArr[0].length; i++){
        title += titleArr[0][i] + ' ';
      }
      
      var table = $("#balita").DataTable({
        ajax: "/verifikasi",
        processing: true,
        serverSide: true,
        columns: [
            {data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false},
            {data: 'tgl_pendataan', name: 'tgl_pelayanan'},
            {data: 'nama_balita', name: 'balita.nama'},
            {data: 'kelurahan', name: 'balita.kelurahan'},
            {data: 'posyandu', name: 'balita.posyanduRelation.name'},
            {data: 'usia_balita', name: 'usia'},
            {data: 'jenis_kelamin', name: 'balita.jenis_kelamin'},
            {data: 'bb_display', name: 'bb'},
            {data: 'tb_display', name: 'tb'},
            {data: 'lk_display', name: 'lingkar_kepala'},
            {data: 'action', name: 'action', orderable: false, searchable: false}
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
            title: `${title}\n`,
            className: 'btn btn-danger',
            exportOptions: {
              columns: [ 0, 1, 2, 3, 4, 5, 6, 7, 8, 9 ]
            }
          },
          {
            extend: 'excel',
            title: `${title}\n`,
            className: 'btn btn-success',
            exportOptions: {
              columns: [ 0, 1, 2, 3, 4, 5, 6, 7, 8, 9 ]
            }
          },
          {
            extend: 'print',
            title: title,
            className: 'btn btn-dark',
            exportOptions: {
              columns: [ 0, 1, 2, 3, 4, 5, 6, 7, 8, 9 ]
            }
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
          var notes = document.createElement('div');
          notes.className = 'text-sm text-primary text-bold d-block';
          notes.innerHTML = '* Keterangan:';
          var notes1 = document.createElement('div');
          notes1.className = 'text-sm text-primary d-block';
          notes1.innerHTML = '- Untuk melihat data pengukuran bulan sebelumnya, arahkan cursor ke angka.';
          var notes2 = document.createElement('div');
          notes2.className = 'text-sm text-primary d-block';
          notes2.innerHTML = '- Perbarui data jika terdapat data pengukuran yang janggal.';
          var notes3 = document.createElement('div');
          notes3.className = 'text-sm text-danger text-bold d-block';
          notes3.innerHTML = '* Aktifkan "Tampilkan Semua Data" sebelum export/print jika ingin semua data terambil.';

          textContainer.appendChild(notes);
          textContainer.appendChild(notes1);
          textContainer.appendChild(notes2);
          textContainer.appendChild(notes3);

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

    // Swal Confirm Update Data
    function confirm() {
      document.getElementById('closeModal').click();
      Swal.fire({
        icon: 'question',
        text: "Perbarui data ini?",
        showConfirmButton: true,
        showCancelButton: true,
        confirmButtonText: 'Ya',
        cancelButtonText: 'Batal',
        confirmButtonColor: '#007bff',
      }).then((result) => {
        if (result.isConfirmed) {
          setTimeout(function() {
            document.getElementById('submit').click();
          }, 500);
        }else{
          myModal.show();
        }
      })
    }

    // Fungsi verif sekarang akan submit form yang sudah dibuat oleh Yajra
    function verif(id, bb, tb) {
      Swal.fire({
        title: 'Anda yakin?',
        text: "Verifikasi data ini?",
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Ya, verifikasi!',
        cancelButtonText: 'Batal'
      }).then((result) => {
        if (result.isConfirmed) {
          // Cek apakah bb dan tb valid (bukan kosong atau bukan angka)
          if (isNaN(bb) || isNaN(tb)) {
            Swal.fire({
              icon: 'error',
              title: 'Data Tidak Valid',
              text: 'Data berat badan dan tinggi badan tidak valid.',
              confirmButtonColor: '#666'
            });
            return;
          }

          // Validasi berat badan
          if (bb < 1.5 || bb > 30) {
            Swal.fire({
              icon: 'warning',
              title: 'Berat Badan Tidak Normal',
              text: 'Sistem mendeteksi kejanggalan data, mohon cek kembali data pengukuran sebelum melakukan verifikasi!',
              confirmButtonColor: '#666'
            });
            return;
          }

          // Validasi tinggi badan
          if (tb < 40 || tb > 130) {
            Swal.fire({
              icon: 'warning',
              title: 'Tinggi Badan Tidak Normal',
              text: 'Sistem mendeteksi kejanggalan data, mohon cek kembali data pengukuran sebelum melakukan verifikasi!',
              confirmButtonColor: '#666'
            });
            return;
          }

          document.getElementById('form-' + id).submit();
        }
      });
    }
  </script>
@endsection