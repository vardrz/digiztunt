@extends('layout.main')

@section('content')
  <!-- Main content -->
  <section class="content">
    <div class="container-fluid">
      <div class="row">
        <div class="col-12 mt-3">
          <div class="card">
            <div class="card-header">
              <h3 class="card-title">Daftar {{ $title }}</h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <div class="text-sm text-primary mb-4" style="line-height : 18px;">
                  <b>*Keterangan:</b>
                  <br>- Password Default kader posyandu : 123456. 
                  <br>- Setelah berhasil membuat/mereset akun kader, password yang berlaku adalah Password Default. 
                </div>
                
                <table id="balita" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th class="text-center">No.</th>
                            <th>Kelurahan</th>
                            <th>Posyandu</th>
                            <th>Nama</th>
                            <th>Username</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $no = 1; @endphp
                        @foreach ($data as $d)
                        <tr>
                            <td class="text-center">{{ $no }}</td>
                            <td>{{ $d->posyandu->kelurahan }}</td>
                            <td>{{ $d->posyandu->name }}</td>
                            <td>{{ $d->name }}</td>
                            <td>{{ $d->email }}</td>
                            <td class="text-center align-middle">
                                <button type="button" class="btn btn-xs btn-primary py-0 px-1 w-100 btn-edit-kader"
                                        data-id="{{ $d->id }}"
                                        data-name="{{ $d->name }}"
                                        data-email="{{ $d->email }}">
                                    Edit Akun
                                </button>
                                <button type="button" onclick="reset({{ $d->id }},'{{ $d->name }}','{{ $d->posyandu->name }}')" class="btn btn-xs btn-secondary py-0 px-1 w-100">Reset Password</button><br>
                                <form action="/kader/reset" method="post" id="reset{{ $d->id }}">@csrf <input type="hidden" name="id" value="{{ $d->id }}"></form>
                                <button type="button" onclick="del({{ $d->id }},'{{ $d->name }}','{{ $d->posyandu->name }}')" class="btn btn-xs btn-danger py-0 px-1 w-100">Hapus Akun</button>
                                <form action="/kader/delete" method="post" id="delete{{ $d->id }}">@csrf <input type="hidden" name="id" value="{{ $d->id }}"></form>
                            </td>
                        </tr>
                        @php $no++; @endphp
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

<!-- Modal Tambah Kader -->
<div class="modal fade" id="kaderNewModal">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
        <div class="h4 pt-4 pb-3 text-center w-100" style="border-bottom: solid 1px #b4b4b4">Buat Akun Kader</div>
        <div class="modal-body">
            <form method="post">
            @csrf
            <div class="form-group">
                <label for="name">Nama Kader</label>
                <input type="text" value="{{ old('name') }}" name="name" class="form-control @error('name') is-invalid @enderror" placeholder="Nama Kader" required>
                @error('name')<span class="error text-uppercase invalid-feedback">{{ $message }}</span>@enderror
            </div>
            <div class="form-group">
                <label for="email">Username Kader</label>
                <input type="text" value="{{ old('email') }}" name="email" class="form-control @error('email') is-invalid @enderror" placeholder="Username Kader" required>
                @error('email')<span class="error text-uppercase invalid-feedback">{{ $message }}</span>@enderror
            </div>
            <div class="form-group">
                <label for="posyandu">Posyandu</label>
                <select name="posyandu" class="form-control @error('posyandu') is-invalid @enderror" required>
                    <option value="" disabled selected>-- Pilih --</option>
                    @foreach ($posyandu as $pos)
                    <option value="{{ $pos->id }}" @if (old('posyandu') == $pos->id) ? selected : @endif>{{ $pos->kelurahan . ' - ' . $pos->name }}</option>
                    @endforeach
                </select>
                @error('posyandu')<span class="error text-uppercase invalid-feedback">{{ $message }}</span>@enderror
            </div>
            <div class="row">
                <div class="col-md-6">
                    <input type="submit" id="submit" class="d-none">
                    <button type="button" onclick="confirm('Buat akun kader?')" class="btn btn-primary w-100 mb-1">Buat</button>
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

