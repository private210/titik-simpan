@extends('layouts.app')

@section('title', 'Titik Simpan - Kelola Keuangan Lebih Bijak')

@section('content')
<style>
    .acc-btn:after { content: '+'; float: right; font-weight: 700; transition: transform 0.2s; }
    .acc-open .acc-btn:after { transform: rotate(45deg); }
    .acc-panel { max-height: 0; overflow: hidden; transition: max-height 0.3s ease; }
    .acc-open .acc-panel { max-height: 220px; }
    main { max-width: none !important; }
    .reveal { opacity: 0; transition: opacity .55s ease; }
    .reveal.show { opacity: 1; }
</style>

<section id="hero" class="relative overflow-hidden -mx-4 sm:-mx-6 lg:-mx-8 px-4 sm:px-6 lg:px-8">
    <div id="aurora-container" class="absolute inset-0 pointer-events-none opacity-70" style="z-index:0"></div>
    <div class="relative mx-auto text-center py-10 md:py-16 px-4 reveal show">
        <div id="hero-logo-wrap" class="inline-block" style="transform-style:preserve-3d; transition: transform .18s ease-out; will-change:transform">
            <img id="hero-logo" src="/assets/logo-light.png" alt="Titik Simpan" class="h-24 md:h-32 mx-auto mb-6 drop-shadow-[0_8px_20px_rgba(27,163,122,0.4)]">
        </div>
        <h2 class="text-xl md:text-3xl font-brand text-gray-900 dark:text-white leading-tight">
            Catat <span class="text-[#1BA37A]">Sekarang</span>, Hemat <span class="text-[#1BA37A]">Hari Ini</span>,<br>
            Untuk <span class="text-[#1BA37A]">Masa Depan</span> Yang Lebih Baik
        </h2>
        <p class="mt-4 text-[14px] md:text-[16px] text-gray-600 dark:text-gray-300 max-w-xl mx-auto">
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
        </div>

        <div class="mt-12 grid grid-cols-2 sm:grid-cols-4 gap-4 max-w-2xl mx-auto">
            <div>
                <p class="text-2xl font-bold text-[#1BA37A]">6</p>
                <p class="text-xs text-gray-600 dark:text-gray-400 mt-0.5">Kategori default</p>
            </div>
            <div>
                <p class="text-2xl font-bold text-[#1BA37A]">3+</p>
                <p class="text-xs text-gray-600 dark:text-gray-400 mt-0.5">Laporan lengkap</p>
            </div>
            <div>
                <p class="text-2xl font-bold text-[#1BA37A]">2</p>
                <p class="text-xs text-gray-600 dark:text-gray-400 mt-0.5">Bahasa (ID/EN)</p>
            </div>
            <div>
                <p class="text-2xl font-bold text-[#1BA37A]">100%</p>
                <p class="text-xs text-gray-600 dark:text-gray-400 mt-0.5">Gratis</p>
            </div>
        </div>
    </div>
</section>

<section class="py-10 md:py-14">
    <div class="max-w-6xl mx-auto px-4">
        <h2 class="text-xl md:text-2xl font-brand text-center text-gray-900 dark:text-white mb-8 reveal">Fitur Unggulan</h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 md:gap-5">
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
                <div class="reveal bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-5 shadow-sm hover:shadow-md hover:border-[#1BA37A]/30 dark:hover:border-[#1BA37A]/40 transition-all">
                    <div class="w-10 h-10 rounded-lg bg-[#1BA37A]/10 dark:bg-[#1BA37A]/20 flex items-center justify-center mb-3">
                        <svg class="w-5 h-5 text-[#1BA37A] dark:text-[#6EE7B0]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $f['icon'] }}"/></svg>
                    </div>
                    <h3 class="font-semibold text-gray-900 dark:text-white mb-1.5">{{ $f['title'] }}</h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400 leading-relaxed">{{ $f['desc'] }}</p>
                </div>
            @endforeach
        </div>
    </div>
</section>

