<?php

namespace App\Http\Requests;

class UpdateTransaccionRequest extends StoreTransaccionRequest
{
    protected function prepareForValidation(): void
    {
        $this->merge([
            '_transaccion_id' => $this->route('transaccion')?->id,
        ]);

        parent::prepareForValidation();
    }
}
