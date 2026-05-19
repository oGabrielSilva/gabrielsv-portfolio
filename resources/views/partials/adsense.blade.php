{{-- Google AdSense — defer após window.load pra não competir com FCP/LCP/TBT.
     Só renderiza se a view setar $showAds = true (via @section ou View::share). --}}
@if (($showAds ?? false) && config('services.google_ads.client_id'))
    <script>
        window.addEventListener('load', () => {
            const s = document.createElement('script');
            s.async = true;
            s.crossOrigin = 'anonymous';
            s.src = 'https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client={{ config('services.google_ads.client_id') }}';
            document.head.appendChild(s);
        });
    </script>
@endif