<section class="py-10 md:py-14">
    <div class="mx-auto px-4">
        <h2 class="text-xl md:text-2xl font-brand text-center text-gray-900 dark:text-white mb-8 reveal">Cara Kerja</h2>
        <div class="relative max-w-3xl mx-auto">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 md:gap-4">
                <div class="text-center reveal">
                    <div class="relative z-10 w-10 h-10 rounded-full bg-[#1BA37A] text-white font-bold flex items-center justify-center mx-auto mb-3 text-sm shadow-md shadow-[#1BA37A]/30">1</div>
                    <h3 class="font-semibold text-gray-900 dark:text-white">Input Gaji</h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Catat gaji atau pendapatan tambahan bulan ini.</p>
                </div>
                <div class="text-center reveal" style="transition-delay: 120ms;">
                    <div class="relative z-10 w-10 h-10 rounded-full bg-[#1BA37A] text-white font-bold flex items-center justify-center mx-auto mb-3 text-sm shadow-md shadow-[#1BA37A]/30">2</div>
                    <h3 class="font-semibold text-gray-900 dark:text-white">Alokasikan Dana</h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Bagi budget ke setiap kategori kebutuhanmu.</p>
                </div>
                <div class="text-center reveal" style="transition-delay: 240ms;">
                    <div class="relative z-10 w-10 h-10 rounded-full bg-[#1BA37A] text-white font-bold flex items-center justify-center mx-auto mb-3 text-sm shadow-md shadow-[#1BA37A]/30">3</div>
                    <h3 class="font-semibold text-gray-900 dark:text-white">Catat & Pantau</h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Catat pengeluaran harian dan pantau lewat laporan.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<section id="faq" class="py-10 md:py-14">
    <div class="mx-auto px-4 max-w-5xl">
        <h2 class="text-xl md:text-2xl font-brand text-center text-gray-900 dark:text-white mb-8 reveal">FAQ & Kontak</h2>
        <div class="grid grid-cols-1 lg:grid-cols-5 gap-8">
            <div class="lg:col-span-3 space-y-3">
                @php
                $faqs = [
                    ['q' => 'Apakah Titik Simpan gratis?', 'a' => 'Ya, aplikasi ini 100% gratis digunakan. Kamu hanya perlu membuat akun untuk mulai mengelola keuanganmu.'],
                    ['q' => 'Apakah data saya aman?', 'a' => 'Data terisolasi per pengguna dan hanya kamu yang bisa melihatnya. Kamu juga bisa menghapus semua data kapan saja lewat tombol Reset Data.'],
                    ['q' => 'Apa bedanya dengan catatan pengeluaran biasa?', 'a' => 'Selain mencatat, Titik Simpan membantu mengalokasikan anggaran per kategori, memantau tagihan berulang, dan menyajikan laporan dalam bentuk grafik serta export PDF/Excel.'],
                    ['q' => 'Bisakah saya mencoba dulu sebelum daftar?', 'a' => 'Tentu! Klik tombol "Coba Demo Sekarang" untuk menjelajah fitur dengan data contoh. Data demo tidak akan tersimpan ke akunmu.'],
                    ['q' => 'Apakah ada aplikasi mobile?', 'a' => 'Saat ini Titik Simpan berbasis web dan sudah responsif, jadi tetap nyaman dipakai dari HP melalui browser.'],
                    ['q' => 'Bagaimana jika saya lupa password?', 'a' => 'Untuk saat ini, hubungi kami melalui formulir kontak di samping untuk bantuan pemulihan akun.'],
                ];
                @endphp
                @foreach($faqs as $faq)
                    <div class="reveal acc-item bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm overflow-hidden">
                        <button type="button" onclick="toggleFaq(this)" class="acc-btn w-full text-left px-4 py-3 font-medium text-gray-900 dark:text-white text-sm">
                            {{ $faq['q'] }}
                        </button>
                        <div class="acc-panel px-4 text-sm text-gray-500 dark:text-gray-400">
                            {{ $faq['a'] }}
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="lg:col-span-2">
                <div class="reveal bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-5 md:p-6 shadow-sm bg-gradient-to-br from-[#1BA37A]/5 via-white dark:via-gray-800 dark:from-[#1BA37A]/10 to-transparent h-full">
                <h3 class="font-brand text-lg text-gray-900 dark:text-white mb-4">Hubungi Kami</h3>
                {{-- <p class="text-sm text-gray-500 dark:text-gray-400 mb-5">
                    Punya pertanyaan atau saran? Kirim pesan ke
                    <a href="mailto:pname210@gmail.com" class="text-[#1BA37A] dark:text-[#6EE7B0] font-medium hover:underline">pname210@gmail.com</a>
                </p> --}}

                <form id="contact-form" class="space-y-3.5">
                    <div>
                        <label for="c-name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Nama</label>
                        <input id="c-name" type="text" required placeholder="Nama kamu"
                            class="w-full border border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-white rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#1BA37A]/50">
                    </div>
                    <div>
                        <label for="c-email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Email</label>
                        <input id="c-email" type="email" required placeholder="email@contoh.com"
                            class="w-full border border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-white rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#1BA37A]/50">
                    </div>
                    <div>
                        <label for="c-message" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Pesan</label>
                        <textarea id="c-message" rows="4" required placeholder="Tulis pesanmu di sini..."
                            class="w-full border border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-white rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#1BA37A]/50 resize-y"></textarea>
                    </div>
                    <button type="submit" onclick="event.preventDefault(); document.getElementById('contact-form').reset(); alert('Terima kasih! Formulir kontak akan segera aktif. Email kami: pname210@gmail.com');"
                        class="w-full bg-[#1BA37A] text-white py-3 rounded-xl font-semibold hover:bg-[#0F8F68] active:bg-[#0C7A59] transition-all btn-press shadow-sm">
                        Kirim Pesan
                    </button>
                    <p class="text-center text-xs text-gray-400 dark:text-gray-500">Formulir ini belum aktif dan belum mengirim email apa pun.</p>
                </form>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script type="module">
