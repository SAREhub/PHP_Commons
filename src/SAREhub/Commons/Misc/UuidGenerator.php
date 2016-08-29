<?php

namespace SAREhub\Component\Worker;

/**
 * Helper class for generate UUID
 */
class UuidGenerator {
	
	/**
	 * @param string $prefix
	 * @return string
	 */
	public static function generate($prefix = '') {
		return uniqid($prefix, true);
	}
}