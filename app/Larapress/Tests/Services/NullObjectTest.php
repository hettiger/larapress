<?php namespace Larapress\Tests\Services;

use InvalidArgumentException;
use Larapress\Services\NullObject;
use Larapress\Tests\TestCase;

class NullObjectTest extends TestCase {

	protected function getNullObjectInstance()
	{
		return new NullObject();
	}

	/**
	 * @test validateMailRecipientData() only accepts arrays
	 * @expectedException InvalidArgumentException
	 */
	public function validateMailRecipientData_only_accepts_arrays()
	{
		$nullObject = $this->getNullObjectInstance();

		$nullObject->validateMailRecipientData('foo');
	}

	/**
	 * @test validateMailRecipientData() requires specific array keys
	 * @expectedException InvalidArgumentException
	 */
	public function validateMailRecipientData_requires_specific_array_keys()
	{
		$nullObject = $this->getNullObjectInstance();

		$nullObject->validateMailRecipientData(array('foo' => 'bar'));
	}

	/**
	 * @test validateMailRecipientData() requires address and name keys
	 * @expectedException InvalidArgumentException
	 */
	public function validateMailRecipientData_requires_address_and_name_keys()
	{
		$nullObject = $this->getNullObjectInstance();

		$nullObject->validateMailRecipientData(array('address' => 'foo'));
	}

	/**
	 * @test validateMailRecipientData() satisfied
	 */
	public function validateMailRecipientData_satisfied()
	{
		$nullObject = $this->getNullObjectInstance();

		$nullObject->validateMailRecipientData(array('address' => 'foo', 'name' => 'bar'));
	}

	/**
	 * @test validateMailViewDetails() requires array or string
	 * @expectedException InvalidArgumentException
	 */
	public function validateMailViewDetails_requires_array_or_string()
	{
		$nullObject = $this->getNullObjectInstance();

		$nullObject->validateMailViewDetails(1);
	}

	/**
	 * @test validateMailViewDetails() satisfy with array
	 */
	public function validateMailViewDetails_satisfy_with_array()
	{
		$nullObject = $this->getNullObjectInstance();

		$nullObject->validateMailViewDetails(array('foo'));
	}

	/**
	 * @test validateMailViewDetails() satisfy with string
	 */
	public function validateMailViewDetails_satisfy_with_string()
	{
		$nullObject = $this->getNullObjectInstance();

		$nullObject->validateMailViewDetails('foo');
	}

	/**
	 * @test validateVarIsString() requires a string
	 * @expectedException InvalidArgumentException
	 */
	public function validateVarIsString_requires_a_string()
	{
		$nullObject = $this->getNullObjectInstance();

		$nullObject->validateVarIsString(1);
	}

	/**
	 * @test validateVarIsString() satisfied
	 */
	public function validateVarIsString_satisfied()
	{
		$nullObject = $this->getNullObjectInstance();

		$nullObject->validateVarIsString('foo');
	}

	/**
	 * @test validateVarIsArray() requires an array
	 * @expectedException InvalidArgumentException
	 */
	public function validateVarIsArray_requires_an_array()
	{
		$nullObject = $this->getNullObjectInstance();

		$nullObject->validateVarIsArray(1);
	}

	/**
	 * @test validateVarIsArray() satisfy with an array
	 */
	public function validateVarIsArray_satisfy_with_an_array()
	{
		$nullObject = $this->getNullObjectInstance();

		$nullObject->validateVarIsArray(array('foo'));
	}

}
