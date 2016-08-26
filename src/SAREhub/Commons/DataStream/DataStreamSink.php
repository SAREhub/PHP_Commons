<?php

namespace SAREhub\Commons\DataStream;

/**
 * Represents place where data can be write like file, database or socket
 */
interface DataStreamSink {
	
	/**
	 * @param mixed $data
	 */
	public function write($data);
}