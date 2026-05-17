<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;

class ChangelogController extends Controller
{
    public function index(): View
    {
        $entries = Cache::remember('site.changelog', now()->addHour(), function (): array {
            $base = base_path();
            $cmd = 'git -C '.escapeshellarg($base).' log --pretty=format:%H%x09%ad%x09%s --date=iso --no-merges -n 80 2>/dev/null';

            $output = [];
            $code = 0;
            @exec($cmd, $output, $code);

            if ($code !== 0 || empty($output)) {
                return [];
            }

            $entries = [];
            foreach ($output as $line) {
                [$hash, $date, $subject] = array_pad(explode("\t", $line, 3), 3, '');

                if (! preg_match('/^(feat|fix|perf|refactor)(\([\w\-]+\))?:\s*(.+)$/i', $subject, $m)) {
                    continue;
                }

                $type = strtolower($m[1]);
                $scope = trim($m[2] ?? '', '()');
                $message = $m[3];

                $entries[] = [
                    'hash' => substr($hash, 0, 7),
                    'date' => Carbon::parse($date),
                    'type' => $type,
                    'scope' => $scope,
                    'message' => $message,
                ];
            }

            return $entries;
        });

        return view('pages.changelog', ['entries' => $entries]);
    }
}
