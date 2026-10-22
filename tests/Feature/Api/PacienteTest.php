<?php

namespace Tests\Feature\Api;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Core\Domain\ValueObject\Uuid;
use DateTime;
use Illuminate\Http\Response;
use Ramsey\Uuid\Uuid as RamseyUuid;

class PacienteTest extends TestCase
{
    protected $endpoint = 'http://localhost:8000/api/pacientes';

    public function test_patient_and_import_endpoints_require_authentication(): void
    {
        $endpoints = [
            ['GET', '/api/pacientes'],
            ['GET', '/api/pacientes/1'],
            ['POST', '/api/pacientes'],
            ['PUT', '/api/pacientes/1'],
            ['PATCH', '/api/pacientes/1'],
            ['DELETE', '/api/pacientes/1'],
            ['POST', '/api/importarPacientes'],
        ];

        foreach ($endpoints as [$method, $uri]) {
            $this->json($method, $uri)->assertUnauthorized();
        }
    }

    public function atest_criacao_paciente(): void
    {
        $uuid = (string) RamseyUuid::uuid4();
        $nascimento = date('Y-m-d H:i:s');
        $createdAt = date('Y-m-d H:i:s');

        $data = [
            'id' => new Uuid($uuid),
            'nome' => 'nome',
            'nomeMae' => 'nomeMae',
            'cpf' => '29308642064',
            'nascimento' => now(),
            'cns' => '153638180670009',
            'foto' => '10',
        ];

        $response = $this->postJson($this->endpoint, $data);

        $response->assertStatus(Response::HTT);
        

    }
}
