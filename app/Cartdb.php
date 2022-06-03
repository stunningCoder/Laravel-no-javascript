<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Uuids;
use App\Marketplace\Cart;

class Cartdb extends Model
{
	use Uuids;
	public $incrementing = false;
	protected $primaryKey = 'id';
	protected $keyType = 'string';
	
	protected $table = 'cartdb';
	
	protected $guarded = [];
	
	
	public static function add($item)
	{
		$new = Cartdb::firstOrNew ([
//			'quantity' => $item->quantity,
//			'coin_name' => $item->coin_name,
			'type' => $item->type,
			'offer_id' => $item->offer_id,
			'buyer_id' => $item->buyer_id,
			'shipping_id' => $item->shipping_id,
			'vendor_id' => $item->vendor_id
		]);
		
//		if(isset($exists))
//		{
		$new->quantity = $item->quantity;
		$new->coin_name = $item->coin_name;
		$new->save();
//		}
		
	}
	
	public static function remove($item)
	{
		$delete = Cartdb::where('coin_name' , $item->coin_name);
		
//		if(!empty($item->coin_name))
//		{
//			$delete->where('coin_name' , $item->coin_name);
//		}
		
		if(!empty($item->offer_id))
		{
			$delete->where('offer_id' , $item->offer_id);
		}
		
		if(!empty($item->buyer_id))
		{
			$delete->where('buyer_id' , $item->buyer_id);
		}
		
		if(!empty($item->shipping_id))
		{
			$delete->where('shipping_id' , $item->shipping_id);
		}
		
		if(!empty($item->vendor_id))
		{
			$delete->where('vendor_id' , $item->vendor_id);
		}
		
		$delete->delete();
		
	}
	
	public static function removeAll()
	{
		Cartdb::where('buyer_id', auth()->id())->delete();
	}
	
	
	public static function updateSessionCart()
	{
		$items = Cartdb::where('buyer_id', auth()->id())
		 ->orderBy('created_at', 'asc')->get();
		
		if(empty($items))
		{
			return;
		}
		
	;
		
		foreach ($items as $item)
		{
			$offer = Offer::find($item->offer_id);
			$product = $offer->product;
			$shipping = null;
			if($product -> isPhysical())
				$shipping = $product -> specificProduct() -> shippings()
					-> where('id', $item->shipping_id)
					-> where('deleted', '=', 0) // is not deleted
					-> first();
			
			Cart::getCart() -> addToCart($product, $item->quantity, $item->coin_name, $shipping, null, $item->type);
		}
		
	}
	
}
