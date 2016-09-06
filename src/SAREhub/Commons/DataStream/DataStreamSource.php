<?php

namespace SAREhub\Commons\DataStream;

use SAREhub\Commons\DataStream\DataStreamSink;

/**
 * Represents place where data come from like file, database or socket
 */
interface DataStreamSource {
	
	public function pipe(DataStreamSink $sink);
	
	public function unpipe();
}
