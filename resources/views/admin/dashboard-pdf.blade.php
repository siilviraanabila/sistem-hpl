<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<style>
body{font-family:sans-serif;font-size:10px}
h3{margin:10px 0}
table{width:100%;border-collapse:collapse;margin-bottom:20px}
th,td{border:1px solid #000;padding:4px;text-align:center}
th{background:#eee}
</style>
</head>

<body>

<h3>Dashboard Pertanahan</h3>

{{-- ================= SHM ================= --}}
<h4>Data Sertifikat Hak Milik (SHM)</h4>

<table>
<thead>
<tr>
<th>No</th>
<th>Provinsi</th>
<th>Kawasan</th>
<th>Lokasi</th>
<th>Target</th>
<th>Realisasi</th>
<th>Sisa</th>
<th>Dokumen</th>
</tr>
</thead>
<tbody>
@foreach($shm as $i=>$d)
<tr>
<td>{{ $i+1 }}</td>
<td>{{ optional($d->kawasan->desa->kecamatan->kabupaten->provinsi)->nama_provinsi }}</td>
<td>{{ $d->kawasan->nama_kawasan }}</td>
<td>{{ $d->kawasan->nama_lokasi }}</td>
<td>{{ $d->beban_shm_target }}</td>
<td>{{ $d->beban_shm_realisasi }}</td>
<td>{{ $d->beban_shm_sisa }}</td>
<td>{{ $d->dokumen->count() }}</td>
</tr>
@endforeach
</tbody>
</table>

{{-- ================= HPL ================= --}}
<h4>Data Hak Pengelolaan Lahan (HPL)</h4>

<table>
<thead>
<tr>
<th>No</th>
<th>Provinsi</th>
<th>Kawasan</th>
<th>Lokasi</th>
<th>Status</th>
<th>Tanggal</th>
<th>Sisa Luas</th>
<th>Dokumen</th>
</tr>
</thead>

<tbody>
@foreach($hpl as $i=>$d)
<tr>
<td>{{ $i+1 }}</td>
<td>{{ optional($d->kawasan->desa->kecamatan->kabupaten->provinsi)->nama_provinsi }}</td>
<td>{{ $d->kawasan->nama_kawasan }}</td>
<td>{{ $d->kawasan->nama_lokasi }}</td>
<td>{{ strtoupper($d->status_hpl) }}</td>
<td>{{ $d->tgl_hpl ?? '-' }}</td>
<td>{{ $d->sisa_luas ?? '-' }}</td>
<td>{{ $d->dokumen->count() }}</td>
</tr>
@endforeach
</tbody>
</table>

{{-- ================= PL ================= --}}
<h4>Data Permasalahan Lahan</h4>

<table>
<thead>
<tr>
<th>No</th>
<th>Provinsi</th>
<th>Kawasan</th>
<th>Lokasi</th>
<th>Total Kasus</th>
<th>Rekomendasi</th>
<th>Dokumen</th>
</tr>
</thead>

<tbody>
@foreach($pl as $i=>$d)
<tr>
<td>{{ $i+1 }}</td>
<td>{{ optional($d->kawasan->desa->kecamatan->kabupaten->provinsi)->nama_provinsi }}</td>
<td>{{ $d->kawasan->nama_kawasan }}</td>
<td>{{ $d->kawasan->nama_lokasi }}</td>
<td>{{ $d->pl_total_kasus }}</td>
<td>{{ $d->rekomendasi }}</td>
<td>{{ $d->dokumen->count() }}</td>
</tr>
@endforeach
</tbody>
</table>

</body>
</html>
