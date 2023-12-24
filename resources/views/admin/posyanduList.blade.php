<?php $no=1; ?>
@extends('layout.main')

@section('content')
  <!-- Main content -->
  <section class="content">
    <div class="container-fluid">
      <div class="row">
        <div class="col-12 mt-3">
          <div class="card">
            <div class="card-header">
              <h3 class="card-title">DAFTAR {{ $title }}</h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
              <table id="balita" class="table table-bordered table-striped">
                <thead>
                <tr>
                  <th class="text-center">No.</th>
                  <th>Kelurahan</th>
                  <th>Posyandu</th>
                  <th>Alamat</th>
                  <th>Aksi</th>
                </tr>
                </thead>
                <tbody>
                @foreach ($posyandu as $pos)
                <tr>
                  <td class="text-center">{{ $no }}</td>
                  <td>{{ $pos->kelurahan }}</td>
                  <td>{{ $pos->name }}</td>
                  <td>{{ $pos->alamat }}</td>
                  <td class="text-center align-middle" width="100">
                    <a href="/posyandu/edit/{{ $pos->id }}" class="btn btn-lg py-0 px-1 text-primary"><i class="fas fa-edit"></i></a>
                    <button type="button" onclick="del({{ $pos->id }})" class="btn btn-lg py-0 px-1 text-danger"><i class="fas fa-trash"></i></button>
                    <form action="/posyandu/delete" method="post" id="delete{{ $pos->id }}">@csrf <input type="hidden" name="id" value="{{ $pos->id }}"></form>
                  </td>
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
@endsection

@section('script')

<div class="modal fade" id="posyanduNewModal">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
        <div class="h4 pt-4 pb-3 text-center w-100" style="border-bottom: solid 1px #b4b4b4">Tambah Data Posyandu</div>
        <div class="modal-body">
            <form method="post">
            @csrf
            <div class="form-group">
                <label for="name">Nama Posyandu</label>
                <input type="text" value="{{ old('name') }}" name="name" class="form-control @error('name') is-invalid @enderror" placeholder="Nama Posyandu" required>
                @error('name')<span class="error text-uppercase invalid-feedback">{{ $message }}</span>@enderror
            </div>
            <div class="form-group">
                <label for="alamat">Alamat Posyandu (RW)</label>
                <input type="text" value="{{ old('alamat') }}" name="alamat" class="form-control @error('alamat') is-invalid @enderror" placeholder="Contoh: RW 01" required>
                @error('alamat')<span class="error text-uppercase invalid-feedback">{{ $message }}</span>@enderror
            </div>
            <div class="form-group">
                <label for="kelurahan">Kelurahan</label>
                <select name="kelurahan" class="form-control @error('kalurahan') is-invalid @enderror" required>
                    <option value="" disabled selected>-- Pilih --</option>
                    @foreach ($kelurahan as $kel)
                    <option value="{{ $kel }}" @if (old('kelurahan') == $kel) ? selected : @endif>{{ $kel }}</option>
                    @endforeach
                </select>
                @error('kelurahan')<span class="error text-uppercase invalid-feedback">{{ $message }}</span>@enderror
            </div>
            <div class="row">
                <div class="col-md-6">
                    <input type="submit" id="submit" class="d-none">
                    <button type="button" onclick="confirm('Tambah data posyandu?')" class="btn btn-primary w-100 mb-1">Tambah Data</button>
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
    const posyanduNewModal = new bootstrap.Modal(document.getElementById('posyanduNewModal'));

    function del(id) {
        Swal.fire({
        html: "<h2>Hapus data ini?</h2>",
        showConfirmButton: false,
        showDenyButton: true,
        showCancelButton: true,
        denyButtonText: 'Hapus',
        cancelButtonText: 'Batal',
        }).then((result) => {
        if (result.isDenied) {
            Swal.fire({
            showConfirmButton: false,
            icon: 'success',
            text: 'Data dihapus.',
            });
            setTimeout(function() {
            document.getElementById("delete"+id).submit();
            }, 2000);
        }
        })
    }

    $(function () {
        var table = $("#balita").DataTable({
            "pageLength": 20,
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
                    columns: [ 0, 1, 2, 3 ]
                    }
                },
                {
                    extend: 'excel',
                    className: 'btn btn-success',
                    exportOptions: {
                    columns: [ 0, 1, 2, 3 ]
                    }
                },
                {
                    extend: 'print',
                    className: 'btn btn-dark',
                    exportOptions: {
                    columns: [ 0, 1, 2, 3 ]
                    }
                },
                {
                    text: '+ Tambah Data',
                    className: 'btn btn-warning',
                    action: function () {
                        posyanduNewModal.show();
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

    function confirm(content) {
      Swal.fire({
        icon: 'question',
        text: content,
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
        }
      })
    }
</script>

@if (session()->has('fail'))
<script>
    posyanduNewModal.show();
</script>
@endif

@endsection