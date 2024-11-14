<?php

namespace App\Tests\_1_Unit\Entity;

use App\Entity\Tag;
use App\Tests\_1_Unit\Entity\AbstractEntityTest;

class TagTest extends AbstractEntityTest
{
	private Tag $tag;

	public function setUp(): void
	{
		$this->tag = (new Tag())
			->setName('I am valid!');
	}

	/**
	 * REQUIREMENTS: A name must not be blank, and have max 255 characters.
	 */
	public function test_name(): void
	{
		$this->assertIsValid($this->tag);

		$this->assertIsInvalid($this->tag->setName(''));
		$this->assertIsInvalid($this->tag->setName(str_repeat('a', 256)));
	}
}
