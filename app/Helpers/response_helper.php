<?php

if (!function_exists('response_json')) {
    function response_json($data, int $status = 200, string $message = '') {
        return \Config\Services::response()->setJSON([
            'status' => $status,
            'message' => $message,
            'data' => $data,
            'timestamp' => date('Y-m-d H:i:s')
        ])->setStatusCode($status);
    }
}