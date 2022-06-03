<?php
	
	namespace App\Console\Commands;
	
	use Illuminate\Console\Command;
	use App\Services\DeleteProductsIndexService;
	use App\Product;
	use App\Services\IndexProduct;
	
	class ReindexProducts extends Command
	{
		/**
		 * The name and signature of the console command.
		 *
		 * @var string
		 */
		protected $signature = 'elasticsearch:reindex-all-products';
		
		/**
		 * The console command description.
		 *
		 * @var string
		 */
		protected $description = 'Re-index all products in elasticsearch';
		
		/**
		 * Create a new command instance.
		 *
		 * @return void
		 */
		public function __construct()
		{
			parent::__construct();
		}
		
		/**
		 * Execute the console command.
		 *
		 * @return mixed
		 */
		public function handle()
		{
			$indexProductService = app(IndexProduct::class);
			
			$confirmed = $this->confirm(
				"Are you sure that you want to re-index all of your products?"
			);
			
			DeleteProductsIndexService::delete();
			
			$this->info("\n<fg=yellow>Indexing all products. This might take a while...</>\n");
			
			$products = Product::where('active', true)->cursor();
			
			$products_count = $products->count();
			
			$bar = $this->output->createProgressBar($products_count);
			
			foreach ($products as $product)
			{
				$indexProductService->store($product);
				$bar->advance();
			}
			
			$bar->finish();
			
			$this->info("\n <fg=yellow>All products were indexed!</>");
			
		}
	}
