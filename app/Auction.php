<?php

namespace App;
use Carbon\Carbon;

use Illuminate\Database\Eloquent\Model;

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
 */
class Auction extends Model
{

    const OBJECT_TYPE = 1;

    protected $fillable = [
        'user_id',
        'player_id',
        'etp_id',
        'auction_status_id',
        'auction_link',
        'auction_number',
        'auction_object',
        'is_price_request',
        'is_223fz',
        'client_id',
        'applicationdeadline',
        'auctiondate',
        'maxprice',
        'ourprice',
        'finalprice',
        'auction_winner',
        'comment',
        'is_archived',
    ];

    public function files()
    {
        return $this->morphMany(File::class, '', 'object_type', 'object_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function auctionStatus()
    {
        return $this->belongsTo(AuctionStatus::class);
    }

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function etp()
    {
        return $this->belongsTo(Etp::class);
    }

    public function player()
    {
        return $this->belongsTo(Player::class);
    }

    public function auctionSecurity()
    {
        return $this->hasOne(AuctionSecurity::class);
    }

    public function setApplicationdeadlineAttribute($value)
    {
        try{
            $this->attributes['applicationdeadline'] = !empty($value)
                ? Carbon::createFromFormat('d.m.Y', $value)->format('Y-m-d') : null;
        }catch(\Throwable $ex){
            $this->attributes['applicationdeadline'] = null;
        }
    }

    public function getApplicationdeadlineAttribute($value)
    {
        try {
            $result = !empty($value) ? Carbon::parse($value)->format('d.m.Y') : 'Дата неизвестна';
        }catch(\Throwable $ex){
            $result = 'Дата неизвестна';
        }

        return $result;
    }

    public function setAuctiondateAttribute($value)
    {
        try{
            $this->attributes['auctiondate'] = !empty($value)
                ? Carbon::createFromFormat('d.m.Y', $value)->format('Y-m-d') : null;
        }catch(\Throwable $ex){
            $this->attributes['auctiondate'] = null;
        }
    }

    public function getAuctiondateAttribute($value)
    {
        try {
            $result = !empty($value) ? Carbon::parse($value)->format('d.m.Y') : 'Дата неизвестна';
        }catch(\Throwable $ex){
            $result = 'Дата неизвестна';
        }

        return $result;
    }

    public function setMaxpriceAttribute($value)
    {
        if ($value) {
            $this->attributes['maxprice'] = str_replace([',', ' ', ' '], ['.', '', ''], $value);
        } else {
            return $this->attributes['maxprice'] = 0;
        }
    }

    public function getMaxpriceAttribute($value)
    {
        if ($value) {
            return $value = number_format($value, 2, ',', ' ');
        } else {
            return $this->attributes['maxprice'] = 'Цена неизвестна';
        }
    }

    public function setOurpriceAttribute($value)
    {
        if ($value) {
            $this->attributes['ourprice'] = str_replace([',', ' ', ' '], ['.', '', ''], $value);
        } else {
            return $this->attributes['ourprice'] = null;
        }
    }

    public function getOurpriceAttribute($value)
    {
        if ($value) {
            return $value = number_format($value, 2, ',', ' ');
        } else {
            return $this->attributes['ourprice'] = 'Цена неизвестна';
        }
    }

    public function setFinalpriceAttribute($value)
    {
        if ($value) {
            $this->attributes['finalprice'] = str_replace([',', ' ', ' '], ['.', '', ''], $value);
        } else {
            return $this->attributes['finalprice'] = null;
        }
    }

    public function getFinalpriceAttribute($value)
    {
        if ($value) {
            return $value = number_format($value, 2, ',', ' ');
        } else {
            return $this->attributes['finalprice'] = 'Цена неизвестна';
        }
    }



}
