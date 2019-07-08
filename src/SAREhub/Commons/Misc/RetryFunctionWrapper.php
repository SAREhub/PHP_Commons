<?php


namespace SAREhub\Commons\Misc;


class RetryFunctionWrapper
{
    /**
     * @var callable
     */
    private $callback;

    /**
     * @var string
     */
    private $expectedErrorClasses;

    /**
     * @var int
     */
    private $maxRetries;

    /**
     * @var float
     */
    private $initialWait;

    /**
     * @var int
     */
    private $exponent;

    /**
     * @var int
     */
    private $leftRetries;

    /**
     * @var float
     */
    private $currentWait;

    public function __construct(callable $callback, array $expectedErrorClasses = [], int $maxRetries = 3, float $initialWait = 1.0, int $exponent = 2)
    {
        $this->callback = $callback;
        $this->expectedErrorClasses = $expectedErrorClasses;
        $this->maxRetries = $this->leftRetries = $maxRetries;
        $this->initialWait = $this->currentWait = $initialWait;
        $this->exponent = $exponent;
    }

    /**
     * @throws \Exception
     */
    public function executeInOnePass()
    {
        while ($this->leftRetries > 0) {
            $result = $this->executeNextTry();
            if ($result["success"]) {
                return $result["data"];
            }
        }
    }

    /**
     * @return array ["success" => bool, "data" => mixed]
     * @throws \Exception
     */
    public function executeNextTry(): array
    {
        try {
            --$this->leftRetries;
            $callback = $this->callback;
            $data = $callback();
            $this->currentWait = $this->initialWait;
            $this->leftRetries = $this->maxRetries;
            return [
                "success" => true,
                "data" => $data
            ];
        } catch (\Exception $e) {
            $errors = class_parents($e);
            array_push($errors, get_class($e));
            if (!array_intersect($errors, $this->expectedErrorClasses) || $this->leftRetries === 0) {
                throw $e;
            }
            usleep($this->currentWait * 1E6);
            $this->currentWait = $this->initialWait * $this->exponent;
            return [
                "success" => false,
                "data" => null
            ];
        }
    }
}