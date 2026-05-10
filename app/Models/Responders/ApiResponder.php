<?php

namespace App\Models\Responders;

use Illuminate\Http\JsonResponse;

class ApiResponder
{
    protected $data;
    protected $transformer;
    protected $message;
    protected $statusCode = 200;
    protected bool $successful = false;

    public function success($data, $transformer = null)
    {
        $this->data = $data;
        $this->transformer = $transformer;
        $this->successful = true;
        return $this;
    }

    public function error($message, $statusCode = 400)
    {
        $this->message = $message;
        $this->statusCode = $statusCode;
        $this->successful = false;
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
        if ($this->successful && $this->data !== null && $this->transformer) {
            $transformedData = $this->transform($this->data);

            return response()->json([
                'success' => true,
                'data' => $transformedData,
                'message' => $this->message ?? 'Request successful',
            ], $this->statusCode);
        }

        if ($this->successful) {
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

        if ($data instanceof \Illuminate\Database\Eloquent\Collection || $data instanceof \Illuminate\Support\Collection || is_array($data)) {
            return array_map(function ($item) {
                return call_user_func($this->transformer, $item);
            }, $data instanceof \Illuminate\Support\Collection ? $data->all() : $data);
        }

        return call_user_func($this->transformer, $data);
    }
}
