<?php

namespace App\Tests\_3_Application\Controller;

use App\Tests\_3_Application\AbstractApiTest;
use Symfony\Component\HttpClient\Exception\ServerException;

class CreateTaskControllerTest extends AbstractApiTest
{
    public function setUp(): void
    {
        parent::setUp();

        $this->iri = '/api/tasks';
        $this->validPayload = [
            'title' => $this->faker->sentence(),
            'startDate' => null,
            'endDate' => null,
            'dueDate' => (new \DateTime())->format('Y-m-d\TH:i:s\Z'),
            'todolist' => '/api/todolists/1',
            'tags' => [
                '/api/tags/1',
                [
                    'name' => $this->faker->word(),
                    'color' => $this->faker->safeColorName(),
                ],
            ],
        ];
    }

    public function testCreateWithoutAuthentication(): void
    {
        parent::test_endpoint_without_authentication('POST');
    }

    public function testCreateWithAValidPayload(): void
    {
        parent::test_create_with_a_valid_payload();

        $this->assertArrayHasKey('title', $this->responseContent);
        $this->assertSame($this->responseContent['title'], $this->validPayload['title']);
        $this->assertCount(count($this->validPayload['tags']), $this->responseContent['tags']);
    }

    public function testCreateWithAnInvalidPayloadMissingTitle(): void
    {
        $this->invalidPayload = $this->validPayload;
        unset($this->invalidPayload['title']);

        $this->expectException(ServerException::class);
        parent::test_create_with_an_invalid_payload();
    }

    public function testCreateWithAnInvalidPayloadDuplicateTitle(): void
    {
        $this->invalidPayload = $this->validPayload;
        $this->invalidPayload['title'] = 'Acheter un vase pour les poireaux';

        $this->expectException(ServerException::class);
        parent::test_create_with_an_invalid_payload();
    }

    public function testCreateWithAnInvalidPayloadMissingTodolist(): void
    {
        $this->invalidPayload = $this->validPayload;
        unset($this->invalidPayload['todolist']);

        $this->expectException(ServerException::class);
        parent::test_create_with_an_invalid_payload();
    }

    public function testCreateWithAnInvalidPayloadMissingTagProperty(): void
    {
        $this->invalidPayload = $this->validPayload;
        unset($this->invalidPayload['tags'][1]['name']);

        $this->expectException(ServerException::class);
        parent::test_create_with_an_invalid_payload();
    }
}
