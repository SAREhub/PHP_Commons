<?php

namespace SAREhub\Commons\Protobuf;

use org\bovigo\vfs\vfsStream;
use org\bovigo\vfs\vfsStreamDirectory;
use PHPUnit\Framework\TestCase;
use Protobuf\Stream;

class FileProtbufMessageSinkTest extends TestCase {
	
	/** @var vfsStreamDirectory */
	private $root;
	private $filename = 'test.fpe';
	/** @var FileProtbufMessageSink */
	private $sink;
	
	protected function setUp() {
		$this->root = vfsStream::setup('tmp');
		$path = $this->root->url().'/'.$this->filename;
		$file = new \SplFileObject($path, 'a');
		$this->sink = new FileProtbufMessageSink($file, 'N');
	}
	
	public function testWrite() {
		$this->sink->write(Stream::fromString("test"));
		$packedMessage = pack('N', 4).'test';
		$this->assertEquals($packedMessage, $this->root->getChild($this->filename)->getContent());
	}
	
}
