<x-app-layout>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ 'My favorite' }}</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/css/bootstrap.min.css">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.16/dist/tailwind.min.css" rel="stylesheet">

    @if(Session::has('success'))
            <div class="alert alert-success">{{Session::get('success')}}</div>
    @endif
</head>

    <x-slot name="header">
        <h2 class="font-semibold font-sans text-center text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Buku Favorit') }}
        </h2>
    </x-slot>


    <div class="container bg-white mt-10 mb-4 p-4 rounded-lg shadow-md">

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-5 p-4">
        @forelse ($bukuFavorite as $buku)
            <div class="mb-4 p-3 border-b">
                <div class="text-center">
                    @if ($buku->filepath)
                        <img class="h-20 w-14 object-fit-contain mx-auto"
                            src="{{ asset($buku->filepath) }}"
                            alt=""
                        />
                    @endif
                </div>
                <div class="mt-2 text-center">
                    <span class="font-bold block">{{ $buku->judul }}</span>
                    <span>{{ $buku->penulis }}</span>
                </div>
            </div>
        @empty
            <div class="text-center font-semibold mt-3" style="color: grey; font-size: 20px;">Tidak ada buku favorit.</div>
        @endforelse
    </div>    
</div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.min.js"></script>
</x-app-layout>