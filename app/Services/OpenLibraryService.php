<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Exception;

class OpenLibraryService
{
    private const BASE_URL = 'https://openlibrary.org';
    private const SEARCH_URL = 'https://openlibrary.org/search.json';
    private const COVER_URL = 'https://covers.openlibrary.org/b';

    /**
     * Buscar livro por ISBN
     */
    public function buscarPorISBN(string $isbn): ?array
    {
        try {
            // Remove hífens e espaços do ISBN
            $isbn = preg_replace('/[^0-9X]/', '', $isbn);

            // Cache por 30 dias
            $cacheKey = "openlib_isbn_{$isbn}";
            
            return Cache::remember($cacheKey, 60 * 60 * 24 * 30, function () use ($isbn) {
                $url = self::BASE_URL . "/api/books?bibkeys=ISBN:{$isbn}&format=json&jscmd=data";
                
                $verify = filter_var(env('HTTP_VERIFY', true), FILTER_VALIDATE_BOOLEAN);
                $response = Http::withOptions(['verify' => $verify])->timeout(10)->get($url);

                if (!$response->successful() || empty($response->json())) {
                    return null;
                }

                $data = $response->json();
                $bookKey = "ISBN:{$isbn}";

                if (!isset($data[$bookKey])) {
                    return null;
                }

                return $this->formatarDados($data[$bookKey], $isbn);
            });
        } catch (Exception $e) {
            report($e);
            return null;
        }
    }

    /**
     * Buscar livros por título
     */
    public function buscarPorTitulo(string $titulo, int $limit = 10): array
    {
        try {
            $verify = filter_var(env('HTTP_VERIFY', true), FILTER_VALIDATE_BOOLEAN);
            $response = Http::withOptions(['verify' => $verify])->timeout(10)->get(self::SEARCH_URL, [
                'title' => $titulo,
                'limit' => $limit
            ]);

            if (!$response->successful()) {
                return [];
            }

            $data = $response->json();
            
            if (!isset($data['docs']) || empty($data['docs'])) {
                return [];
            }

            return array_map(function ($item) {
                return $this->formatarDadosBusca($item);
            }, $data['docs']);
        } catch (Exception $e) {
            report($e);
            return [];
        }
    }

    /**
     * Buscar livros por autor
     */
    public function buscarPorAutor(string $autor, int $limit = 10): array
    {
        try {
            $verify = filter_var(env('HTTP_VERIFY', true), FILTER_VALIDATE_BOOLEAN);
            $response = Http::withOptions(['verify' => $verify])->timeout(10)->get(self::SEARCH_URL, [
                'author' => $autor,
                'limit' => $limit
            ]);

            if (!$response->successful()) {
                return [];
            }

            $data = $response->json();
            
            if (!isset($data['docs']) || empty($data['docs'])) {
                return [];
            }

            return array_map(function ($item) {
                return $this->formatarDadosBusca($item);
            }, $data['docs']);
        } catch (Exception $e) {
            report($e);
            return [];
        }
    }

    /**
     * Buscar livros (busca geral)
     */
    public function buscar(string $query, int $limit = 10): array
    {
        try {
            $verify = filter_var(env('HTTP_VERIFY', true), FILTER_VALIDATE_BOOLEAN);
            $response = Http::withOptions(['verify' => $verify])->timeout(10)->get(self::SEARCH_URL, [
                'q' => $query,
                'limit' => $limit
            ]);

            if (!$response->successful()) {
                return [];
            }

            $data = $response->json();
            
            if (!isset($data['docs']) || empty($data['docs'])) {
                return [];
            }

            return array_map(function ($item) {
                return $this->formatarDadosBusca($item);
            }, $data['docs']);
        } catch (Exception $e) {
            report($e);
            return [];
        }
    }

    /**
     * Obter URL da capa do livro
     */
    public function obterUrlCapa(?string $coverId, string $size = 'M'): ?string
    {
        if (!$coverId) {
            return null;
        }

        // Sizes: S (small), M (medium), L (large)
        return self::COVER_URL . "/id/{$coverId}-{$size}.jpg";
    }

    /**
     * Obter URL da capa por ISBN
     */
    public function obterUrlCapaPorISBN(string $isbn, string $size = 'M'): string
    {
        $isbn = preg_replace('/[^0-9X]/', '', $isbn);
        return self::COVER_URL . "/isbn/{$isbn}-{$size}.jpg";
    }

    /**
     * Formatar dados do livro (busca por ISBN)
     */
    private function formatarDados(array $data, string $isbn): array
    {
        $autores = [];
        if (isset($data['authors'])) {
            $autores = array_map(fn($author) => $author['name'] ?? '', $data['authors']);
        }

        $categorias = [];
        if (isset($data['subjects'])) {
            $categorias = array_slice(array_map(fn($subject) => $subject['name'] ?? '', $data['subjects']), 0, 5);
        }

        return [
            'titulo' => $data['title'] ?? '',
            'autor' => !empty($autores) ? implode(', ', $autores) : '',
            'autores' => $autores,
            'isbn' => $isbn,
            'descricao' => $data['notes'] ?? ($data['description'] ?? ''),
            'categoria' => !empty($categorias) ? $categorias[0] : '',
            'categorias' => $categorias,
            'ano_publicacao' => $this->extrairAno($data['publish_date'] ?? null),
            'editora' => isset($data['publishers'][0]['name']) ? $data['publishers'][0]['name'] : '',
            'numero_paginas' => $data['number_of_pages'] ?? null,
            'imagem' => isset($data['cover']['large']) ? $data['cover']['large'] : 
                       (isset($data['cover']['medium']) ? $data['cover']['medium'] : 
                       (isset($data['cover']['small']) ? $data['cover']['small'] : null)),
            'url' => $data['url'] ?? null,
        ];
    }

    /**
     * Formatar dados do livro (busca geral)
     */
    private function formatarDadosBusca(array $item): array
    {
        $isbn = null;
        if (isset($item['isbn']) && !empty($item['isbn'])) {
            $isbn = $item['isbn'][0];
        }

        $coverId = $item['cover_i'] ?? null;
        $imagemUrl = $coverId ? $this->obterUrlCapa($coverId) : null;

        return [
            'titulo' => $item['title'] ?? '',
            'autor' => isset($item['author_name']) ? implode(', ', $item['author_name']) : '',
            'autores' => $item['author_name'] ?? [],
            'isbn' => $isbn,
            'categoria' => isset($item['subject']) && !empty($item['subject']) ? $item['subject'][0] : '',
            'categorias' => $item['subject'] ?? [],
            'ano_publicacao' => isset($item['first_publish_year']) ? (int) $item['first_publish_year'] : null,
            'editora' => isset($item['publisher']) && !empty($item['publisher']) ? $item['publisher'][0] : '',
            'numero_paginas' => $item['number_of_pages_median'] ?? null,
            'imagem' => $imagemUrl,
            'cover_id' => $coverId,
        ];
    }

    /**
     * Limpar cache de um ISBN específico
     */
    public function limparCache(string $isbn): void
    {
        $isbn = preg_replace('/[^0-9X]/', '', $isbn);
        Cache::forget("openlib_isbn_{$isbn}");
    }

    /**
     * Extrair ano de uma string de data
     */
    private function extrairAno(?string $data): ?int
    {
        if (!$data) {
            return null;
        }

        if (preg_match('/\b(19|20)\d{2}\b/', $data, $matches)) {
            return (int) $matches[0];
        }

        return null;
    }
}
