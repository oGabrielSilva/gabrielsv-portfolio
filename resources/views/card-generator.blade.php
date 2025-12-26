@extends('layouts.app')

@section('extra_head')
    <meta name="robots" content="noindex" />
@endsection

@section('content')
    <div
        class="min-h-screen bg-[#070707] text-zinc-300 p-6 md:p-12 flex flex-col items-center font-sans selection:bg-zinc-700">
        <div class="max-w-6xl w-full grid grid-cols-1 lg:grid-cols-[1fr_auto] gap-16 items-start">

            <!-- Painel de Configuração -->
            <div class="bg-zinc-900/40 border border-zinc-800/60 rounded-[2rem] p-8 shadow-2xl space-y-8">
                <div class="flex flex-col gap-1">
                    <h2 class="text-2xl font-black italic uppercase tracking-tighter text-white">Gerador de Cartas</h2>
                    <p class="text-zinc-500 text-[10px] font-bold uppercase tracking-widest opacity-50">High-Fidelity Engine
                        v0.4</p>
                </div>

                <div class="grid grid-cols-1 gap-6">
                    <div class="space-y-2">
                        <label class="text-[10px] font-black uppercase text-zinc-500 ml-1">Identificação</label>
                        <input type="text" id="card-name" value="Soberano de Ferro"
                            class="w-full bg-black/50 border border-zinc-800 rounded-xl p-3 text-sm outline-none focus:border-zinc-500 transition-all text-white font-bold" />
                    </div>

                    <div class="space-y-2">
                        <label class="text-[10px] font-black uppercase text-zinc-500 ml-1">Efeito Especial</label>
                        <textarea id="card-description"
                            class="w-full bg-black/50 border border-zinc-800 rounded-xl p-3 text-sm h-28 outline-none focus:border-zinc-500 resize-none transition-all text-zinc-300">Enquanto esta carta estiver em campo, todas as outras entidades aliadas da classe Guerreiro ganham +200 de ATK.</textarea>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div class="space-y-2">
                            <label class="text-[10px] font-black uppercase text-zinc-500 ml-1">Classe</label>
                            <select id="card-class"
                                class="w-full bg-black border border-zinc-800 rounded-xl p-3 text-sm outline-none cursor-pointer text-white">
                                <option value="WARRIOR">Guerreiro</option>
                                <option value="ARCANE">Arcano</option>
                                <option value="SHADOW">Sombra</option>
                                <option value="SACRED">Sagrado</option>
                                <option value="BEAST">Besta</option>
                                <option value="MECHANICAL">Mecânico</option>
                                <option value="ELEMENTAL">Elemental</option>
                                <option value="ABYSSAL">Abissal</option>
                            </select>
                        </div>
                        <div class="space-y-2">
                            <label class="text-[10px] font-black uppercase text-zinc-500 ml-1">Raridade</label>
                            <select id="card-rarity"
                                class="w-full bg-black border border-zinc-800 rounded-xl p-3 text-sm outline-none cursor-pointer text-white">
                                <option value="COMMON">Comum</option>
                                <option value="RARE" selected>Rara</option>
                                <option value="HIDDEN">Oculta</option>
                                <option value="ETHEREAL">Etérea</option>
                            </select>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div class="space-y-2">
                            <label class="text-[10px] font-black uppercase text-zinc-500 ml-1">Tipo</label>
                            <select id="card-type"
                                class="w-full bg-black border border-zinc-800 rounded-xl p-3 text-sm outline-none text-white">
                                <option value="ENTITY" selected>Entidade</option>
                                <option value="SPELL">Magia</option>
                            </select>
                        </div>
                        <div class="space-y-2">
                            <label class="text-[10px] font-black uppercase text-zinc-500 ml-1">Invocação</label>
                            <select id="summon-type"
                                class="w-full bg-black border border-zinc-800 rounded-xl p-3 text-sm outline-none text-white">
                                <option value="NORMAL" selected>Normal</option>
                                <option value="OVERLAY">Overlay</option>
                            </select>
                        </div>
                    </div>

                    <div id="stats-section"
                        class="grid grid-cols-2 gap-4 p-4 bg-black/30 rounded-2xl border border-zinc-800/50">
                        <div class="space-y-1">
                            <label class="text-[9px] font-black uppercase text-red-500/70 text-center block">Ataque</label>
                            <input type="number" id="card-atk" value="1200"
                                class="w-full bg-transparent text-2xl text-center font-black outline-none text-white" />
                        </div>
                        <div class="space-y-1">
                            <label class="text-[9px] font-black uppercase text-blue-500/70 text-center block">Defesa</label>
                            <input type="number" id="card-def" value="1000"
                                class="w-full bg-transparent text-2xl text-center font-black outline-none text-white" />
                        </div>
                    </div>
                </div>
            </div>

            <!-- Visualização e Download -->
            <div class="flex flex-col items-center gap-10 lg:sticky lg:top-12">
                <div class="flex flex-col items-center gap-3">
                    <span class="text-[9px] font-black uppercase tracking-[0.5em] text-zinc-700">Visualização Digital</span>

                    <!-- Card Preview -->
                    <div id="card-preview"
                        class="relative w-64 h-96 rounded-[1.25rem] border-[3px] border-blue-500 shadow-[0_0_15px_rgba(59,130,246,0.4)] overflow-hidden bg-[#050505] text-white flex flex-col transition-all duration-500">
                        <!-- Header -->
                        <div
                            class="p-3.5 flex justify-between items-start bg-zinc-900/95 z-10 pt-4 border-b border-white/5">
                            <div class="flex flex-col">
                                <span id="preview-name"
                                    class="font-black text-[13px] tracking-tight truncate uppercase italic leading-tight text-blue-400">
                                    Soberano de Ferro
                                </span>
                                <span id="preview-class-rarity"
                                    class="text-[7px] text-zinc-500 font-bold uppercase tracking-[0.2em] mt-0.5">
                                    Guerreiro • Rara
                                </span>
                            </div>
                            <div id="class-icon-container"
                                class="p-1.5 rounded-lg bg-black/60 border border-white/5 shadow-inner">
                                <i id="class-icon" data-lucide="sword" class="w-4 h-4" style="color: #C41E3A;"></i>
                            </div>
                        </div>

                        <!-- Artwork Area -->
                        <div id="artwork-area"
                            class="relative flex-grow bg-gradient-to-br from-red-950/40 to-black flex items-center justify-center overflow-hidden border-b border-white/5">
                            <div class="absolute inset-0 opacity-[0.03]"
                                style="background-image: linear-gradient(#fff 1px, transparent 1px), linear-gradient(90deg, #fff 1px, transparent 1px); background-size: 20px 20px;">
                            </div>

                            <div id="artwork-icon" class="relative transition-transform duration-700">
                                <i data-lucide="star" class="w-17.5 h-17.5"
                                    style="color: rgba(255,255,255,0.03); stroke-width: 1;"></i>
                            </div>
                        </div>

                        <!-- Info Area -->
                        <div class="p-3.5 bg-[#0a0a0a] flex flex-col gap-2 z-10 relative">
                            <div class="flex gap-1.5 items-center">
                                <span id="type-badge"
                                    class="text-[7px] px-1.5 py-0.5 rounded-sm font-black text-white uppercase tracking-tighter bg-blue-600">
                                    ENTITY
                                </span>
                                <span id="overlay-badge"
                                    class="text-[7px] bg-white/5 text-zinc-400 px-1.5 py-0.5 rounded-sm border border-white/10 items-center gap-0.5 font-bold uppercase hidden">
                                    <i data-lucide="layers" style="width: 7px; height: 7px;"></i> Overlay
                                </span>
                            </div>

                            <p id="preview-description" class="text-[10px] leading-[1.3] text-zinc-400 font-medium">
                                Enquanto esta carta estiver em campo, todas as outras entidades aliadas da classe Guerreiro
                                ganham +200 de ATK.
                            </p>
                        </div>

                        <!-- Stats Footer -->
                        <div id="stats-footer" class="flex border-t border-white/5 bg-black">
                            <div class="flex-1 py-2 flex flex-col items-center justify-center relative">
                                <span
                                    class="text-[7px] uppercase font-black text-zinc-600 mb-0.5 tracking-widest">Atk</span>
                                <div class="flex items-center gap-1.5">
                                    <i data-lucide="sword" class="w-2.75 h-2.75" style="color: rgba(220, 38, 38, 0.6);"></i>
                                    <span id="preview-atk" class="font-black text-lg text-zinc-100">1200</span>
                                </div>
                            </div>
                            <div class="w-[1px] bg-white/5 my-2"></div>
                            <div class="flex-1 py-2 flex flex-col items-center justify-center relative">
                                <span
                                    class="text-[7px] uppercase font-black text-zinc-600 mb-0.5 tracking-widest">Def</span>
                                <div class="flex items-center gap-1.5">
                                    <i data-lucide="shield" class="w-2.75 h-2.75"
                                        style="color: rgba(37, 99, 235, 0.6);"></i>
                                    <span id="preview-def" class="font-black text-lg text-zinc-100">1000</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <button id="export-btn"
                    class="w-full flex items-center justify-center gap-3 bg-white text-black py-4 px-8 rounded-2xl font-black uppercase text-xs tracking-widest hover:bg-zinc-200 transition-all hover:-translate-y-1 active:scale-95 disabled:opacity-50 shadow-xl shadow-white/5">
                    <i data-lucide="download" class="w-4.5 h-4.5" style="stroke-width: 3;"></i>
                    Descarregar PNG
                </button>

                <div class="flex items-center gap-2 text-[10px] text-zinc-600 font-bold uppercase tracking-tight">
                    <i data-lucide="check" class="w-3 h-3 text-green-500"></i>
                    Exportação Optimizada Ativa
                </div>
            </div>

        </div>
    </div>

    <script src="https://unpkg.com/lucide@latest"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/dom-to-image/2.6.0/dom-to-image.min.js"
        integrity="sha512-01CJ9/g7e8cUmY0DFTMcUw/ikS799FHiOA0eyHsUWfOetgbx/t6oV4otQ5zXKQyIrQGTHSmRVPIgrgLcZi/WMA=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script>
        lucide.createIcons();
    </script>
    @vite(['resources/js/card-generator.js'])
@endsection
