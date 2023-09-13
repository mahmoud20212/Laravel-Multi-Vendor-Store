<?php

namespace App\Http\Controllers\Front;

use App\Events\OrderCreated;
use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Repositories\Cart\CartRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Symfony\Component\Intl\Countries;

class CheckoutController extends Controller
{
    public function create(CartRepository $cart)
    {
        if ($cart->get()->count() == 0) {
            return redirect()->route('home'); 
        }
        $countries = Countries::getNames();
        return view('front.checkout', compact('cart', 'countries'));
    }
    public function store(Request $request, CartRepository $cart)
    {
        $request->validate([
            'addr.billing.first_name' => 'required|string|max:255',
            'addr.billing.last_name' => 'required',
            'addr.billing.email' => 'required',
            'addr.billing.phone_number' => 'required',
            'addr.billing.street_address' => 'required',
            'addr.billing.city' => 'required',
            'addr.billing.postal_code' => 'required',
            'addr.billing.state' => 'required',
            'addr.billing.country' => 'required',
        ]);

        $items = $cart->get()->groupBy('product.store_id')->all();

        DB::beginTransaction();
        try {
            foreach ($items as $store_id => $cart_items) {
                $order = Order::create([
                    'store_id' => $store_id,
                    'user_id' => Auth::id(),
                    'payment_method' => 'cod',
                ]);

                foreach ($cart_items as $item) {
                    // dd($item->product->name);
                    OrderItem::create([
                        'order_id' => $order->id,
                        'product_id' => $item->product_id,
                        'product_name' => $item->product->name,
                        'price' => $item->product->price,
                        'quantity' => $item->quantity,
                    ]);
                }

                foreach ($request->post('addr') as $type => $address) {
                    $address['type'] = $type;
                    $order->addresses()->create($address);
                }
            }

            DB::commit();

            event(new OrderCreated($order));

        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
        return redirect()->route('home');
    }
}
