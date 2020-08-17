<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

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
class AuctionStatus extends Model
{
    public function auctions()
    {
        return $this->hasMany(Auction::class);
    }
}
