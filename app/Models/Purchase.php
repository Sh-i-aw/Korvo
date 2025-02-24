<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;


class Purchase extends Model
{
    use \Sushi\Sushi;

    protected $fillable = [
        'name',
        'amount',
        'category'
    ];
    protected $rows = [
        ['id' => 1, 'name' => 'pastry', 'amount' => '1230', 'category' => "food"],
        ['id' => 2, 'name' => 'coffee', 'amount' => '580', 'category' => "food"],
        ['id' => 3, 'name' => 'tofu', 'amount' => '725', 'category' => "food"],
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }
}
