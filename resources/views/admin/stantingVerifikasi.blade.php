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
                      <button type="button" onclick="verif({{ $d->id }})" class="btn btn-lg py-0 px-1 text-success" data-bs-placement="bottom" title="Accept"><i class="fas fa-check-circle"></i></a>
                      <button type="button" onclick="confirm()" class="btn btn-lg py-0 px-1 text-danger" data-bs-placement="bottom"  title="Delete"><i class="fas fa-ban"></i></button>
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
  <script>
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
  </script>
@endsection