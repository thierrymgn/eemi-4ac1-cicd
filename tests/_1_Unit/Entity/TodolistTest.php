<?php

namespace App\Tests\_1_Unit\Entity;

use App\Entity\Todolist;
use App\Tests\_1_Unit\Entity\AbstractEntityTest;

class TodolistTest extends AbstractEntityTest
{
	private Todolist $todolist;

	public function setUp(): void
	{
		$this->todolist = (new Todolist())
			->setName('I am valid!');
	}

	/**
	 * REQUIREMENTS: A name must not be blank, and have max 255 characters.
	 */
	public function test_name(): void
	{
		$this->assertIsValid($this->todolist);

		$this->assertIsInvalid($this->todolist->setName(''));
		$this->assertIsInvalid($this->todolist->setName(str_repeat('a', 256)));
	}
}
