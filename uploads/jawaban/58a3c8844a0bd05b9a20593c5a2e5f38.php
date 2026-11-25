<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    @vite('resources/css/app.css')
   
    <title>Document</title>
</head>
<body class="p-6 text-sm text-gray-800 bg-gray-50">
    <div>
<p class="text-lg font-bold mb-1">DATA INVENTARIS BARANG LAB KOMPUTER</p>
<p>Halaman pengelolaan dan pemantauan data inventaris barang laboratorium komputer
    </div>
    <p></p> mencakup kode barang, nama barang, jumlah stok, serta kondisi perangkat</p>
<div class="p-2 shadow rounded ">
    <div class="mb-4 flex justify-end">
  <a href="{{ route('barang.create') }}" class="py-1 px-2 bg-green-400 text-white hover:bg-green-600 rounded">
    +tambah data
  </a>
</div>
  <form action="{{ route('barang.index') }}" method="get"  class="mb-2">
        <input type="text" name="search" class="border border-gray-300 px-2 py-1 rounded w-60" placeholder="kode,nama,kondisi">
        <button type="submit" class="bg-gray-800 text-white px-3 py-1 rounded">cari</button>
    </form>
   
  
   
    
        <table class="border-collapse border border-gray-300 w-full">
            <tr class="bg-gray-100">
                <th class="border border-gray-300 px-3 py-1.5 text-center">Kode Barang</th>
                <th class="border border-gray-300 px-3 py-1.5 text-center">Nama Barang</th>
                <th class="border border-gray-300 px-3 py-1.5 text-center">Jumlah</th>
                <th class="border border-gray-300 px-3 py-1.5 text-center">Kondisi</th>
                <th class="border border-gray-300 px-3 py-1.5 text-center">Aksi</th>
            </tr>
             @foreach ($barangs as $s)
            <tr class="hover:bg-gray-50">
                <td class="border border-gray-300 px-3 py-1.5">{{ $s-> kode_barang }}</td>
                <td class="border border-gray-300 px-3 py-1.5">{{ $s-> nama_barang }}</td>
                <td class="border border-gray-300 px-3 py-1.5">{{ $s-> jumlah }}</td>
                <td class="border border-gray-300 px-3 py-1.5">{{ $s-> kondisi }}</td>
                <td class="border border-gray-300 px-3 py-1.5">
                    <center>
                    <form action="{{ route('barang.destroy', $s->id) }}" method="POST" onsubmit="return confirm('Anda yakin ingin menghapus data ini?')" class="flex gap-2 justify-center">
                        <a href="{{ route('barang.show', $s->id) }}" class="text-white py-1 px-2 rounded bg-gray-500 hover:bg-gray-600">Detail</a>
                        <a href="{{ route('barang.edit',$s->id) }}" class="text-white py-1 px-2 rounded bg-orange-400 hover:bg-orange-600">Edit</a>
                        @csrf @method('DELETE')
                        <button type="submit" class="text-white py-1 px-2 rounded bg-red-500 hover:bg-red-600">Hapus</button>
                    </form>
                </td>
            </tr>
             @endforeach
        </table>
       <form action="{{ route('logout') }}" method="POST" class="inline">
    @csrf
    <button type="submit" class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-semibold rounded-lg transition cursor-pointer">
        Logout
    </button>
</form>

    </div>
</body>
</html>
