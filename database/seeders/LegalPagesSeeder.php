<?php

namespace Database\Seeders;

use App\Models\LegalPage;
use Illuminate\Database\Seeder;

class LegalPagesSeeder extends Seeder
{
    public function run(): void
    {
        $appName = config('app.name', 'Gabriel');
        $today = now()->translatedFormat('d \d\e F \d\e Y');

        $pages = [
            [
                'slug' => 'privacidade',
                'title' => 'Política de Privacidade',
                'meta_description' => 'Como o site coleta, usa e protege dados pessoais, conforme a LGPD.',
                'body_html' => <<<HTML
<p><em>Última atualização: {$today}.</em></p>

<p>Esta política descreve como o site <strong>{$appName}</strong> coleta, usa e protege dados ao receber sua visita, em conformidade com a Lei Geral de Proteção de Dados (LGPD, Lei 13.709/2018).</p>

<h2>1. Dados coletados</h2>
<p>O site registra dados anônimos de navegação, exclusivamente para entender o tráfego e melhorar o conteúdo:</p>
<ul>
    <li>Caminho da página visitada (ex: <code>/blog</code>);</li>
    <li>Domínio de origem (referrer), quando enviado pelo seu navegador;</li>
    <li>Tipo de dispositivo (mobile, tablet, desktop);</li>
    <li>Parâmetros UTM presentes na URL (quando houver);</li>
    <li>País aproximado, quando disponível via cabeçalho do provedor de CDN.</li>
</ul>
<p>Não usamos cookies de rastreamento, não coletamos seu endereço IP completo, não atribuímos identificadores únicos e não compartilhamos dados com terceiros para fins publicitários.</p>

<h2>2. Finalidade</h2>
<p>Os dados servem apenas para estatísticas internas de audiência (quantas visitas o site recebeu, quais páginas são mais acessadas). Não há decisão automatizada sobre você, nem perfilamento.</p>

<h2>3. Cookies</h2>
<p>O site usa apenas cookies estritamente necessários para o funcionamento (sessão, CSRF). Não há cookies de marketing ou de análise. Por isso não exibimos banner de consentimento.</p>

<h2>4. Seus direitos</h2>
<p>Você pode solicitar a qualquer momento, pelo e-mail abaixo, o acesso, a correção ou a exclusão dos dados que porventura tenhamos sobre você. Como não usamos identificadores, normalmente não há dados pessoais associados à sua navegação.</p>

<h2>5. Contato</h2>
<p>Encarregado pelo Tratamento de Dados (DPO): Gabriel Henrique da Silva.<br>
E-mail para questões de privacidade: <a href="mailto:help@rota42.com">help@rota42.com</a>.</p>
HTML,
            ],
            [
                'slug' => 'termos',
                'title' => 'Termos de Uso',
                'meta_description' => 'Regras de uso do site e responsabilidades.',
                'body_html' => <<<HTML
<p><em>Última atualização: {$today}.</em></p>

<p>Ao acessar o site <strong>{$appName}</strong>, você concorda com os termos abaixo.</p>

<h2>1. Conteúdo</h2>
<p>Os artigos, ferramentas e materiais são publicados em caráter informativo. Não substituem aconselhamento profissional. Toda a responsabilidade pelo uso é do leitor.</p>

<h2>2. Ferramentas online</h2>
<p>As ferramentas disponíveis em <code>/tools</code> rodam principalmente no seu navegador. Quando enviam dados ao servidor (ex: gerador UUID), eles são processados em memória e não armazenados.</p>

<h2>3. Propriedade intelectual</h2>
<p>O conteúdo original publicado aqui é de autoria de Gabriel Henrique da Silva. Reprodução parcial é permitida com atribuição e link para a fonte; reprodução integral requer autorização prévia.</p>

<h2>4. Limitação de responsabilidade</h2>
<p>O site é oferecido "como está". Não garantimos disponibilidade ininterrupta nem que as ferramentas estejam livres de erros. Em qualquer hipótese, não respondemos por danos diretos ou indiretos decorrentes do uso.</p>

<h2>5. Alterações</h2>
<p>Estes termos podem ser revisados a qualquer momento. A versão vigente é sempre a publicada nesta página.</p>

<h2>6. Foro</h2>
<p>Fica eleito o foro da comarca de domicílio do autor, no Brasil, para dirimir eventuais conflitos.</p>
HTML,
            ],
            [
                'slug' => 'cookies',
                'title' => 'Política de Cookies',
                'meta_description' => 'Quais cookies o site utiliza e por quê.',
                'body_html' => <<<HTML
<p><em>Última atualização: {$today}.</em></p>

<p>O site <strong>{$appName}</strong> usa um número mínimo de cookies, todos estritamente necessários ao funcionamento. Não usamos cookies de marketing, publicidade ou rastreamento entre sites.</p>

<h2>Cookies essenciais</h2>
<ul>
    <li><strong>XSRF-TOKEN / laravel_session</strong>: usados pelo framework Laravel para manter sessão e prevenir falsificação de requisições (CSRF). Expiram ao fechar o navegador.</li>
</ul>

<h2>Cookies de analytics</h2>
<p>Não usamos. A análise de tráfego é feita do lado do servidor, sem identificar visitantes, sem cookie e sem JavaScript externo. Veja a <a href="/legal/privacidade">Política de Privacidade</a> para detalhes.</p>

<h2>Cookies de terceiros</h2>
<p>Não há cookies de terceiros. O site não embute Google Analytics, Facebook Pixel, Hotjar ou similares.</p>

<h2>Como controlar</h2>
<p>Você pode bloquear cookies pelas configurações do seu navegador. O bloqueio dos cookies essenciais pode impedir o login no painel administrativo (uso interno) e o envio de formulários.</p>
HTML,
            ],
        ];

        foreach ($pages as $page) {
            LegalPage::updateOrCreate(['slug' => $page['slug']], $page);
        }

        $this->command?->info('LegalPages: '.count($pages).' páginas garantidas.');
    }
}
