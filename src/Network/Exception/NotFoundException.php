<?php
namespace User\Network\Exception;

use Cake\Core\Exception\Exception;

class NotFoundException extends Exception
{
    /**
     * Constructor
     *
     * @param string|null $message If no message is given 'Not Found' will be the message
     * @param int $code Status code, defaults to 404
     */
    public function __construct($message = null, $code = 404)
    {
        $this->responseHeader('Access-Control-Allow-Origin', '*');
        if (empty($message)) {
            $message = 'Bad Request';
        }

        parent::__construct($message, $code);
    }
}
