<?php

namespace App\Message;

final class UserCreated
{
    public function __construct(protected array $data)
    {
    }

    public function getData(): array
    {
        return $this->data;
    }
}
