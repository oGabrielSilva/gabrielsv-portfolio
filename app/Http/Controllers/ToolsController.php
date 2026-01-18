<?php

namespace App\Http\Controllers;

use App\Providers\AppServiceProvider;
use App\Services\LoremService;
use App\Services\UuidService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class ToolsController extends Controller
{
    public function __construct(
        private UuidService $uuidService,
        private LoremService $loremService
    ) {
    }

    public function index()
    {
        return view('tools.index', ['tools' => AppServiceProvider::TOOLS]);
    }

    /**
     * UUID Generator - página principal (redireciona para v4)
     */
    public function uuid()
    {
        return redirect()->route('tools.uuid.type', ['type' => 'uuid-v4']);
    }

    /**
     * UUID Generator - página específica por tipo (SEO friendly)
     */
    public function uuidByType(string $type)
    {
        if (!$this->uuidService->isValidType($type)) {
            abort(404);
        }

        $typeInfo = $this->uuidService->getTypeInfo($type);
        $ids = $this->uuidService->generate($type, 5);
        $types = $this->uuidService->getTypes();

        return view('tools.uuid', [
            'currentType' => $type,
            'typeInfo' => $typeInfo,
            'ids' => $ids,
            'types' => $types,
            'quantity' => 5,
        ]);
    }

    /**
     * Gera UUIDs via API (AJAX)
     */
    public function generateUuid(Request $request): JsonResponse
    {
        $request->validate([
            'type' => 'required|in:uuid-v1,uuid-v4,uuid-v6,uuid-v7,cuid,nanoid',
            'quantity' => 'required|integer|min:1|max:50',
        ]);

        $ids = $this->uuidService->generate(
            $request->input('type'),
            $request->input('quantity')
        );

        return response()->json(['ids' => $ids]);
    }

    /**
     * Lorem Ipsum Generator - página inicial
     */
    public function lorem()
    {
        $types = $this->loremService->getTypes();
        $text = $this->loremService->generate('paragraphs', 3, true);

        return view('tools.lorem', [
            'types' => $types,
            'text' => $text,
            'type' => 'paragraphs',
            'quantity' => 5,
            'startWithLorem' => true,
        ]);
    }

    /**
     * Gera Lorem Ipsum via API (AJAX)
     */
    public function generateLorem(Request $request): JsonResponse
    {
        $request->validate([
            'type' => 'required|in:paragraphs,sentences,words',
            'quantity' => 'required|integer|min:1|max:50',
            'start_with_lorem' => 'boolean',
        ]);

        $text = $this->loremService->generate(
            $request->input('type'),
            $request->input('quantity'),
            $request->input('start_with_lorem', true)
        );

        return response()->json(['text' => $text]);
    }

    public function percentage()
    {
        return view('tools.percentage');
    }

    public function imageCompressor()
    {
        return view('tools.image-compressor');
    }

    public function cpfCnpj(string $type = 'cpf')
    {
        return view('tools.cpf-cnpj', [
            'type' => $type,
        ]);
    }

    public function base64()
    {
        return view('tools.base64');
    }

    public function slugify()
    {
        return view('tools.slugify');
    }
}
