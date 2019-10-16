<?php
/**
 * Created by PhpStorm.
 * User: D3xter
 * Date: 4/26/2019
 * Time: 6:27 PM
 */

namespace App;


use Illuminate\Database\Eloquent\Model;
class Properties extends Model
{
    protected $table = 'save_property';
    protected $fillable = [
        'line1','line2'
    ];
    public function users()
    {
        return $this->belongsTohasMany(User::class,"user_id");
    }
}