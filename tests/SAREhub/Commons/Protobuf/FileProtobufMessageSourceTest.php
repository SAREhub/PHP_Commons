<?php

namespace SAREhub\Commons\Protobuf;

use org\bovigo\vfs\vfsStream;
use org\bovigo\vfs\vfsStreamDirectory;
use PHPUnit\Framework\TestCase;
use PHPUnit_Framework_MockObject_MockObject;
use Protobuf\Stream;
use SAREhub\Commons\DataPipeline\Filter;

class FileProtobufMessageSourceTest extends TestCase {
	
	/** @var vfsStreamDirectory */
	private $root;
	private $filename = 'test.fpe';
	
	/** @var PHPUnit_Framework_MockObject_MockObject */
	private $filterMock;
	
	/** @var PHPUnit_Framework_MockObject_MockObject */
	private $fileHeaderMock;
	
	/** @var FileProtobufMessageSource */
	private $source;
	
	public function testFlow() {
		$this->filterMock->expects($this->once())
		  ->method('write')->with($this->callback(function (Stream $stream) {
			  return $stream->getContents() === 'test' && $stream->getSize() === 4;
		  }));
		$this->source->flow();
	}
	
	public function testPipe() {
		$otherFilterMock = $this->createMock(Filter::class);
		$otherFilterMock->expects($this->once())
		  ->method('onPipe')
		  ->with($this->identicalTo($this->source));
		
		$this->filterMock->expects($this->once())
		  ->method('onUnpipe')
		  ->with($this->identicalTo($this->source));
		
		$this->source->pipe($otherFilterMock);
	}
	
	public function testUnpipe() {
		$this->filterMock->expects($this->once())
		  ->method('onUnpipe')
		  ->with($this->identicalTo($this->source));
		
		$this->source->unpipe();
	}
	
	protected function setUp() {
		$this->root = vfsStream::setup('tmp', null, [
		  $this->filename => pack('N', 4).'test'
		]);
		$path = $this->root->url().'/'.$this->filename;
		$file = new \SplFileObject($path, 'r');
		
		$this->filterMock = $this->createMock(Filter::class);
		
		$this->fileHeaderMock = $this->createMock(ProtobufMessagesFileHeader::class);
		$this->fileHeaderMock->method('getMessageSizeInfoPackFormat')->willReturn('N');
		$this->fileHeaderMock->method('getMessageSizeInfoBytes')->willReturn(4);
		$this->source = new FileProtobufMessageSource($file, $this->fileHeaderMock);
		$this->source->pipe($this->filterMock);
	}
}
