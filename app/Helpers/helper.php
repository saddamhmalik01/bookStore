<?php

use Illuminate\Http\JsonResponse as JSON;

function jsonResponse($response): JSON
{
    return response()->json($response);
}

function successResponse($message = null, $data = null): JSON
{
    $response['success'] = true;

    if (!is_null($message)) {
        $response['message'] = $message;
    }

    if (!is_null($data)) {
        $response['data'] = $data;
    }

    return jsonResponse($response);
}

function errorResponse($message = null, $statusCode = 400): JSON
{
    $response['success'] = false;

    if (!is_null($message)) {
        $response['message'] = $message;
    }

    if (!is_null($statusCode)) {
        $response['statusCode'] = $statusCode;
    }

    return jsonResponse($response);
}


function exceptionResponse(Exception $exception, $trace = false, $getResponseAsString = false, $code = null): JSON|string
{
    $ex['success'] = false;
    $ex['exception'] = [
        'message' => $exception->getMessage(),
        'file' => $exception->getFile(),
        'line' => $exception->getLine(),
    ];

    if (!is_null($code)) {
        $ex['exception']['code'] = $exception->getCode();
    }

    if ($trace) {
        $ex['exception']['trace'] = $exception->getTraceAsString();
    }

    if ($getResponseAsString) {
        $e = $ex['exception'];
        return $e['message'] . ' in ' . $e['file'] . ' at line ' . $e['line'];
    }

    return jsonResponse($ex);
}
