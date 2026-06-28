<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class DndApiService
{
    protected string $baseUrl;

    public function __construct()
    {
        $this->baseUrl = config('services.dnd_api.url');
    }

    // Colecciones
    public function getClases(): array
    {
        return Http::get("{$this->baseUrl}/classes")->json()['results'] ?? [];
    }

    public function getRazas(): array
    {
        return Http::get("{$this->baseUrl}/races")->json()['results'] ?? [];
    }

    public function getConjuros(): array
    {
        return Http::get("{$this->baseUrl}/spells")->json()['results'] ?? [];
    }

    public function getMonstruos(): array
    {
        return Http::get("{$this->baseUrl}/monsters")->json()['results'] ?? [];
    }

    // Individuales
    public function getClase(string $index): array
    {
        return Http::get("{$this->baseUrl}/classes/{$index}")->json();
    }

    public function getRaza(string $index): array
    {
        return Http::get("{$this->baseUrl}/races/{$index}")->json();
    }

    public function getConjuro(string $index): array
    {
        return Http::get("{$this->baseUrl}/spells/{$index}")->json();
    }

    public function getMonstruo(string $index): array
    {
        return Http::get("{$this->baseUrl}/monsters/{$index}")->json();
    }
}