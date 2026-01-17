@extends('layouts.tools')

@section('title', 'Ferramentas Online Gratuitas')
@section('description', 'Ferramentas online gratuitas para desenvolvedores: gerador de UUID, Lorem Ipsum, calculadora de porcentagem, compressor de imagens e mais.')

@section('content')
    <div class="space-y-6 md:space-y-10 px-2 md:px-0">
        {{-- Header --}}
        <div class="text-center sm:text-left px-2">
            <h1 class="text-2xl md:text-3xl font-bold text-white mb-2">Ferramentas</h1>
            <p class="text-sm md:text-base text-gray-400">Ferramentas online gratuitas para desenvolvedores e uso geral</p>
        </div>

        {{-- Grid de ferramentas --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 md:gap-4">
            @foreach($tools as $tool)
                <a href="{{ route('tools.' . $tool['slug']) }}"
                    class="group block p-4 md:p-6 bg-neutral-800/50 border border-neutral-700/50 rounded-xl hover:border-{{ $tool['color'] }}/50 hover:bg-neutral-800 active:scale-[0.98] transition-all duration-300">
                    <div class="flex items-center sm:items-start gap-4">
                        {{-- Icon Container --}}
                        <div
                            class="flex-shrink-0 w-10 h-10 md:w-12 md:h-12 flex items-center justify-center rounded-lg bg-neutral-700/50 text-{{ $tool['color'] }} group-hover:bg-{{ $tool['color'] }}/10 transition-colors">
                            <i class="fa-solid {{ $tool['icon'] }} text-lg md:text-xl"></i>
                        </div>

                        {{-- Text --}}
                        <div class="flex-1 min-w-0">
                            <h2
                                class="text-base md:text-lg font-semibold text-white group-hover:text-{{ $tool['color'] }} transition-colors truncate">
                                {{ $tool['name'] }}
                            </h2>
                            <p class="text-xs md:text-sm text-gray-400 mt-1 line-clamp-2 sm:line-clamp-none">
                                {{ $tool['description'] }}
                            </p>
                        </div>

                        {{-- Arrow (Hidden on very small screens to save space) --}}
                        <div
                            class="hidden xs:flex flex-shrink-0 text-gray-600 group-hover:text-{{ $tool['color'] }} transition-colors items-center">
                            <i class="fa-solid fa-arrow-right text-sm"></i>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>

        {{-- Em breve --}}
        <div class="mt-8 md:mt-12 px-2">
            <h2 class="text-lg md:text-xl font-semibold text-white mb-4 flex items-center gap-2">
                <span class="w-2 h-2 bg-bulma-primary rounded-full"></span>
                Em breve
            </h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">
                @php
                    $upcoming = [
                        ['name' => 'JSON Formatter', 'icon' => 'fa-code'],
                        ['name' => 'Base64 Encode/Decode', 'icon' => 'fa-lock'],
                        ['name' => 'Color Converter', 'icon' => 'fa-palette'],
                        ['name' => 'Hash Generator', 'icon' => 'fa-hashtag'],
                        ['name' => 'Regex Tester', 'icon' => 'fa-asterisk'],
                        ['name' => 'QR Code Generator', 'icon' => 'fa-qrcode'],
                    ];
                @endphp
                @foreach($upcoming as $tool)
                    <div class="p-3.5 bg-neutral-800/30 border border-neutral-700/30 rounded-lg opacity-60">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 flex items-center justify-center rounded bg-neutral-900/50">
                                <i class="fa-solid {{ $tool['icon'] }} text-gray-600 text-sm"></i>
                            </div>
                            <span class="text-sm text-gray-500 font-medium">{{ $tool['name'] }}</span>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endsection