<?php

namespace SAREhub\Commons\DataPipeline;

/**
 * Represents place where data can be write like file, database or socket
 */
abstract class DataSink implements Filter {
	
	public function pipe(Filter $output) {
		// noop for sink
	}
	
	public function unpipe(Filter $output = null) {
		// noop for sink
	}
	
	/**
	 * @param $data
	 */
	public abstract function write($data);
	
	public function onPipe(DataSource $source) {
		
	}
	
	public function onUnpipe(DataSource $source) {
		
	}
}