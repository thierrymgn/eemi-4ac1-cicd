<?php

namespace App\Tests\_3_Application;

use Symfony\Component\HttpClient\Exception\ClientException;

class CreateTodolistTest extends AbstractApiTest
{
    public function setUp(): void
    {
        parent::setUp();

        $this->iri = '/api/todolists';
        $this->validPayload = [
            'name' => $this->faker->sentence(),
        ];
        $this->invalidPayload = [
            'name' => '',
        ];
    }

    public function testCreateWithoutAuthentication(): void
    {
        parent::test_endpoint_without_authentication('POST');
    }

    public function testCreateWithAValidPayload(): void
    {
        parent::test_create_with_a_valid_payload();

        $this->assertArrayHasKey('name', $this->responseContent);
        $this->assertSame($this->responseContent['name'], $this->validPayload['name']);
    }

    public function testCreateWithAnInvalidPayload(): void
    {
        $this->expectException(ClientException::class);

        parent::test_create_with_an_invalid_payload();
    }
}
