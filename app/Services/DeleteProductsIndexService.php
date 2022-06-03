<?php
	
	
	namespace App\Services;
	
	use App\Product;
	
	class DeleteProductsIndexService
	{
		public static function delete()
		{
			Product::removeAllFromSearch();
		}
	}
