<?php

namespace App\Models;

use App\Rules\Filter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use HasFactory, SoftDeletes;

    public function products()
    {
        return $this->hasMany(Product::class, 'category_id', 'id');
    }

    public function parent() {
        return $this->belongsTo(Category::class, 'parent_id', 'id')
            ->withDefault([
                'name' => '-'
            ]);
    }
    
    public function children() {
        return $this->hasMany(Category::class, 'parent_id', 'id');
    }

    public function scopeActive(Builder $builder)
    {
        $builder->where('status', '=', 'active');
    }
    
    public function scopeStatus(Builder $builder, $status)
    {
        $builder->where('status', '=', $status);
    }
    
    public function scopeFilter(Builder $builder, $filters)
    {
        $builder->when($filters['name'] ?? false, function($builder, $value){
            $builder->where('name', 'like', "%{$value}%");
        });
        
        $builder->when($filters['status'] ?? false, function($builder, $value){
            $builder->where('status', '=', "{$value}");
        });

        // if ($filters['name'] ?? false) {
        //     $builder->where('name', 'like', "%{$filters['name']}%");
        // }

        // if ($filters['status'] ?? false) {
        //     $builder->where('status', '=', "{$filters['status']}");
        // }
    }

    // white list
    protected $fillable = [
        'name',
        'parent_id',
        'description',
        'image',
        'status',
        'slug',
    ];
    // black list
    // protected $guarded = [];

    public static function rules($id = 0)
    {
        return [
            'name' => [
                'required',
                'string',
                'min:3',
                'max:255',
                "unique:categories,name,$id",
                // new Filter(['laravel']),
                // function ($attribute, $value, $fails) {
                //     if (strtolower($value) == 'laravel') {
                //         $fails('This name is forbidden!');
                //     }
                // }
                'filter:laravel',
            ],
            'parent_id' => 'nullable|int|exists:categories,id',
            'image' => 'image|max:1048576|dimensions:min_width=100,min_height=100',
            'status' => 'required|in:active,archived',
        ];
    }
}
