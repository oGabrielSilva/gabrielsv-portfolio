@props(['items' => []])

@if(count($items) > 0)
    <nav aria-label="Breadcrumbs" class="text-xs text-gray-500">
        <ol class="flex flex-wrap items-center gap-1.5">
            @foreach($items as $i => $item)
                <li class="flex items-center gap-1.5">
                    @if(!empty($item['url']) && $i < count($items) - 1)
                        <a href="{{ $item['url'] }}" class="transition-colors hover:text-bulma-primary">
                            {{ $item['name'] }}
                        </a>
                    @else
                        <span class="text-gray-400" aria-current="page">{{ $item['name'] }}</span>
                    @endif
                    @if($i < count($items) - 1)
                        <span aria-hidden="true" class="text-gray-700">›</span>
                    @endif
                </li>
            @endforeach
        </ol>
    </nav>
@endif
