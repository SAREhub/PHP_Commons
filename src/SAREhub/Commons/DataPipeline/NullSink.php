<?php

namespace SAREhub\Commons\DataPipeline;

/**
 * Represents linux dev/null All written data will be lost.
 */
class NullSink extends DataSink {
	
	public function write($data) {
		
	}
	
}