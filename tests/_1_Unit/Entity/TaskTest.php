<?php

namespace App\Tests\_1_Unit\Entity;

use App\Entity\Task;
use App\Entity\Todolist;

class TaskTest extends AbstractEntityTest
{
    private Task $task;
    private Todolist $todolist;

    public function setUp(): void
    {
        $this->task = (new Task())
            ->setTitle('I am valid!');

        $this->todolist = (new Todolist())
            ->addTask($this->task);
    }

    /**
     * REQUIREMENTS: A title must not be blank, and have max 255 characters.
     */
    public function testTitle(): void
    {
        $this->assertIsValid($this->task);

        $this->assertIsInvalid($this->task->setTitle(''));
        $this->assertIsInvalid($this->task->setTitle(str_repeat('a', 256)));
    }

    /**
     * REQUIREMENTS: A task must belong to a Todolist.
     */
    public function testTodolist(): void
    {
        $this->assertIsValid($this->task);

        $this->todolist->removeTask($this->task);
        $this->assertIsInvalid($this->task);
    }
}
