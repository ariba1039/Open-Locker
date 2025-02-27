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

    /**
     * @param  array{id: string, modbus_address: int, coil_register: int, status_register?: int|null}  $data
     */
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
