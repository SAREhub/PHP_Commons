<?php

namespace SAREhub\Commons\Misc;


class PackFormat {
	
	protected $format;
	protected $bigEndian;
	protected $bytes;
	
	public function __construct($format, $bigEndian, $bytes) {
		$this->format = $format;
		$this->bigEndian = $bigEndian;
		$this->bytes = $bytes;
	}
	
	/**
	 * @return PackFormat
	 */
	public static function uint8() {
		return new self('C', true, 1);
	}
	
	/**
	 * @param bool $bigEndian
	 * @return PackFormat
	 */
	public static function uint16($bigEndian = true) {
		return new self($bigEndian ? 'n' : 'v', $bigEndian, 2);
	}
	
	/**
	 * @param bool $bigEndian
	 * @return PackFormat
	 */
	public static function uint32($bigEndian = true) {
		return new self($bigEndian ? 'N' : 'V', $bigEndian, 4);
	}
	
	public static function uint64($bigEndian = true) {
		return new self($bigEndian ? 'J' : 'P', $bigEndian, 8);
	}
	
	/**
	 * @return string
	 */
	public function getFormat() {
		return $this->format;
	}
	
	/**
	 * @return bool
	 */
	public function isBigEndian() {
		return (bool)$this->bigEndian;
	}
	
	/**
	 * @return int
	 */
	public function getBytes() {
		return $this->bytes;
	}
}