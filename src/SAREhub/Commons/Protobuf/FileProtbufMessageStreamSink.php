<?php

namespace SAREhub\Commons\Protobuf;

use Protobuf\Message;
use Protobuf\Stream;
use SAREhub\Commons\DataStream\DataStreamSink;

/**
 * Class for writing Protocol Buffers messages to file.
 */
class FileProtbufMessageStreamSink implements DataStreamSink {
	
	/** @var \SplFileObject */
	private $file;
	
	/** @var ProtobufMessagesFileHeader */
	private $fileHeader;
	
	/** @var string */
	private $messageSizeInfoPackFormat;
	
	/**
	 * @param \SplFileObject $file File handle for writing messages.
	 * @param ProtobufMessagesFileHeader $fileHeader
	 */
	public function __construct(\SplFileObject $file, ProtobufMessagesFileHeader $fileHeader) {
		$this->file = $file;
		$this->fileHeader = $fileHeader;
		$this->file->fwrite($this->fileHeader->toBinaryString());
		$this->messageSizeInfoPackFormat = $fileHeader->getMessageSizeInfoPackFormat();
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