import { Renderer, Program, Mesh, Triangle } from 'https://cdn.jsdelivr.net/npm/ogl@1.0.11/src/index.min.js';

(function() {
    var container = document.getElementById('aurora-container');
    if (!container) return;

    function hexToVec3(hex) {
        var h = hex.replace('#', '');
        return [parseInt(h.slice(0,2),16)/255, parseInt(h.slice(2,4),16)/255, parseInt(h.slice(4,6),16)/255];
    }

    var VS = 'attribute vec2 uv;attribute vec2 position;varying vec2 vUv;void main(){vUv=uv;gl_Position=vec4(position,0,1);}';
    var FS = `
precision highp float;
uniform float uTime;uniform vec3 uResolution;uniform float uSpeed,uScale,uBrightness;
uniform vec3 uColor1,uColor2;uniform float uNoiseFreq,uNoiseAmp,uBandHeight,uBandSpread;
uniform float uOctaveDecay,uLayerOffset,uColorSpeed,uMouseInfluence,uLightMode;
uniform vec2 uMouse;uniform bool uEnableMouse;
#define TAU 6.28318
vec3 gradientHash(vec3 p){p=vec3(dot(p,vec3(127.1,311.7,234.6)),dot(p,vec3(269.5,183.3,198.3)),dot(p,vec3(169.5,283.3,156.9)));vec3 h=fract(sin(p)*43758.5453123);float phi=acos(2.0*h.x-1.0);float theta=TAU*h.y;return vec3(cos(theta)*sin(phi),sin(theta)*cos(phi),cos(phi));}
float quinticSmooth(float t){float t2=t*t;float t3=t*t2;return 6.0*t3*t2-15.0*t2*t2+10.0*t3;}
vec3 cosineGradient(float t,vec3 a,vec3 b,vec3 c,vec3 d){return a+b*cos(TAU*(c*t+d));}
float perlin3D(float amplitude,float frequency,float px,float py,float pz){float x=px*frequency;float y=py*frequency;float fx=floor(x);float fy=floor(y);float fz=floor(pz);float cx=ceil(x);float cy=ceil(y);float cz=ceil(pz);vec3 g000=gradientHash(vec3(fx,fy,fz));vec3 g100=gradientHash(vec3(cx,fy,fz));vec3 g010=gradientHash(vec3(fx,cy,fz));vec3 g110=gradientHash(vec3(cx,cy,fz));vec3 g001=gradientHash(vec3(fx,fy,cz));vec3 g101=gradientHash(vec3(cx,fy,cz));vec3 g011=gradientHash(vec3(fx,cy,cz));vec3 g111=gradientHash(vec3(cx,cy,cz));float d000=dot(g000,vec3(x-fx,y-fy,pz-fz));float d100=dot(g100,vec3(x-cx,y-fy,pz-fz));float d010=dot(g010,vec3(x-fx,y-cy,pz-fz));float d110=dot(g110,vec3(x-cx,y-cy,pz-fz));float d001=dot(g001,vec3(x-fx,y-fy,pz-cz));float d101=dot(g101,vec3(x-cx,y-fy,pz-cz));float d011=dot(g011,vec3(x-fx,y-cy,pz-cz));float d111=dot(g111,vec3(x-cx,y-cy,pz-cz));float sx=quinticSmooth(x-fx);float sy=quinticSmooth(y-fy);float sz=quinticSmooth(pz-fz);float lx00=mix(d000,d100,sx);float lx10=mix(d010,d110,sx);float lx01=mix(d001,d101,sx);float lx11=mix(d011,d111,sx);float ly0=mix(lx00,lx10,sy);float ly1=mix(lx01,lx11,sy);return amplitude*mix(ly0,ly1,sz);}
float auroraGlow(float t,vec2 shift){vec2 uv=gl_FragCoord.xy/uResolution.y;uv+=shift;float noiseVal=0.0;float freq=uNoiseFreq;float amp=uNoiseAmp;vec2 samplePos=uv*uScale;for(float i=0.0;i<3.0;i+=1.0){noiseVal+=perlin3D(amp,freq,samplePos.x,samplePos.y,t);amp*=uOctaveDecay;freq*=2.0;}float yBand=uv.y*10.0-uBandHeight*10.0;return 0.3*max(exp(uBandSpread*(1.0-1.1*abs(noiseVal+yBand))),0.0);}
void main(){vec2 uv=gl_FragCoord.xy/uResolution.xy;float t=uSpeed*0.4*uTime;vec2 shift=uEnableMouse?(uMouse-0.5)*uMouseInfluence:vec2(0);float glow1=auroraGlow(t,shift);float glow2=auroraGlow(t+uLayerOffset,shift);vec3 g1=cosineGradient(uv.x+uTime*uSpeed*0.2*uColorSpeed,vec3(0.5),vec3(0.5),vec3(1.0),vec3(0.3,0.2,0.2));vec3 g2=cosineGradient(uv.x+uTime*uSpeed*0.1*uColorSpeed,vec3(0.5),vec3(0.5),vec3(2.0,1.0,0.0),vec3(0.5,0.2,0.25));vec3 col=0.99*glow1*g1*uColor1+0.99*glow2*g2*uColor2;col*=uBrightness;if(uLightMode>0.5){float p1=dot(g1,vec3(.299,.587,.114));float p2=dot(g2,vec3(.299,.587,.114));float w1=pow(max(glow1*(.62+.38*p1),0.),1.35);float w2=pow(max(glow2*(.62+.38*p2),0.),1.35);float ws=max(w1+w2,.0001);vec3 ch=(w1*uColor1+w2*uColor2)/ws;float ne=min(ch.r,min(ch.g,ch.b));ch=max(ch-vec3(ne*.78),vec3(0));float pk=max(ch.r,max(ch.g,ch.b));ch=pow(clamp(ch/max(pk,.0001),0.,1.),vec3(1.08));float ink=clamp((w1+w2)*uBrightness*1.55,0.,.82);gl_FragColor=vec4(mix(vec3(1),ch,ink),1);}else{gl_FragColor=vec4(col,clamp(length(col),0.,1.));}}
`;

    var r = new Renderer({ alpha: true, premultipliedAlpha: false });
    r.gl.clearColor(0,0,0,0);
    container.appendChild(r.gl.canvas);

    var tgt=[.5,.5], cur=[.5,.5];
    var mouseEl = document.getElementById('hero') || container.parentElement;
    mouseEl.addEventListener('mousemove', function(e){ var b=r.gl.canvas.getBoundingClientRect(); tgt=[(e.clientX-b.left)/b.width, 1-(e.clientY-b.top)/b.height]; });
    mouseEl.addEventListener('mouseleave', function(){ tgt=[.5,.5]; });

    function resize(){ r.setSize(container.offsetWidth, container.offsetHeight); }
    window.addEventListener('resize', resize); resize();

    var prog = new Program(r.gl, { vertex: VS, fragment: FS, uniforms:{
        uTime:{value:0}, uResolution:{value:[r.gl.canvas.width,r.gl.canvas.height,r.gl.canvas.width/r.gl.canvas.height]},
        uSpeed:{value:0.6}, uScale:{value:1.5}, uBrightness:{value:1}, uColor1:{value:hexToVec3('#1BA37A')}, uColor2:{value:hexToVec3('#6EE7B0')},
        uNoiseFreq:{value:2.5}, uNoiseAmp:{value:1}, uBandHeight:{value:0.5}, uBandSpread:{value:1}, uOctaveDecay:{value:0.1}, uLayerOffset:{value:0}, uColorSpeed:{value:1},
        uMouse:{value:new Float32Array([.5,.5])}, uMouseInfluence:{value:0.25}, uEnableMouse:{value:true}, uLightMode:{value:0}
    }});

    var mesh = new Mesh(r.gl, { geometry: new Triangle(r.gl), program: prog });

    function loop(t){
        requestAnimationFrame(loop);
        prog.uniforms.uTime.value = t * 0.001;
        cur[0]+=.05*(tgt[0]-cur[0]); cur[1]+=.05*(tgt[1]-cur[1]);
        prog.uniforms.uMouse.value[0]=cur[0]; prog.uniforms.uMouse.value[1]=cur[1];
        prog.uniforms.uLightMode.value = 0;
        r.render({scene:mesh});
    }
    requestAnimationFrame(loop);
})();

