<?php

namespace App\Http\Requests\RoomPrice;

use App\Enums\PriceType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class UpdateRoomPriceRequest extends FormRequest
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
            'room_type_id' => ['sometimes', 'exists:room_types,id'],
            'weekday' => ['sometimes', 'integer', 'min:0'],
            'weekend' => ['sometimes', 'integer', 'min:0'],
            'type' => ['sometimes', new Enum(PriceType::class)],
            'promotion_name' => [
                'sometimes',
                'string',
                'required_if:type,' . PriceType::PROMOTION->value,
            ],
            'effective_from' => ['sometimes', 'date', 'after_or_equal:today'],
            'effective_to' => [
                'sometimes',
                'nullable',
                'date',
                'after:effective_from',
                'required_if:type,' . PriceType::PROMOTION->value,
            ],
        ];
    }
}
