<?php

namespace App;

class DatabaseMapper
{
    private DatabaseGateway $gateway;

    public function __construct(DatabaseGateway $gateway)
    {
        $this->gateway = $gateway;
    }

    public function findAll(): array
    {
        return $this->gateway->listDbs();
    }
}
