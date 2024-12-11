<?php

namespace App\Tests\_3_Application;

class ReadTaskTest extends AbstractApiTest
{
    public function setUp(): void
    {
        parent::setUp();

        $this->iri = '/api/tasks';
    }

    public function testReadAllWithoutAuthentication(): void
    {
        parent::test_endpoint_without_authentication('GET');
    }

    public function testReadAllWithAValidIdAndValidTagIdFilter(): void
    {
        $validTagId = '6';
        $this->iri .= '?tags%5B%5D='.$validTagId;
        $this->test_read_all_by_valid_tag('id', $validTagId);
    }

    public function testReadAllWithAValidIdAndValidTagNameFilter(): void
    {
        $validTagName = 'Urgent';
        $this->iri .= '?tags.name%5B%5D='.$validTagName;
        $this->test_read_all_by_valid_tag('name', $validTagName);
    }

    public function testReadAllWithAValidIdAndInvalidTagIdFilter(): void
    {
        $this->iri .= '?tags%5B%5D=0';
        $this->test_read_all_by_invalid_tag();
    }

    public function testReadAllWithAValidIdAndInvalidTagNameFilter(): void
    {
        $this->iri .= '?tags.name%5B%5D=NotAValidTag';
        $this->test_read_all_by_invalid_tag();
    }

    public function testReadAllExpiredTasks(): void
    {
        $this->iri .= '?isExpired=true';

        $this->makeRequest('GET');

        $this->assertResponseStatusCodeSame(200);
        $this->assertNotEmpty($this->responseContent);

        $today = (new \DateTime())->format('Y-m-d');

        /** @var array<string> $task */
        foreach ($this->responseContent as $task) {
            $this->assertArrayHasKey('dueDate', $task);
            $this->assertTrue((new \DateTime($task['dueDate']))->format('Y-m-d') < $today);
        }
    }

    /* ***************************************************** *\
        Support methods
    \* ***************************************************** */

    private function testReadAllByValidTag(string $key, string $validTag): void
    {
        $this->makeRequest('GET');

        $this->assertResponseStatusCodeSame(200);
        $this->assertNotEmpty($this->responseContent);

        /** @var array<array<int|string>> $task */
        foreach ($this->responseContent as $task) {
            $this->assertNotEmpty($task['tags']);
            $this->assertTrue(in_array($validTag, array_column($task['tags'], $key)));
        }
    }

    private function testReadAllByInvalidTag(): void
    {
        $this->makeRequest('GET');

        $this->assertResponseStatusCodeSame(200);
        $this->assertEmpty($this->responseContent);
    }
}
