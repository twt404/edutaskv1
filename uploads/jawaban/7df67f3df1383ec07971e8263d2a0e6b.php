<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    @vite('resources/css/app.css')
    <title>Document</title>
</head>
<body class="px-100 py-7 bg-gray-50">
    <div class="shadow bg-white p-6 rounded">
    <h3 class="mb-3 text-2xl font-bold ">Tambah data</h3>
  
        <form action="{{ route('barang.update',$barang->id) }}" method="POST" >
            @csrf @method('PUT')
            <div>
            <label for="" class="block font-medium text-md">Kode Barang :</label>
            <input type="text" name="kode_barang" value="{{ $barang->kode_barang }}" required
            class="border block border-gray-500 rounded w-full mt-1 py-0.5 hover:border-gray-900 hover:border-2 transition "
            >
            </div>
            <div>
            <label class="block font-medium text-md mt-2.5">Nama barang :</label>
            <input type="text" name="nama_barang" value="{{ $barang->nama_barang }}" required
            class="border block border-gray-500 rounded w-full mt-1 py-0.5 hover:border-gray-900 hover:border-2"
            >
            </div>
            <div>
            <label class="block font-medium text-md mt-2.5">Jumlah :</label>
            <input type="number" name="jumlah" value="{{ $barang->jumlah }}" required
            class="border block border-gray-500 rounded w-full mt-1 py-0.5 hover:border-gray-900 hover:border-2"
            >
            </div>
            <div>
            <label class="block font-medium text-md mt-2.5">Kondisi :</label>
           <select name="kondisi" id="" class="border block border-gray-500 rounded w-full mt-1 py-0.5 hover:border-gray-900 hover:border-2">
            <option value="Baik" @selected($barang->kondisi == 'Baik')>Baik</option>
            <option value="Rusak Ringan" @selected($barang->kondisi == 'Rusak Ringan')>Rusak Ringan</option>
            <option value="Rusak Berat" @selected($barang->kondisi == 'Rusak Berat')>Rusak Berat</option>
           </select>
           </div>
           <br>
           <div>
           <button type="submit" class="bg-orange-400 hover:bg-orange-600 py-1 px-3 rounded text-white">
                    Edit
                </button>
                
              
            </form>
              <button class="bg-gray-400 ml-3 hover:bg-gray-600 py-1 px-2 rounded text-white">
                <a href="{{ route('barang.index') }}" >
                    Kembali
                </a>
        </button>
</div>
        </div>
</body>
</html>