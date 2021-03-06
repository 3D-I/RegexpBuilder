<?php

/**
* @package   s9e\RegexpBuilder
* @copyright Copyright (c) 2016 The s9e Authors
* @license   http://www.opensource.org/licenses/mit-license.php The MIT License
*/
namespace s9e\RegexpBuilder\Input;

class Bytes extends BaseImplementation
{
	/**
	* {@inheritdoc}
	*/
	public function split($string)
	{
		if ($string === '')
		{
			return [];
		}

		return array_map('ord', str_split($string, 1));
	}
}