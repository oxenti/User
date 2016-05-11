<?php
namespace User\Network\Exception;

use Cake\Core\Exception\Exception;

class UnauthorizedException extends Exception
{
    /**
     * Constructor
     *
     * @param string|null $message If no message is given 'Bad Request' will be the message
     * @param int $code Status code, defaults to 400
     */
    public function __construct($message = null, $code = 401)
    {
        $this->responseHeader('Access-Control-Allow-Origin', '*');
        if (empty($message)) {
            $message = 'Bad Request';
        }

        parent::__construct($message, $code);
    }
}
