<?php

class Validate
{
	public function presence($value)
	{
		return !empty($value)? true : false;
	}

	public function maxLength($value,$length)
	{
		(mb_strlen($value,"UTF8") <= $length)? true : false;
	}

	public function minLength($value,$length)
	{
		(mb_strlen($value,"UTF8") >= $length)? true : false;
	}

}