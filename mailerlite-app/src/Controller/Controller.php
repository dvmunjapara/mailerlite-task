<?php

namespace App\Controller;

class Controller
{
    /**
     * @param array<mixed> $data
     * @param int $status
     * @return void
     */
    public function json(array $data, int $status = 200) : void
    {
        header('Content-Type: application/json');
        http_response_code($status);

        echo json_encode($data);
    }
}
