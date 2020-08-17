<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

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
class Etp extends Model
{
    public function auctions()
    {
        return $this->hasMany(Auction::class);
    }
}
