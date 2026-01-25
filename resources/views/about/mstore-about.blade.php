<x-cms-layout>
    <div class="py-12 bg-gray-50/50 min-h-screen overflow-hidden">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            {{-- HERO SECTION --}}
            <div class="relative py-20 lg:py-32" data-aos="fade-up">
                <div class="absolute top-0 right-0 w-96 h-96 bg-green-50 rounded-full blur-3xl opacity-50 -mr-20 -mt-20 animate-pulse"></div>
                
                <div class="relative z-10 text-center space-y-8 max-w-4xl mx-auto">
                    <div class="inline-flex items-center gap-2 px-4 py-2 bg-green-50 border border-green-100 rounded-2xl text-[#00AA5B] text-[10px] font-black uppercase tracking-[0.2em]">
                        <span class="w-2 h-2 bg-[#00AA5B] rounded-full animate-ping"></span>
                        Introducing {{ $siteSettings['site_name'] ?? 'MiniQ-Store' }}
                    </div>
                    
                    <h1 class="text-6xl lg:text-8xl font-black text-gray-900 tracking-tighter leading-[0.9]">
                        @php
                            $title = $siteSettings['about_hero_title'] ?? 'Jembatan Keren Pelaku UMKM Mojokerto.';
                            $words = explode(' ', $title);
                            $middle = ceil(count($words) / 2);
                            $firstPart = implode(' ', array_slice($words, 0, $middle));
                            $secondPart = implode(' ', array_slice($words, $middle));
                        @endphp
                        {{ $firstPart }} <br>
                        <span class="text-[#00AA5B]">{{ $secondPart }}</span>
                    </h1>
                    
                    <p class="text-lg lg:text-xl text-gray-500 font-medium leading-relaxed max-w-2xl mx-auto">
                        {{ $siteSettings['about_hero_desc'] ?? 'Kami bukan sekadar marketplace, tapi ekosistem digital untuk memajukan potensi lokal Mojokerto ke kancah yang lebih luas dengan sentuhan teknologi modern.' }}
                    </p>
                </div>
            </div>

            {{-- STORY SECTION --}}
            <div class="grid lg:grid-cols-2 gap-20 items-center mb-40">
                <div class="relative" data-aos="fade-right">
                    <div class="absolute -inset-4 bg-green-100 rounded-[3rem] blur-2xl opacity-40 animate-pulse"></div>
                    <div class="relative rounded-[3rem] overflow-hidden shadow-2xl border-8 border-white">
                        <img src="{{ isset($siteSettings['about_image']) ? asset($siteSettings['about_image']) : 'https://images.unsplash.com/photo-1542838132-92c53300491e?auto=format&fit=crop&q=80&w=800' }}" alt="Marketplace" class="w-full h-[500px] object-cover">
                        <div class="absolute inset-0 bg-gradient-to-t from-green-900/40 to-transparent"></div>
                    </div>
                </div>
                
                <div class="space-y-8" data-aos="fade-left">
                    <div class="inline-block px-4 py-1 bg-white rounded-full text-[10px] font-black uppercase text-[#00AA5B] tracking-widest shadow-sm">Core Mission</div>
                    <h2 class="text-5xl font-black text-gray-900 leading-tight tracking-tighter">Misi Kami: <br><span class="text-[#00AA5B]">Digitalisasi UMKM</span> Mojokerto.</h2>
                    <p class="text-gray-500 leading-relaxed font-medium">
                        {{ $siteSettings['site_name'] ?? 'MiniQ-Store' }} lahir dari kesadaran bahwa potensi produk lokal Mojokerto sangat luar biasa namun seringkali terkendala akses teknologi.
                    </p>
                    <div class="space-y-4">
                        @if(isset($siteSettings['about_mission_1']))
                        <div class="flex items-start gap-4">
                            <div class="w-6 h-6 bg-green-100 text-[#00AA5B] rounded-full flex items-center justify-center flex-shrink-0 mt-1">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                            </div>
                            <p class="text-sm font-bold text-gray-700">{{ $siteSettings['about_mission_1'] }}</p>
                        </div>
                        @endif
                        @if(isset($siteSettings['about_mission_2']))
                        <div class="flex items-start gap-4">
                            <div class="w-6 h-6 bg-green-100 text-[#00AA5B] rounded-full flex items-center justify-center flex-shrink-0 mt-1">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                            </div>
                            <p class="text-sm font-bold text-gray-700">{{ $siteSettings['about_mission_2'] }}</p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- AUTHOR IDENTITY SECTION --}}
            <div class="mb-40" data-aos="fade-up">
                <div class="text-center mb-16">
                    <h4 class="text-[10px] font-black uppercase tracking-[0.4em] text-[#00AA5B] mb-2">The Brains Behind</h4>
                    <h2 class="text-4xl font-black text-gray-900 tracking-tighter">Siapa di Balik Layar?</h2>
                </div>
                <div class="max-w-3xl mx-auto">
                    <div class="bg-white p-2 rounded-[3.5rem] shadow-2xl shadow-gray-100 border border-gray-50 flex flex-col md:flex-row items-center gap-10">
                        <div class="w-full md:w-64 h-80 rounded-[3rem] overflow-hidden flex-shrink-0 relative group">
                            <img src="https://images.unsplash.com/photo-1519085360753-af0119f7cbe7?auto=format&fit=crop&q=80&w=600" alt="Developer" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
                            <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity"></div>
                        </div>
                        <div class="p-4 md:pr-12 text-center md:text-left space-y-6">
                            <div>
                                <h3 class="text-3xl font-black text-gray-900 tracking-tight">Mojokerto Dev Team</h3>
                                <p class="text-[#00AA5B] font-black uppercase text-[10px] tracking-widest mt-1">Fullstack Developers & Tech Enthusiasts</p>
                            </div>
                            <p class="text-gray-500 font-medium leading-relaxed italic">
                                "Kami adalah sekumpulan pemuda yang percaya bahwa teknologi tidak boleh hanya dinikmati oleh kota besar. Kami membangun {{ $siteSettings['site_name'] ?? 'MiniQ-Store' }} dengan cinta untuk membantu UMKM tetangga kami naik kelas."
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- CTA --}}
            <div class="bg-[#00AA5B] rounded-[4rem] p-12 lg:p-20 text-center relative overflow-hidden mb-20 shadow-2xl shadow-green-200" data-aos="zoom-in">
                <div class="absolute top-0 right-0 w-64 h-64 bg-white/10 rounded-full blur-3xl -mr-20 -mt-20 scale-150"></div>
                <div class="relative z-10 max-w-2xl mx-auto space-y-10">
                    <h2 class="text-4xl lg:text-6xl font-black text-white leading-tight tracking-tighter">Siap Jadi Bagian <br> Sejarah Kami?</h2>
                    <div class="flex flex-col sm:flex-row gap-4 justify-center">
                        <a href="{{ route('seller.create') }}" class="px-10 py-5 bg-white text-[#00AA5B] rounded-2xl font-black uppercase text-[10px] tracking-widest hover:bg-gray-50 transition-all transform hover:scale-105 shadow-xl">Daftar Jadi Seller</a>
                        <a href="{{ route('home') }}" class="px-10 py-5 bg-green-700 text-white rounded-2xl font-black uppercase text-[10px] tracking-widest border border-green-400/30 hover:bg-green-800 transition-all">Jelajahi Produk</a>
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-cms-layout>
