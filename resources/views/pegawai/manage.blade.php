@extends('layout.master')
@section('title', 'Manajemen Pegawai')

@section('content')
<h2>Data Pegawai</h2>

<a href="create.html">Tambah Pegawai</a>

<table border="1" cellpadding="8">
    <tr>
        <th>No</th>
        <th>NIP</th>
        <th>Nama</th>
        <th>Jabatan</th>
        <th>Golongan</th>
        <th>Jenis Pegawai</th>
        <th>Aksi</th>
    </tr>

    <tr>
        <td>1</td>
        <td>1987654321</td>
        <td>Ahmad Fauzi</td>
        <td>Staff IT</td>
        <td>III/a</td>
        <td>PNS</td>
        <td>
            <a href="edit.html">Edit</a>
            <button>Hapus</button>
        </td>
    </tr>

</table>

@endsection