<?php

namespace SAREhub\Commons\Protobuf;

use org\bovigo\vfs\vfsStream;
use PHPUnit\Framework\TestCase;
use PHPUnit_Framework_MockObject_MockObject;
use Protobuf\Stream;
use SAREhub\Commons\DataStream\DataStreamSink;
use SAREhub\Commons\DataStream\NullDataStreamSink;

class FileProtobufMessageStreamSourceTest extends TestCase {
	
	/** @var vfsStreamDirectory */
	private $root;
	private $filename = 'test.fpe';
	
	/** @var PHPUnit_Framework_MockObject_MockObject */
	private $sinkMock;
	
	/** @var PHPUnit_Framework_MockObject_MockObject */
	private $fileHeaderMock;
	
	/** @var FileProtobufMessageStreamSource */
	private $source;
	
	public function testFlow() {
		$this->sinkMock->expects($this->once())
		  ->method('write')->with($this->callback(function (Stream $stream) {
			  return $stream->getContents() === 'test' && $stream->getSize() === 4;
		  }));
		$this->source->flow();
	}
	
	public function testPipe() {
		$otherSinkMock = $this->createMock(DataStreamSink::class);
		$otherSinkMock->expects($this->once())
		  ->method('onPipe')
		  ->with($this->identicalTo($this->source));
		
		$this->sinkMock->expects($this->once())
		  ->method('onUnpipe')
		  ->with($this->identicalTo($this->source));
		
		$this->source->pipe($otherSinkMock);
		$this->assertSame($otherSinkMock, $this->source->getSink());
	}
	
	public function testUnpipe() {
		$this->sinkMock->expects($this->once())
		  ->method('onUnpipe')
		  ->with($this->identicalTo($this->source));
		
		$this->source->unpipe();
		$this->assertInstanceOf(NullDataStreamSink::class, $this->source->getSink());
	}
	
	protected function setUp() {
		$this->root = vfsStream::setup('tmp', null, [
		  $this->filename => pack('N', 4).'test'
		]);
		$path = $this->root->url().'/'.$this->filename;
		$file = new \SplFileObject($path, 'r');
		
		$this->sinkMock = $this->createMock(DataStreamSink::class);
		
		$this->fileHeaderMock = $this->createMock(ProtobufMessagesFileHeader::class);
		$this->fileHeaderMock->method('getMessageSizeInfoPackFormat')->willReturn('N');
		$this->fileHeaderMock->method('getMessageSizeInfoBytes')->willReturn(4);
		$this->source = new FileProtobufMessageStreamSource($file, $this->fileHeaderMock);
		$this->source->pipe($this->sinkMock);
	}
}
