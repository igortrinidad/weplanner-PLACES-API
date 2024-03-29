<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class Post extends Model
{

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'wp_posts';

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $connection = 'blog_weplaces';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'post_content',
        'post_title',
        'post_status',
        'post_name',
        'comment_status',
        'ping_status',
    ];

        /*
     * Change the Date attribute
     */
    public function getPostDateAttribute($value)
    {

        return \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $value)->format('d/m/Y');
    }


}
