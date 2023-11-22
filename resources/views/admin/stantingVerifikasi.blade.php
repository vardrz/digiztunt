


<?php 
use App\Models\Pelayanan;
function lastData($d, $id){
  $lastData = Pelayanan::where('id_balita', $id)->limit(2)->orderBy('tgl_pelayanan', 'DESC')->get();
  if(count($lastData) > 1){
    switch ($d) {
      case 'tb':
        return $lastData[1]->tb . ' pada usia ' . $lastData[1]->usia . ' bulan.';
        break;
      case 'bb':
        return $lastData[1]->bb . ' pada usia ' . $lastData[1]->usia . ' bulan.';
        break;
      case 'lk':
        return $lastData[1]->lingkar_kepala . ' pada usia ' . $lastData[1]->usia . ' bulan.';
        break;
    }
  }else{
    return "Belum ada.";
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
              <div class="text-sm text-primary mb-4" style="line-height : 18px;">
                <b>*Keterangan:</b>
                <br>- Untuk melihat data pengukuran bulan sebelumnya, arahkan cursor ke angka.
                <br>- Perbarui data jika terdapat data pengukuran yang janggal. 
              </div>

              <table id="balita" class="table table-bordered table-striped">
                <thead>
                  <tr>
                    <th>No.</th>
                    <th>Tanggal Pendataan</th>
                    <th>Nama</th>
                    <th>Kelurahan</th>
                    <th>Usia</th>
                    <th>Jenis Kelamin</th>
                    <th>Berat Badan</th>
                    <th>Tinggi Badan</th>
                    <th>Lingkar Kepala</th>
                    <th>Verifikasi</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach ($data as $d)
                  <tr>
                    <td></td>
                    <td>{{ $d->tgl_pelayanan }}</td>
                    <td>{{ $d->balita->nama }}</td>
                    <td>{{ $d->balita->kelurahan }}</td>
                    <td>{{ $d->usia }} Bulan</td>
                    <td>{{ ($d->balita->jenis_kelamin == 'lk') ? 'Laki-laki' : 'Perempuan' }}</td>
                    <td>
                      <button class="btn p-0" style="cursor:help" data-bs-toggle="tooltip" data-bs-placement="bottom"  title="Berat badan sebelumnya :&NewLine;{{ lastData('bb', $d->id_balita) }}">
                        {{ $d->bb }}
                      </button>
                    </td>
                    <td>
                      <button class="btn p-0" style="cursor:help" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Tinggi badan sebelumnya :&NewLine;{{ lastData('tb', $d->id_balita) }}">
                        {{ $d->tb }}
                      </button>
                    </td>
                    <td>
                      <button class="btn p-0" style="cursor:help" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Lingkar kepala sebelumnya :&NewLine;{{ lastData('lk', $d->id_balita) }}">
                        {{ $d->lingkar_kepala }}
                      </button>
                    </td>
                    <td class="text-center align-middle">
                      <button onclick="verif({{ $d->id }})" class="btn btn-lg py-0 px-1 text-success" data-bs-placement="bottom" title="Accept"><i class="fas fa-check-square"></i></a>
                      <button onclick="update({{ $d->id }}, '{{ $d->balita->nama }}', {{ $d->bb }}, {{ $d->tb }}, {{ $d->lingkar_kepala }})" class="btn btn-lg py-0 px-1 text-primary" data-bs-placement="bottom"  title="Update"><i class="fas fa-pen-square"></i></button>

                      <form action="/verifikasi/accept" method="post" id="{{ $d->id }}">
                        @csrf
                        <input type="hidden" name="id_balita" value="{{ $d->id_balita }}">
                        <input type="hidden" name="id" value="{{ $d->id }}">
                        <input type="hidden" name="bb" value="{{ $d->bb }}">
                        <input type="hidden" name="tb" value="{{ $d->tb }}">
                        <input type="hidden" name="usia" value="{{ $d->usia }}">
                        <input type="hidden" name="jenis_kelamin" value="{{ $d->balita->jenis_kelamin }}">
                      </form>
                    </td>
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
    const myModal = new bootstrap.Modal(document.getElementById('dataModal')); // creating modal object
    
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
      var table = $("#balita").DataTable({
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
                columns: [ 0, 1, 2, 3, 4, 5, 6, 7, 8 ]
              }
          },
          {
              extend: 'excel',
              className: 'btn btn-success',
              exportOptions: {
                columns: [ 0, 1, 2, 3, 4, 5, 6, 7, 8 ]
              }
          },
          {
              extend: 'print',
              className: 'btn btn-dark',
              exportOptions: {
                columns: [ 0, 1, 2, 3, 4, 5, 6, 7, 8 ]
              }
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
  </script>
@endsection