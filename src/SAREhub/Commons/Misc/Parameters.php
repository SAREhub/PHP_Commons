<?php

namespace SAREhub\Commons\Misc;

/**
 * Class for operate on custom configurations
 */
class Parameters implements \JsonSerializable
{

    /**
     * @var array
     */
    private $params;

    public function __construct(array $params)
    {
        $this->params = $params;
    }

    public static function create(array $params): self
    {
        return new self($params);
    }

    public static function createFlatten(array $params): self
    {
        return self::create(ArrayHelper::flatten($params));
    }

    public static function createFromJson(string $json): self
    {
        return self::create(json_decode($json, true));
    }

    /**
     * @param string $name
     * @param mixed $default
     * @return mixed
     */
    public function get(string $name, $default = null)
    {
        return ($this->has($name)) ? $this->params[$name] : $default;
    }

    /**
     * @param string $name
     * @return mixed
     */
    public function getRequired(string $name)
    {
        if ($this->has($name)) {
            return $this->params[$name];
        }

        throw new NotFoundParameterException("Required parameter doesn't exists: $name");
    }

    public function getAsMap(string $name, array $default = []): self
    {
        $params = $this->get($name, $default);
        if (is_array($params)) {
            return self::create($params);
        }

        throw new ParameterException("Parameter isn't array: $name");
    }

    public function getRequiredAsMap(string $name): self
    {
        $params = $this->getRequired($name);
        if (is_array($params)) {
            return self::create($params);
        }

        throw new ParameterException("Parameter isn't array: $name");
    }

    public function has(string $name): bool
    {
        return isset($this->params[$name]);
    }

    public function getAll(): array
    {
        return $this->params;
    }

    public function jsonSerialize()
    {
        return $this->getAll();
    }
}