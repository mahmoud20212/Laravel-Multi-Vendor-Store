<?php

namespace App\Repositories\Cart;

use App\Models\Cart;
use Illuminate\Support\Collection;
use App\Models\Product;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Str;

class CartModelRepository implements CartRepository
{
    protected $items;

    public function __construct()
    {
        $this->items = collect([]);
    }

    public function get(): Collection
    {
        if (!$this->items->count()) {
            $this->itmes = Cart::with('product')->get();
        }
        return $this->itmes;
    }

    public function add(Product $product, $quantity = 1)
    {
        $item = Cart::where('product_id', '=', $product->id)
            ->first();
        
        if (!$item) {
            // return Cart::create([
            //     'id' => Auth::id(),
            //     'product_id' => $product->id,
            //     'quantity' => $quantity,
            // ]);
            $cart = Cart::create([
                'id' => Auth::id(),
                'product_id' => $product->id,
                'quantity' => $quantity,
            ]);
            $this->get()->push($cart);
            return $cart;
        }
        return $item->increment('quantity', $quantity);
    }

    public function update($id, $quantity = 1)
    {
        Cart::where('product_id', '=', $id)
        ->update([
            'quantity' => $quantity,
        ]);
    }
    
    public function delete($id)
    {
        Cart::where('id', '=', $id)
        ->delete();
    }
    
    public function empty()
    {
        Cart::query()->delete();
    }

    public function total() : float
    {
        // استعلام على مستوى قاعدة البيانات لحساب المجموع
        // return (float) Cart::join('products', 'products.id', '=', 'carts.product_id')
        // ->selectRaw('SUM(products.price * carts.quantity) as total')
        // ->value('total');
        
        // حساب المجموع على مستوى collection بدلا من قاعدة البيانات
        return $this->get()->sum(function($items) {
            // dd($items->product);
            return $items->quantity * $items->product->price;
        });
    }
}