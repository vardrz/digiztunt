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
                    <form method="post">
                    @csrf
                    <input type="hidden" name='id' value="{{ auth()->user()->id }}">
                    <div class="card-body">
                        <div class="form-group">
                            <label for="old">Password Lama</label>
                            <input type="password" id="pass1" name="old" class="form-control" placeholder="Password Sekarang" required>
                            <span class="small text-danger">{{ session()->get('fail') }}</span>
                            {{-- @if (session()->has('fail'))<span class="small text-danger">{{ session()->has('fail') }}</span>@endif --}}
                        </div>
                        <div class="form-group">
                            <label for="new">Password Baru</label>
                            <input type="password" id="pass2" name="new" class="form-control" placeholder="Password Baru" required>
                            <input type="password" id="pass3" name="new2" class="form-control mt-2" placeholder="Ulangi Password Baru" required>
                        </div>
                        <div class="form-group">
                            <div class="custom-control custom-checkbox">
                                <input class="custom-control-input" type="checkbox" id="showPassword">
                                <label for="showPassword" class="custom-control-label">Tampilkan Password</label>
                            </div>
                        </div>
                        <div class="form-group">
                            <input type="submit" id="submit" class="d-none">
                            <button type="button" onclick="confirm('Ganti Password?')" class="btn btn-primary w-100 mb-1" id="buttonSubmit" disabled>Ganti Password</button>
                            <a href="/home" class="btn btn-danger w-100">Kembali</a>
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
    const checkbox = document.getElementById('showPassword');
    checkbox.addEventListener('change', (event) => {
        if (event.currentTarget.checked) {
            document.getElementById('pass1').type = 'text';
            document.getElementById('pass2').type = 'text';
            document.getElementById('pass3').type = 'text';
        } else {
            document.getElementById('pass1').type = 'password';
            document.getElementById('pass2').type = 'password';
            document.getElementById('pass3').type = 'password';
        }
    })

    const repeatPass = document.getElementById('pass3');
    repeatPass.addEventListener('input', (event) => {
        newPass1 = document.getElementById('pass2');
        newPass2 = repeatPass;
        
        if(newPass1.value == newPass2.value){
            newPass1.classList.remove("is-invalid");
            newPass2.classList.remove("is-invalid");
            newPass1.classList.add("is-valid");
            newPass2.classList.add("is-valid");
            document.getElementById('buttonSubmit').disabled = false;
        }else{
            newPass1.classList.remove("is-valid");
            newPass2.classList.remove("is-valid");
            newPass1.classList.add("is-invalid");
            newPass2.classList.add("is-invalid");
            document.getElementById('buttonSubmit').disabled = true;
        }
    })

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