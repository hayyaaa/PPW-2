<x-app-layout>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.16/dist/tailwind.min.css" rel="stylesheet">

    @if(count($data_buku))
    <div class="alert alert-success">Ditemukan <strong>{{count($data_buku)}}</strong> data dengan
        kata: <strong>{{ $cari }}</strong></div>
    @else
    <div class="alert alert-warning">
        <h4>Data {{ $cari }} tidak ditemukan</h4>
        <a href="/buku" class="btn btn-warning">Kembali</a>
    </div>
    @endif

    <title>Daftar Buku</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-2">
        <p align="right"><a href="{{ route('buku.create') }}" class="btn btn-primary">Tambah Buku</a></p>
        <form action="{{ route('buku.search') }}" method="get">@csrf
            <input type="text" name="kata" class="form-control" placeholder="Cari..." style="width: 30%;
        display: inline; margin-top: 10px; margin-bottom: 10px; float: right;">
        </form>

        <table class="table table-striped">
            <thead>
                <tr>
                    <th>id</th>
                    <th>Judul Buku</th>
                    <th>Penulis</th>
                    <th>Harga</th>
                    <th>Tgl. Terbit</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($data_buku as $buku)
                <tr>
                    <td>{{ $buku->id }}</td>
                    <td>{{ $buku->judul }}</td>
                    <td>{{ $buku->penulis }}</td>
                    <td>{{ "Rp ".number_format($buku->harga, 2, ',', '.') }}</td>
                    <td>{{ $buku->tgl_terbit }}</td>
                    <td>
                        <form action="{{ route('buku.destroy', $buku->id) }}" method="post">
                            @csrf
                            <button onclick="return confirm('Apakah yakin ingin menghapus data?')" class="btn btn-danger">Hapus</button>
                        </form>
                    </td>
                    <td>
                        <form action="{{ route('buku.edit', $buku->id) }}" method="GET">
                            @csrf
                            <button type="submit" class="btn btn-primary">Edit</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <div>{{ $data_buku->links() }}</div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.min.js"></script>
    
</body>
</html>

</x-app-layout>