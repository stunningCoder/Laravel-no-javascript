<?php
	
	namespace App\Http\Controllers\Admin;
	
	use App\Category;
	use App\Dispute;
	use App\Events\Support\TicketClosed;
	use App\Exceptions\RequestException;
	use App\Http\Controllers\Controller;
	use App\Http\Requests\Admin\SendMessagesRequest;
	use App\Http\Requests\Categories\NewCategoryRequest;
	use App\Http\Requests\Purchase\ResolveDisputeRequest;
	use App\Offer;
	use App\Product;
	use App\Purchase;
	use App\Ticket;
	use App\User;
	use App\Vendor;
	use App\VendorPurchase;
	use Carbon\Carbon;
	use Illuminate\Http\Request;
	use Illuminate\Support\Facades\Gate;
	use App\Http\Requests\Purchase\NewDisputeMessageRequest;
	use App\Events\Purchase\ProductDisputeNewMessageSent;
	use App\Log;
    use DB;
	
	class AdminController extends Controller
	{
		public function __construct()
		{
			$this->middleware('admin_panel_access');
		}
		
		private function categoriesCheck()
		{
			if (Gate::denies('has-access', 'categories'))
				abort(403);
		}
		
		private function messagesCheck()
		{
			if (Gate::denies('has-access', 'messages'))
				abort(403);
		}
		
		private function disputesCheck() //can message
		{
			if (Gate::denies('has-access', 'disputes'))
				abort(403);
		}
		
		private function appealsCheck() //can message
		{
			if (Gate::denies('has-access', 'disputeappeals'))
				abort(403);
		}
		
		private function ticketsCheck()
		{
			if (Gate::denies('has-access', 'tickets'))
				abort(403);
		}
		
		private function ticketsRemoveCheck()
		{
			if (Gate::denies('has-access', 'deletetickets'))
				abort(403);
		}
		
		/**
		 * Return home view of category section
		 *
		 * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
		 */
		public function index()
		{
			$total_spent = 0;
			$total_earnings_coin = [];
			foreach (Purchase::where('state', 'delivered')->get() as $deliveredPurchase) {
				$total_spent += $deliveredPurchase->getSumDollars();
				
				// sum up earning per coin
				if (!array_key_exists($deliveredPurchase->coin, $total_earnings_coin)) {
					$total_earnings_coin[$deliveredPurchase->coin_name] = $deliveredPurchase->to_pay;
				} // add up for the coin
				else {
					$total_earnings_coin[$deliveredPurchase->coin_name] += $deliveredPurchase->to_pay;
				}
			}
			
			return view('admin.index',
				[
					'total_products' => Product::count(),
					'total_purchases' => Purchase::count(),
					'total_daily_purchases' => Purchase::where('updated_at', '>', Carbon::now()->subDay())->where('state', 'delivered')->count(),
					'total_users' => User::count(),
					'total_vendors' => Vendor::count(),
					'avg_product_price' => Offer::avg('price'),
					'total_spent' => $total_spent,
					'total_earnings_coin' => $total_earnings_coin
				]
			);
		}
		
		/**
		 * Return view with the category list
		 *
		 * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
		 */
		public function categories()
		{
			$this->categoriesCheck();
			
			
			return view('admin.categories',
				[
					'rootCategories' => Category::roots(),
					'categories' => Category::nameOrdered()
				]
			);
		}
		
		/**
		 * Accepts the request for the new Category
		 *
		 * @param NewCategoryRequest $request
		 *
		 * @return \Illuminate\Http\RedirectResponse
		 */
		public function newCategory(NewCategoryRequest $request)
		{
			$this->categoriesCheck();
			try {
				$request->flash(); //save data to edit
				$request->persist();
				session()->flash('success', 'You have added new category!');
			} catch (RequestException $e) {
				session()->flash('errormessage', $e->getMessage());
				
			}
			return redirect()->back();
		}
		
		/**
		 * Remove category
		 *
		 * @param $id
		 *
		 * @return \Illuminate\Http\RedirectResponse
		 * @throws \Exception
		 */
		public function removeCategory($id)
		{
			try {
				$this->categoriesCheck();
				$catToDelete = Category::findOrFail($id);
				$catToDelete->delete();
				
				session()->flash('success', 'You have successfully deleted category!');
			} catch (\Exception $e) {
				session()->flash('errormessage', $e->getMessage());
			}
			
			return redirect()->back();
		}
		
		/**
		 * Show form for editing category
		 *
		 * @param $id
		 *
		 * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
		 */
		public function editCategoryShow($id)
		{
			$this->categoriesCheck();
			$categoryToShow = Category::findOrFail($id);
			
			
			return view('admin.category', [
				'category' => $categoryToShow,
				'categories' => Category::nameOrdered(),
			]);
			
		}
		
		/**
		 * Accepts request for editing category
		 *
		 * @return \Illuminate\Http\RedirectResponse
		 */
		public function editCategory($id, NewCategoryRequest $request)
		{
			$this->categoriesCheck();
			
			try {
				$request->persist($id);
				session()->flash('success', 'You have changed category!');
			} catch (RequestException $e) {
				session()->flash('errormessage', $e->getMessage());
			}
			return redirect()->route('admin.categories');
		}
		
		/**
		 * Send new dispute message to the dispute
		 *
		 * @param NewDisputeMessageRequest $request
		 * @param Dispute                  $dispute
		 *
		 * @return \Illuminate\Http\RedirectResponse
		 */
		public function newDisputeMessage(NewDisputeMessageRequest $request, Dispute $dispute)
		{
			$this->disputesCheck();
			
			try {
				
				$newMessage = new \App\DisputeMessage();
				$newMessage->message = $request->message;
				$newMessage->dispute_id = $request->post('dispute_id');
				$newMessage->author_id = auth()->id();
				$newMessage->save();
				
				$purchase = \App\Purchase::where('dispute_id', $newMessage->dispute_id)->first();
				
				event(new ProductDisputeNewMessageSent($purchase, auth()->user()));
				
				session()->flash('success', 'You have successfully posted new message for dispute!');
			} catch (RequestException $e) {
				$e->flashError();
			}
			
			
			return redirect()->back();
		}
		
		/**
		 * Form for the new message
		 *
		 * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
		 */
		public function massMessage()
		{
			$this->messagesCheck();
			
			return view('admin.messages');
		}
		
		/**
		 * Send mass message to group of users
		 *
		 * @param SendMessagesRequest $request
		 *
		 * @return \Illuminate\Http\RedirectResponse
		 */
		public function sendMessage(SendMessagesRequest $request)
		{
			
			$this->messagesCheck();
			try {
				$noMessages = $request->persist();
				session()->flash('success', "$noMessages messages has been sent!");
			} catch (RequestException $e) {
				$e->flashError();
			}
			
			return redirect()->back();
		}
		
		
		/**
		 * Single Purchase view for admin
		 *
		 * @param Purchase $purchase
		 *
		 * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
		 */
		public function disputedPurchase(Purchase $purchase)
		{
			return view('admin.purchase', [
				'purchase' => $purchase,
			]);
		}
		
		/**
		 * Return view with the table of disputes
		 *
		 * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
		 */
		public function disputes($state = 'open')
		{
			$this->disputesCheck();
			
			
			return view('admin.disputes', [
				'allDisputes' => Dispute::where('state', $state)->orderBy('created_at', 'asc')->paginate(config('marketplace.products_per_page')),
				'state' => $state
			]);
		}
		
		/**
		 * Resolve dispute of the purchase
		 *
		 * @param ResolveDisputeRequest $request
		 * @param Purchase              $purchase
		 *
		 * @return \Illuminate\Http\RedirectResponse
		 */
		public function resolveDispute(ResolveDisputeRequest $request, Purchase $purchase)
		{
			$this->disputesCheck();
			
			try {
				$purchase->resolveDispute($request->winner);
				session()->flash('success', 'You have successfully resolved this dispute!');
			} catch (RequestException $e) {
				$e->flashError();
			}
			
			return redirect()->back();
		}
		
		
		public function resolveAppeal(ResolveDisputeRequest $request, Purchase $purchase)
		{
			$this->appealsCheck();
			
			try {
				$purchase->resolveAppeal($request->winner);
				session()->flash('success', 'You have successfully resolved this Appeal!');
			} catch (RequestException $e) {
				$e->flashError();
			}
			
			return redirect()->back();
		}
		
		
		/**
		 * Single Purchase view for admin
		 *
		 * @param Purchase $purchase
		 *
		 * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
		 */
		public function purchase(Purchase $purchase)
		{
			return view('admin.purchase', [
				'purchase' => $purchase,
			]);
		}
		
		/**
		 * Displayed all paginated tickets without solved!
		 *
		 * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
		 */
		public function tickets($state = 'Open')
		{
			
			
			$tickets = Ticket::orderByDesc('created_at');
			
			if ($state == 'Open') {
				$tickets = $tickets->where([
					['answered', '=', 0],
					['solved', '=', 0]
				]);
			}
			
			if ($state == 'Answered') {
				$tickets = $tickets->where([
					['answered', '=', 1],
					['solved', 0]
				]);
			}
			
			if ($state == 'Solved') {
				$tickets = $tickets->where([
					['solved', '=', 1],
				]);
			}
			
			$tickets = $tickets->paginate(config('marketplace.posts_per_page'));
			
			return view('admin.tickets', [
				'state' => $state,
				'tickets' => $tickets
			]);
		}
		
		
		/**
		 * Solve/Unsolve ticket request by Moderator/Admin
		 *
		 * @param Ticket $ticket
		 *
		 * @return \Illuminate\Http\RedirectResponse
		 */
		public function solveTicket(Ticket $ticket)
		{
			$this->ticketsCheck();
			
			if ($ticket->user_id != auth()->user()->id) {
				
				$ticket->solved = !$ticket->solved;
				$ticket->resolver_id = auth()->user()->id;
				$ticket->update();
				session()->flash('successmessage', 'The ticket has been solved!');
				event(new TicketClosed($ticket));
				
			} else {
				session()->flash('error', 'Not yours ticket!');
			}
			
			return redirect()->back();
		}
		
		
		/**
		 * Displayed all paginated tickets and Remove options
		 *
		 * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
		 */
		public function ticketsRemove()
		{
			$this->ticketsRemoveCheck();
			
			return view('admin.tickets.remove', [
				'tickets' => Ticket::orderByDesc('created_at')->paginate(config('marketplace.posts_per_page'))
			]);
		}
		
		/**
		 * Single ticket Admin view
		 *
		 * @param Ticket $ticket
		 *
		 * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
		 */
		public function viewTicket(Ticket $ticket)
		{
			return view('admin.ticket', [
				'ticket' => $ticket,
				'replies' => $ticket->replies()->orderByDesc('created_at')->paginate(config('marketplace.posts_per_page', 24)),
			]);
		}
		
		
		/**
		 * List of vendor purchases
		 *
		 * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
		 */
		public function vendorPurchases()
		{
			return view('admin.vendorpurchases', [
				'vendors' => Vendor::orderByDesc('created_at')->paginate(24),
			]);
		}
		
		
		public function removeTickets(Request $request)
		{
			
			$type = $request->type;
			if ($type == 'all') {
				foreach (Ticket::all() as $ticket) {
					$ticket->delete();
				}
			}
			if ($type == 'solved') {
				foreach (Ticket::where('solved', 1)->get() as $ticket) {
					$ticket->delete();
				}
			}
			
			if ($type == 'orlder_than_days') {
				foreach (Ticket::where('created_at', '<', Carbon::now()->subDays($request->days))->get() as $ticket) {
					$ticket->delete();
				}
			}
			
			return redirect()->back();
			
			
		}
		
		public function elasticsearchIndexState()
		{
			$total_products = Product::count();
			$active_products = Product::where('active', true)->count();
			$inactive_products = Product::where('active', false)->count();
			
			$curl_session = curl_init();
			curl_setopt($curl_session, CURLOPT_URL, env('ELASTICSEARCH_HOST') . '/' . env('ELASTICSEARCH_INDEX') . '/_stats');
			curl_setopt($curl_session, CURLOPT_RETURNTRANSFER, TRUE);
			curl_setopt($curl_session, CURLOPT_SSL_VERIFYHOST, 0);
			curl_setopt($curl_session, CURLOPT_CUSTOMREQUEST, 'GET');
			
			$elasticsearch = curl_exec($curl_session);
			
			$error = curl_error($curl_session);
			
			curl_close($curl_session);
			
			return view('admin.elasticsearchstate', [
				'total_products' => $total_products,
				'active_products' => $active_products,
				'inactive_products' => $inactive_products,
				'elasticsearch' => (empty($error)) ? json_decode($elasticsearch, true) : $error,
			]);
			
		}
		
		/** Adds product to elasticsearch
		 *
		 * @param $id
		 */
		public function elasticsearchIndexProduct($id)
		{
			Product::where('id', $id)->searchable();
			session()->flash('successmessage', "Product $id indexed in ElasticSearch");
			
			return redirect()->home();
		}
		
		/** Reindex products in elasticsearch
		 *
		 * @param $id
		 */
		public function elasticsearchReindexAllProducts()
		{
			Product::removeAllFromSearch();
			
			$products = Product::where('active', 1)->cursor();
			
			foreach ($products as $product) {
				Product::where('id', $product->id)->searchable();
			}
			
			session()->flash('successmessage', "Products are  Reindexed in ElasticSearch");
			
			return redirect()->home();
			
		}
		
		
		/** Delete All products indexes from elasticsearch
		 */
		public function elasticsearchDeleteAllIndex()
		{
			Product::removeAllFromSearch();
			session()->flash('successmessage', 'Product indexes deleted from ElasticSearch');
			
			return redirect()->home();
			
		}
        
        public function announcement(){
            $data = DB::table('announcements')->get();
            return view('admin.announcement',['data' => $data]);
        }

        public function newAnnouncements(Request $request){
            if(!empty($request->post_id)){
                $data = DB::table('announcements')
                    ->where('id', $request->post_id)
                    ->update(['body' => $request->htmlcode]);
                return redirect()->route('admin.announcement');
            }
            DB::table('announcements')->insert(
                ['user_id' => auth()->user()->id, 'body' => $request->htmlcode]
            );
            return view('admin.announcement');
        }
		
	}
