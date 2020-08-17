<?php

namespace App;

use Carbon\Carbon;

use Illuminate\Database\Eloquent\Model;

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
class File extends Model
{
    protected $table = 'file';

    protected $dateFormat = 'U';

    protected $appends = [
        'fullPath',
    ];

    public function object()
    {
        return $this->morphTo();
    }

    public function getFullPathAttribute()
    {
        return storage_path('app/public/' . $this->path);
    }
}
