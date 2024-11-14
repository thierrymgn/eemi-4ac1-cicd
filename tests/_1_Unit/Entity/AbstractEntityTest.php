<?php

namespace App\Tests\_1_Unit\Entity;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Validator\Validator\ValidatorInterface;

abstract class AbstractEntityTest extends KernelTestCase
{
	protected function assertIsValid(mixed $entity): void
	{
		$this->assertViolationsIs(false, $entity);
	}

	protected function assertIsInvalid(mixed $entity): void
	{
		$this->assertViolationsIs(true, $entity);
	}

	private function assertViolationsIs(bool $expectedHasViolations, mixed $entity): void
	{
		/** @var ValidatorInterface $validator */
		$validator = self::getContainer()->get(ValidatorInterface::class);
		$violations = $validator->validate($entity);
		$hasViolations = (count($violations) >= 1);

		if ($hasViolations)
			foreach ($violations as $violation)
				$debug[] = $violation->getMessage() . '(propertyPath: ' . $violation->getPropertyPath() . ', invalidValue: ' . $violation->getInvalidValue() . ')';

		$this->assertSame($expectedHasViolations, $hasViolations, implode("\n", $debug ?? []));
	}
}
