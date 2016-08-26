<?php

use PHPUnit\Framework\TestCase;
use Protobuf\Stream;
use SAREhub\Commons\Protobuf\ProtobufMessagesFileHeader;

class ProtobufMessagesFileHeaderTest extends TestCase {
	
	public function testToBinaryString() {
		$header = new ProtobufMessagesFileHeader();
		$binaryString = $header->toBinaryString();
		$stream = Stream::fromString($binaryString);
		$version = unpack('N', $stream->read(ProtobufMessagesFileHeader::HEADER_FORMAT_VERSION_BYTES))[1];
		
		$this->assertEquals(1, $version);
		$contentsSize = unpack('N', $stream->read(ProtobufMessagesFileHeader::HEADER_CONTENTS_SIZE_INFO_BYTES))[1];
		$this->assertEquals(1, $contentsSize);
		$messageSizeInfoBytes = unpack('C', $stream->read(1))[1];
		$this->assertEquals(2, $messageSizeInfoBytes);
	}
	
	public function testFromBinaryString() {
		$headerContents = pack('C', 2);
		$header = ProtobufMessagesFileHeader::fromBinaryString(1, $headerContents);
		$this->assertEquals(1, $header->getHeaderFormatVersion());
		$this->assertEquals(2, $header->getMessageSizeInfoBytes());
	}
	
	public function testGetMessageSizeInfoPackFormatFor2Bytes() {
		$header = new ProtobufMessagesFileHeader();
		$this->assertEquals('n', $header->messageSizeInfoBytes(2)->getMessageSizeInfoPackFormat());
	}
	
	public function testGetMessageSizeInfoPackFormatFor4Bytes() {
		$header = new ProtobufMessagesFileHeader();
		$this->assertEquals('N', $header->messageSizeInfoBytes(4)->getMessageSizeInfoPackFormat());
	}
	
	public function testGetMessageSizeInfoPackFormatFor8Bytes() {
		$header = new ProtobufMessagesFileHeader();
		$this->assertEquals('J', $header->messageSizeInfoBytes(8)->getMessageSizeInfoPackFormat());
	}
}
