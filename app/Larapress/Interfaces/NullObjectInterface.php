<?php namespace Larapress\Interfaces;

use InvalidArgumentException;

interface NullObjectInterface {

	/**
	 * Check if a given variable contains a valid mail recipient
	 *
	 * @param mixed $var
	 *
	 * @throws InvalidArgumentException
	 * @return void
	 */
	public function validateMailRecipientData($var);

	/**
	 * Check if a given variable contains a string
	 *
	 * @param mixed $var
	 *
	 * @throws InvalidArgumentException
	 * @return void
	 */
	public function validateVarIsString($var);

	/**
	 * Check if a given variable contains an array
	 *
	 * @param mixed $var
	 * @throws InvalidArgumentException
	 */
	public function validateVarIsArray($var);

}