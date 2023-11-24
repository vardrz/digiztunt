@extends('layout.main')

@section('content')
<!-- Main content -->
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-6 mt-3">
                <div class="card card-primary">
                    <div class="card-header">
                        <h3 class="card-title">{{ $title }}</h3>
                    </div>
                    <form method="post" action="/posyandu/update">
                    @csrf
                    <input type="hidden" name='id' value="{{ $data->id }}">
                    <input type="hidden" name='oldName' value="{{ $data->name }}">
                    <div class="card-body">
                        <div class="form-group">
                            <label for="name">Nama Posyandu</label>
                            <input type="text" name="name" value="{{ $data->name }}" class="form-control @error('name') is-invalid @enderror" placeholder="Nama Posyandu" required>
                            @error('name')<span class="error text-uppercase invalid-feedback">{{ $message }}</span>@enderror
                        </div>
                        <div class="form-group">
                            <label for="kelurahan">Kelurahan</label>
                            <select name="kelurahan" class="form-control" required>
                                @foreach ($kelurahan as $kel)
                                    <option value="{{ $kel }}" @if($kel == $data->kelurahan) ? selected : @endif>{{ $kel }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <input type="submit" id="submit" class="d-none">
                            <button type="button" onclick="confirm('Update data posyandu?')" class="btn btn-primary w-100 mb-1">Update</button>
                            <a href="/posyandu" class="btn btn-danger w-100">Kembali</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
 <!-- /.content -->
@endsection

@section('script')
<script>
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
@endsection