<?php
$no = 1;
$dateFrom = $tahun.'-'.$bulan.'-01';
$dateTo = $tahun.'-'.$bulan.'-31';
$beforeDateFrom = $tahun.'-'.($bulan-1).'-01';
$beforeDateTo = $tahun.'-'.($bulan-1).'-31';
?>

<table>
    <thead>
    <tr>
        <th colspan="18">Rekap Data Balita Posyandu {{ str_replace('Kader ', '', auth()->user()->name) }} - {{ $bulanName[$bulan-1] }} {{ $tahun }}</th>
    </tr>
    <tr>
        <th rowspan="2">No</th>
        <th rowspan="2">Nama</th>
        <th rowspan="2">NIK</th>
        <th rowspan="2">Tanggal Lahir</th>
        <th rowspan="2">Usia</th>
        <th rowspan="2">Jenis Kelamin</th>
        <th rowspan="2">Nama Ibu</th>
        <th rowspan="2">NIK Ibu</th>
        <th rowspan="2">No. KK</th>
        <th rowspan="2">Kelurahan</th>
        <th rowspan="2">Posyandu</th>
        <th rowspan="2">Tanggal Pendataan</th>
        <th rowspan="2">BB</th>
        <th rowspan="2">TB</th>
        <th colspan="3">GIZI (BB)</th>
        <th rowspan="2">STATUS GIZI</th>
    </tr>
    <tr>
        <th>N</th>
        <th>TT</th>
        <th>TR</th>
    </tr>
    </thead>
    <tbody>
    @foreach($balitas as $balita)
    <?php
    $pendataan = $balita->pelayanan()->whereBetween('tgl_pelayanan', [$dateFrom, $dateTo])->first();
    $cekBeforePendataan = $balita->pelayanan()->whereBetween('tgl_pelayanan', [$beforeDateFrom, $beforeDateTo])->first();
    $beforeBB = $cekBeforePendataan ? $cekBeforePendataan->bb : 0;

    // dd($beforeBB);
    ?>
        <tr>
            <td>{{ $no++ }}</td>
            <td>{{ $balita->nama }}</td>
            <td>{{ $balita->nik }}</td>
            <td>{{ date('d-m-Y', strtotime($balita->tgl_lahir)) }}</td>
            <td>{{ $pendataan->usia }}</td>
            <td>{{ $balita->jenis_kelamin == 'lk' ? 'Laki-laki' : 'Perempuan' }}</td>
            <td>{{ $balita->nama_ibu }}</td>
            <td>{{ $balita->nik_ibu }}</td>
            <td>{{ $balita->no_kk }}</td>
            <td>{{ $balita->kelurahan }}</td>
            <td>{{ $balita->posyandu()->first()->name }}</td>
            <td>{{ date('d-m-Y', strtotime($pendataan->tgl_pelayanan)) }}</td>
            <td>{{ $pendataan->bb }}</td>
            <td>{{ $pendataan->tb }}</td>
            <td>{{ ($pendataan->bb > $beforeBB) ? '✓' : '' }}</td>
            <td>{{ ($pendataan->bb == $beforeBB) ? '✓' : '' }}</td>
            <td>{{ ($pendataan->bb < $beforeBB) ? '✓' : '' }}</td>
            <td>{{ $balita->status }}</td>
        </tr>
    @endforeach
    </tbody>
</table>