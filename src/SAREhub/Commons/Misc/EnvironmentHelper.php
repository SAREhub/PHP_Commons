<?php

namespace SAREhub\Commons\Misc;


class EnvironmentHelper {
	
	public static function getVar(string $name, $default = null) {
		$value = getenv($name);
		return $value !== false ? $value : $default;
	}
	
	public static function getVars(array $schema): array {
		$env = [];
		foreach ($schema as $name => $default) {
			$env[$name] = self::getVar($name, $default);
		}
		
		return $env;
	}
}