@props(['icon', 'title', 'description', 'color' => 'bulma-primary', 'delay' => 0])

<div @class([
    'w-full md:w-[calc(50%-12px)] lg:w-[calc(33.33%-16px)] p-8 bg-neutral-800/50 backdrop-blur-sm rounded-xl border border-neutral-700',
    'hover:bg-neutral-800 transition-all duration-500 ease-out hover:-translate-y-2 group',
    "service-card--{$color}",
])
    data-aos="fade-up" data-aos-delay="{{ $delay }}">

    <div class="service-card__icon w-12 h-12 bg-neutral-900/80 rounded-lg flex items-center justify-center mb-6 text-2xl
        group-hover:scale-110 group-hover:rotate-3 transition-transform duration-500 ease-out">
        <i class="fa-solid {{ $icon }}"></i>
    </div>
    <h3 class="service-card__title text-xl font-bold text-white mb-3 transition-colors duration-300">
        {!! $title !!}
    </h3>
    <p class="text-gray-400 leading-relaxed text-sm">
        {{ $description }}
    </p>
</div>
