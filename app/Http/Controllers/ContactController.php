<?php

namespace App\Http\Controllers;

use App\Models\ContactMessage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ContactController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'min:2', 'max:120'],
            'email' => ['required', 'email:rfc', 'max:180'],
            'subject' => ['required', 'string', 'min:3', 'max:200'],
            'message' => ['required', 'string', 'min:10', 'max:5000'],
        ]);

        // Honeypot: bot preencheu o campo invisível "website". Aceita silenciosamente
        // pra não revelar que detectamos. Nenhum registro é criado.
        if (filled($request->input('website'))) {
            return back()->with('contact_success', 'Mensagem enviada! Respondo em breve.');
        }

        if (! $this->verifyTurnstile($request)) {
            return back()
                ->withInput()
                ->withErrors(['turnstile' => 'Não foi possível validar a verificação anti-bot. Tente novamente.']);
        }

        ContactMessage::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'subject' => $validated['subject'],
            'message' => $validated['message'],
            'ip' => $request->ip(),
            'user_agent' => mb_substr((string) $request->userAgent(), 0, 500),
        ]);

        return back()->with('contact_success', 'Mensagem enviada! Respondo em breve.');
    }

    private function verifyTurnstile(Request $request): bool
    {
        $secret = config('services.turnstile.secret_key');

        // Sem chave configurada: aceita (útil em dev). Em produção, sempre tem.
        if (empty($secret)) {
            return true;
        }

        $token = (string) $request->input('cf-turnstile-response');
        if ($token === '') {
            return false;
        }

        try {
            $response = Http::asForm()
                ->timeout(5)
                ->post('https://challenges.cloudflare.com/turnstile/v0/siteverify', [
                    'secret' => $secret,
                    'response' => $token,
                    'remoteip' => $request->ip(),
                ]);
        } catch (\Throwable) {
            return false;
        }

        return (bool) ($response->json('success') ?? false);
    }
}
