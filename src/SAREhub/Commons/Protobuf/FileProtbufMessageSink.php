<?php

namespace SAREhub\Commons\Protobuf;

use Protobuf\Stream;
use SAREhub\Commons\DataPipeline\DataSink;

/**
 * Class for writing Protocol Buffers messages to file.
 */
class FileProtbufMessageSink extends DataSink {
	
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
	
}