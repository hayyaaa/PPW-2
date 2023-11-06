<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Buku; //memanggil model Buku.php, yang didalamnya ada definisi tabel yang digunakan, yaitu tabel buku.

class BukuController extends Controller
{   
    //fungsi index
    public function index() {
        $data_buku = Buku::all();
        // Buku::all( ) untuk menampilkan semua data buku pada tabel buku.

        // menghitung jumlah baris
        $jumlah_data = Buku::count();

        // menghitung total harga
        $total_harga = 0;
        foreach ($data_buku as $buku) {
            $total_harga = $total_harga +  (int)$buku->harga;
        }

        // paginate
        $batas = 5;
        $jumlah_buku = Buku::count();
        $data_buku = Buku::orderBy('id', 'asc')->paginate($batas);
        $no = $batas * ($data_buku->currentPage() - 1);

        return view('buku.index', compact('data_buku', 'jumlah_buku', 'total_harga', 'no'));
        //Compact( ) untuk mem-passing/mengirimkan variabel dari Controller ke View.

    }

    public function create() {
        return view('buku.create');
    }

    public function search(Request $request) {
        $batas = 5;
        $cari = $request->kata;
        $data_buku = Buku::where('judul', 'like',"%".$cari."%")->orwhere('penulis','like',"%".$cari."%")
            ->paginate($batas);
        $jumlah_buku = Buku::count();
        $total_harga = Buku::sum('harga');

        // Menghitung nomor urut berdasarkan halaman saat ini
        $no = $batas * ($data_buku->currentPage() - 1);

        return view('buku.search', compact('jumlah_buku', 'data_buku', 'no', 'cari'));
    }

    public function store(Request $request) {
        $buku = new Buku();
        $buku->judul = $request->judul;
        $buku->penulis = $request->penulis;
        $buku->harga = $request->harga;
        $buku->tgl_terbit = date('Y-m-d', strtotime($request->tgl_terbit));
        
        $this->validate($request,[
            'judul' => 'required|string',
            'penulis' => 'required|string|max:30',
            'harga' => 'required|numeric',
            'tgl_terbit' => 'required|date'
        ]);

        $buku->save(); 

        return redirect('/buku')->with('pesan','Data buku berhasil disimpan.');
    }

    public function destroy($id) {
        $buku = Buku::find($id);
        $buku->delete();
        return redirect('/buku')->with('pesan','Data buku berhasil dihapus');
    }

    public function edit($id) {
        $buku = Buku::find($id);
        return view('buku.edit', compact('buku'));
    }


    public function update(Request $request, $id) {
        $buku = Buku::find($id);
        $buku->update([
            'judul' => $request->judul,
            'penulis' => $request->penulis,
            'harga' => $request->harga,
            'tgl_terbit' => $request->tgl_terbit
        ]);
        return redirect('/buku')->with('pesan','Data buku berhasil diubah');
    }
}