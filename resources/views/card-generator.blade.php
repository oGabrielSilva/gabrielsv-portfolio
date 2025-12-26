@extends('layouts.app')

@section('extra_head')
    <meta name="robots" content="noindex" />
@endsection

@section('content')
    <section class="mb-24">
        <div class="mb-12" data-aos="fade-up">
            <span class="text-bulma-primary font-medium tracking-wide text-sm mb-4 block uppercase">DESENVOLVIMENTO DE CARD GAME</span>
            <h1 class="text-4xl md:text-5xl font-bold text-white mb-4 tracking-tight">Gerador de Cartas - Ambiente de Testes</h1>
            <p class="text-gray-400 max-w-2xl leading-relaxed">
                Ferramenta interna para prototipação e teste de cartas do nosso card game em desenvolvimento. Use para experimentar mecânicas, visualizar designs e validar conceitos com a equipe.
            </p>
            <div class="mt-4 p-3 bg-neutral-900/50 border border-neutral-700 rounded text-xs text-gray-500">
                <strong class="text-gray-400">Termos de Uso:</strong> Esta é uma ferramenta de desenvolvimento interno.
                As cartas criadas aqui são apenas para fins de teste e prototipação. Todos os designs e mecânicas estão sujeitos a alterações.
                Não use para fins comerciais ou distribuição pública.
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-[1fr_auto] gap-8 items-start">
            <!-- Painel de Configuração -->
            <div class="bg-neutral-800/50 border border-neutral-700 rounded-lg p-6 md:p-8 space-y-6">
                <h2 class="text-xl font-bold text-white border-b border-neutral-700 pb-3">Configurações da Carta</h2>

                <div class="grid grid-cols-1 gap-6">
                    <!-- Campo de Upload de Imagem -->
                    <div class="space-y-2">
                        <label class="text-sm font-medium text-gray-300">Imagem da Carta</label>
                        <div class="flex items-center gap-4">
                            <input type="file" id="card-image" accept="image/*"
                                class="block w-full text-sm text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded file:border-0 file:text-sm file:font-semibold file:bg-bulma-primary file:text-neutral-900 hover:file:bg-bulma-primary/90 file:cursor-pointer transition-all" />
                        </div>
                        <p class="text-xs text-gray-500">Recomendado: imagem quadrada ou vertical</p>
                    </div>

                    <div class="space-y-2">
                        <label class="text-sm font-medium text-gray-300">Nome da Carta</label>
                        <input type="text" id="card-name" value="Soberano de Ferro"
                            class="w-full bg-neutral-900 border border-neutral-700 rounded p-3 text-sm outline-none focus:border-bulma-primary transition-all text-white" />
                    </div>

                    <div class="space-y-2">
                        <label class="text-sm font-medium text-gray-300">Efeito Especial</label>
                        <textarea id="card-description"
                            class="w-full bg-neutral-900 border border-neutral-700 rounded p-3 text-sm h-28 outline-none focus:border-bulma-primary resize-none transition-all text-gray-300">Enquanto esta carta estiver em campo, todas as outras entidades aliadas da classe Guerreiro ganham +200 de ATK.</textarea>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div class="space-y-2">
                            <label class="text-sm font-medium text-gray-300">Classe</label>
                            <select id="card-class"
                                class="w-full bg-neutral-900 border border-neutral-700 rounded p-3 text-sm outline-none cursor-pointer text-white focus:border-bulma-primary transition-all">
                                <option value="WARRIOR">Guerreiro</option>
                                <option value="ARCANE">Arcano</option>
                                <option value="SHADOW">Sombra</option>
                                <option value="SACRED">Sagrado</option>
                                <option value="BEAST">Besta</option>
                                <option value="MECHANICAL">Mecânico</option>
                                <option value="ELEMENTAL">Elemental</option>
                                <option value="ABYSSAL">Abissal</option>
                            </select>
                            <button id="add-class-btn" type="button"
                                class="w-full text-xs text-gray-400 hover:text-bulma-primary transition-colors flex items-center justify-center gap-1.5 py-1">
                                <i data-lucide="plus" class="w-3 h-3"></i>
                                Nova classe
                            </button>
                        </div>
                        <div class="space-y-2">
                            <label class="text-sm font-medium text-gray-300">Raridade</label>
                            <select id="card-rarity"
                                class="w-full bg-neutral-900 border border-neutral-700 rounded p-3 text-sm outline-none cursor-pointer text-white focus:border-bulma-primary transition-all">
                                <option value="COMMON">Comum</option>
                                <option value="RARE" selected>Rara</option>
                                <option value="HIDDEN">Oculta</option>
                                <option value="ETHEREAL">Etérea</option>
                            </select>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div class="space-y-2">
                            <label class="text-sm font-medium text-gray-300">Tipo</label>
                            <select id="card-type"
                                class="w-full bg-neutral-900 border border-neutral-700 rounded p-3 text-sm outline-none text-white focus:border-bulma-primary transition-all">
                                <option value="ENTITY" selected>Entidade</option>
                                <option value="SPELL">Magia</option>
                            </select>
                        </div>
                        <div class="space-y-2">
                            <label class="text-sm font-medium text-gray-300">Invocação</label>
                            <select id="summon-type"
                                class="w-full bg-neutral-900 border border-neutral-700 rounded p-3 text-sm outline-none text-white focus:border-bulma-primary transition-all">
                                <option value="NORMAL" selected>Normal</option>
                                <option value="OVERLAY">Overlay</option>
                            </select>
                        </div>
                    </div>

                    <div id="stats-section"
                        class="grid grid-cols-2 gap-4 p-4 bg-neutral-900 rounded border border-neutral-700">
                        <div class="space-y-1">
                            <label class="text-xs font-bold uppercase text-red-400 text-center block">Ataque</label>
                            <input type="number" id="card-atk" value="1200"
                                class="w-full bg-transparent text-2xl text-center font-black outline-none text-white" />
                        </div>
                        <div class="space-y-1">
                            <label class="text-xs font-bold uppercase text-blue-400 text-center block">Defesa</label>
                            <input type="number" id="card-def" value="1000"
                                class="w-full bg-transparent text-2xl text-center font-black outline-none text-white" />
                        </div>
                    </div>
                </div>
            </div>

            <!-- Visualização e Download -->
            <div class="flex flex-col items-center gap-8 lg:sticky lg:top-12">
                <div class="flex flex-col items-center gap-4">
                    <h3 class="text-sm font-bold text-gray-400 uppercase tracking-wider">Preview</h3>

                    <!-- Card Preview Front -->
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

                    <!-- Card Preview Back -->
                    <div id="card-back-preview"
                        class="relative w-64 h-96 rounded-[1.25rem] border-[3px] border-neutral-700 shadow-xl overflow-hidden bg-gradient-to-br from-neutral-900 via-neutral-950 to-black transition-all duration-500">

                        <!-- Padrão de fundo diagonal -->
                        <div class="absolute inset-0 opacity-[0.03]"
                            style="background: repeating-linear-gradient(45deg, #fff 0px, #fff 2px, transparent 2px, transparent 10px);"></div>

                        <!-- Grade de pontos -->
                        <div class="absolute inset-0 opacity-[0.08]"
                            style="background-image: radial-gradient(circle, #fff 1px, transparent 1px); background-size: 20px 20px;"></div>

                        <!-- Moldura externa -->
                        <div class="absolute inset-4 border-2 border-white/5 rounded-xl"></div>

                        <!-- Moldura interna -->
                        <div class="absolute inset-8 border border-white/10 rounded-lg"></div>

                        <!-- Padrão geométrico central -->
                        <div class="absolute inset-0 flex items-center justify-center">
                            <!-- Hexágono decorativo central -->
                            <div class="relative w-40 h-40">
                                <!-- Círculos concêntricos -->
                                <div class="absolute inset-0 rounded-full border-2 border-white/5 animate-pulse" style="animation-duration: 3s;"></div>
                                <div class="absolute inset-4 rounded-full border border-white/10"></div>
                                <div class="absolute inset-8 rounded-full border border-white/5"></div>

                                <!-- Elementos decorativos nos cantos -->
                                <div class="absolute -top-2 left-1/2 transform -translate-x-1/2 w-1 h-4 bg-gradient-to-b from-white/20 to-transparent"></div>
                                <div class="absolute -bottom-2 left-1/2 transform -translate-x-1/2 w-1 h-4 bg-gradient-to-t from-white/20 to-transparent"></div>
                                <div class="absolute top-1/2 -left-2 transform -translate-y-1/2 h-1 w-4 bg-gradient-to-r from-white/20 to-transparent"></div>
                                <div class="absolute top-1/2 -right-2 transform -translate-y-1/2 h-1 w-4 bg-gradient-to-l from-white/20 to-transparent"></div>
                            </div>
                        </div>

                        <!-- Cantos decorativos -->
                        <div class="absolute top-6 left-6 w-8 h-8 border-l-2 border-t-2 border-white/20 rounded-tl-lg"></div>
                        <div class="absolute top-6 right-6 w-8 h-8 border-r-2 border-t-2 border-white/20 rounded-tr-lg"></div>
                        <div class="absolute bottom-6 left-6 w-8 h-8 border-l-2 border-b-2 border-white/20 rounded-bl-lg"></div>
                        <div class="absolute bottom-6 right-6 w-8 h-8 border-r-2 border-b-2 border-white/20 rounded-br-lg"></div>

                        <!-- Marca d'água no rodapé -->
                        <div class="absolute bottom-3 left-0 right-0 text-center">
                            <p class="text-[8px] text-white/20 font-bold uppercase tracking-[0.2em]">Card Game Prototype</p>
                        </div>
                    </div>
                </div>

                <button id="export-btn"
                    class="w-full flex items-center justify-center gap-3 bg-bulma-primary hover:bg-bulma-primary/90 text-neutral-900 font-bold py-3 px-6 rounded transition-all transform hover:-translate-y-1 active:scale-95 disabled:opacity-50">
                    <i data-lucide="download" class="w-4 h-4"></i>
                    Exportar Carta (Frente)
                </button>

                <p class="text-xs text-gray-500 text-center">A imagem será exportada em alta qualidade</p>
            </div>

        </div>
    </section>

    <!-- Hidden elements to ensure Tailwind compiles these classes -->
    <div class="hidden">
        <div class="bg-gradient-to-br from-red-950/40 to-black"></div>
        <div class="bg-gradient-to-br from-blue-950/40 to-black"></div>
        <div class="bg-gradient-to-br from-purple-950/40 to-black"></div>
        <div class="bg-gradient-to-br from-yellow-950/40 to-black"></div>
        <div class="bg-gradient-to-br from-green-950/40 to-black"></div>
        <div class="bg-gradient-to-br from-slate-950/40 to-black"></div>
        <div class="bg-gradient-to-br from-orange-950/40 to-black"></div>
        <div class="bg-gradient-to-br from-indigo-950/60 to-black"></div>
        <div class="bg-gradient-to-br from-pink-950/40 to-black"></div>
        <div class="bg-gradient-to-br from-cyan-950/40 to-black"></div>
        <div class="bg-gradient-to-br from-emerald-950/40 to-black"></div>
        <div class="bg-gradient-to-br from-teal-950/40 to-black"></div>
    </div>

    <!-- Modal: Adicionar Nova Classe -->
    <div id="class-modal" class="fixed inset-0 bg-black/80 backdrop-blur-sm z-50 hidden items-center justify-center p-6">
        <div class="bg-neutral-900 border border-neutral-700 rounded-lg max-w-md w-full p-6 space-y-6 transform transition-all">
            <div class="flex justify-between items-center border-b border-neutral-700 pb-4">
                <h3 class="text-xl font-bold text-white">Adicionar Nova Classe</h3>
                <button id="close-modal-btn" type="button" class="text-gray-400 hover:text-white transition-colors">
                    <i data-lucide="x" class="w-5 h-5"></i>
                </button>
            </div>

            <form id="class-form" class="space-y-4">
                <div class="space-y-2">
                    <label class="text-sm font-medium text-gray-300">Chave da Classe (ex: WARRIOR)</label>
                    <input type="text" id="class-key" required
                        placeholder="EXEMPLO"
                        class="w-full bg-neutral-800 border border-neutral-700 rounded p-3 text-sm outline-none focus:border-bulma-primary transition-all text-white uppercase" />
                    <p class="text-xs text-gray-500">Use letras maiúsculas e underscores apenas</p>
                </div>

                <div class="space-y-2">
                    <label class="text-sm font-medium text-gray-300">Nome da Classe (ex: Guerreiro)</label>
                    <input type="text" id="class-name" required
                        placeholder="Exemplo"
                        class="w-full bg-neutral-800 border border-neutral-700 rounded p-3 text-sm outline-none focus:border-bulma-primary transition-all text-white" />
                </div>

                <div class="space-y-2">
                    <label class="text-sm font-medium text-gray-300">Cor (HEX)</label>
                    <div class="flex gap-2">
                        <input type="color" id="class-color-picker"
                            class="w-12 h-12 bg-neutral-800 border border-neutral-700 rounded cursor-pointer" />
                        <input type="text" id="class-color" required
                            placeholder="#FF0000"
                            pattern="^#[0-9A-Fa-f]{6}$"
                            class="flex-1 bg-neutral-800 border border-neutral-700 rounded p-3 text-sm outline-none focus:border-bulma-primary transition-all text-white" />
                    </div>
                </div>

                <div class="space-y-2">
                    <label class="text-sm font-medium text-gray-300">Tema</label>
                    <select id="modal-class-theme" required
                        class="w-full bg-neutral-800 border border-neutral-700 rounded p-3 text-sm outline-none focus:border-bulma-primary transition-all text-white cursor-pointer">
                        <option value="from-red-950/40 to-black">Vermelho Escuro</option>
                        <option value="from-blue-950/40 to-black">Azul Escuro</option>
                        <option value="from-purple-950/40 to-black">Roxo Escuro</option>
                        <option value="from-yellow-950/40 to-black">Amarelo Escuro</option>
                        <option value="from-green-950/40 to-black">Verde Escuro</option>
                        <option value="from-slate-950/40 to-black">Cinza Escuro</option>
                        <option value="from-orange-950/40 to-black">Laranja Escuro</option>
                        <option value="from-indigo-950/60 to-black">Índigo Escuro</option>
                        <option value="from-pink-950/40 to-black">Rosa Escuro</option>
                        <option value="from-cyan-950/40 to-black">Ciano Escuro</option>
                        <option value="from-emerald-950/40 to-black">Esmeralda Escuro</option>
                        <option value="from-teal-950/40 to-black">Teal Escuro</option>
                    </select>
                </div>

                <div class="space-y-2">
                    <label class="text-sm font-medium text-gray-300">Ícone</label>
                    <select id="modal-class-icon" required
                        class="w-full bg-neutral-800 border border-neutral-700 rounded p-3 text-sm outline-none focus:border-bulma-primary transition-all text-white cursor-pointer">
                        <option value="sword">Espada (sword)</option>
                        <option value="wand">Varinha (wand)</option>
                        <option value="skull">Caveira (skull)</option>
                        <option value="sun">Sol (sun)</option>
                        <option value="flame">Chama (flame)</option>
                        <option value="hammer">Martelo (hammer)</option>
                        <option value="wind">Vento (wind)</option>
                        <option value="infinity">Infinito (infinity)</option>
                        <option value="shield">Escudo (shield)</option>
                        <option value="zap">Raio (zap)</option>
                        <option value="star">Estrela (star)</option>
                        <option value="heart">Coração (heart)</option>
                        <option value="moon">Lua (moon)</option>
                        <option value="sparkles">Brilho (sparkles)</option>
                        <option value="snowflake">Floco de Neve (snowflake)</option>
                        <option value="feather">Pena (feather)</option>
                        <option value="crown">Coroa (crown)</option>
                        <option value="gem">Gema (gem)</option>
                        <option value="target">Alvo (target)</option>
                        <option value="crosshair">Mira (crosshair)</option>
                    </select>
                </div>

                <div class="flex gap-3 pt-4">
                    <button type="button" id="cancel-modal-btn"
                        class="flex-1 py-3 px-6 border border-neutral-700 hover:border-gray-500 text-white font-medium rounded transition-all bg-neutral-800">
                        Cancelar
                    </button>
                    <button type="submit"
                        class="flex-1 py-3 px-6 bg-bulma-primary hover:bg-bulma-primary/90 text-neutral-900 font-bold rounded transition-all">
                        Adicionar Classe
                    </button>
                </div>
            </form>
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
