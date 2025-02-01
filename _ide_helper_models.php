<?php

// @formatter:off
// phpcs:ignoreFile
/**
 * A helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */


namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string $name
 * @property int|null $room_type_id
 * @property string $room_type_name
 * @property string $room_type_code
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\RoomType|null $type
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Room newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Room newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Room query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Room whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Room whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Room whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Room whereRoomTypeCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Room whereRoomTypeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Room whereRoomTypeName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Room whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperRoom {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property int $weekday
 * @property int $weekend
 * @property \App\Enums\PriceType $type
 * @property string|null $promotion_name
 * @property \Illuminate\Support\Carbon $effective_from
 * @property \Illuminate\Support\Carbon|null $effective_to
 * @property int|null $room_type_id
 * @property string $room_type_name
 * @property string $room_type_code
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\RoomType|null $roomType
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RoomPrice active(?\Carbon\Carbon $date = null)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RoomPrice future()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RoomPrice newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RoomPrice newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RoomPrice promotional()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RoomPrice query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RoomPrice standard()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RoomPrice whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RoomPrice whereEffectiveFrom($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RoomPrice whereEffectiveTo($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RoomPrice whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RoomPrice wherePromotionName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RoomPrice whereRoomTypeCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RoomPrice whereRoomTypeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RoomPrice whereRoomTypeName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RoomPrice whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RoomPrice whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RoomPrice whereWeekday($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RoomPrice whereWeekend($value)
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperRoomPrice {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string $name
 * @property string $code
 * @property string|null $description
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\RoomPrice> $prices
 * @property-read int|null $prices_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Room> $rooms
 * @property-read int|null $rooms_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RoomType newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RoomType newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RoomType query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RoomType whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RoomType whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RoomType whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RoomType whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RoomType whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|RoomType whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperRoomType {}
}

namespace App\Models{
/**
 * 
 *
 * @property int $id
 * @property string $name
 * @property string $email
 * @property \App\Enums\Role $role
 * @property string|null $image
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property string $password
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\TFactory|null $use_factory
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<int, \Illuminate\Notifications\DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 * @method static \Database\Factories\UserFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereRole($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperUser {}
}

