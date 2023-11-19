@extends('layout.main')

@section('content')

<!-- Main content -->
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-6 mt-3">
                <div class="card card-primary">
                    <div class="card-header">
                        <h3 class="card-title">Pelayanan</h3>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <label for="nama">Cari Balita</label>
                            <div class="input-group">
                                <input type="text" class="form-control" id="caridata" placeholder="NIK / Nama Anak">
                                <span class="input-group-append">
                                    <button type="button" class="btn btn-info btn-flat" id="cari">Cari</button>
                                </span>
                            </div>
                            <span class="text-sm text-danger" id="pesan"></span>
                        </div>
                        <div class="d-none py-2" id="leftcolumn">
                            <form method="post">
                            <input type="hidden" name="id_balita" value="" id="id_balita">
                            <input type="hidden" value="" id="dob_balita">
                            @csrf
                            <div class="form-group">
                                <label for="nik_balita">NIK</label>
                                <input type="text" name="nik_balita" class="form-control" id="nik_balita" placeholder="NIK" required readonly>
                            </div>
                            <div class="form-group">
                                <label>Nama Anak</label>
                                <input type="text" class="form-control text-uppercase" id="nama_balita" placeholder="Nama" required readonly>
                            </div>
                            <div class="form-group">
                                <label>Tanggal Pendataan</label>
                                <div class="row">
                                    <div class="col-sm-3">
                                        <select name="tgl" class="form-control mb-1" id="tgl_pendataan" required>
                                            <option value="" disabled selected>Tanggal</option>
                                            @for ($i = 1; $i <= 31; $i++)
                                                @if (date('d') - $i == 0)
                                                <option value="{{ $i }}" selected>{{ $i }}</option>
                                                @else
                                                <option value="{{ $i }}">{{ $i }}</option>
                                                @endif
                                            @endfor
                                        </select>
                                    </div>
                                    <div class="col-sm-5">
                                        <select name="bulan" class="form-control mb-1" id="bulan_pendataan" required>
                                            <option value="" disabled selected>Bulan</option>
                                            @foreach ($month as $i)
                                                @if (date('m') - $i['no'] == 0)
                                                <option value="{{ $i['no'] }}" selected>{{ $i['name'] }}</option>
                                                @else
                                                <option value="{{ $i['no'] }}">{{ $i['name'] }}</option>
                                                @endif
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-sm-4">
                                        <select name="tahun" class="form-control" id="tahun_pendataan" required>
                                            <option value="" disabled selected>Tahun</option>
                                            @for ($i = date('Y'); $i >= date('Y')-5; $i--)
                                                @if (date('Y') - $i == 0)
                                                <option value="{{ $i }}" selected>{{ $i }}</option>
                                                @else
                                                <option value="{{ $i }}">{{ $i }}</option>
                                                @endif
                                            @endfor
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 mt-3 d-none" id="rightcolumn">
                <div class="card card-primary">
                    <div class="card-header">
                        <h3 class="card-title">Data Balita</h3>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <label for="usia">Usia</label> <span>(Bulan) </span>
                            <input type="text" name="usia" class="form-control" id="usia" placeholder="Usia" required>
                            <span class="text-sm text-info">Dihitung otomatis berdasarkan Tanggal Lahir & Tanggal Pendataan, perhitungan usia bisa saja tidak tepat, silahkan koreksi lagi.</span>
                        </div>
                        <div class="form-group">
                            <label for="bb">Berat Badan</label> <span>(Kilogram)</span>
                            <input type="text" name="bb" class="form-control" id="bb" placeholder="Berat Badan" required>
                        </div>
                        <div class="form-group">
                            <label for="tb">Tinggi Badan</label> <span>(Centimeter)</span>
                            <input type="text" name="tb" class="form-control" id="tb" placeholder="Tinggi Badan" required>
                            <span class="text-sm text-danger" id="tb-invalid"></span>
                        </div>
                        <div class="form-group">
                            <label for="lingkar_kepala">Lingkar Kepala</label> <span>(Centimeter)</span>
                            <input type="text" name="lingkar_kepala" class="form-control" id="lingkar_kepala" placeholder="Lingkar Kepala" required>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row d-none" id="bottomcolumn">
            <div class="col-md-12 mb-5">
                <input type="submit" class="d-none" id="submit">
                <button type="button" onclick="confirm()" class="btn btn-primary w-100">Simpan</button>
            </div>
        </div>
        </form>
    </div>
</section>
 <!-- /.content --> 
@endsection

@section('script')
<div class="modal fade" id="modal-data">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Data Balita Tersedia</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body p-0">
                <table class="table table-striped" id="listdata"></table>
            </div>
        </div>
    </div>
</div>

