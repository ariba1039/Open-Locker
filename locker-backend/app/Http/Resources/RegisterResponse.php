<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RegisterResponse extends JsonResource {
    public function toArray( Request $request ) {
        return [
            "token" => $this->token,
            "name"  => $this->name
        ];
    }

}
