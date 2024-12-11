<?php

namespace App\Controller;

use App\Entity\Task;
use App\Repository\TaskRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Attribute\AsController;

#[AsController]
class CreateTaskController extends AbstractController
{
    private TaskRepository $taskRepository;
    private EntityManagerInterface $entityManager;
    private Task $task;

    public function __construct(
        TaskRepository $taskRepository,
        EntityManagerInterface $entityManager,
    ) {
        $this->taskRepository = $taskRepository;
        $this->entityManager = $entityManager;
    }

    public function __invoke(Task $task): Task
    {
        $this->task = $task;

        $this->validateRequest();

        $this->entityManager->persist($this->task);
        $this->entityManager->flush();

        return $this->task;
    }

    private function validateRequest(): void
    {
        if (!$this->task->getTitle()) {
            throw new \Exception('Title is mandatory');
        }

        if (null !== $this->taskRepository->findOneBy(['title' => $this->task->getTitle()])) {
            throw new \Exception('Title already exists');
        }

        if (!$this->task->getTodolist()) {
            throw new \Exception('Todolist is mandatory');
        }

        if (!$this->task->getTags()->isEmpty()) {
            foreach ($this->task->getTags() as $tag) {
                if (null === $tag->getId()) {
                    if (!$tag->getName()) {
                        throw new \Exception('Tag name is mandatory');
                    }
                    if (!$tag->getColor()) {
                        throw new \Exception('Tag color is mandatory');
                    }
                }
            }
        }
    }
}
