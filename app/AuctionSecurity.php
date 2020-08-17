<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

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
class AuctionSecurity extends Model
{
    protected $fillable = [
    'application_security_price',
    'application_security_is_cash',
    'application_security_is_paid',
    'contract_security_price',
    'contract_security_is_cash',
    'contract_security_is_paid',
    'warranty_security_price',
    'warranty_security_is_cash',
    'warranty_security_is_paid',
    ];

    public function auction()
    {
        return $this->belongsTo(Auction::class);
    }
}
