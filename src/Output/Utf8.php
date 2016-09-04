<?php

/**
* @package   s9e\RegexpBuilder
* @copyright Copyright (c) 2016 The s9e Authors
* @license   http://www.opensource.org/licenses/mit-license.php The MIT License
*/
namespace s9e\RegexpBuilder\Output;

use InvalidArgumentException;

class Utf8 implements OutputInterface
{
	/**
	* {@inheritdoc}
	*/
	public function output($value)
	{
		if ($value < 0x80)
		{
			return chr($value);
		}
		if ($value < 0x800)
		{
			return chr(0xC0 | ($value >> 6)) . chr(0x80 | ($value & 0x3F));
		}
		if ($value < 0x10000)
		{
			return chr(0xE0 | ($value >> 12))
			     . chr(0x80 | (($value >> 6) & 0x3F))
			     . chr(0x80 | ($value & 0x3F));
		}
		if ($value < 0x110000)
		{
			return chr(0xF0 | ($value >> 18))
			     . chr(0x80 | (($value >> 12) & 0x3F))
			     . chr(0x80 | (($value >> 6) & 0x3F))
			     . chr(0x80 | ($value & 0x3F));
		}
		throw new InvalidArgumentException('Invalid UTF-8 codepoint 0x' . dechex($value));
	}
}