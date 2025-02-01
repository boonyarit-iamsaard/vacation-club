<?php

namespace App\Http\Requests\RoomPrice;

use App\Enums\PriceType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class StoreRoomPriceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'room_type_id' => ['required', 'exists:room_types,id'],
            'weekday' => ['required', 'integer', 'min:0'],
            'weekend' => ['required', 'integer', 'min:0'],
            'type' => ['required', new Enum(PriceType::class)],
            'promotion_name' => ['required_if:type,' . PriceType::PROMOTION->value, 'string'],
            'effective_from' => ['required', 'date', 'after_or_equal:today'],
            'effective_to' => [
                'nullable',
                'date',
                'after:effective_from',
                'required_if:type,' . PriceType::PROMOTION->value,
            ],
        ];
    }
}
