@extends('layout.main')

@section('content')

<!-- Main content -->
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-6 mt-3">
                <div class="card card-primary">
                    <div class="card-header">
                        <h3 class="card-title">Data Balita</h3>
                    </div>
                    <form method="post" id="formAddBalita">
                    @csrf
                    <div class="card-body">
                        <div class="form-group">
                            <label for="nama">Nama Anak</label>
                            <input type="text" name="nama" value="{{ old('nama') }}" class="form-control @error('nama') is-invalid @enderror" id="nama" placeholder="Nama Anak" required>
                            @error('nama')<span class="error text-uppercase invalid-feedback">{{ $message }}</span>@enderror
                        </div>
                        <div class="form-group">
                            <label for="tgl_lahir">Tanggal Lahir</label>
                            <div class="row">
                                <div class="col-sm-3">
                                    <select name="tgl" class="form-control mb-1" required>
                                        <option value="" disabled selected>Tanggal</option>
                                        @for ($i = 1; $i <= 31; $i++)
                                        <option value="{{ $i }}">{{ $i }}</option>
                                        @endfor
                                    </select>
                                </div>
                                <div class="col-sm-5">
                                    <select name="bulan" class="form-control mb-1" required>
                                        <option value="" disabled selected>Bulan</option>
                                        @foreach ($month as $i)
                                        <option value="{{ $i['no'] }}">{{ $i['name'] }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-sm-4">
                                    <select name="tahun" class="form-control" required>
                                        <option value="" disabled selected>Tahun</option>
                                        @for ($i = date('Y'); $i >= date('Y')-5; $i--)
                                        <option value="{{ $i }}">{{ $i }}</option>
                                        @endfor
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="jenis_kelamin">Jenis Kelamin</label>
                            <select name="jenis_kelamin" id="jenis_kelamin" class="form-control" required>
                                <option value="lk">Laki-laki</option>
                                <option value="pr">Perempuan</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="nik">NIK</label>
                            <input type="text" name="nik" value="{{ old('nik') }}" class="form-control @error('nik') is-invalid @enderror" id="nik" placeholder="NIK" required>
                            @error('nik')<span class="error text-uppercase invalid-feedback">{{ $message }}</span>@enderror
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 mt-3 mb-5">
                <div class="card card-primary">
                    <div class="card-header">
                        <h3 class="card-title">Data Orang Tua</h3>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <label for="namaibu">Nama Ibu</label>
                            <input type="text" name="namaibu" value="{{ old('namaibu') }}" class="form-control @error('namaibu') is-invalid @enderror" id="namaibu" placeholder="Nama Ibu" required>
                            @error('namaibu')<span class="error text-uppercase invalid-feedback">{{ $message }}</span>@enderror
                        </div>
                        <div class="form-group">
                            <label for="nikibu">NIK Ibu</label>
                            <input type="text" name="nikibu" value="{{ old('nikibu') }}" class="form-control @error('nikibu') is-invalid @enderror" id="nikibu" placeholder="NIK Ibu" required>
                            @error('nikibu')<span class="error text-uppercase invalid-feedback">{{ $message }}</span>@enderror
                        </div>
                        <div class="form-group">
                            <label for="namaayah">Nama Ayah</label>
                            <input type="text" name="namaayah" value="{{ old('namaayah') }}" class="form-control @error('namaayah') is-invalid @enderror" id="namaayah" placeholder="Nama Ayah" required>
                            @error('namaayah')<span class="error text-uppercase invalid-feedback">{{ $message }}</span>@enderror
                        </div>
                        <div class="form-group">
                            <label for="nikayah">NIK Ayah</label>
                            <input type="text" name="nikayah" value="{{ old('nikayah') }}" class="form-control @error('nikayah') is-invalid @enderror" id="nikayah" placeholder="NIK Ayah" required>
                            @error('nikayah')<span class="error text-uppercase invalid-feedback">{{ $message }}</span>@enderror
                        </div>
                        <div class="form-group">
                            <label for="nokk">Nomor Kartu Keluarga</label>
                            <input type="text" name="nokk" value="{{ old('nokk') }}" class="form-control @error('nokk') is-invalid @enderror" id="nokk" placeholder="Nomor KK" required>
                            @error('nokk')<span class="error text-uppercase invalid-feedback">{{ $message }}</span>@enderror
                        </div>
                        <div class="form-group">
                            <label for="kecamatan">Kecamatan</label>
                            <select name="kecamatan" class="form-control" id="kecamatan" required>
                                <option value="" disabled selected>Kecamatan</option>
                                @foreach ($kecamatan as $i)
                                <option value="{{ $i['name'] }}" id="{{ $i['id'] }}">{{ $i['name'] }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="kelurahan">Kelurahan</label>
                            <select name="kelurahan" class="form-control" id="kelurahan" required>
                                <option value="" disabled selected>Kelurahan</option>
                            </select>
                        </div>
                    </div>
                    <div class="card-footer">
                        <input type="submit" id="submit" class="d-none">
                        <button type="button" onclick="confirm()" class="btn btn-primary">Tambahkan Data</button>
                    </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
 <!-- /.content -->

<script>
    var aa = document.getElementById('kecamatan');
    aa.addEventListener('change', function() {
        var urlkelurahan = window.location.origin + '/json/villages/' + aa.options[aa.selectedIndex].id + '.json';
        document.getElementById("kelurahan").innerHTML = "<option value='' disabled selected>Kelurahan</option>";

        fetch(urlkelurahan)
        .then(
            response => response.json()
        ).then(
            villages => {
                var kelurahan = villages;
                for(var i = 0; i < kelurahan.length; i++){
                    var option= document.createElement("option");
                    option.value= kelurahan[i]['name'];
                    option.text= kelurahan[i]['name'];
                    document.getElementById("kelurahan").add(option);
                }
            }
        );
    });

    function confirm() {
        Swal.fire({
            icon: 'question',
            text: "Simpan data balita?",
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