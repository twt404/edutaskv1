<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Detail Barang - {{ $barang->nama_barang }}</title>
   @vite('resources/css/app.css')
</head>
<body class="bg-gray-50 text-gray-800flex items-center px-100 justify-center p-6">

    <div class="w-full max-w-md bg-white border border-gray-200 rounded-xl shadow-sm p-6">

        <div class="border-b border-gray-100 pb-4 mb-5">
            <span class="text-xs font-medium text-gray-400">Detail Barang</span>
            <h2 class="text-xl font-semibold text-gray-900 mt-1">{{ $barang->nama_barang ?? '-' }}</h2>
        </div>

        <dl class="divide-y divide-gray-100 text-sm">
            <div class="py-3 flex justify-between">
                <dt class="text-gray-500">Kode Produk</dt>
                <dd class="font-medium text-gray-900">{{ $barang->kode_barang ?? '-' }}</dd>
            </div>
            <div class="py-3 flex justify-between">
                <dt class="text-gray-500">Nama Barang</dt>
                <dd class="font-medium text-gray-900">{{ $barang->nama_barang ?? '-' }}</dd>
            </div>
            <div class="py-3 flex justify-between">
                <dt class="text-gray-500">Jumlah</dt>
                <dd class="font-medium text-gray-900">{{ $barang->jumlah ?? 0 }}</dd>
            </div>
            <div class="py-3 flex justify-between items-center">
                <dt class="text-gray-500">Kondisi</dt>
                <dd class="px-2.5 py-0.5 text-xs font-medium rounded-full bg-gray-100 text-gray-700">
                    {{ $barang->kondisi ?? '-' }}
                </dd>
            </div>
        </dl>

        <div class="mt-6 pt-4 border-t border-gray-100">
            <a href="{{ route('barang.index') }}" class=" w-full text-center text-sm font-medium text-gray-600 hover:text-gray-900 bg-gray-100 hover:bg-gray-200 py-2 rounded-lg transition"> Kembali
            </a>
        </div>

    </div>

</body>
</html>
