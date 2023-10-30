<x-app-layout>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.16/dist/tailwind.min.css">

    @if(Session::has('pesan'))
    <div class="alert alert-success">{{Session::get('pesan')}}</div>
    @endif

    <title>Daftar Buku</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/css/bootstrap.min.css">
</head>
<body>
    <br>
    <h2 align="center">Daftar Buku</h2>
    <br>
    <div class="container mt-2">
        <div class="flex justify-between items-center">
            <a href="{{ route('buku.create') }}" class="btn btn-primary">Tambah Buku</a>
            <form action="{{ route('buku.search') }}" method="get" class="flex items-center">
                <input type="text" name="kata" class="form-control" placeholder="Cari..." style="width: 100%; display: inline; margin-top: 10px; margin-bottom: 10px; float: right;">
            </form>
        </div>

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
                    <td>{{ \Carbon\Carbon::parse($buku->tgl_terbit)->format('d/m/Y') }}</td>
                    <td>
                        <form action="{{ route('buku.destroy', $buku->id) }}" method="post" style="display: inline-block; margin-right: 5px;">
                            @csrf
                            @method('DELETE')
                            <button onclick="return confirm('Apakah yakin ingin menghapus data?')" class="btn btn-sm btn-danger">Hapus</button>
                        </form>

                        <form action="{{ route('buku.edit', $buku->id) }}" method="GET" style="display: inline-block;">
                            @csrf
                            <button type="submit" class="btn btn-sm btn-primary">Edit</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>

            <tfoot>
                <tr>
                    <th> TOTAL </th>
                    <th>{{ $jumlah_buku }}</th>
                    <th colspan="1"></th>
                    <th>{{ "Rp ".number_format($total_harga, 2, ',', '.') }}</th>
                </tr>
            </tfoot>
        </table>

        <div>{{ $data_buku->links() }}</div>

    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.min.js"></script>

</body>
</html>

</x-app-layout>