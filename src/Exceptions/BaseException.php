<?php

namespace SoliDry\Exceptions;

use Illuminate\Http\JsonResponse;
use SoliDry\Extension\JSONApiInterface;
use SoliDry\Helpers\Json;

class BaseException extends \Exception
{

    /**
     * BaseException constructor.
     *
     * @param $message
     * @param int $code
     */
    public function __construct($message, $code = 0)
    {
        parent::__construct($message, $code);
    }

    public function __toString()
    {
        parent::__toString();
        return (new Json)->getErrors([
            'code'    => $this->getCode(),
            'message' => $this->getMessage(),
            'file'    => $this->getFile(),
            'line'    => $this->getLine(),
        ]);
    }

    /**
     * Render the exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request $request
     * @return JsonResponse
     */
    public function render($request) : JsonResponse
    {
        return response()->json(
            [
                JSONApiInterface::CONTENT_ERRORS => [
                    [
                        'code'    => $this->getCode(),
                        'message' => $this->getMessage(),
                        'file'    => $this->getFile(),
                        'line'    => $this->getLine(),
                        'uri'     => $request->getUri(),
                        'meta'    => $this->getTraceAsString(),
                    ],
                ]
            ]
        );
    }
}
