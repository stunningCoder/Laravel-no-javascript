<?php

// @formatter:off
/**
 * A helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */


namespace App{
/**
 * App\Offer
 *
 * @property string $id
 * @property string $product_id
 * @property int $min_quantity
 * @property float $price
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int $deleted
 * @property-read string $dollars
 * @property-read string $local_price
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Purchase[] $purchases
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Offer newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Offer newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Offer query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Offer whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Offer whereDeleted($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Offer whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Offer whereMinQuantity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Offer wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Offer whereProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Offer whereUpdatedAt($value)
 */
	class Offer extends \Eloquent {}
}

namespace App{
/**
 * App\Shipping
 *
 * @property string $id
 * @property string $product_id
 * @property string $name
 * @property float $price
 * @property string $duration
 * @property int $from_quantity
 * @property int $to_quantity
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int $deleted
 * @property-read string $dollars
 * @property-read string $local_value
 * @property-read mixed $long_name
 * @property-read \App\PhysicalProduct $physicalProduct
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Shipping newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Shipping newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Shipping query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Shipping whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Shipping whereDeleted($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Shipping whereDuration($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Shipping whereFromQuantity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Shipping whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Shipping whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Shipping wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Shipping whereProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Shipping whereToQuantity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Shipping whereUpdatedAt($value)
 */
	class Shipping extends \Eloquent {}
}

namespace App{
/**
 * App\DisputeMessage
 *
 * @property string $id
 * @property string $message
 * @property string $author_id
 * @property string $dispute_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\User $author
 * @property-read \App\Dispute|null $dispute
 * @property-read string $time_ago
 * @method static \Illuminate\Database\Eloquent\Builder|\App\DisputeMessage newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\DisputeMessage newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\DisputeMessage query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\DisputeMessage whereAuthorId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\DisputeMessage whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\DisputeMessage whereDisputeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\DisputeMessage whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\DisputeMessage whereMessage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\DisputeMessage whereUpdatedAt($value)
 */
	class DisputeMessage extends \Eloquent {}
}

namespace App{
/**
 * App\PGPKey
 *
 * @property int $id
 * @property string $user_id
 * @property string $key
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|\App\PGPKey newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\PGPKey newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\PGPKey query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\PGPKey whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\PGPKey whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\PGPKey whereKey($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\PGPKey whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\PGPKey whereUserId($value)
 */
	class PGPKey extends \Eloquent {}
}

namespace App{
/**
 * App\Permission
 *
 * @property int $id
 * @property string $user_id
 * @property string $name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Permission newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Permission newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Permission query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Permission whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Permission whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Permission whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Permission whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Permission whereUserId($value)
 */
	class Permission extends \Eloquent {}
}

namespace App{
/**
 * App\Log
 *
 * @property string $id
 * @property string $user_id
 * @property string $type
 * @property string|null $performed_id
 * @property string|null $performed_on
 * @property string $description
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Log newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Log newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Log query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Log whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Log whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Log whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Log wherePerformedId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Log wherePerformedOn($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Log whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Log whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Log whereUserId($value)
 */
	class Log extends \Eloquent {}
}

namespace App{
/**
 * App\Image
 *
 * @property string $id
 * @property string $product_id
 * @property string $image
 * @property int $first
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Product $product
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Image newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Image newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Image query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Image whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Image whereFirst($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Image whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Image whereImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Image whereProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Image whereUpdatedAt($value)
 */
	class Image extends \Eloquent {}
}

namespace App{
/**
 * App\Wishlist
 *
 * @property string $user_id
 * @property string $product_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Product $product
 * @property-read \App\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Wishlist newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Wishlist newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Wishlist query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Wishlist whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Wishlist whereProductId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Wishlist whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Wishlist whereUserId($value)
 */
	class Wishlist extends \Eloquent {}
}

namespace App{
/**
 * App\TicketReply
 *
 * @property string $id
 * @property string $user_id
 * @property string $ticket_id
 * @property string $text
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read string $time_passed
 * @property-read \App\Ticket $ticket
 * @property-read \App\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|\App\TicketReply newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\TicketReply newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\TicketReply query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\TicketReply whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\TicketReply whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\TicketReply whereText($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\TicketReply whereTicketId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\TicketReply whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\TicketReply whereUserId($value)
 */
	class TicketReply extends \Eloquent {}
}

namespace App{
/**
 * Address of the coin
 * 
 * Class DepositAddress
 *
 * @package App
 * @property string $id
 * @property string $user_id
 * @property string $address
 * @property string $coin
 * @property float $amount
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read string $added_ago
 * @property-read string $balance
 * @property-read string $target
 * @method static \Illuminate\Database\Eloquent\Builder|\App\VendorPurchase newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\VendorPurchase newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\VendorPurchase query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\VendorPurchase whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\VendorPurchase whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\VendorPurchase whereCoin($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\VendorPurchase whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\VendorPurchase whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\VendorPurchase whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\VendorPurchase whereUserId($value)
 */
	class VendorPurchase extends \Eloquent {}
}

namespace App{
/**
 * App\Ban
 *
 * @property string $id
 * @property string $user_id
 * @property string $until
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ban newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ban newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ban query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ban whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ban whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ban whereUntil($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ban whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Ban whereUserId($value)
 */
	class Ban extends \Eloquent {}
}

namespace App{
/**
 * Represents the instance of the coin address for any user
 * Can be any Coin that is supported in the config
 * 
 * Class Address
 *
 * @package App
 * @property string $id
 * @property string $user_id
 * @property string $address
 * @property string|null $pubkey
 * @property string $coin
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read string $added_ago
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Address newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Address newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Address query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Address whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Address whereCoin($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Address whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Address whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Address wherePubkey($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Address whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Address whereUserId($value)
 */
	class Address extends \Eloquent {}
}

