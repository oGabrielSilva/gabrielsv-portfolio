<?php

namespace App\Http\Middleware;

use App\Models\PageView;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class TrackPageView
{
    /**
     * Padrões que NÃO devem ser registrados (assets, admin, healthchecks).
     */
    private const SKIP_PATTERNS = [
        'console',
        'console/*',
        'build/*',
        'storage/*',
        'sitemap.xml',
        'robots.txt',
        'favicon.*',
        'livewire/*',
        'filament/*',
        '_debugbar/*',
        '_ignition/*',
    ];

    /**
     * Sinais simples de bot/crawler no UA.
     */
    private const BOT_SIGNALS = [
        'bot', 'crawl', 'spider', 'slurp', 'mediapartners',
        'facebookexternalhit', 'lighthouse', 'headlesschrome',
        'phantomjs', 'curl/', 'wget/', 'python-requests',
        'go-http-client', 'libwww', 'java/', 'okhttp',
    ];

    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        try {
            $this->record($request, $response);
        } catch (Throwable) {
            // Telemetria nunca quebra a request.
        }

        return $response;
    }

    private function record(Request $request, Response $response): void
    {
        if ($request->method() !== 'GET') {
            return;
        }

        if ($response->getStatusCode() >= 400) {
            return;
        }

        $path = $request->path();
        if ($this->shouldSkip($path)) {
            return;
        }

        $userAgent = (string) $request->userAgent();

        PageView::create([
            'path' => Str::limit('/'.ltrim($path, '/'), 500, ''),
            'route_name' => $request->route()?->getName(),
            'referrer_host' => $this->extractReferrerHost($request),
            'device' => $this->detectDevice($userAgent),
            'is_bot' => $this->isBot($userAgent),
            'country' => $this->extractCountry($request),
            'utm_source' => $this->utm($request, 'utm_source'),
            'utm_medium' => $this->utm($request, 'utm_medium'),
            'utm_campaign' => $this->utm($request, 'utm_campaign'),
            'utm_content' => $this->utm($request, 'utm_content'),
            'utm_term' => $this->utm($request, 'utm_term'),
            'viewed_at' => now(),
        ]);
    }

    private function shouldSkip(string $path): bool
    {
        foreach (self::SKIP_PATTERNS as $pattern) {
            if (Str::is($pattern, $path)) {
                return true;
            }
        }

        return false;
    }

    private function isBot(string $userAgent): bool
    {
        $ua = strtolower($userAgent);
        foreach (self::BOT_SIGNALS as $signal) {
            if (str_contains($ua, $signal)) {
                return true;
            }
        }

        return false;
    }

    private function detectDevice(string $userAgent): string
    {
        $ua = strtolower($userAgent);
        if (str_contains($ua, 'tablet') || str_contains($ua, 'ipad')) {
            return 'tablet';
        }
        if (preg_match('/mobile|android|iphone|ipod/i', $ua)) {
            return 'mobile';
        }

        return 'desktop';
    }

    private function extractReferrerHost(Request $request): ?string
    {
        $referer = $request->headers->get('referer');
        if (! $referer) {
            return null;
        }

        $host = parse_url($referer, PHP_URL_HOST);
        if (! $host) {
            return null;
        }

        $currentHost = $request->getHost();
        if ($host === $currentHost) {
            return null;
        }

        return Str::limit($host, 255, '');
    }

    /**
     * País via Cloudflare (CF-IPCountry header). Outros provedores podem ser
     * adicionados depois. Sem GeoIP local pra não bater no banco de dados de
     * IPs ou exigir extensão.
     */
    private function extractCountry(Request $request): ?string
    {
        $cf = $request->headers->get('cf-ipcountry');
        if ($cf && strlen($cf) === 2 && ctype_alpha($cf)) {
            return strtoupper($cf);
        }

        return null;
    }

    private function utm(Request $request, string $key): ?string
    {
        $value = $request->query($key);
        if (! is_string($value) || $value === '') {
            return null;
        }

        return Str::limit($value, 100, '');
    }
}
