<?php
	
	
	namespace App\Services;
	
	
	use App\Product;
	
	class IndexProduct
	{
		
		public function store($product)
		{
			Product::where('id', $product->id)->searchable();
		}
		
	}
