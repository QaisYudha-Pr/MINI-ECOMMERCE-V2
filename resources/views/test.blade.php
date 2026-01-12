<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Item</title>
</head>
<body>

    <form action="{{ route('search') }}" method="GET">
        <input 
            type="text" 
            name="query" 
            placeholder="Search..." 
            value="{{ request('query') }}"
        >
        <button type="submit">Search</button>
    </form>

    <hr>

    @if(request('query'))
        <h3>Hasil pencarian untuk: "{{ request('query') }}"</h3>
    @endif

    @forelse($results as $item)
        <img  src="{{ $item->gambar }}" alt="{{ $item->nama_barang }}" width="200" >
        <p>{{ $item->nama_barang }}</p>
    @empty
        <p>Tidak ada barang ditemukan.</p>
    @endforelse

</body>
</html>
