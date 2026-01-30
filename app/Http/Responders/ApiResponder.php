<?php

namespace App\Http\Responders;

use Illuminate\Http\JsonResponse;

class ApiResponder
{
    protected $data;
    protected $transformer;
    protected $message;
    protected $statusCode = 200;

    public function success($data, $transformer = null)
    {
        $this->data = $data;
        $this->transformer = $transformer;
        return $this;
    }

    public function error($message, $statusCode = 400)
    {
        $this->message = $message;
        $this->statusCode = $statusCode;
        return $this;
    }

    public function message($message)
    {
        $this->message = $message;
        return $this;
    }

    public function statusCode($code)
    {
        $this->statusCode = $code;
        return $this;
    }

    public function respond(): JsonResponse
    {
        if ($this->data !== null && $this->transformer) {
            $transformedData = $this->transform($this->data);

            return response()->json([
                'success' => true,
                'data' => $transformedData,
                'message' => $this->message ?? 'Request successful',
            ], $this->statusCode);
        }

        if ($this->data !== null) {
            return response()->json([
                'success' => true,
                'data' => $this->data,
                'message' => $this->message ?? 'Request successful',
            ], $this->statusCode);
        }

        return response()->json([
            'success' => false,
            'message' => $this->message ?? 'Request failed',
        ], $this->statusCode);
    }

    protected function transform($data)
    {
        if (is_null($data)) {
            return null;
        }

        if ($data instanceof \Illuminate\Database\Eloquent\Collection || is_array($data)) {
            return array_map(function ($item) {
                return call_user_func($this->transformer, $item);
            }, $data instanceof \Illuminate\Database\Eloquent\Collection ? $data->all() : $data);
        }

        return call_user_func($this->transformer, $data);
    }
}
