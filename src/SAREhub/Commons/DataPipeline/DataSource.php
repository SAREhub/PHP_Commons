<?php

namespace SAREhub\Commons\DataPipeline;

/**
 * Represents place where data come from like file, database or socket
 */
interface DataSource {
	
	/**
	 * @param Filter $output
	 * @return Filter
	 */
	public function pipe(Filter $output);
	
	/**
	 * @param Filter|null $output
	 * @return Filter
	 */
	public function unpipe(Filter $output = null);
	
}
