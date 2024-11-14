<?php

namespace App\Entity;

use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\Post;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Metadata\ApiResource;
use App\Repository\TodolistRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: TodolistRepository::class)]
#[ApiResource(
	operations: [
		new Get(),
		new Post(),
	],
	normalizationContext: ['groups' => ['todolist:read:one']],
	denormalizationContext: ['groups' => ['todolist:create']],
)]
class Todolist
{
	#[ORM\Id]
	#[ORM\GeneratedValue]
	#[ORM\Column]
	#[Groups(['todolist:read:one'])]
	private ?int $id = null;

	#[ORM\Column(length: 255, unique: true)]
	#[Assert\NotBlank]
	#[Assert\Length(max: 255)]
	#[Groups(['todolist:read:one', 'todolist:create'])]
	private ?string $name = null;

	/**
	 * @var Collection<int, Task> $tasks
	 */
	#[ORM\OneToMany(targetEntity: Task::class, mappedBy: 'todolist', cascade: ["all"], orphanRemoval: true)]
	#[Groups(['todolist:read:one'])]
	private Collection $tasks;

	public function __construct()
	{
		$this->tasks = new ArrayCollection();
	}

	public function getId(): ?int
	{
		return $this->id;
	}

	public function getName(): ?string
	{
		return $this->name;
	}

	public function setName(string $name): self
	{
		$this->name = $name;

		return $this;
	}

	/**
	 * @return Collection<int, Task>
	 */
	public function getTasks(): Collection
	{
		return $this->tasks;
	}

	public function addTask(Task $task): self
	{
		if (!$this->tasks->contains($task))
		{
			$this->tasks->add($task);
			$task->setTodolist($this);
		}

		return $this;
	}

	public function removeTask(Task $task): self
	{
		if ($this->tasks->removeElement($task))
			if ($task->getTodolist() === $this)
				$task->setTodolist(null);

		return $this;
	}
}