var logoWrap = document.getElementById('hero-logo-wrap');
if (logoWrap) {
    logoWrap.addEventListener('mousemove', function(e) {
        var b = logoWrap.getBoundingClientRect();
        var x = (e.clientX - b.left) / b.width - 0.5;
        var y = (e.clientY - b.top) / b.height - 0.5;
        logoWrap.style.transform = 'perspective(800px) rotateY('+(x*25)+'deg) rotateX('+(-y*25)+'deg)';
    });
    logoWrap.addEventListener('mouseleave', function() { logoWrap.style.transform = 'perspective(800px) rotateY(0deg) rotateX(0deg)'; });
}
</script>

<script>
    function toggleFaq(btn) {
        var item = btn.closest('.acc-item');
        var wasOpen = item.classList.contains('acc-open');
        document.querySelectorAll('.acc-item').forEach(function(el) { el.classList.remove('acc-open'); });
        if (!wasOpen) item.classList.add('acc-open');
    }

    var observer = new IntersectionObserver(function(entries) {
        entries.forEach(function(e) {
            if (e.isIntersecting) e.target.classList.add('show');
            else e.target.classList.remove('show');
        });
    }, { threshold: 0.15, rootMargin: '0px 0px -40px 0px' });
    document.querySelectorAll('.reveal:not(.show)').forEach(function(el) { observer.observe(el); });
</script>
@endpush
