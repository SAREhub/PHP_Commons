<?php

namespace SAREhub\Commons\Protobuf;

use Protobuf\Stream;

/**
 * Represents File header for Protocol Buffers messages file
 *
 * FORMAT
 * |------------------------------------------------------------|
 * |4B            |4B                   |1B                     |
 * |--------------|---------------------|-----------------------|
 * |HEADER_VERSION| HEADER_CONTENTS_SIZE|MESSAGE_SIZE_INFO_BYTES|
 */
class ProtobufMessagesFileHeader {
	
	const HEADER_FORMAT_VERSION_BYTES = 4;
	const HEADER_CONTENTS_SIZE_INFO_BYTES = 4;
	
	/** @var int */
	private $headerFormatVersion = 1;
	
	/** @var int */
	private $messageSizeInfoBytes = 2;
	
	public function headerFormatVersion($version) {
		$this->headerFormatVersion = $version;
		return $this;
	}
	
	public function messageSizeInfoBytes($bytes) {
		$this->messageSizeInfoBytes = (int)$bytes;
		return $this;
	}
	
	/**
	 * @param \SplFileObject $file
	 * @return ProtobufMessagesFileHeader
	 */
	public static function readFromFile(\SplFileObject $file) {
		$file->fread(self::HEADER_CONTENTS_SIZE_INFO_BYTES);
		$headerFormatVersion = unpack('N', $file->fread(self::HEADER_FORMAT_VERSION_BYTES))[1];
		$headerContentsSize = unpack('N', $file->fread(self::HEADER_CONTENTS_SIZE_INFO_BYTES))[1];
		return self::fromBinaryString($headerFormatVersion, $file->fread($headerContentsSize));
	}
	
	/**
	 * @param int $headerFormatVersion
	 * @param string $contents
	 * @return ProtobufMessagesFileHeader
	 */
	public static function fromBinaryString($headerFormatVersion, $contents) {
		$header = new self();
		$header->headerFormatVersion($headerFormatVersion);
		$stream = Stream::fromString($contents);
		$header->messageSizeInfoBytes = unpack('C', $stream->read(1))[1];
		$stream = null; // for close stream
		return $header;
	}
	
	/**
	 * @return int
	 */
	public function getHeaderFormatVersion() {
		return $this->headerFormatVersion;
	}
	
	/**
	 * @return int
	 */
	public function getMessageSizeInfoBytes() {
		return $this->messageSizeInfoBytes;
	}
	
	/**
	 * @return string
	 */
	public function getMessageSizeInfoPackFormat() {
		switch ($this->messageSizeInfoBytes) {
			case 2:
				return 'n';
			case 4:
				return 'N';
			case 8:
				return 'J';
		}
	}
	
	/**
	 * @return string
	 */
	public function toBinaryString() {
		$contents = pack('C', $this->messageSizeInfoBytes);
		return pack('N', $this->headerFormatVersion).pack('N', strlen($contents)).$contents;
	}
	
}