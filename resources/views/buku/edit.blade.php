<x-app-layout>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Buku</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/css/bootstrap.min.css">
</head>
<body>
    <br><br>
    <div class="container bg-white"><br>
        <h2 align="center">Edit Buku</h2>
        <br>
        <form action="{{ route('buku.update', $buku->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label for="judul">Judul</label>
                <input type="text" class="form-control" id="judul" name="judul" value="{{ $buku->judul }}">
            </div>
            <div class="form-group">
                <label for="penulis">Penulis</label>
                <input type="text" class="form-control" id="penulis" name="penulis" value="{{ $buku->penulis }}">
            </div>
            <div class="form-group">
                <label for="harga">Harga</label>
                <input type="text" class="form-control" id="harga" name="harga" value="{{ $buku->harga }}">
            </div>
            <div class="form-group">
                <label for="tgl_terbit">Tgl. Terbit</label>
                <input type="date" class="form-control" id="tgl_terbit" name="tgl_terbit" value="{{ $buku->tgl_terbit }}">
            </div>
            <div class="form-group">
                <label for="thumbnail">Thumbnail</label><br>
                <input type="file" id="thumbnail" name="thumbnail">
            </div><br>
            <div class="form-group">
                <label for="gallery">Gallery</label><br>
                <div class="mt-2" id="fileinput_wrapper"></div>
                    <a class="btn btn-outline-secondary my-2" href="javascript:void(0);" id="tambah" onclick="addFileInput()"><i class="bi bi-plus-circle-fill"></i>Tambah</a>
                    <script type="text/javascript">
                        function addFileInput () {
                            var div = document.getElementById('fileinput_wrapper');
                            div.innerHTML += '<input type="file" name="gallery[]" id="gallery" class="block w-full mb-5" style="margin-bottom:5px;">';
                            };
                    </script>
            </div>
            <div class="gallery_items">
                @foreach($buku->galleries()->get() as $gallery)
                    <div class="gallery_item">
                        <img
                            src="{{ asset($gallery->path) }}"
                            alt=""
                            width="300"
                            />
                        <a href="{{ route('buku.deleteGallery', $gallery->id) }}" class="btn btn-danger btn-sm mt-2 mb-5" style="position:inherit; top: 10px; right: 10px;"><i class="bi bi-x-lg"></i>Delete</a>
                    </div>
                @endforeach
            </div>

            <button onclick="return confirm('Apakah ingin menyimpan perubahan?')" type="submit" class="btn btn-primary">Simpan Perubahan</button>
            <a href="/buku" class="btn btn-secondary">Batal</a>
        </form><br>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.min.js"></script>

</body>
</html>

</x-app-layout>