<script>
    var balitaSelected = '';

    var tb = document.getElementById('tb');
    tb.addEventListener('change', function() {
        if(balitaSelected != undefined){
            if(parseFloat(tb.value.replace(",", ".")) < parseFloat(balitaSelected['tb'])){
                document.getElementById('tb-invalid').innerHTML = 'Tinggi badan tidak bisa kurang dari tinggi sebelumnya. (' + balitaSelected['tb'] + ' cm)';
            }else{
                document.getElementById('tb-invalid').innerHTML = '';
            }
        }
    });

    document.getElementById('tahun_pendataan').addEventListener('change', () => updateUsia());
    document.getElementById('bulan_pendataan').addEventListener('change', () => updateUsia());
    document.getElementById('tgl_pendataan').addEventListener('change', () => updateUsia());
    function updateUsia() {
        update_usia = month(document.getElementById('dob_balita').value);
        document.querySelector('#usia').value = update_usia;
    }

    var buttonCari = document.getElementById('cari');
    buttonCari.addEventListener('click', function() {
        var url = window.location.origin + '/balita/find/' + document.getElementById('caridata').value;

        fetch(url)
        .then(
            response => response.json()
        ).then(
            data => {
                if(data.length > 0){
                    document.getElementById('pesan').innerHTML = ""; // reset message
                    document.getElementById('listdata').innerHTML = ""; // reset data on modal list

                    const myModal = new bootstrap.Modal(document.getElementById('modal-data')); // creating modal object
                    myModal.show();

                    for(var i = 0; i < data.length; i++){
                        if(month(data[i]['tgl_lahir']) > 60){
                            document.getElementById('listdata').innerHTML += 
                                `<tr>
                                    <td class='pl-3'>
                                        <b>` + data[i]['nama'] + `</b><br><small>Ibu : ` + data[i]['nama_ibu'] + ` | ` + data[i]['kelurahan'] + `</small>
                                    </td>
                                    <td class='align-middle'>
                                        <button class='btn btn-sm btn-danger' disabled>> 5 Tahun</button>
                                    </td>
                                </tr>`;
                        }else{
                            document.getElementById('listdata').innerHTML += 
                                `<tr>
                                    <td class='pl-3'>
                                        <b>` + data[i]['nama'] + `</b><br><small>Ibu : ` + data[i]['nama_ibu'] + ` | ` + data[i]['kelurahan'] + `</small>
                                    </td>
                                    <td class='align-middle'>
                                        <button class='btn btn-sm btn-success' onclick="pilih('` + 
                                            data[i]['id'] + `','` + 
                                            data[i]['nik'] + `','` + 
                                            data[i]['nama'] + `','` + 
                                            data[i]['tgl_lahir']
                                         + `')">Pilih</button>
                                    </td>
                                </tr>`;
                        }
                    }
                }else{
                    document.getElementById('pesan').innerHTML = "Data tidak ditemukan.";
                }
            }
        );
    });

    function pilih(id, nik, nama, usia){
        document.getElementById('leftcolumn').classList.remove("d-none");
        document.getElementById('rightcolumn').classList.remove("d-none");
        document.getElementById('bottomcolumn').classList.remove("d-none");

        document.querySelector('#modal-data').style.display = "none";
        document.querySelector('.modal-backdrop').remove();
        document.body.style.overflow = "visible";
        
        document.querySelector('#id_balita').value = id;
        document.querySelector('#nik_balita').value = nik;
        document.querySelector('#nama_balita').value = nama;
        document.querySelector('#dob_balita').value = usia;
        document.querySelector('#usia').value = month(usia);

        var url = window.location.origin + '/pelayanan/find/' + id;
        fetch(url)
        .then(
            response => response.json()
        ).then(
            data => {
                balitaSelected = data[0];
            }
        );
    }

    function month(dob) {
        datePendataan = document.querySelector('#tahun_pendataan').value + '-' +
                        document.querySelector('#bulan_pendataan').value + '-' +
                        document.querySelector('#tgl_pendataan').value;
        // console.log(datePendataan);

        const dobObj = new Date(dob);
        const hariIni = new Date(datePendataan);
        // const hariIni = new Date();

        const selisihTahun = hariIni.getFullYear() - dobObj.getFullYear();
        const selisihBulan = hariIni.getMonth() - dobObj.getMonth();
        const selisihHari = Math.floor((hariIni - dobObj) / (1000 * 60 * 60 * 24));

        const totalBulan = selisihTahun * 12 + selisihBulan;

        if(selisihHari >= 30){
            return totalBulan;
        }else{
            return 0;
        }
    }

    function confirm() {
        Swal.fire({
            icon: 'question',
            text: "Simpan data pengukuran?",
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