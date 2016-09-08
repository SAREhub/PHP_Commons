<?php

namespace SAREhub\Commons\Protobuf;

use Protobuf\Stream;
use SAREhub\Commons\DataPipeline\DataSource;
use SAREhub\Commons\DataPipeline\Filter;
use SAREhub\Commons\DataPipeline\NullSink;

class FileProtobufMessageSource implements DataSource {
	
	/** @var \SplFileObject */
	protected $file;
	
	protected $sizeInfoBytes;
	
	/** @var string */
	protected $sizeInfoPackFormat;
	
	/** @var Filter */
	protected $output;
	
	public function __construct(\SplFileObject $file, ProtobufMessagesFileHeader $header) {
		$this->file = $file;
		$this->sizeInfoPackFormat = $header->getMessageSizeInfoPackFormat();
		$this->sizeInfoBytes = $header->getMessageSizeInfoBytes();
		$this->output = new NullSink();
	}
	
	public function flow() {
		$size = unpack($this->sizeInfoPackFormat, $this->file->fread($this->sizeInfoBytes))[1];
		$data = $this->file->fread($size);
		$this->output->write(Stream::fromString($data, $size));
	}
	
	public function pipe(Filter $output) {
		$this->unpipe();
		$this->output = $output;
		$this->output->onPipe($this);
	}
	
	public function unpipe(Filter $output = null) {
		$this->output->onUnpipe($this);
		$this->output = new NullSink();
	}
}