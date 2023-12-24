@extends('layout.main')

@section('content')

  <!-- Main content -->
  <section class="content">
    <div class="container-fluid">
      <div class="row">
        <div class="col-6 mt-3">
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">Rekap Laporan</h3>
                </div>
                <div class="card-body px-3 py-4">
                    <div class="row">
                      <div class="col-md-5">
                        <div class="input-group mb-2 mr-2">
                          <div class="input-group-prepend">
                            <span class="input-group-text">Tahun</span>
                          </div>
                          <select id="tahun" class="form-control">
                            @for ($i = date('Y'); $i > date('Y')-3; $i--)
                              <option value="{{ $i }}">{{ $i }}</option>
                            @endfor
                          </select>
                        </div>
                      </div>
                      <div class="col-md-7">
                        <div class="input-group mb-2">
                          <div class="input-group-prepend">
                            <span class="input-group-text">Bulan</span>
                          </div>
                          <select id="bulan" class="form-control">
                            @foreach ($bulan as $i => $val)
                            <option value="{{ $i+1 }}" @if(date('m') == $i+1) selected @endif>{{ $val }}</option>
                            @endforeach
                          </select>
                        </div>
                      </div>
                    </div>
                    <button type="button" onclick="cetak()" class="col-12 btn btn-primary">Cetak</button>
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
  function cetak(){
    var tahun = document.getElementById('tahun').value;
    var bulan = document.getElementById('bulan').value;
    var url = window.location.origin + "/rekap/" + tahun + "/" + bulan;
    window.open(url, '_blank');
  }
</script>
@endsection