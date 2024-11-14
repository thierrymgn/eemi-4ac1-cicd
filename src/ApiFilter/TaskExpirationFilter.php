<?php

namespace App\ApiFilter;

use ApiPlatform\Doctrine\Orm\Filter\AbstractFilter;
use ApiPlatform\Doctrine\Orm\Util\QueryNameGeneratorInterface;
use ApiPlatform\Metadata\Operation;
use Doctrine\ORM\QueryBuilder;

final class TaskExpirationFilter extends AbstractFilter
{
	/**
	 * This is the logic that applies the filtering.
	 */
	// @phpstan-ignore-next-line
	protected function filterProperty(
		string $property,
		$value,
		QueryBuilder $queryBuilder,
		QueryNameGeneratorInterface $queryNameGenerator,
		string $resourceClass,
		Operation $operation = null,
		array $context = []
	): void
	{
		if ($property === 'isExpired')
		{
			$alias = $queryBuilder->getRootAliases()[0];
			$queryBuilder
				->andWhere(sprintf('%s.dueDate < :today', $alias))
				->setParameter('today', date('Y-m-d'));
		}
	}

	/**
	 * This describes the filter, for the documentation.
	 */
	// @phpstan-ignore-next-line
	public function getDescription(string $resourceClass): array
	{
		return [
			'isExpired' => [
				'property' => 'dueDate',
				'type' => 'bool',
				'required' => false,
			]
		];
	}
}
