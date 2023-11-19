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
                  {!! $giziBuruk->container() !!}
                </div>
              </div>
            </div>

            <div class="col-md-6">
              <div class="card">
                <div class="card-body">
                  {!! $utaraLastMonth->container() !!}
                </div>
              </div>
            </div>
            <div class="col-md-6">
              <div class="card">
                <div class="card-body">
                  {!! $utaraBalita->container() !!}
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <script src="{{ $giziBuruk->cdn() }}"></script>
  <script src="{{ $utaraLastMonth->cdn() }}"></script>
  <script src="{{ $utaraBalita->cdn() }}"></script>
  
  {{ $giziBuruk->script() }}
  {{ $utaraLastMonth->script() }}
  {{ $utaraBalita->script() }}

  <!-- /.content -->
@endsection