<?php

namespace SAREhub\Commons\DataPipeline;

class Transformer implements Filter {
	
	protected $transformer;
	protected $output;
	
	public function __construct(callable $transfromer) {
		$this->transformer = $transfromer;
		$this->output = new NullSink();
	}
	
	public function write($data) {
		$transfromer = $this->transformer;
		$this->output->write($transfromer($data));
	}
	
	public function pipe(Filter $output) {
		$this->unpipe();
		$this->output = $output;
		$this->output->onPipe($this);
		return $this->output;
	}
	
	public function unpipe(Filter $output = null) {
		$this->output->onUnpipe($this);
		$this->output = new NullSink();
	}
	
	public function onPipe(DataSource $source) {
		
	}
	
	public function onUnpipe(DataSource $source) {
		
	}
}