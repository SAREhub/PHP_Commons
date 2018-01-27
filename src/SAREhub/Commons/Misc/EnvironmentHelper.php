<?php

namespace SAREhub\Commons\Misc;


class EnvironmentHelper
{
    /**
     * Returns value of environment variable or default value
     * @param string $name
     * @param null $defaultValue
     * @return mixed value from env or defaultValue
     */
    public static function getVar(string $name, $defaultValue = null)
    {
        $value = getenv($name);
        return $value !== false ? $value : $defaultValue;
    }

    /**
     * Returns values of environment variables defined in schema parameter
     * @param array $schema Format: ["variableName" => defaultValue]
     * @param string $prefix Its added to variableName when getting value from env
     * @return array ["variableName" => valueFromEnv|defaultValue]
     */
    public static function getVars(array $schema, string $prefix = ""): array
    {
        $env = [];
        foreach ($schema as $name => $default) {
            $env[$name] = self::getVar($prefix . $name, $default);
        }

        return $env;
    }
}
