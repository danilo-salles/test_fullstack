<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Desenvolvedor;
use App\Models\Niveis;

class DesenvolvedorControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testStore()
    {
        $nivel = Niveis::factory()->create();

        $dados = [
            'nivel_id' => $nivel->id,
            'nome' => 'Novo Desenvolvedor',
            'sexo' => 'M',
            'data_nascimento' => '1990-01-01',
            'hobby' => 'Programar',
        ];

        $retorno = $this->postJson('/api/desenvolvedores', $dados);

        $retorno->assertStatus(201)->assertJsonStructure([
            'success',
            'message',
            'data' => [
                'nivel_id',
                'nome',
                'sexo',
                'data_nascimento',
                'hobby',
                'id',
            ],
        ]);

        $nivel->delete();
    }

    public function testUpdate()
    {
        $desenvolvedor  = Desenvolvedor::factory()->create();
        $novoNivel      = Niveis::factory()->create();

        $dados = [
            'nivel_id' => $novoNivel->id,
            'nome' => 'Desenvolvedor Atualizado',
            'sexo' => 'F',
            'data_nascimento' => '1988-05-15',
            'hobby' => 'Desenvolver software',
        ];

        $retorno = $this->putJson("/api/desenvolvedores/{$desenvolvedor->id}", $dados);

        $retorno->assertStatus(200)->assertJsonStructure([
            'success',
            'message',
            'data' => [
                'id',
                'nivel_id',
                'nome',
                'sexo',
                'data_nascimento',
                'hobby',
            ],
        ]);
    }

    public function testDestroy()
    {
        $desenvolvedor = Desenvolvedor::factory()->create();

        $retorno = $this->deleteJson("/api/desenvolvedores/{$desenvolvedor->id}");

        $retorno->assertStatus(200)->assertJson([
            'success' => true,
            'message' => 'Desenvolvedor deletado com sucesso.',
        ]);

        $this->assertDatabaseMissing('desenvolvedores', ['id' => $desenvolvedor->id]);
    }

    public function testShow()
    {
        $desenvolvedor = Desenvolvedor::factory()->create();

        $retorno = $this->getJson("/api/desenvolvedores/{$desenvolvedor->id}");

        $retorno->assertStatus(200)->assertJsonStructure([
            'id',
            'nivel_id',
            'nome',
            'sexo',
            'data_nascimento',
            'hobby',
        ]);
    }

    public function testCountByNivel()
    {
        $nivel = Niveis::factory()->create();

        Desenvolvedor::factory()->create(['nivel_id' => $nivel->id]);

        $response = $this->getJson('/api/niveis/desenvolvedores/count');

        $response->assertStatus(200)->assertJsonStructure([
                'data' => [
                    '*' => [
                        'id',
                        'nivel',
                        'quantidade',
                    ],
                ],
            ]);
    }
}
