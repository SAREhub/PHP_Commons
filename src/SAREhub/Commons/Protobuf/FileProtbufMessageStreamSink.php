<?php

namespace SAREhub\Commons\Protobuf;

use Protobuf\Message;
use Protobuf\Stream;
use SAREhub\Commons\DataStream\DataStreamSink;
use SAREhub\Commons\DataStream\DataStreamSource;

/**
 * Class for writing Protocol Buffers messages to file.
 */
class FileProtbufMessageStreamSink implements DataStreamSink {
	
	/** @var \SplFileObject */
	protected $file;
	
	/** @var string */
	protected $messageSizeInfoPackFormat;
	
	/**
	 * @param \SplFileObject $file File handle for writing messages.
	 * @param string $messageSizeInfoPackFormat
	 */
	public function __construct(\SplFileObject $file, $messageSizeInfoPackFormat) {
		$this->file = $file;
		$this->messageSizeInfoPackFormat = $messageSizeInfoPackFormat;
	}
	
	/**
	 * @param Stream $data
	 */
	public function write($data) {
		$this->file->fwrite(
		  pack($this->messageSizeInfoPackFormat, $data->getSize()).
		  $data->getContents()
		);
	}
	
	public function onPipe(DataStreamSource $source) {
	}
	
	public function onUnpipe(DataStreamSource $source) {
	}
}