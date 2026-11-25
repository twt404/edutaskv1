<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
   @vite('resources/css/app.css')

    <title>Document</title>
</head>
<body class="py-6 px-100 bg-gray-50">
    <div class="shadow bg-white p-4 text-gray-800 rounded">
    <h3 class="text-2xl font-bold mb-5">Tambah data</h3>
  
        <form action="{{ route('barang.store') }}" method="POST" >
            @csrf
            <div>
            <label for="" class="block text-md font-medium">Kode Barang :</label>
            <input type="text" name="kode_barang" required
            class="border border-gray-500 rounded w-full mt-1 block py-0.5"
            >
            </div>
            <div>
            <label class="block text-md font-medium">Nama barang :</label>
            <input type="text" name="nama_barang" required
            class="border border-gray-500 rounded w-full mt-1 block py-0.5"
            >
            </div>
            <div>
            <label class="block text-md font-medium">Jumlah :</label>
            <input type="number" name="jumlah" required
            class="border border-gray-500 rounded w-full mt-1 block py-0.5"
            >
            </div>
            <div>
            <label class="block text-md font-medium">Kondisi :</label>
           <select name="kondisi" id="" 
           class="border border-gray-500 rounded w-full mt-1 block py-0.5"
           >
            <option value="Baik">Baik</option>
            <option value="Rusak Ringan">Rusak Ringan</option>
            <option value="Rusak Berat">Rusak Berat</option>
           </select>
           </div>
           <button type="submit" class="bg-green-400 py-1 px-2 rounded mt-7 hover:bg-green-600 text-white">
                    Simpan
                </button>
                <a href="{{ route('barang.index') }}" class="hover:underline ">
                    Kembali
                </a>
        </form>
        </div>
</body>
</html>