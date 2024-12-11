<?php

namespace App\Tests\_3_Application;

class ReadTodolistTest extends AbstractApiTest
{
    public function setUp(): void
    {
        parent::setUp();

        $this->iri = '/api/todolists';
    }

    public function testReadOneWithoutAuthentication(): void
    {
        $this->iri .= '/1';
        parent::test_endpoint_without_authentication('GET');
    }

    public function testReadOneWithAValidId(): void
    {
        parent::test_read_one_with_a_valid_id();
    }

    public function testReadOneWithAnInvalidId(): void
    {
        parent::test_read_one_with_an_invalid_id();
    }
}
