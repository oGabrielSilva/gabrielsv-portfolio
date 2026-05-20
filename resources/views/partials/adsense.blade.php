{{-- Google AdSense (Auto Ads). Carrega no <head> com async, snippet oficial do Google.
     Só renderiza se a view setar $showAds = true (View::composer) e o .env tiver client_id. --}}
@if (($showAds ?? false) && config('services.google_ads.client_id'))
    <script async
        src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client={{ config('services.google_ads.client_id') }}"
        crossorigin="anonymous"></script>
@endif
