<?php

namespace App\Exceptions;

use Log;
use Exception;
use Throwable;

class MovieApiException extends Exception
{
    protected $message = 'An error occurred while fetching movie data from the API';
    protected $code = 400;
    const API_DOWN = 1;
    const PAGINATION_OUT_OF_RANGE = 2;


    public function __construct($message = "", $code = 0, Throwable $previous = null) {
        parent::__construct($message, $code, $previous);
    }

    public function render($request)
    {
        $route='movies.trending';
        switch ($this->getCode()) {
            case self::API_DOWN:
                $message = "The MovieDB API is currently down.";
                break;
            case self::PAGINATION_OUT_OF_RANGE:
                $message = "The requested page is out of range, or prameter is wrong";
                break;
            case '404':
                $message = "Movie doesn't exist";
                break;
            default:
                $message = "An unknown error occurred.CODE : " . $this->getCode();
                $route='dashboard';
        }

        if ($request->expectsJson()) {
            return response()->json(['error' => $message], 400);
        }
        return redirect()->route($route)->withErrors($message);
    }

    public function report()
    {
        Log::error("Movie API error: {$this->getMessage()} (Code: {$this->getCode()})");
    }
}
