<?php

class Response
{
	public function setResponseCode($code)
	{
		return http_response_code($code);
	}
}