@extends('layouts.app')

@section('content')
<div class="container mx-auto p-6 bg-gray-100 rounded-lg shadow-md">
    <h1 class="text-3xl font-bold text-center text-gray-800 mb-6">User Dashboard</h1>
    
    <!-- Daftar Barang Section -->
    <h2 class="text-2xl font-semibold text-gray-700 mb-4">Daftar Barang</h2>
    <div class="overflow-x-auto">
        <table class="w-full bg-white shadow-md rounded-lg overflow-hidden">
            <thead class="bg-blue-500 text-white">
                <tr>
                    <th class="px-4 py-2">Nama Barang</th>
                    <th class="px-4 py-2">Kategori</th>
                    <th class="px-4 py-2">Stok</th>
                    <th class="px-4 py-2">Supplier</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($items as $item)
                <tr class="hover:bg-blue-100">
                    <td class="px-4 py-2 border">{{ $item->item_name }}</td>
                    <td class="px-4 py-2 border">{{ $item->category->name }}</td>
                    <td class="px-4 py-2 border">{{ $item->stocks->first() ? $item->stocks->first()->quantity : 'Stok Tidak Tersedia' }}</td>
                    <td class="px-4 py-2 border">{{ $item->supplier->name }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Riwayat Transaksi Section -->
    <h2 class="text-2xl font-semibold text-gray-700 mt-8 mb-4">Riwayat Transaksi</h2>
    <div class="overflow-x-auto">
        <table class="w-full bg-white shadow-md rounded-lg overflow-hidden">
            <thead class="bg-green-500 text-white">
                <tr>
                    <th class="px-4 py-2">ID Transaksi</th>
                    <th class="px-4 py-2">Nama</th>
                    <th class="px-4 py-2">Alamat</th>
                    <th class="px-4 py-2">Item</th>
                    <th class="px-4 py-2">Terverifikasi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($transactions as $transaction)
                <tr class="hover:bg-green-100">
                    <td class="px-4 py-2 border">{{ $transaction->id }}</td>
                    <td class="px-4 py-2 border">{{ $transaction->name }}</td>
                    <td class="px-4 py-2 border">{{ $transaction->address }}</td>
                    <td class="px-4 py-2 border">{{ $transaction->item->item_name }}</td>
                    <td class="px-4 py-2 border {{ $transaction->is_paid ? 'text-green-500 font-bold' : 'text-red-500 font-bold' }}">
                        {{ $transaction->is_paid ? '✅ Sukses Dibayar' : '❌ Belum Dibayar' }}
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
