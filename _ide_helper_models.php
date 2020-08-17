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
 * App\AuctionStatus
 *
 * @property int $id
 * @property string|null $name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Auction[] $auctions
 * @property-read int|null $auctions_count
 * @method static \Illuminate\Database\Eloquent\Builder|\App\AuctionStatus newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\AuctionStatus newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\AuctionStatus query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\AuctionStatus whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\AuctionStatus whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\AuctionStatus whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\AuctionStatus whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	class AuctionStatus extends \Eloquent {}
}

namespace App{
/**
 * Class User
 *
 * @package App
 * @property int $id
 * @property string $name
 * @property string $email
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property string $password
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Auction[] $auctions
 * @property-read int|null $auctions_count
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @property-read int|null $notifications_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Role[] $roles
 * @property-read int|null $roles_count
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\User whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	class User extends \Eloquent {}
}

namespace App{
/**
 * App\Player
 *
 * @property int $id
 * @property string|null $name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Auction[] $auctions
 * @property-read int|null $auctions_count
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Player newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Player newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Player query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Player whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Player whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Player whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Player whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	class Player extends \Eloquent {}
}

namespace App{
/**
 * App\Etp
 *
 * @property int $id
 * @property string|null $name
 * @property string|null $link
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Auction[] $auctions
 * @property-read int|null $auctions_count
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Etp newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Etp newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Etp query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Etp whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Etp whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Etp whereLink($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Etp whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Etp whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	class Etp extends \Eloquent {}
}

namespace App{
/**
 * App\AuctionSecurity
 *
 * @property int $id
 * @property int $auction_id
 * @property int|null $application_security_price
 * @property int|null $application_security_is_cash
 * @property int|null $application_security_is_paid
 * @property int|null $contract_security_price
 * @property int|null $contract_security_is_cash
 * @property int|null $contract_security_is_paid
 * @property int|null $warranty_security_price
 * @property int|null $warranty_security_is_cash
 * @property int|null $warranty_security_is_paid
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Auction $auction
 * @method static \Illuminate\Database\Eloquent\Builder|\App\AuctionSecurity newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\AuctionSecurity newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\AuctionSecurity query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\AuctionSecurity whereApplicationSecurityIsCash($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\AuctionSecurity whereApplicationSecurityIsPaid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\AuctionSecurity whereApplicationSecurityPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\AuctionSecurity whereAuctionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\AuctionSecurity whereContractSecurityIsCash($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\AuctionSecurity whereContractSecurityIsPaid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\AuctionSecurity whereContractSecurityPrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\AuctionSecurity whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\AuctionSecurity whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\AuctionSecurity whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\AuctionSecurity whereWarrantySecurityIsCash($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\AuctionSecurity whereWarrantySecurityIsPaid($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\AuctionSecurity whereWarrantySecurityPrice($value)
 * @mixin \Eloquent
 */
	class AuctionSecurity extends \Eloquent {}
}

namespace App{
/**
 * App\Role
 *
 * @property int $id
 * @property string $name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\User[] $users
 * @property-read int|null $users_count
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Role newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Role newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Role query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Role whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Role whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Role whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Role whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	class Role extends \Eloquent {}
}

namespace App{
/**
 * Class Auction
 *
 * @package App
 * @property int $id
 * @property int $user_id
 * @property int|null $player_id
 * @property int|null $etp_id
 * @property int|null $auction_status_id
 * @property string|null $auction_link
 * @property int|null $auction_number
 * @property string|null $auction_object
 * @property int|null $is_price_request
 * @property int|null $is_223fz
 * @property int|null $client_id
 * @property string|null $applicationdeadline
 * @property string|null $auctiondate
 * @property float|null $maxprice
 * @property float|null $ourprice
 * @property float|null $finalprice
 * @property string|null $auction_winner
 * @property string|null $comment
 * @property int $is_archived
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\AuctionSecurity|null $auctionSecurity
 * @property-read \App\AuctionStatus|null $auctionStatus
 * @property-read \App\Client|null $client
 * @property-read \App\Etp|null $etp
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\File[] $files
 * @property-read int|null $files_count
 * @property-read \App\Player|null $player
 * @property-read \App\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Auction newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Auction newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Auction query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Auction whereApplicationdeadline($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Auction whereAuctionLink($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Auction whereAuctionNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Auction whereAuctionObject($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Auction whereAuctionStatusId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Auction whereAuctionWinner($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Auction whereAuctiondate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Auction whereClientId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Auction whereComment($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Auction whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Auction whereEtpId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Auction whereFinalprice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Auction whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Auction whereIs223fz($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Auction whereIsArchived($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Auction whereIsPriceRequest($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Auction whereMaxprice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Auction whereOurprice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Auction wherePlayerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Auction whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Auction whereUserId($value)
 * @mixin \Eloquent
 * @property float|null $winner_price
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Auction whereWinnerPrice($value)
 */
	class Auction extends \Eloquent {}
}

namespace App{
/**
 * App\Role
 *
 * @property int $id
 * @property string $name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\User[] $users
 * @property-read int|null $users_count
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Role newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Role newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Role query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Role whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Role whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Role whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Role whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property int $role_id
 * @property int $user_id
 * @method static \Illuminate\Database\Eloquent\Builder|\App\RoleHasUser whereRoleId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\RoleHasUser whereUserId($value)
 */
	class RoleHasUser extends \Eloquent {}
}

namespace App{
/**
 * App\Client
 *
 * @property int $id
 * @property string|null $name
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Auction[] $auctions
 * @property-read int|null $auctions_count
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Client newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Client newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Client query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Client whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Client whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Client whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Client whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	class Client extends \Eloquent {}
}

namespace App{
/**
 * Class File
 *
 * @package App
 * @property path
 * @property int $id
 * @property string $path
 * @property string $name
 * @property int $object_id
 * @property int $object_type
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @property-read mixed $full_path
 * @property-read \Illuminate\Database\Eloquent\Model|\Eloquent $object
 * @method static \Illuminate\Database\Eloquent\Builder|\App\File newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\File newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\File query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\File whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\File whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\File whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\File whereObjectId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\File whereObjectType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\File wherePath($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\File whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	class File extends \Eloquent {}
}

