<?php

namespace Tests\Feature;
use Tests\TestCase;
use App\Models\Niveis;

class NiveisControllerTest extends TestCase
{
    public function testIndex()
    {
        Niveis::factory()->count(5)->create();

        $retorno = $this->getJson('/api/niveis');

        $retorno->assertStatus(200)->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'nivel'
                ]
            ],
            'meta' => ['total', 'per_page', 'current_page', 'last_page']
        ]);
    }

    public function testStore()
    {
        $dados = [
            'nivel' => 'Estagiário',
        ];

        $retorno = $this->postJson('/api/niveis', $dados);

        $retorno->assertStatus(201)->assertJsonStructure([
            'success',
            'message',
            'data' => [
                'nivel',
                'id',
            ],
        ]);
    }

    public function testUpdate()
    {
        $nivel = Niveis::factory()->create();

        $dados = [
            'nivel' => 'Analista',
        ];

        $retorno = $this->putJson("/api/niveis/{$nivel->id}", $dados);

        $retorno->assertStatus(200)->assertJsonStructure([
            'success',
            'message',
            'data' => [
                'id',
                'nivel',
            ],
        ]);
    }

    public function testDestroy()
    {
        $nivel = Niveis::factory()->create();

        $retorno = $this->deleteJson("/api/niveis/{$nivel->id}");

        $retorno->assertStatus(200)->assertJson([
            'success' => true,
            'message' => 'Nível deletado com sucesso.',
        ]);

        $this->assertDatabaseMissing('niveis', ['id' => $nivel->id]);
    }

    public function testShow()
{
    $nivel = Niveis::factory()->create();

    $response = $this->getJson("/api/niveis/{$nivel->id}");

    $response->assertStatus(200)
        ->assertJson([
            'success' => true,
            'data' => [
                'id' => $nivel->id,
                'nivel' => $nivel->nivel,
            ],
        ]);
}
}