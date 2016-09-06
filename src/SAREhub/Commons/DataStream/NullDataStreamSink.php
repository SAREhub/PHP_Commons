<?php

namespace SAREhub\Commons\DataStream;


class NullDataStreamSink implements DataStreamSink {
	
	public function write($data) { }
	
	public function onPipe(DataStreamSource $source) { }
	
	public function onUnpipe(DataStreamSource $source) { }
}