<?php

namespace App\Entity;

use ApiPlatform\Metadata\Get;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\TagRepository;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Action\NotFoundAction;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: TagRepository::class)]
#[ApiResource(
	operations: [
		new Get(
			controller: NotFoundAction::class,
			read: false,
			output: false,
			openapi: false
		),
	]
)]
class Tag
{
	#[ORM\Id]
	#[ORM\GeneratedValue]
	#[ORM\Column]
	#[Groups(['todolist:read:one', 'task:read', 'task:write'])]
	private ?int $id = null;

	#[ORM\Column(length: 255, unique: true)]
	#[Assert\NotBlank]
	#[Assert\Length(max: 255)]
	#[Groups(['todolist:read:one', 'task:read', 'task:write'])]
	private ?string $name = null;

	#[ORM\Column(length: 255)]
	#[Groups(['todolist:read:one', 'task:read', 'task:write'])]
	private ?string $color = null;

	/**
	 * @var Collection<int, Task> $tasks
	 */
	#[ORM\ManyToMany(targetEntity: Task::class, inversedBy: 'tags')]
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

	public function getColor(): ?string
	{
		return $this->color;
	}

	public function setColor(?string $color): self
	{
		$this->color = $color;

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
			$this->tasks->add($task);

		return $this;
	}

	public function removeTask(Task $task): self
	{
		$this->tasks->removeElement($task);

		return $this;
	}
}
