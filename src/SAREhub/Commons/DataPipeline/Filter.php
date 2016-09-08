<?php

namespace SAREhub\Commons\DataPipeline;

/**
 * Transfrom, process, filter data and can output it to next Filter.
 */
interface Filter extends DataSource {
	
	/**
	 * @param $data
	 */
	public function write($data);
	
	/**
	 * @param DataSource $source
	 * @return
	 */
	public function onPipe(DataSource $source);
	
	public function onUnpipe(DataSource $source);
}