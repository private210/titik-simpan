@extends('layouts.app')

@section('title', 'Titik Simpan - Kelola Keuangan Lebih Bijak')

@section('content')
<style>
    .acc-btn:after { content: '+'; float: right; font-weight: 700; transition: transform 0.2s; }
    .acc-open .acc-btn:after { transform: rotate(45deg); }
    .acc-panel { max-height: 0; overflow: hidden; transition: max-height 0.3s ease; }
    .acc-open .acc-panel { max-height: 220px; }
</style>

<section class="relative overflow-hidden">
    <div class="absolute inset-0 bg-gradient-to-br from-[#BDE0D2]/40 via-transparent to-[#1BA37A]/10 dark:from-[#1BA37A]/20 dark:to-transparent pointer-events-none"></div>
    <div class="relative max-w-4xl mx-auto text-center py-14 md:py-24 px-4">
        <img src="/assets/logo-light.webp" alt="Titik Simpan" class="h-20 md:h-28 mx-auto mb-6 drop-shadow-[0_8px_20px_rgba(27,163,122,0.4)]">
        <h1 class="text-3xl md:text-5xl font-brand text-gray-900 dark:text-white leading-tight">
            Catat <span class="text-[#1BA37A]">Sekarang</span>,<br>
            Hemat <span class="text-[#1BA37A]">Hari Ini</span>,<br>
            Untuk <span class="text-[#1BA37A]">Masa Depan</span> Yang Lebih Baik
        </h1>
        <p class="mt-5 text-base md:text-lg text-gray-600 dark:text-gray-300 max-w-xl mx-auto">
            Aplikasi budget tracker sederhana untuk mencatat pemasukan, mengalokasikan anggaran,
            dan mengontrol pengeluaran bulanan Anda.
        </p>

        <div class="mt-8 flex flex-wrap gap-3 justify-center">
            <a href="{{ route('demo.index') }}" class="bg-[#1BA37A] text-white px-7 py-3 rounded-2xl font-semibold hover:bg-[#0F8F68] active:bg-[#0C7A59] transition-all btn-press shadow-lg shadow-[#1BA37A]/30">
                Coba Demo Sekarang
            </a>
            <a href="{{ route('register') }}" class="bg-white dark:bg-gray-800 text-gray-900 dark:text-white border border-gray-300 dark:border-gray-600 px-7 py-3 rounded-2xl font-semibold hover:bg-gray-50 dark:hover:bg-gray-700 transition-all btn-press">
                Daftar Gratis
            </a>
            <a href="{{ route('login') }}" class="text-[#1BA37A] dark:text-[#6EE7B0] px-5 py-3 font-semibold hover:underline transition-all">
                Masuk
            </a>
        </div>

        <div class="mt-12 grid grid-cols-2 sm:grid-cols-4 gap-4 max-w-2xl mx-auto">
            <div class="bg-white/70 dark:bg-gray-800/70 backdrop-blur rounded-2xl border border-gray-200 dark:border-gray-700 p-4">
                <p class="text-2xl font-bold text-[#1BA37A]">6</p>
                <p class="text-xs text-gray-600 dark:text-gray-400 mt-0.5">Kategori default</p>
            </div>
            <div class="bg-white/70 dark:bg-gray-800/70 backdrop-blur rounded-2xl border border-gray-200 dark:border-gray-700 p-4">
                <p class="text-2xl font-bold text-[#1BA37A]">3+</p>
                <p class="text-xs text-gray-600 dark:text-gray-400 mt-0.5">Laporan lengkap</p>
            </div>
            <div class="bg-white/70 dark:bg-gray-800/70 backdrop-blur rounded-2xl border border-gray-200 dark:border-gray-700 p-4">
                <p class="text-2xl font-bold text-[#1BA37A]">2</p>
                <p class="text-xs text-gray-600 dark:text-gray-400 mt-0.5">Bahasa (ID/EN)</p>
            </div>
            <div class="bg-white/70 dark:bg-gray-800/70 backdrop-blur rounded-2xl border border-gray-200 dark:border-gray-700 p-4">
                <p class="text-2xl font-bold text-[#1BA37A]">100%</p>
                <p class="text-xs text-gray-600 dark:text-gray-400 mt-0.5">Gratis</p>
            </div>
        </div>
    </div>
