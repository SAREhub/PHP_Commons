<?php
use SAREhub\Commons\DataStream;

/**
 * Represents place where data come from like file, database or socket
 */
interface DataStreamSource {
	
	public function pipe(DataStreamSink $sink);
}
