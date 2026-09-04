@extends('layouts.app')

@section('title', 'Demo - Budget Tracker')

@section('content')
<div class="min-h-[60vh] flex items-center justify-center">
    <div class="text-center max-w-2xl mx-auto">
        <div class="mb-8">
            <img src="/assets/logo-light.webp" alt="Titik Simpan" class="h-24 mx-auto mb-4 drop-shadow-[0_4px_12px_rgba(27,163,122,0.4)]">
            <h1 class="text-3xl md:text-4xl font-bold text-gray-900 dark:text-white mb-2">
                Mode Demo
            </h1>
            <p class="text-gray-600 dark:text-gray-400 text-lg">
                Menampilkan data contoh untuk mencoba fitur aplikasi.
            </p>
        </div>

        <div class="bg-gradient-to-br from-[#BDE0D2] to-[#1BA37A]/10 dark:from-[#1BA37A]/20 dark:to-[#6EE7B0]/10 rounded-2xl p-6 md:p-8 mb-8">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-center">
                <div>
                    <p class="text-3xl font-bold text-[#1BA37A] dark:text-[#6EE7B0]" data-count="8500000">Rp 8.500.000</p>
                    <p class="text-xs text-gray-600 dark:text-gray-400 mt-1">Gaji Bulan Ini</p>
                </div>
                <div>
                    <p class="text-3xl font-bold text-red-600 dark:text-red-400" data-count="4325000">Rp 4.325.000</p>
                    <p class="text-xs text-gray-600 dark:text-gray-400 mt-1">Total Pengeluaran</p>
                </div>
                <div>
                    <p class="text-3xl font-bold text-blue-600 dark:text-blue-400" data-count="2800000">Rp 2.800.000</p>
                    <p class="text-xs text-gray-600 dark:text-gray-400 mt-1">Alokasi Budget</p>
                </div>
                <div>
                    <p class="text-3xl font-bold text-green-600 dark:text-green-400" data-count="1375000">Rp 1.375.000</p>
                    <p class="text-xs text-gray-600 dark:text-gray-400 mt-1">Sisa Gaji</p>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 mb-8">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Fitur yang Tersedia</h2>
            <div class="grid grid-cols-2 md:grid-cols-3 gap-4 text-center">
                <div class="p-4 bg-gray-50 dark:bg-gray-700/30 rounded-xl">
                    <div class="w-10 h-10 rounded-xl bg-[#BDE0D2] dark:bg-[#1BA37A]/25 flex items-center justify-center mx-auto mb-2">
                        <svg class="w-5 h-5 text-[#1BA37A] dark:text-[#6EE7B0]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <p class="text-sm font-medium text-gray-900 dark:text-white">Dashboard Bento Layout</p>
                </div>
                <div class="p-4 bg-gray-50 dark:bg-gray-700/30 rounded-xl">
                    <div class="w-10 h-10 rounded-xl bg-[#BDE0D2] dark:bg-[#1BA37A]/25 flex items-center justify-center mx-auto mb-2">
                        <svg class="w-5 h-5 text-[#1BA37A] dark:text-[#6EE7B0]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                    </div>
                    <p class="text-sm font-medium text-gray-900 dark:text-white">Alokasi Budget</p>
                </div>
                <div class="p-4 bg-gray-50 dark:bg-gray-700/30 rounded-xl">
                    <div class="w-10 h-10 rounded-xl bg-[#BDE0D2] dark:bg-[#1BA37A]/25 flex items-center justify-center mx-auto mb-2">
                        <svg class="w-5 h-5 text-[#1BA37A] dark:text-[#6EE7B0]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2z"/></svg>
                    </div>
                    <p class="text-sm font-medium text-gray-900 dark:text-white">Pengeluaran</p>
                </div>
                <div class="p-4 bg-gray-50 dark:bg-gray-700/30 rounded-xl">
                    <div class="w-10 h-10 rounded-xl bg-[#BDE0D2] dark:bg-[#1BA37A]/25 flex items-center justify-center mx-auto mb-2">
                        <svg class="w-5 h-5 text-[#1BA37A] dark:text-[#6EE7B0]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                    </div>
                    <p class="text-sm font-medium text-gray-900 dark:text-white">Berulang</p>
                </div>
                <div class="p-4 bg-gray-50 dark:bg-gray-700/30 rounded-xl">
                    <div class="w-10 h-10 rounded-xl bg-[#BDE0D2] dark:bg-[#1BA37A]/25 flex items-center justify-center mx-auto mb-2">
                        <svg class="w-5 h-5 text-[#1BA37A] dark:text-[#6EE7B0]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                    </div>
                    <p class="text-sm font-medium text-gray-900 dark:text-white">Laporan</p>
                </div>
                <div class="p-4 bg-gray-50 dark:bg-gray-700/30 rounded-xl">
                    <div class="w-10 h-10 rounded-xl bg-[#BDE0D2] dark:bg-[#1BA37A]/25 flex items-center justify-center mx-auto mb-2">
                        <svg class="w-5 h-5 text-[#1BA37A] dark:text-[#6EE7B0]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z"/></svg>
                    </div>
                    <p class="text-sm font-medium text-gray-900 dark:text-white">Multi-bahasa</p>
                </div>
            </div>
        </div>

        <p class="text-gray-500 dark:text-gray-400 text-sm mb-6">
            Ini adalah tampilan demo dengan data contoh. Untuk menyimpan data pribadi Anda,
            silakan mendaftar dan masuk ke akun Anda.
        </p>

        <div class="flex gap-3 justify-center">
            <a href="{{ route('register') }}" class="bg-[#1BA37A] text-white px-6 py-2.5 rounded-2xl hover:bg-[#0F8F68] active:bg-[#0C7A59] transition-all btn-press font-medium shadow-sm">
                Daftar Sekarang
            </a>
            <a href="{{ route('login') }}" class="bg-gray-100 dark:bg-gray-700 text-gray-900 dark:text-white px-6 py-2.5 rounded-2xl hover:bg-gray-200 dark:hover:bg-gray-600 transition-all btn-press font-medium">
                Masuk
            </a>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        animateNumbers();
    });
</script>
@endpush