</section>

<section class="py-12 md:py-16">
    <div class="max-w-6xl mx-auto px-4">
        <h2 class="text-2xl md:text-3xl font-brand text-center text-gray-900 dark:text-white mb-10">Fitur Unggulan</h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 md:gap-6">
            @php
            $features = [
                ['icon' => 'M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z', 'title' => 'Dashboard Bento', 'desc' => 'Ringkasan keuangan dalam satu layar: saldo, alokasi, dan pengeluaran terbaru.'],
                ['icon' => 'M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z', 'title' => 'Alokasi Budget', 'desc' => 'Bagi gaji ke kategori kebutuhan, transport, tabungan, dan lainnya per bulan.'],
                ['icon' => 'M3 10h18M7 15h2m4 0h4m-9 5h8a2 2 0 002-2V7a2 2 0 00-2-2H3a2 2 0 00-2 2v11a2 2 0 002 2z', 'title' => 'Tagihan Berulang', 'desc' => 'Atur tagihan bulanan/mingguan, pantau jatuh tempo, dan tandai sebagai bayar.'],
                ['icon' => 'M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z', 'title' => 'Laporan & Grafik', 'desc' => 'Export PDF/Excel, grafik per kategori, ringkasan harian, pengeluaran terbesar.'],
                ['icon' => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z', 'title' => 'Mode Gelap & Multi-bahasa', 'desc' => 'Tampilan terang/gelap otomatis dan bahasa Indonesia & English.'],
                ['icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z', 'title' => 'Data Aman', 'desc' => 'Data terisolasi per pengguna, login aman dengan rate limiter dan Google OAuth.'],
            ];
            @endphp
            @foreach($features as $f)
                <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-sm p-5 hover:shadow-md transition-all">
                    <div class="w-11 h-11 rounded-xl bg-[#BDE0D2] dark:bg-[#1BA37A]/25 flex items-center justify-center mb-3">
                        <svg class="w-5 h-5 text-[#1BA37A] dark:text-[#6EE7B0]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $f['icon'] }}"/></svg>
                    </div>
                    <h3 class="font-semibold text-gray-900 dark:text-white">{{ $f['title'] }}</h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">{{ $f['desc'] }}</p>
                </div>
            @endforeach
        </div>
    </div>
</section>

<section class="py-12 md:py-16 bg-white dark:bg-gray-800/50 border-y border-gray-200 dark:border-gray-700">
    <div class="max-w-4xl mx-auto px-4">
        <h2 class="text-2xl md:text-3xl font-brand text-center text-gray-900 dark:text-white mb-10">Cara Kerja</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="text-center">
                <div class="w-12 h-12 rounded-full bg-[#1BA37A] text-white font-bold flex items-center justify-center mx-auto mb-3 text-lg">1</div>
                <h3 class="font-semibold text-gray-900 dark:text-white">Input Gaji</h3>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Catat gaji atau pendapatan tambahan bulan ini.</p>
            </div>
            <div class="text-center">
                <div class="w-12 h-12 rounded-full bg-[#1BA37A] text-white font-bold flex items-center justify-center mx-auto mb-3 text-lg">2</div>
                <h3 class="font-semibold text-gray-900 dark:text-white">Alokasikan Dana</h3>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Bagi budget ke setiap kategori kebutuhanmu.</p>
            </div>
            <div class="text-center">
                <div class="w-12 h-12 rounded-full bg-[#1BA37A] text-white font-bold flex items-center justify-center mx-auto mb-3 text-lg">3</div>
                <h3 class="font-semibold text-gray-900 dark:text-white">Catat & Pantau</h3>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Catat pengeluaran harian dan pantau lewat laporan.</p>
            </div>
        </div>
    </div>
</section>

<section id="faq" class="py-12 md:py-16">
    <div class="max-w-3xl mx-auto px-4">
        <h2 class="text-2xl md:text-3xl font-brand text-center text-gray-900 dark:text-white mb-10">FAQ</h2>
        <div class="space-y-3">
            @php
            $faqs = [
                ['q' => 'Apakah Titik Simpan gratis?', 'a' => 'Ya, aplikasi ini 100% gratis digunakan. Kamu hanya perlu membuat akun untuk mulai mengelola keuanganmu.'],
                ['q' => 'Apakah data saya aman?', 'a' => 'Data terisolasi per pengguna dan hanya kamu yang bisa melihatnya. Kamu juga bisa menghapus semua data kapan saja lewat tombol Reset Data.'],
                ['q' => 'Apa bedanya dengan catatan pengeluaran biasa?', 'a' => 'Selain mencatat, Titik Simpan membantu mengalokasikan anggaran per kategori, memantau tagihan berulang, dan menyajikan laporan dalam bentuk grafik serta export PDF/Excel.'],
                ['q' => 'Bisakah saya mencoba dulu sebelum daftar?', 'a' => 'Tentu! Klik tombol "Coba Demo Sekarang" untuk menjelajah fitur dengan data contoh. Data demo tidak akan tersimpan ke akunmu.'],
                ['q' => 'Apakah ada aplikasi mobile?', 'a' => 'Saat ini Titik Simpan berbasis web dan sudah responsif, jadi tetap nyaman dipakai dari HP melalui browser.'],
                ['q' => 'Bagaimana jika saya lupa password?', 'a' => 'Untuk saat ini, hubungi kami melalui formulir kontak di bawah untuk bantuan pemulihan akun.'],
            ];
            @endphp
            @foreach($faqs as $i => $faq)
                <div class="acc-item bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 overflow-hidden">
                    <button type="button" onclick="toggleFaq(this)" class="acc-btn w-full text-left px-5 py-4 font-medium text-gray-900 dark:text-white text-sm md:text-base">
                        {{ $faq['q'] }}
                    </button>
                    <div class="acc-panel px-5 pb-4 text-sm text-gray-500 dark:text-gray-400">
                        {{ $faq['a'] }}
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>

<section id="kontak" class="py-12 md:py-16 bg-white dark:bg-gray-800/50 border-t border-gray-200 dark:border-gray-700">
    <div class="max-w-lg mx-auto px-4">
        <h2 class="text-2xl md:text-3xl font-brand text-center text-gray-900 dark:text-white mb-2">Hubungi Kami</h2>
        <p class="text-center text-sm text-gray-500 dark:text-gray-400 mb-8">
            Punya pertanyaan atau saran? Kirim pesan ke
            <a href="mailto:pname210@gmail.com" class="text-[#1BA37A] dark:text-[#6EE7B0] font-medium hover:underline">pname210@gmail.com</a>
        </p>

        <form id="contact-form" class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-200 dark:border-gray-700 shadow-sm p-6 space-y-4">
            <div>
                <label for="c-name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Nama</label>
                <input id="c-name" type="text" required placeholder="Nama kamu"
                    class="w-full border border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-white rounded-2xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#1BA37A]/50">
            </div>
            <div>
                <label for="c-email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Email</label>
                <input id="c-email" type="email" required placeholder="email@contoh.com"
                    class="w-full border border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-white rounded-2xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#1BA37A]/50">
            </div>
            <div>
                <label for="c-message" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Pesan</label>
                <textarea id="c-message" rows="4" required placeholder="Tulis pesanmu di sini..."
                    class="w-full border border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-white rounded-2xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#1BA37A]/50 resize-y"></textarea>
            </div>
            <button type="submit" onclick="event.preventDefault(); document.getElementById('contact-form').reset(); alert('Terima kasih! Formulir kontak akan segera aktif. Email kami: pname210@gmail.com');"
                class="w-full bg-[#1BA37A] text-white py-3 rounded-2xl font-semibold hover:bg-[#0F8F68] active:bg-[#0C7A59] transition-all btn-press shadow-sm">
                Kirim Pesan
            </button>
            <p class="text-center text-xs text-gray-400 dark:text-gray-500">Formulir ini belum aktif dan belum mengirim email apa pun.</p>
        </form>
    </div>
</section>
@endsection

@push('scripts')
<script>
    function toggleFaq(btn) {
        var item = btn.closest('.acc-item');
        var wasOpen = item.classList.contains('acc-open');
        document.querySelectorAll('.acc-item').forEach(function(el) { el.classList.remove('acc-open'); });
        if (!wasOpen) item.classList.add('acc-open');
    }
</script>
@endpush