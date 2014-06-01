<?php namespace Larapress\Services;

use InvalidArgumentException;
use Larapress\Interfaces\NullObjectInterface;

class NullObject implements NullObjectInterface {

	/**
	 * Check if a given variable contains a valid mail recipient
	 *
	 * @param mixed $var
	 * @throws InvalidArgumentException
	 * @return void
	 */
	public function validateMailRecipientData($var)
	{
		if ( ! is_array($var) )
		{
			throw new InvalidArgumentException;
		}
		elseif ( ! array_key_exists('address', $var) or ! array_key_exists('name', $var) )
		{
			throw new InvalidArgumentException;
		}
	}

	/**
	 * Check if a given variable contains valid mail view details
	 *
	 * @param mixed $var
	 * @throws InvalidArgumentException
	 * @return void
	 */
	public function validateMailViewDetails($var)
	{
		if ( ! is_array($var) and ! is_string($var) )
		{
			throw new InvalidArgumentException;
		}
	}

	/**
	 * Check if a given variable contains a string
	 *
	 * @param string $var
	 * @throws InvalidArgumentException
	 * @return void
	 */
	public function validateVarIsString($var)
	{
		if ( ! is_string($var) )
		{
			throw new InvalidArgumentException;
		}
	}

	/**
	 * Check if a given variable contains an array
	 *
	 * @param mixed $var
	 * @throws InvalidArgumentException
	 * @return void
	 */
	public function validateVarIsArray($var)
	{
		if ( ! is_array($var) )
		{
			throw new InvalidArgumentException;
		}
	}

}
