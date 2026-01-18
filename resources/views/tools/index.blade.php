@extends('layouts.tools')

@section('title', 'Ferramentas Online Gratuitas')
@section('description', 'Ferramentas online gratuitas para desenvolvedores: gerador de UUID, Lorem Ipsum, calculadora de porcentagem, compressor de imagens e mais.')

@section('content')
    <div class="space-y-6 sm:space-y-8">
        {{-- Header --}}
        <div class="text-center lg:text-left">
            <h1 class="text-2xl sm:text-3xl font-bold text-white mb-2">Ferramentas</h1>
            <p class="text-gray-400 text-sm sm:text-base">Ferramentas online gratuitas para desenvolvedores e uso geral</p>
        </div>

        {{-- Grid de ferramentas --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 sm:gap-4">
            @foreach($tools as $tool)
                <a href="{{ route('tools.' . $tool['slug']) }}"
                    class="group block p-4 sm:p-6 bg-neutral-800/50 border border-neutral-700/50 rounded-xl hover:border-{{ $tool['color'] }}/50 hover:bg-neutral-800 transition-all duration-300">
                    <div class="flex items-start gap-3 sm:gap-4">
                        <div
                            class="shrink-0 w-10 h-10 sm:w-12 sm:h-12 flex items-center justify-center rounded-lg bg-neutral-700/50 text-{{ $tool['color'] }} group-hover:bg-{{ $tool['color'] }}/10 transition-colors">
                            <i data-lucide="{{ $tool['icon'] }}" class="w-5 h-5 sm:w-6 sm:h-6"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <h2
                                class="text-base sm:text-lg font-semibold text-white group-hover:text-{{ $tool['color'] }} transition-colors">
                                {{ $tool['name'] }}
                            </h2>
                            <p class="text-xs sm:text-sm text-gray-400 mt-1 line-clamp-2">
                                {{ $tool['description'] }}
                            </p>
                        </div>
                        <div
                            class="shrink-0 text-gray-600 group-hover:text-{{ $tool['color'] }} transition-colors hidden sm:block">
                            <i data-lucide="arrow-right" class="w-5 h-5"></i>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>


    </div>
@endsection