<?php

namespace App\Tests\_3_Application;

use Exception;
use Faker\Factory;
use Faker\Generator;
use ApiPlatform\Symfony\Bundle\Test\Client;
use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;
use Symfony\Component\HttpClient\Exception\ClientException;
use Symfony\Component\HttpClient\Exception\ServerException;

abstract class AbstractApiTest extends ApiTestCase
{
	protected string $iri;
	protected array $validPayload; // @phpstan-ignore-line
	protected array $invalidPayload; // @phpstan-ignore-line
	protected Generator $faker;
	protected Client $client;
	protected array $responseContent; // @phpstan-ignore-line

	private array $requestOptions; // @phpstan-ignore-line

	/* ********************************************************** *\
		Common setup
	\* ********************************************************** */

	protected function setUp(): void
	{
		parent::setUp();

		$this->client = static::createClient();
		$this->faker = Factory::create();
		$this->requestOptions['headers']['Authorization'] = 'Basic ' . base64_encode($_ENV['AUTH_USER_EMAIL'] . ':' . $_ENV['AUTH_USER_PASS']);
		$this->requestOptions['headers']['accept'] = 'application/json';
	}

	/* ********************************************************** *\
		Common tests
	\* ********************************************************** */

	// --------- Unauthenticated access ---------

	protected function test_endpoint_without_authentication(string $method): void
	{
		unset($this->requestOptions['headers']['Authorization']);

		$this->expectException(ClientException::class);
		$this->makeRequest($method);

		$this->assertResponseStatusCodeSame(401);
	}

	// --------- Create ---------

	protected function test_create_with_a_valid_payload(): void
	{
		$this->makeRequest('POST', $this->validPayload);

		$this->assertResponseStatusCodeSame(201);
		$this->assertArrayHasKey('id', $this->responseContent);
	}

	protected function test_create_with_an_invalid_payload(): void
	{
		$this->makeRequest('POST', $this->invalidPayload);

		$this->assertResponseStatusCodeSame(400);
	}

	// --------- Read ---------

	protected function test_read_one_with_a_valid_id(): void
	{
		$this->iri .= '/1';

		$this->makeRequest('GET');

		$this->assertResponseStatusCodeSame(200);
		$this->assertArrayHasKey('id', $this->responseContent);
		$this->assertSame($this->responseContent['id'], 1);
	}

	protected function test_read_one_with_an_invalid_id(): void
	{
		$this->iri .= '/0';

		$this->expectException(ClientException::class);
		$this->makeRequest('GET');

		$this->assertResponseStatusCodeSame(404);
	}

	// --------- Update ---------

	protected function test_update_with_a_valid_payload(): void
	{
		$this->makeRequest('PUT', $this->validPayload);

		$this->assertResponseStatusCodeSame(200);
		$this->assertArrayHasKey('id', $this->responseContent);
	}

	protected function test_update_with_an_invalid_payload(): void
	{
		$this->expectException(ServerException::class);

		$this->makeRequest('PUT', $this->invalidPayload);

		$this->assertResponseStatusCodeSame(400);
	}

	/* ********************************************************** *\
		Generic request
	\* ********************************************************** */

	// @phpstan-ignore-next-line
	protected function makeRequest(string $method, ?array $payload = null): void
	{
		if (in_array($method, ['POST', 'PUT']))
			$this->requestOptions['headers']['content-type'] = 'application/json';

		if ($payload !== null)
			$this->requestOptions['json'] = $payload;

		$clientResponse = $this->client->request($method, $this->iri, $this->requestOptions);
		if (!is_array($responseArray = json_decode($clientResponse->getContent(), true)))
			throw new Exception('json_decode failed.');
		$this->responseContent = $responseArray;

		$this->assertResponseHeaderSame('content-type', 'application/json; charset=utf-8');
	}
}
