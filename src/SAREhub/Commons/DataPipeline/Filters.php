<?php

namespace SAREhub\Commons\DataPipeline;


class Filters {
	
	public static function transform(callable $transfromer) {
		return new Transformer($transfromer);
	}
}