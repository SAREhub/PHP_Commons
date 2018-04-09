<?php


namespace SAREhub\Commons\Task;


class Sequence implements Task
{
    /**
     * @var Task[]
     */
    private $tasks;

    public function __construct(array $tasks)
    {
        $this->tasks = $tasks;
    }

    public function run()
    {
        foreach ($this->getTasks() as $task) {
            $task->run();
        }
    }

    /**
     * @return Task[]
     */
    public function getTasks(): array
    {
        return $this->tasks;
    }
}