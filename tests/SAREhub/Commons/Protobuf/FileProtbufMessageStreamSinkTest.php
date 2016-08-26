<?php

namespace SAREhub\Commons\Protobuf;

use PHPUnit\Framework\TestCase;
use Protobuf\Stream;

class FileProtbufMessageStreamSinkTest extends TestCase {
	
	public function testWriteFileHeader() {
		$fileMock = $this->getMockBuilder(\SplFileObject::class)
		  ->setConstructorArgs(['php://memory', 'w'])
		  ->getMock();
		
		$fileMock->expects($this->once())->method('fwrite')->with('test');
		
		$fileHeaderMock = $this->getMockBuilder(ProtobufMessagesFileHeader::class)->getMock();
		$fileHeaderMock->method('toBinaryString')->willReturn('test');
		$fileHeaderMock->method('getMessageSizeInfoPackFormat')->willReturn('n');
		$sink = new FileProtbufMessageStreamSink($fileMock, $fileHeaderMock);
	}
	
	public function testWrite() {
		$fileMock = $this->getMockBuilder(\SplFileObject::class)
		  ->setConstructorArgs(['php://memory', 'w'])
		  ->getMock();
		$dataStream = Stream::fromString("test");
		
		$fileHeaderMock = $this->getMockBuilder(ProtobufMessagesFileHeader::class)->getMock();
		$fileHeaderMock->method('toBinaryString')->willReturn('test');
		$fileHeaderMock->method('getMessageSizeInfoPackFormat')->willReturn('n');
		$sink = new FileProtbufMessageStreamSink($fileMock, $fileHeaderMock);
		
		$fileMock->expects($this->once())
		  ->method('fwrite')
		  ->with(pack('n', $dataStream->getSize()).$dataStream->getContents());
		$sink->write($dataStream);
	}
	
}
