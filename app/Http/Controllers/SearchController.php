<?php
	
	namespace App\Http\Controllers;
	
	use App\Category;
	use App\Product;
	use App\User;
	use Illuminate\Http\Request;
	use Illuminate\Pagination\LengthAwarePaginator;
	use Illuminate\Pagination\Paginator;
	use Illuminate\Support\Collection;
	use App\Purchase;
	
	
	class SearchController extends Controller
	{
		/**
		 * Handles POST request for search, convert form input to query string and redirects to searchShow
		 *
		 * @param Request $request
		 *
		 * @return \Illuminate\Http\RedirectResponse
		 */
		public function search(Request $request)
		{
			$searchQuery = $request->search == null ? '' : $request->search;
			
			$orderMethods = [
				'price_asc',
				'price_desc',
				'newest'
			];
			
//        if (!in_array($request->order_by, $orderMethods)) {
			$orderBy = 'newest';
//        } else {
//            $orderBy = $request->order_by;
//        }
			
			return redirect()->route('search.show', [
				'query' => $searchQuery,
				'category' => $request->category,
				'coins' => $request->coins,
				'purchase_type' => $request->purchase_type,
				'from_country_code' => $request->from_country_code,
				'to_country_code' => $request->to_country_code,
//            'type'      => $request->product_type,
				'price_min' => $request->minimum_price,
				'price_max' => $request->maximum_price,
				'user' => $request->user,
				'order_by' => $orderBy,
			]);
		}
		
		/**
		 * Applying all search parameters from query string and returns a view
		 *
		 * @param Request $request
		 *
		 * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
		 */
		public function searchShow(Request $request)
		{
			$searchQuery = $request->get('query');
			
			$start = microtime(true);
			
			$query = Product::search($searchQuery);

			// dd($query); exit;
			
			
			$query->limit = 1000;
			/**
			 * Limit search by parameters
			 */
			//user
			$userQuery = $request->get('user');
			
			if ($userQuery !== null) {
				$query->where('user', $userQuery);
			}

			//category
			$categoryQuery = $request->get('category');
			
			if (!empty($categoryQuery) && $categoryQuery !== 'any') {
				
				if (is_array($categoryQuery)) {//Adding support for multiple categories
					$query->where('category', array_shift($categoryQuery));
				} else {
					$query->where('category', $categoryQuery);
				}
				
			}
			
			//coins
			$coinsQuery = $request->get('coins');
			if ($coinsQuery !== null && $coinsQuery != 'any') {
				$query->where('coins', $coinsQuery);
			}
			
			
			$purchase_typeQuery = $request->get('purchase_type');
			$supportedPurchaseTypes = array_keys(Purchase::$types);
			if ($purchase_typeQuery !== 'any' && in_array($purchase_typeQuery, $supportedPurchaseTypes)) {
				$query->where('types', $purchase_typeQuery);
				
			}
			
			$from_country_code = $request->get('from_country_code');
			$to_country_code = $request->get('to_country_code');
			
			
			if ((!empty($from_country_code) || !empty($to_country_code)) && ($from_country_code != 'any' || $to_country_code != 'any')) {
				$query->where('type', 'physical');
			}
			
			if (!empty($from_country_code) && $from_country_code != 'any') {
				$query->where('from_country_code', $from_country_code);
			}
			
			
			if (!empty($to_country_code) && $to_country_code != 'all') {
				$query->where('to_country_code', $to_country_code);
			}
			
			
			//type
			/*        $typeQuery = $request->get('type');
					$supportedTypes = ['digital', 'physical'];
					if ($typeQuery !== null && in_array($typeQuery, $supportedTypes)) {
						$query->where('type', $typeQuery);
					}*/
			
			// perform search
			$perPage = config('marketplace.products_per_page');
			$results = $query->get();
			
			
			if ($categoryQuery !== null && $categoryQuery !== 'any') {//Adding support for multiple categories
				if (is_array($categoryQuery)) {
					foreach ($categoryQuery as $category) {
						$query->where('category', $category);
						
						$results = $results->merge($query->get());
					}
					$results = $results->unique();
				}
			}
			
			//ordering
			$orderQuery = $request->get('order_by');
			$results = $this->order($results, $orderQuery);
			
			//price filter
			$minPriceQuery = $request->get('price_min');
			$maxPriceQuery = $request->get('price_max');
			if (!empty($minPriceQuery) || !empty($maxPriceQuery)) {
				$results = $this->priceFilter($results, $minPriceQuery, $maxPriceQuery);
			}
			
			
			$finalResult = $this->paginate($results, $perPage);
			$finalResult->setPath($request->fullUrl());
			
			
			$end = (microtime(true) - $start);
			
			$end = round($end, 5);
			
			return view('results', [
				'productsView' => session()->get('products_view'),
				'products' => $finalResult,
				'categories' => Category::roots(),
				'query' => $searchQuery,
				'time' => $end,
				'results_count' => $results->count()
			]);
		}
		
		/**
		 * Get All Products from selected categories
		 *
		 *
		 * @return Collection
		 */
		public function searchCategoryFilter($request, $categoryQuery)
		{
//        $categoryQuery = Category::whereIn('id', $categoryQuery)->pluck('name')->toArray();
//                dd($categoryQuery);
			
			$perPage = config('marketplace.products_per_page');
			/*if ($categoryQuery === null) {
				return $this->paginate([], $perPage);
			}
	
					$query = Product::search("");
	
					$query->limit = 1000;
	
			//category
					if ($categoryQuery !== null && $categoryQuery !== 'any') {
						if (is_array($categoryQuery)) {
							$query->where('category', array_shift($categoryQuery));
						} else {
							$query->where('category', $categoryQuery);
						}
					}
	
			// perform search
	
					$results = $query->get();
					if ($categoryQuery !== null && $categoryQuery !== 'any') {
						if (is_array($categoryQuery)) {
							foreach ($categoryQuery as $category) {
								$query->where('category', $category);
								$results = $results->merge($query->get());
							}
							$results = $results->unique();
						}
					}*/
			
			$products = collect();
			foreach ($categoryQuery as $cat) {
				$category = Category::where('id', $cat)->first();
				$products = $products->merge($category->products()->get());
			}
			
			$finalResult = $this->paginate($products, $perPage);
			$finalResult->setPath($request->fullUrl());
			
			return $finalResult;
			
			
			/*        $end = (microtime(true) - $start);
			
					$end = round($end, 5);
			
					return view('results', [
						'productsView'  => session()->get('products_view'),
						'products'      => $finalResult,
						'categories'    => Category::roots(),
						'query'         => $searchQuery,
						'time'          => $end,
						'results_count' => $results->count()
					]);*/
		}
		
		/**
		 * Paginates a collection
		 *
		 * @param       $items
		 * @param int   $perPage
		 * @param null  $page
		 * @param array $options
		 *
		 * @return LengthAwarePaginator
		 */
		private function paginate($items, $perPage = 15, $page = null, $options = [])
		{
			$page = $page ?: (Paginator::resolveCurrentPage() ?: 1);
			$items = $items instanceof Collection ? $items : Collection::make($items);
			
			return new LengthAwarePaginator($items->forPage($page, $perPage), $items->count(), $perPage, $page, $options);
		}
		
		/**
		 * Accepts collection of products and orders them based on provided $orderQuery
		 *
		 * @param $collection
		 * @param $orderQuery
		 *
		 * @return mixed
		 */
		private function order($collection, $orderQuery)
		{
			$ordered = $collection;
			if ($orderQuery !== null) {
				if ($orderQuery == 'price_asc') {
					$ordered = $collection->sortBy(function ($product) {
						return (float)$product->price_from;
					});
				}
				if ($orderQuery == 'price_desc') {
					
					$ordered = $collection->sortByDesc(function ($product) {
						return $product->price_from;
					});
				}
				if ($orderQuery == 'newest') {
					$ordered = $collection->sortByDesc(function ($product) {
						return $product->created_at;
					});
				}
			}
			
			return $ordered;
		}
		
		private function priceFilter($collection, $minPriceQuery, $maxPriceQuery)
		{
			//min price
			$filteredCollection = $collection;
			if ($minPriceQuery !== null && floatval($minPriceQuery) > 0) {
				$minPrice = floatval($minPriceQuery);
				$filteredCollection = $collection->filter(function ($product) use ($minPrice) {
					return $product->price_from >= floatval($minPrice);
				});
			}
			//max price
			if ($maxPriceQuery !== null && floatval($maxPriceQuery) > 0) {
				$maxPrice = floatval($maxPriceQuery);
				$filteredCollection = $collection->filter(function ($product) use ($maxPrice) {
					return $product->price_from <= floatval($maxPrice);
				});
			}
			
			return $filteredCollection;
		}
		
	}
