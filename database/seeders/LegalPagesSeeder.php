<?php

namespace Database\Seeders;

use App\Models\LegalPage;
use Illuminate\Database\Seeder;

class LegalPagesSeeder extends Seeder
{
    public function run(): void
    {
        $appName = config('app.name', 'Gabriel Silva');
        $today = now()->translatedFormat('d \d\e F \d\e Y');
        $contactEmail = config('site.social.email')
            ? str_replace('mailto:', '', config('site.social.email'))
            : 'help@rota42.com';

        $pages = [
            [
                'slug' => 'privacidade',
                'title' => 'Política de Privacidade',
                'meta_description' => 'Como o site coleta, usa e protege dados pessoais, conforme a LGPD.',
                'body_html' => <<<HTML
<p><em>Última atualização: {$today}.</em></p>

<p>Esta política descreve como o site <strong>{$appName}</strong> coleta, usa e protege dados pessoais ao receber sua visita, em conformidade com a Lei Geral de Proteção de Dados (LGPD, Lei 13.709/2018).</p>

<h2>1. Quem é o controlador</h2>
<p>O controlador dos dados tratados neste site é <strong>Gabriel Henrique da Silva</strong>, pessoa física, autor e mantenedor do site. Contato para questões de privacidade: <a href="mailto:{$contactEmail}">{$contactEmail}</a>.</p>

<h2>2. Quais dados coletamos</h2>

<h3>2.1. Dados de navegação (analytics próprio)</h3>
<p>Cada visita gera um registro anônimo no nosso banco de dados, contendo:</p>
<ul>
    <li><strong>Caminho da página</strong> visitada (ex: <code>/blog</code>);</li>
    <li><strong>Domínio de origem</strong> (referrer host), quando enviado pelo seu navegador (ex: <code>google.com</code>);</li>
    <li><strong>Tipo de dispositivo</strong> aproximado (mobile, tablet, desktop);</li>
    <li><strong>País</strong> aproximado, quando disponível pelo cabeçalho da CDN;</li>
    <li><strong>Parâmetros UTM</strong> (source, medium, campaign, content, term), se presentes na URL;</li>
    <li>Indicador booleano de <strong>bot</strong> detectado por user-agent;</li>
    <li>Data e hora da visita.</li>
</ul>
<p>Não armazenamos endereço IP, não atribuímos identificadores únicos persistentes e não construímos perfil individual de leitor. Os registros são agregados para gerar estatísticas como "posts mais lidos no mês" e "origens de tráfego".</p>

<h3>2.2. Dados de conta administrativa</h3>
<p>O painel <code>/console</code> é de uso exclusivo do autor. Se você não tem acesso autorizado, não há criação de conta nem cadastro de dados pessoais seus.</p>

<h3>2.3. Dados de contato voluntário</h3>
<p>Se você nos enviar e-mail diretamente, processaremos seu endereço de e-mail e a mensagem para responder. Não usamos esses dados para nenhuma outra finalidade nem os adicionamos a listas de marketing.</p>

<h2>3. Base legal e finalidade</h2>
<p>O tratamento dos dados de navegação tem como base legal o <strong>legítimo interesse</strong> do controlador (art. 7º, IX, LGPD) em entender o tráfego do próprio site e melhorar o conteúdo publicado. As finalidades são restritas a:</p>
<ul>
    <li>Estatísticas internas de audiência;</li>
    <li>Diagnóstico de erros e desempenho técnico;</li>
    <li>Direcionamento editorial (entender quais temas têm interesse).</li>
</ul>
<p>Não há decisão automatizada, scoring, perfilamento individual ou compartilhamento dos dados de navegação com terceiros.</p>

<h2>4. Cookies</h2>
<p>Veja a <a href="/legal/cookies">Política de Cookies</a> para detalhes. Em resumo: usamos apenas cookies essenciais ao funcionamento (sessão, CSRF). O Google AdSense, quando ativo, define seus próprios cookies de publicidade — gerenciados pelo Google nos termos da política dele.</p>

<h2>5. Publicidade (Google AdSense)</h2>
<p>Este site pode exibir anúncios servidos pelo Google AdSense. O Google e seus parceiros podem usar cookies e identificadores de dispositivo para:</p>
<ul>
    <li>Servir anúncios baseados em visitas suas a este site ou a outros sites;</li>
    <li>Medir performance dos anúncios.</li>
</ul>
<p>Você pode desativar a personalização de anúncios na sua conta Google em <a href="https://adssettings.google.com" target="_blank" rel="noopener">adssettings.google.com</a>. Mais informações na <a href="https://policies.google.com/technologies/ads" target="_blank" rel="noopener">política de publicidade do Google</a>.</p>

<h2>6. Compartilhamento</h2>
<p>Não vendemos nem alugamos dados. Compartilhamentos restringem-se a:</p>
<ul>
    <li><strong>Hospedagem (Hostinger)</strong>: a infraestrutura processa requisições HTTP por necessidade técnica;</li>
    <li><strong>Google (AdSense)</strong>: apenas quando o módulo de anúncios estiver ativo, nos termos do item 5;</li>
    <li><strong>Autoridades</strong>: somente mediante ordem judicial ou requisição legalmente fundamentada.</li>
</ul>

<h2>7. Retenção</h2>
<p>Registros de navegação são mantidos por até <strong>12 meses</strong> e depois agregados ou excluídos. E-mails recebidos são mantidos pelo tempo necessário para resposta e arquivamento (até 5 anos por padrão).</p>

<h2>8. Segurança</h2>
<p>Adotamos medidas técnicas e administrativas razoáveis: HTTPS em todo o site, banco de dados em servidor com acesso restrito por chave, atualizações regulares de segurança. Nenhuma transmissão na internet é 100% segura, mas trabalhamos para mitigar riscos.</p>

<h2>9. Seus direitos (LGPD)</h2>
<p>Você tem o direito de, a qualquer momento, solicitar gratuitamente:</p>
<ul>
    <li>Confirmação da existência de tratamento;</li>
    <li>Acesso aos dados;</li>
    <li>Correção de dados incompletos, inexatos ou desatualizados;</li>
    <li>Anonimização, bloqueio ou eliminação de dados desnecessários ou tratados em desconformidade;</li>
    <li>Portabilidade;</li>
    <li>Eliminação de dados tratados com base no seu consentimento;</li>
    <li>Informação sobre compartilhamento;</li>
    <li>Revogação do consentimento.</li>
</ul>
<p>Como os registros de navegação são anônimos, em geral não conseguimos vinculá-los a você individualmente — o que limita parte desses direitos por impossibilidade técnica. Para exercer qualquer direito, envie um e-mail para <a href="mailto:{$contactEmail}">{$contactEmail}</a> com o assunto "LGPD".</p>

<h2>10. Encarregado pelo Tratamento (DPO)</h2>
<p>Gabriel Henrique da Silva atua como encarregado pelo tratamento de dados pessoais neste site. Canal de contato: <a href="mailto:{$contactEmail}">{$contactEmail}</a>.</p>

<h2>11. Crianças e adolescentes</h2>
<p>O site não é direcionado a menores de 13 anos. Não coletamos conscientemente dados de crianças. Se você é responsável legal e identificou coleta indevida, escreva-nos para remoção.</p>

<h2>12. Alterações desta política</h2>
<p>Esta política pode ser atualizada a qualquer momento. A versão vigente é sempre a publicada nesta página, com a data da última atualização visível no topo. Recomendamos consultar periodicamente.</p>

<h2>13. Autoridade Nacional</h2>
<p>Se você entender que seus direitos não foram respeitados, pode encaminhar reclamação à <strong>Autoridade Nacional de Proteção de Dados (ANPD)</strong> em <a href="https://www.gov.br/anpd" target="_blank" rel="noopener">gov.br/anpd</a>.</p>
HTML,
            ],
            [
                'slug' => 'termos',
                'title' => 'Termos de Uso',
                'meta_description' => 'Regras de uso do site, ferramentas e conteúdo.',
                'body_html' => <<<HTML
<p><em>Última atualização: {$today}.</em></p>

<p>Ao acessar e utilizar o site <strong>{$appName}</strong>, você concorda integralmente com estes Termos de Uso. Se não concorda, por favor, não utilize o site.</p>

<h2>1. Sobre o site</h2>
<p>O site é um espaço pessoal mantido por <strong>Gabriel Henrique da Silva</strong>, com finalidade informativa e educacional. Inclui blog (artigos técnicos), ferramentas online utilitárias e materiais de portfólio.</p>

<h2>2. Conteúdo editorial</h2>
<p>Os artigos publicados no blog refletem opiniões e experiências do autor em determinado momento. Não constituem aconselhamento profissional, jurídico, médico, financeiro ou de qualquer outra natureza. Toda a responsabilidade pela aplicação dos conteúdos é do leitor.</p>

<h2>3. Ferramentas online</h2>
<p>As ferramentas disponibilizadas em <code>/tools</code> (gerador de UUID, slugify, formatador JSON, gerador de Lorem Ipsum, validador de CPF/CNPJ, etc.) são oferecidas <strong>gratuitamente e sem garantia</strong>.</p>
<ul>
    <li>A maioria das ferramentas executa <strong>inteiramente no navegador</strong> (lado cliente). Os dados que você digita não são enviados ao servidor.</li>
    <li>Ferramentas que dependem de processamento no servidor (ex: gerador UUID, Lorem Ipsum) processam os dados em memória, retornam o resultado e <strong>não armazenam o conteúdo</strong> enviado.</li>
    <li>Não use as ferramentas para tratar dados pessoais sensíveis sem antes verificar a natureza do processamento.</li>
</ul>

<h2>4. Propriedade intelectual</h2>
<p>Os artigos, imagens, ilustrações, design e código-fonte original deste site são de autoria de <strong>Gabriel Henrique da Silva</strong>, salvo indicação em contrário.</p>
<ul>
    <li><strong>Reprodução parcial</strong> de artigos é permitida, desde que com atribuição clara e link para a publicação original;</li>
    <li><strong>Reprodução integral</strong> requer autorização prévia por escrito;</li>
    <li>Trechos de código publicados nos artigos podem ser reutilizados livremente, exceto quando indicado licenciamento específico;</li>
    <li>Marcas, logos e nomes de terceiros mencionados pertencem a seus respectivos titulares.</li>
</ul>

<h2>5. Conduta do usuário</h2>
<p>Ao utilizar o site, você se compromete a:</p>
<ul>
    <li>Não tentar acessar áreas restritas (como o painel <code>/console</code>);</li>
    <li>Não usar as ferramentas para fins ilícitos ou que sobrecarreguem a infraestrutura (ex: scripts de scraping massivo);</li>
    <li>Não introduzir código malicioso, vírus ou tentar comprometer a segurança do site.</li>
</ul>

<h2>6. Publicidade</h2>
<p>O site pode exibir anúncios servidos pelo <strong>Google AdSense</strong>. Os anúncios são identificados como tal e seguem as políticas do Google. Cliques em anúncios direcionam você para sites de terceiros, sobre os quais não temos responsabilidade.</p>

<h2>7. Limitação de responsabilidade</h2>
<p>O site é oferecido "como está" (as is) e "conforme disponível" (as available). Na máxima extensão permitida pela lei:</p>
<ul>
    <li>Não garantimos disponibilidade ininterrupta, ausência de erros ou que as ferramentas atendam a suas necessidades específicas;</li>
    <li>Não respondemos por danos diretos, indiretos, incidentais ou consequenciais decorrentes do uso ou da impossibilidade de uso do site;</li>
    <li>Eventuais bugs, perda de dados temporária ou indisponibilidade não geram direito a indenização.</li>
</ul>

<h2>8. Links para terceiros</h2>
<p>O site pode conter links para sites externos (GitHub, LinkedIn, ferramentas referenciadas em artigos, etc.). Não nos responsabilizamos pelo conteúdo, políticas ou práticas de privacidade de sites externos. O acesso é por sua conta e risco.</p>

<h2>9. Privacidade</h2>
<p>O tratamento de dados pessoais segue nossa <a href="/legal/privacidade">Política de Privacidade</a> e a Lei Geral de Proteção de Dados (LGPD).</p>

<h2>10. Alterações nos termos</h2>
<p>Estes termos podem ser revisados a qualquer momento. A versão vigente é sempre a publicada nesta página, com a data da última atualização no topo. O uso continuado do site após mudanças significa aceitação dos novos termos.</p>

<h2>11. Foro e lei aplicável</h2>
<p>Estes termos são regidos pela legislação brasileira. Fica eleito o foro da comarca de domicílio do autor, no Brasil, para dirimir eventuais conflitos, com renúncia a qualquer outro, por mais privilegiado que seja.</p>

<h2>12. Contato</h2>
<p>Dúvidas sobre estes termos: <a href="mailto:{$contactEmail}">{$contactEmail}</a>.</p>
HTML,
            ],
            [
                'slug' => 'cookies',
                'title' => 'Política de Cookies',
                'meta_description' => 'Quais cookies o site utiliza, por que e como controlar.',
                'body_html' => <<<HTML
<p><em>Última atualização: {$today}.</em></p>

<p>Esta página explica de forma transparente quais cookies o site <strong>{$appName}</strong> utiliza, qual a finalidade de cada um, e como você pode controlá-los. Cookies são pequenos arquivos de texto que sites armazenam no seu navegador para lembrar de informações entre páginas.</p>

<h2>1. Cookies essenciais (sempre ativos)</h2>
<p>Estes cookies são <strong>necessários</strong> para o funcionamento básico do site e não podem ser desabilitados sem prejudicar a navegação. Não exigem consentimento prévio, conforme a LGPD.</p>
<ul>
    <li><strong>laravel_session</strong> — mantém sua sessão entre páginas (necessário para envio de formulários e login administrativo). Expira ao fechar o navegador ou após 2 horas de inatividade.</li>
    <li><strong>XSRF-TOKEN</strong> — token de segurança contra falsificação de requisições (CSRF). Validade igual à sessão.</li>
    <li><strong>{$appName}_session</strong> ou variante com prefixo do app — mesmo papel do <code>laravel_session</code> dependendo da configuração de produção.</li>
</ul>

<h2>2. Cookies de analytics</h2>
<p><strong>Nenhum.</strong> Não usamos Google Analytics, Facebook Pixel, Hotjar, Mixpanel, Plausible nem similares. A medição de tráfego é feita do lado do servidor, registrando dados anônimos (sem cookie, sem JavaScript de terceiros, sem identificador persistente). Detalhes na <a href="/legal/privacidade">Política de Privacidade</a>.</p>

<h2>3. Cookies de publicidade (Google AdSense)</h2>
<p>Quando o site exibe anúncios do <strong>Google AdSense</strong>, o Google e seus parceiros podem definir cookies próprios para:</p>
<ul>
    <li>Servir anúncios relevantes;</li>
    <li>Medir performance de anúncios;</li>
    <li>Limitar a frequência de exibição;</li>
    <li>Detectar fraude.</li>
</ul>
<p>Estes cookies são gerenciados pelo Google, sob a política deles. Consulte:</p>
<ul>
    <li><a href="https://policies.google.com/technologies/ads" target="_blank" rel="noopener">Como o Google usa cookies em publicidade</a>;</li>
    <li><a href="https://policies.google.com/technologies/cookies" target="_blank" rel="noopener">Tipos de cookies usados pelo Google</a>.</li>
</ul>
<p>Você pode optar por não receber anúncios personalizados do Google em <a href="https://adssettings.google.com" target="_blank" rel="noopener">adssettings.google.com</a> ou via <a href="https://optout.aboutads.info/" target="_blank" rel="noopener">Digital Advertising Alliance</a>.</p>

<h2>4. Cookies do painel administrativo (uso interno)</h2>
<p>O painel <code>/console</code> usa cookies adicionais (Filament/Livewire) para manter o estado da interface durante a sessão administrativa. <strong>Esses cookies só são definidos para usuários autenticados</strong> (apenas o autor) — não afetam visitantes do blog.</p>

<h2>5. Como controlar cookies</h2>
<p>Você pode, a qualquer momento:</p>
<ul>
    <li><strong>Bloquear todos os cookies</strong> nas configurações do seu navegador;</li>
    <li><strong>Aceitar apenas cookies de sessão</strong> (que são apagados ao fechar o navegador);</li>
    <li><strong>Apagar cookies existentes</strong> manualmente.</li>
</ul>
<p>Instruções por navegador:</p>
<ul>
    <li><a href="https://support.google.com/chrome/answer/95647" target="_blank" rel="noopener">Chrome</a></li>
    <li><a href="https://support.mozilla.org/pt-BR/kb/protecao-aprimorada-contra-rastreamento-firefox-desktop" target="_blank" rel="noopener">Firefox</a></li>
    <li><a href="https://support.apple.com/pt-br/guide/safari/sfri11471/mac" target="_blank" rel="noopener">Safari</a></li>
    <li><a href="https://support.microsoft.com/pt-br/microsoft-edge" target="_blank" rel="noopener">Edge</a></li>
</ul>
<p>Bloquear cookies essenciais pode impedir o envio de formulários e o login no painel administrativo. A leitura de artigos e o uso da maioria das ferramentas client-side continua funcionando normalmente.</p>

<h2>6. Por que não exibimos banner de consentimento</h2>
<p>Como não usamos cookies de analytics nem de marketing próprio (apenas essenciais e, quando ativo, AdSense de terceiros), entendemos que não há base que exija banner de consentimento prévio para a maior parte da navegação. Os cookies de AdSense, quando aplicáveis, são objeto da política do próprio Google.</p>

<h2>7. Atualizações</h2>
<p>Esta política pode mudar se introduzirmos novos serviços ou alterarmos a forma como tratamos cookies. A versão vigente é sempre a publicada aqui, com data no topo.</p>

<h2>8. Dúvidas</h2>
<p>Fale com a gente: <a href="mailto:{$contactEmail}">{$contactEmail}</a>.</p>
HTML,
            ],
        ];

        foreach ($pages as $page) {
            LegalPage::updateOrCreate(['slug' => $page['slug']], $page);
        }

        $this->command?->info('LegalPages: '.count($pages).' páginas garantidas.');
    }
}
