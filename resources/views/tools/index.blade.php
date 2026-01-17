@extends('layouts.tools')

@section('title', 'Ferramentas Online Gratuitas')
@section('description', 'Ferramentas online gratuitas para desenvolvedores: gerador de UUID, Lorem Ipsum, calculadora de porcentagem, compressor de imagens e mais.')

@section('content')
    <div class="space-y-8">
        {{-- Header --}}
        <div class="text-center lg:text-left">
            <h1 class="text-3xl font-bold text-white mb-2">Ferramentas</h1>
            <p class="text-gray-400">Ferramentas online gratuitas para desenvolvedores e uso geral</p>
        </div>

        {{-- Grid de ferramentas --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            @foreach($tools as $tool)
                <a href="{{ route('tools.' . $tool['slug']) }}"
                    class="group block p-6 bg-neutral-800/50 border border-neutral-700/50 rounded-xl hover:border-{{ $tool['color'] }}/50 hover:bg-neutral-800 transition-all duration-300">
                    <div class="flex items-start gap-4">
                        <div
                            class="flex-shrink-0 w-12 h-12 flex items-center justify-center rounded-lg bg-neutral-700/50 text-{{ $tool['color'] }} group-hover:bg-{{ $tool['color'] }}/10 transition-colors">
                            <i class="fa-solid {{ $tool['icon'] }} text-xl"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <h2
                                class="text-lg font-semibold text-white group-hover:text-{{ $tool['color'] }} transition-colors">
                                {{ $tool['name'] }}
                            </h2>
                            <p class="text-sm text-gray-400 mt-1">
                                {{ $tool['description'] }}
                            </p>
                        </div>
                        <div class="flex-shrink-0 text-gray-600 group-hover:text-{{ $tool['color'] }} transition-colors">
                            <i class="fa-solid fa-arrow-right"></i>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>

        {{-- Em breve --}}
        <div class="mt-12">
            <h2 class="text-xl font-semibold text-white mb-4">Em breve</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
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
                    <div class="p-4 bg-neutral-800/30 border border-neutral-700/30 rounded-lg opacity-50">
                        <div class="flex items-center gap-3">
                            <i class="fa-solid {{ $tool['icon'] }} text-gray-600"></i>
                            <span class="text-gray-500">{{ $tool['name'] }}</span>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endsection