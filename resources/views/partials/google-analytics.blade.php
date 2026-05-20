{{-- Google Analytics 4 (gtag.js) — só renderiza se a view setar $showAnalytics = true
     via View::composer e o .env tiver GA_MEASUREMENT_ID. Async pra não bloquear render. --}}
@if (($showAnalytics ?? false) && config('services.google_analytics.measurement_id'))
    @php $gaId = config('services.google_analytics.measurement_id'); @endphp
    <script async src="https://www.googletagmanager.com/gtag/js?id={{ $gaId }}"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());
        gtag('config', '{{ $gaId }}', {
            anonymize_ip: true,
            transport_type: 'beacon',
        });
    </script>
@endif
