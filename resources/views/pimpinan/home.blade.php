@extends('layout.main')

@section('content')

  <!-- Main content -->
  <section class="content">
    <div class="container-fluid">
      <div class="row">
        <div class="col-12 mt-3">
          <!-- Default box -->
          <div class="row">
            <div class="col-md-12">
              <div class="card">
                <div class="card-body">
                  {!! $kota->container() !!}
                </div>
              </div>
            </div>

            <div class="col-md-6">
              <div class="card">
                <div class="card-body">
                  {!! $utaraCount->container() !!}
                </div>
              </div>
            </div>
            <div class="col-md-6">
              <div class="card">
                <div class="card-body">
                  {!! $utaraList->container() !!}
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <script src="{{ $kota->cdn() }}"></script>
  <script src="{{ $utaraCount->cdn() }}"></script>
  <script src="{{ $utaraList->cdn() }}"></script>
  
  {{ $kota->script() }}
  {{ $utaraCount->script() }}
  {{ $utaraList->script() }}

  <!-- /.content -->
@endsection