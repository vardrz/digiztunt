<?php $date = explode('-', $data->tgl_lahir); ?>

@extends('layout.main')

@section('head')
<style>
    input[type="range"] {
        width: 100%;
    }
</style>    
@endsection

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
                    <form method="post" action="/balita/update" id="formEditBalita">
                    @csrf
                    <input type="hidden" name='id' value="{{ $data->id }}">
                    <div class="card-body">
                        <div class="form-group">
                            <label for="nama">Nama Anak</label>
                            <input type="text" name="nama" value="{{ $data->nama }}" class="form-control @error('nama') is-invalid @enderror" id="nama" placeholder="Nama Anak" required>
                            @error('nama')<span class="error text-uppercase invalid-feedback">{{ $message }}</span>@enderror
                        </div>
                        <div class="form-group">
                            <label for="tgl_lahir">Tanggal Lahir</label>
                            <div class="row">
                                <div class="col-sm-3">
                                    <select name="tgl" class="form-control mb-1" required>
                                        <option value="" disabled selected>Tanggal</option>
                                        @for ($i = 1; $i <= 31; $i++)
                                        <option value="{{ $i }}" @if($i == $date[2]) ? selected @endif>{{ $i }}</option>
                                        @endfor
                                    </select>
                                </div>
                                <div class="col-sm-5">
                                    <select name="bulan" class="form-control mb-1" required>
                                        <option value="" disabled selected>Bulan</option>
                                        @foreach ($month as $i)
                                        <option value="{{ $i['no'] }}" @if($i['no'] == $date[1]) ? selected @endif>{{ $i['name'] }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-sm-4">
                                    <select name="tahun" class="form-control" required>
                                        <option value="" disabled selected>Tahun</option>
                                        @for ($i = date('Y'); $i >= date('Y')-5; $i--)
                                        <option value="{{ $i }}" @if($i == $date[0]) ? selected @endif>{{ $i }}</option>
                                        @endfor
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="jenis_kelamin">Jenis Kelamin</label>
                            <select name="jenis_kelamin" id="jenis_kelamin" class="form-control" required>
                                <option value="lk" @if($data->jenis_kelamin == 'lk') ? selected @endif>Laki-laki</option>
                                <option value="pr" @if($data->jenis_kelamin == 'pr') ? selected @endif>Perempuan</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="nik">NIK</label>
                            <select id="haveNIK" class="form-control mb-1" required {{ ($data->nik != '-') ? 'disabled' : '' }}>
                                <option value="y">Sudah memiliki NIK</option>
                                <option value="n" {{ ($data->nik == '-') ? 'selected' : '' }}>Belum memiliki NIK</option>
                            </select>
                            <input type="text" {{ ($data->nik == '-') ? 'readonly' : '' }} name="nik" value="{{ $data->nik }}" class="form-control @error('nik') is-invalid @enderror" id="nik" placeholder="NIK" required>
                            <div id="showRangeNIK" @if($data->nik == '-') ? class="d-none" @endif>
                                <input type="range" value="0" min="0" max="16" id="rangeNIK" disabled>
                            </div>
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
                            <input type="text" name="namaibu" value="{{ $data->nama_ibu }}" class="form-control @error('namaibu') is-invalid @enderror" id="namaibu" placeholder="Nama Ibu" required>
                            @error('namaibu')<span class="error text-uppercase invalid-feedback">{{ $message }}</span>@enderror
                        </div>
                        <div class="form-group">
                            <label for="nikibu">NIK Ibu</label>
                            <input type="text" name="nikibu" value="{{ $data->nik_ibu }}" minlength="16" maxlength="16" class="form-control @error('nikibu') is-invalid @enderror" id="nikibu" placeholder="NIK Ibu" required>
                            <div id="showRangeNIKibu">
                                <input type="range" value="0" min="0" max="16" id="rangeNIKibu" disabled>
                            </div>
                            @error('nikibu')<span class="error text-uppercase invalid-feedback">{{ $message }}</span>@enderror
                        </div>
                        <div class="form-group">
                            <label for="namaayah">Nama Ayah</label>
                            <input type="text" name="namaayah" value="{{ $data->nama_ayah }}" class="form-control @error('namaayah') is-invalid @enderror" id="namaayah" placeholder="Nama Ayah" required>
                            @error('namaayah')<span class="error text-uppercase invalid-feedback">{{ $message }}</span>@enderror
                        </div>
                        <div class="form-group">
                            <label for="nikayah">NIK Ayah</label>
                            <input type="text" name="nikayah" value="{{ $data->nik_ayah }}" minlength="16" maxlength="16" class="form-control @error('nikayah') is-invalid @enderror" id="nikayah" placeholder="NIK Ayah" required>
                            <div id="showRangeNIKayah">
                                <input type="range" value="0" min="0" max="16" id="rangeNIKayah" disabled>
                            </div>
                            @error('nikayah')<span class="error text-uppercase invalid-feedback">{{ $message }}</span>@enderror
                        </div>
                        <div class="form-group">
                            <label for="nokk">Nomor Kartu Keluarga</label>
                            <input type="text" name="nokk" value="{{ $data->no_kk }}" class="form-control @error('nokk') is-invalid @enderror" id="nokk" placeholder="Nomor KK" required>
                            @error('nokk')<span class="error text-uppercase invalid-feedback">{{ $message }}</span>@enderror
                        </div>
                        <div class="form-group">
                            <label for="kecamatan">Kecamatan</label>
                            <select name="kecamatan" class="form-control" id="kecamatan" required>
                                <option value="" disabled selected>Kecamatan</option>
                                @foreach ($kecamatan as $i)
                                <option value="{{ $i['name'] }}" id="{{ $i['id'] }}" @if($i['name'] == $data->kecamatan) ? selected @endif>{{ $i['name'] }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="kelurahan">Kelurahan</label>
                            <select name="kelurahan" class="form-control" id="kelurahan" required></select>
                            <input type="hidden" value="{{ $data->kelurahan }}" id="curentVillage">
                        </div>
                    </div>
                    <div class="row card-footer">
                        <input type="submit" class="d-none" id="submit">
                        <button type="button" onclick="confirm()" class="col-12 btn btn-primary">Update Data</button>
                    </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
 <!-- /.content -->
@endsection
 
@section('script')
<script>
    document.getElementById("rangeNIK").value = document.getElementById('nik').value.length;
    document.getElementById("rangeNIKibu").value = document.getElementById('nikibu').value.length;
    document.getElementById("rangeNIKayah").value = document.getElementById('nikayah').value.length;
    document.getElementById('nik').addEventListener("input", function(event) {
        document.getElementById("rangeNIK").value = event.target.value.length;
    });
    document.getElementById('nikibu').addEventListener("input", function(event) {
        document.getElementById("rangeNIKibu").value = event.target.value.length;
    });
    document.getElementById('nikayah').addEventListener("input", function(event) {
        document.getElementById("rangeNIKayah").value = event.target.value.length;
    });

    // var current_nik_value = "";
    var cek_have_nik = document.getElementById('haveNIK');
    cek_have_nik.addEventListener('change', function() {
        if(cek_have_nik.value == "y"){
            // current_nik_value = "y"
            document.getElementById("showRangeNIK").classList.remove("d-none");
            document.getElementById("nik").value = "";
            document.getElementById("nik").readOnly = false;
        }else{
        // else if(cek_have_nik.value == "y" && current_nik_value == "n"){
            document.getElementById("showRangeNIK").classList.add("d-none");
            document.getElementById("nik").value = "-";
            document.getElementById("nik").readOnly = true;
        }
    });

    function getKelurahan(id){
        var urlkelurahan = window.location.origin + '/json/villages/' + id + '.json';
        document.getElementById("kelurahan").innerHTML = "<option value='' disabled selected>Kelurahan</option>";

        fetch(urlkelurahan)
        .then(
            response => response.json()
        ).then(
            villages => {
                var kelurahan = villages;
                for(var i = 0; i < kelurahan.length; i++){
                    if(document.getElementById("curentVillage").value == kelurahan[i]['name']){
                        document.getElementById("kelurahan").innerHTML += "<option value='" + kelurahan[i]['name'] + "' selected>" + kelurahan[i]['name'] + "</option>";
                    }else{
                        document.getElementById("kelurahan").innerHTML += "<option value='" + kelurahan[i]['name'] + "'>" + kelurahan[i]['name'] + "</option>";
                    }

                }
            }
        );
    }

    var aa = document.getElementById('kecamatan');
    window.onload = (event) => {
        getKelurahan(aa.options[aa.selectedIndex].id);
    }
    aa.addEventListener('change', function() {
        getKelurahan(aa.options[aa.selectedIndex].id);
    });

    function confirm() {
        Swal.fire({
            icon: 'question',
            text: "Update data balita?",
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