<!-- Modal Edit Kader -->
<div class="modal fade" id="kaderEditModal">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="h4 pt-4 pb-3 text-center w-100" style="border-bottom: solid 1px #b4b4b4">Edit Akun Kader</div>
            <div class="modal-body">
                <form method="post" action="{{ route('kader.update') }}" id="editKaderForm">
                    @csrf
                    @method('PUT') <!-- Gunakan method PUT untuk update -->
                    <input type="hidden" name="id_kader" id="edit_id_kader">
                    <div class="form-group">
                        <label for="edit_name">Nama Kader</label>
                        <input type="text" name="edit_name" id="edit_name" class="form-control @error('edit_name', 'updateKader') is-invalid @enderror" placeholder="Nama Kader" required>
                        @error('edit_name', 'updateKader')<span class="error text-danger invalid-feedback">{{ $message }}</span>@enderror
                    </div>
                    <div class="form-group">
                        <label for="edit_email">Username Kader</label>
                        <input type="text" name="edit_email" id="edit_email" class="form-control @error('edit_email', 'updateKader') is-invalid @enderror" placeholder="Username Kader" required>
                        @error('edit_email', 'updateKader')<span class="error text-danger invalid-feedback">{{ $message }}</span>@enderror
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <button type="submit" class="btn btn-primary w-100 mb-1">Simpan Perubahan</button>
                        </div>
                        <div class="col-md-6">
                            <button type="button" class="btn btn-danger w-100" data-dismiss="modal">Batal</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    const kaderNewModal = new bootstrap.Modal(document.getElementById('kaderNewModal'));
    const kaderEditModal = new bootstrap.Modal(document.getElementById('kaderEditModal'));

    // SweetAlert functions (reset, del, confirm)
    function typeConfirm(id, name, posyandu, type) {
        Swal.fire({
            html: `
                Ketik "Konfirmasi" di bawah ini<br>untuk memastikan aksi anda.<br>
                <input type='text' id='inputConfirm' class='form-control mt-3' placeholder="Ketik 'Konfirmasi' disini.">
                `,
            showConfirmButton: true,
            showDenyButton: true,
            showCancelButton: false,
            confirmButtonText: 'Kirim',
            denyButtonText: 'Batal',
        }).then((result) => {
            if (result.isConfirmed) {
                input = document.getElementById('inputConfirm');
                if(input.value == 'Konfirmasi'){
                    if(type == 'reset'){
                        Swal.fire({
                            showConfirmButton: false,
                            icon: 'success',
                            html: `Password berhasil direset.<br>Password baru : 123456`,
                        });
                        setTimeout(function() {
                            document.getElementById(type+id).submit();
                        }, 3000);
                    }else{
                        Swal.fire({
                            showConfirmButton: false,
                            icon: 'success',
                            text: 'Akun kader berhasil dihapus.',
                        });
                        setTimeout(function() {
                            document.getElementById(type+id).submit();
                        }, 2000);
                    }
                }else{
                    Swal.fire({
                        showConfirmButton: false,
                        icon: 'error',
                        text: 'Gagal, ulangi lagi.',
                    });
                    setTimeout(function() {
                        typeConfirm(id, name, posyandu, type);
                    }, 2000);
                }
            } else if(result.isDenied){
                if(type=='reset'){
                    reset(id, name, posyandu);
                }else{
                    del(id, name, posyandu);
                }
            }
        })
    }

    function reset(id, name, posyandu) {
        Swal.fire({
            html: `<h3>Reset password akun kader?</h3>${name} (Posyandu ${posyandu})`,
            showConfirmButton: true,
            showDenyButton: false,
            showCancelButton: true,
            confirmButtonText: 'Ya',
            cancelButtonText: 'Batal',
        }).then((result) => {
            if (result.isConfirmed) {
                typeConfirm(id, name, posyandu, 'reset');
            }
        })
    }

    function del(id, name, posyandu) {
        Swal.fire({
            html: `<h3>Hapus akun kader?</h3>${name} (Posyandu ${posyandu})`,
            showConfirmButton: true,
            showDenyButton: false,
            showCancelButton: true,
            confirmButtonText: 'Ya',
            cancelButtonText: 'Batal',
        }).then((result) => {
            if (result.isConfirmed) {
                typeConfirm(id, name, posyandu, 'delete');
            }
        })
    }

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

    // DataTable
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
                    columns: [ 0, 1, 2, 3, 4 ]
                    }
                },
                {
                    extend: 'excel',
                    className: 'btn btn-success',
                    exportOptions: {
                    columns: [ 0, 1, 2, 3, 4 ]
                    }
                },
                {
                    extend: 'print',
                    className: 'btn btn-dark',
                    exportOptions: {
                    columns: [ 0, 1, 2, 3, 4 ]
                    }
                },
                {
                    text: '+ Tambah Data',
                    className: 'btn btn-warning',
                    action: function () {
                        kaderNewModal.show();
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

    // Event listener untuk tombol edit
    $('.btn-edit-kader').on('click', function() {
        const id = $(this).data('id');
        const name = $(this).data('name');
        const email = $(this).data('email');
    
        $('#edit_id_kader').val(id);
        $('#edit_name').val(name);
        $('#edit_email').val(email);
        
        // Hapus kelas is-invalid dan pesan error sebelumnya jika ada
        $('#editKaderForm .is-invalid').removeClass('is-invalid');
        $('#editKaderForm .invalid-feedback').text('');
    
        kaderEditModal.show();
    });
    
    @if (session()->has('fail'))
    kaderNewModal.show();
    @endif
    
    // Jika ada error validasi saat update, buka kembali modal edit yang sesuai
    @if ($errors->updateKader->any() && session('error_modal_id'))
    $(document).ready(function() {
        // Isi kembali field jika ada old input (meskipun tidak ideal untuk modal, tapi bisa membantu)
        $('#edit_id_kader').val('{{ session('error_modal_id') }}');
        // Anda mungkin perlu cara yang lebih baik untuk mengisi ulang data lama jika validasi gagal
        // karena data asli dari tombol edit mungkin sudah hilang.
        // Untuk sekarang, kita hanya buka modalnya.
        
        // Cari tombol edit yang sesuai dengan ID untuk mendapatkan data lama jika perlu
        // Ini hanya contoh, mungkin perlu disesuaikan
        var originalButton = $('.btn-edit-kader[data-id="{{ session('error_modal_id') }}"]');
        if (originalButton.length) {
                $('#edit_name').val('{{ old('edit_name') }}' || originalButton.data('name'));
                $('#edit_email').val('{{ old('edit_email') }}' || originalButton.data('email'));
        } else {
                $('#edit_name').val('{{ old('edit_name') }}');
                $('#edit_email').val('{{ old('edit_email') }}');
        }

        kaderEditModal.show();
    });
    @endif
    
</script>
@endsection