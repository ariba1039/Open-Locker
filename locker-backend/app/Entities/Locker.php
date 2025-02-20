<?php

namespace App\Entities;

class Locker
{
    public function __construct(
        public string $id,
        public int $modbusAddress,
        public int $coilRegister,
        public ?int $statusRegister = null
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            id: $data['id'],
            modbusAddress: $data['modbus_address'],
            coilRegister: $data['coil_register'],
            statusRegister: $data['status_register'] ?? null
        );
    }
}
