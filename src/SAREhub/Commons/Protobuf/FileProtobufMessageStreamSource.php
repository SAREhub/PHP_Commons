<?php

namespace SAREhub\Commons\Protobuf;

use Protobuf\Stream;
use SAREhub\Commons\DataStream\DataStreamSink;
use SAREhub\Commons\DataStream\DataStreamSource;
use SAREhub\Commons\DataStream\NullDataStreamSink;

class FileProtobufMessageStreamSource implements DataStreamSource {
	
	/** @var \SplFileObject */
	protected $file;
	
	protected $sizeInfoBytes;
	
	/** @var string */
	protected $sizeInfoPackFormat;
	
	/** @var DataStreamSink */
	protected $sink;
	
	public function __construct(\SplFileObject $file, ProtobufMessagesFileHeader $header) {
		$this->file = $file;
		$this->sizeInfoPackFormat = $header->getMessageSizeInfoPackFormat();
		$this->sizeInfoBytes = $header->getMessageSizeInfoBytes();
		$this->sink = new NullDataStreamSink();
	}
	
	public function flow() {
		$size = unpack($this->sizeInfoPackFormat, $this->file->fread($this->sizeInfoBytes))[1];
		$data = $this->file->fread($size);
		$this->sink->write(Stream::fromString($data, $size));
	}
	
	public function pipe(DataStreamSink $sink) {
		$this->unpipe();
		$this->sink = $sink;
		$this->sink->onPipe($this);
	}
	
	public function unpipe(DataStreamSink $sink = null) {
		$this->sink->onUnpipe($this);
		$this->sink = new NullDataStreamSink();
	}
	
	public function getSink() {
		return $this->sink;
	}
}