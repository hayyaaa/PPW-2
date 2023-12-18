<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Buku; //memanggil model Buku.php, yang didalamnya ada definisi tabel yang digunakan, yaitu tabel buku.
use Intervention\Image\Facades\Image;
use App\Models\Gallery;
use App\Models\Favorite; 
use App\Models\Rate;
use Illuminate\Support\Facades\Auth;

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

        $request->validate([
            'thumbnail' => 'image|mimes:jpeg,jpg,png'
        ]);

        $fileName = time().'_'.$request->thumbnail->getClientOriginalName();
        $filePath = $request->file('thumbnail')->storeAs('uploads', $fileName, 'public');
        $buku -> filename = $fileName;
        $buku -> filepath = '/storage/' . $filePath;

        Image::make(storage_path().'/app/public/uploads/'.$fileName)->fit(140,220)->save();

        $buku->save(); 

        if ($request->file('gallery')) {
            foreach($request->file('gallery') as $key => $file) {
                $fileName = time().'_'.$file->getClientOriginalName();
                $filePath = $file->storeAs('uploads', $fileName, 'public');

                $gallery = Gallery::create([
                    'nama_galeri'   => $fileName,
                    'path'          => '/storage/' . $filePath,
                    'foto'          => $fileName,
                    'buku_id'       => $buku -> id
                ]);
            }
        }

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

        if ($request->file('thumbnail')) {
            $request->validate([
                'thumbnail' => 'image|mimes:jpeg,jpg,png'
            ]);

            $fileName = time().'_'.$request->thumbnail->getClientOriginalName();
            $filePath = $request->file('thumbnail')->storeAs('uploads', $fileName, 'public');

            Image::make(storage_path().'/app/public/uploads/'.$fileName)->fit(240,320)->save();
        }

        if ($request->file('gallery')) {
            foreach($request->file('gallery') as $key => $file) {
                $fileName = time().'_'.$file->getClientOriginalName();
                $filePath = $file->storeAs('uploads', $fileName, 'public');

                $gallery = Gallery::create([
                    'nama_galeri' => $fileName,
                    'path' => '/storage/'. $filePath,
                    'foto' => $fileName,
                    'buku_id' => $id
                ]);
            }
        }

        if ($request->file('thumbnail')) {
            $buku->update([
                'judul' => $request->judul,
                'penulis' => $request->penulis,
                'harga' => $request->harga,
                'tgl_terbit' => $request->tgl_terbit,
                'filename' => $fileName,
                'filepath' => '/storage/' . $filePath
            ]);
        } else {
            if ($buku->filepath) {
                $buku->update([
                    'judul' => $request->judul,
                    'penulis' => $request->penulis,
                    'harga' => $request->harga,
                    'tgl_terbit' => $request->tgl_terbit,
                    'filename' => $buku->filename,
                    'filepath' => $buku->filepath
                ]);
            }
        }
        return redirect('/buku')->with('pesan','Data buku berhasil diubah');
    }

        //     $request->validate([
        //     'thumbnail' => 'image|mimes:jpeg,jpg,png|max:2048'
        // ]);

        // $fileName = time().'_'.$request->thumbnail->getClientOriginalName();
        // $filePath = $request->file('thumbnail')->storeAs('uploads', $fileName, 'public');

        // Image::make(storage_path().'/app/public/uploads/'.$fileName)
        //     ->fit(240,320)
        //     ->save();

        // $buku->update([
        //     'judul' => $request->judul,
        //     'penulis' => $request->penulis,
        //     'harga' => $request->harga,
        //     'tgl_terbit' => $request->tgl_terbit,
        //     'filename' => $fileName,
        //     'filepath' => '/storage/' . $filePath
        // ]);

        // // if ($request->file('gallery')) {
        // //     foreach($request->file('gallery') as $key => $file) {
        // //         $fileName = time().'_'.$file->getClientOriginalName();
        // //         $filePath = $file->storeAs('uploads', $fileName, 'public');

        // //         $gallery = Gallery::create([
        // //             'nama_galeri'   => $fileName,
        // //             'path'          => '/storage/' . $filePath,
        // //             'foto'          => $fileName,
        // //             'buku_id'       => $id
        // //         ]);
        // //     }
        // // }

    public function deleteGallery($id) {
        $gallery = Gallery::findOrFail($id);

        $gallery->delete();

        return redirect()->back();
    }

    public function galbuku($judul) {
        $buku = Buku::where('judul', $judul)->first();
        $galeri = $buku->galleries()->orderBy('id','desc')->paginate(5);
        return view('buku.detail', compact('buku', 'galeri'));
    }

    public function rate(Request $request, $id) {
    $buku = Buku::find($id);

    // Cek apakah pengguna sudah memberikan rating
    $existingRating = Rate::where('user_id', auth()->user()->id)
        ->where('buku_id', $buku->id)
        ->first();

    // Jika sudah memberikan rating, update rating
    if ($existingRating) {
        // Update rating yang sudah ada
        $existingRating->update(['rating' => $request->rating]);

        return redirect()->back()->with('success', 'Rating berhasil diperbarui.');
    }

    // Jika belum memberikan rating, tambahkan rating baru
    $rating = new Rate([
        'user_id' => auth()->user()->id,
        'rating' => $request->rating,
    ]);

    $buku->rating()->save($rating);

    return redirect()->back()->with('success', 'Rating berhasil disimpan.');
}


    public function addToFavorites(Request $request, $id)
    {
        $buku = Buku::find($id);

        $existingFavorites = Favorite::where('user_id', auth()->user()->id)
            ->where('buku_id', $buku->id)
            ->first();

        if ($existingFavorites) {
            return redirect()->back()->with('error', 'Buku sudah ada di favorit Anda.');
        }

        $buku->favoritedBy()->attach(auth()->user()->id);
        return redirect("/buku/myfavorite")->with('success', 'Buku ditambahkan ke favorit.');
    }
}

//     public function show(string $id)
//     {
//         //
//         $buku = Buku::findOrFail($id);

//         $ratings = rating::where('book_id', $id)->get();

//         $averageRating = $ratings->avg('rating');

//         return view('buku.detail', [
//             'buku' => $buku,
//             'ratings' => $ratings,
//             'averageRating' => $averageRating
//         ]);
//     }

//     public function rate(Request $request, $id)
//     {

//         $request->validate([
//             'rating' => 'required|integer|min:1|max:5', // Adjust the validation rules as needed
//         ]);

//         $rating = new rating();
//         $rating->user_id = auth()->id();
//         $rating->book_id = $id;
//         $rating->rating = $request->input('rating');
//         $rating->save();

//         // You may want to redirect back or to the book detail page
//         return redirect()->route('buku.show', $id)->with('success', 'Rating submitted successfully!');
//     }

//     public function favbuku() {
//         $data_buku = Favourite::where('fav_id', Auth::id())->get();
//         return view('buku.favorit', compact('data_buku'));
//     }

//     public function addFavourite(Buku $buku) {
//         $user = Auth::user();

//         if ($user) {
//             $favourite = new Favourite();
//             $favourite->user_id = $user->id;
//             $favourite->book_id = $buku->id;

//             return redirect()->back()->with('success', "Book added to favorite");
//         }
//     }
// }