<?php

/**
* @package   s9e\RegexpBuilder
* @copyright Copyright (c) 2016 The s9e Authors
* @license   http://www.opensource.org/licenses/mit-license.php The MIT License
*/
namespace s9e\RegexpBuilder\Passes;

abstract class AbstractPass implements PassInterface
{
	/**
	* @var bool Whether the current set of strings is optional
	*/
	protected $isOptional;

	/**
	* {@inheritdoc}
	*/
	public function run(array $strings)
	{
		$strings = $this->beforeRun($strings);
		if ($this->canRun($strings))
		{
			$strings = $this->runPass($strings);
		}
		$strings = $this->afterRun($strings);

		return $strings;
	}

	/**
	* Process the list of strings after the pass is run
	*
	* @param  array[] $strings
	* @return array[]
	*/
	protected function afterRun(array $strings)
	{
		if ($this->isOptional && $strings[0] !== [])
		{
			array_unshift($strings, []);
		}

		return $strings;
	}

	/**
	* Prepare the list of strings before the pass is run
	*
	* @param  array[] $strings
	* @return array[]
	*/
	protected function beforeRun(array $strings)
	{
		$this->isOptional = (isset($strings[0]) && $strings[0] === []);
		if ($this->isOptional)
		{
			array_shift($strings);
		}

		return $strings;
	}

	/**
	* Test whether this pass can be run on a given list of strings
	*
	* @param  array[] $strings
	* @return bool
	*/
	protected function canRun(array $strings)
	{
		return true;
	}

	/**
	* Run this pass on a list of strings
	*
	* @param  array[] $strings
	* @return array[]
	*/
	abstract protected function runPass(array $strings);

	/**
	* Test whether given string has an optional suffix
	*
	* @param  array $string
	* @return bool
	*/
	protected function hasOptionalSuffix(array $string)
	{
		$suffix = end($string);

		return (is_array($suffix) && $suffix[0] === []);
	}

	/**
	* Test whether given string contains a single alternations made of single values
	*
	* @param  array $string
	* @return bool
	*/
	protected function isCharacterClassString(array $string)
	{
		return ($this->isSingleAlternationString($string) && $this->isSingleCharStringList($string[0]));
	}

	/**
	* Test whether given string contains one single element that is an alternation
	*
	* @param  array
	* @return bool
	*/
	protected function isSingleAlternationString(array $string)
	{
		return (count($string) === 1 && is_array($string[0]));
	}

	/**
	* Test whether given string contains a single character value
	*
	* @param  array $string
	* @return bool
	*/
	protected function isSingleCharString(array $string)
	{
		return (count($string) === 1 && !is_array($string[0]));
	}

	/**
	* Test whether given list of strings contains nothing but single-char strings
	*
	* @param  array[] $strings
	* @return bool
	*/
	protected function isSingleCharStringList(array $strings)
	{
		foreach ($strings as $string)
		{
			if (!$this->isSingleCharString($string))
			{
				return false;
			}
		}

		return true;
	}
}