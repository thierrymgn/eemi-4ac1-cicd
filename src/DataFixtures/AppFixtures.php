<?php

namespace App\DataFixtures;

use DateTime;
use Faker\Factory;
use App\Entity\Tag;
use App\Entity\Task;
use Faker\Generator;
use App\Entity\Todolist;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class AppFixtures extends Fixture
{
	private Generator $faker;

	public function __construct()
	{
		$this->faker = Factory::create();
	}

	public function load(ObjectManager $manager): void
	{
		$tags = $this->createTags();
		foreach ($tags as $tag)
			$manager->persist($tag);

		$lists = $this->createLists($tags);
		foreach ($lists as $list)
			$manager->persist($list);

		$manager->flush();
	}

	/**
	 * @return array<int, Tag>
	 */
	private function createTags(): array
	{
		$tagNames = [
			0 => 'Décoration',
			1 => 'Divertissement',
			2 => 'Eco',
			3 => 'Maison',
			4 => 'Sécurité',
			5 => 'Urgent'
		];

		/** @var string $name */
		foreach ($tagNames as $i => $name)
		{
			$tags[$i] = new Tag();
			$tags[$i]->setName($name);
			$tags[$i]->setColor($this->faker->safeColorName());
		}

		return $tags;
	}

	/**
	 * @param array<int, Tag> $tags
	 * @return array<int, Todolist>
	 */
	private function createLists(array $tags): array
	{
		$lists = [
			[
				'name' => "Décorer l'appartement",
				'tasks' => [
					['title' => 'Acheter un vase pour les poireaux', 'tags' => [0, 5]],
					['title' => 'Demander un plan du réseau à la STIB', 'tags' => [3, 0]],
					['title' => 'Tracer un chemin lumineux vers la sortie avec des guirlandes de Noël', 'tags' => [3, 4, 5]]
				]
			],
			[
				'name' => "M'occuper le prochain week-end pluvieux",
				'tasks' => [
					['title' => 'Vernir les ongles du chat', 'tags' => [1]],
					['title' => 'Faire un puzzle de ciel bleu', 'tags' => [1]],
					['title' => 'Tricoter un cartable', 'tags' => [2]],
					['title' => 'Fabriquer un sapin de Noël avec des cure-dents', 'tags' => [0, 1, 2, 3]],
					['title' => 'Faire un gâteau de betteraves', 'tags' => []]
				],
			],
			[
				'name' => 'Faire le ménage',
				'tasks' => [
					['title' => "Remettre la télé à l'endroit", 'tags' => [3]],
					['title' => 'Mettre le clavier à tremper', 'tags' => []],
					['title' => 'Huiler le carrelage', 'tags' => [1, 3]],
					['title' => 'Dépoussiérer le jardin', 'tags' => [5]]
				],
			]
		];

		foreach ($lists as $i => $listDetails)
		{
			$todolists[$i] = new Todolist();
			$todolists[$i]->setName($listDetails['name']);

			foreach ($listDetails['tasks'] as $taskDetails)
			{
				$task = new Task();
				$todolists[$i]->addTask($task);

				$task->setTitle($taskDetails['title']);
				$task->setDueDate((new DateTime())->modify('+' . strval(rand(2, 100)) . ' days'));

				foreach ($taskDetails['tags'] as $id)
					$task->addTag($tags[$id]);
			}
		}

		/**
		 * Set one Task as started and done.
		 * @var Task $taskDone
		 */
		$taskDone = $todolists[0]->getTasks()[0];
		$taskDone->setStartDate((new DateTime())->modify('-2 days'));
		$taskDone->setEndDate(new DateTime());

		/**
		 * Set one Task as started and in progress.
		 * @var Task $taskInProgress
		 */
		$taskInProgress = $todolists[1]->getTasks()[1];
		$taskInProgress->setStartDate((new DateTime())->modify('-2 days'));

		/**
		 * Set one Task as expired.
		 * @var Task $taskExpired;
		 */
		$taskExpired = $todolists[2]->getTasks()[2];
		$taskExpired->setDueDate((new DateTime())->modify('-2 days'));

		return $todolists;
	}
}
