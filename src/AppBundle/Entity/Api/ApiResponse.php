<?php

namespace AppBundle\Entity\Api;


/**
 * ApiResponse
 */
class ApiResponse
{
    public $code;
    public $message;
    public $data;

    /**
     * @param int $code
     * @param string $message
     */
    public function __construct($code = 200, $message = 'success')
    {
        $this->code = $code;
        $this->message = $message;
        $this->data = [];
    }

    /**
     * @param int $code
     * @return ApiResponse
     */
    public function setCode($code)
    {
        $this->code = $code;

        return $this;
    }

    /**
     * @param mixed $message
     * @return ApiResponse
     */
    public function setMessage($message)
    {
        $this->message = $message;

        return $this;
    }

    /**
     * @param $data
     * @return $this
     */
    public function setData($data)
    {
        $this->data = $data;

        return $this;
    }

}

