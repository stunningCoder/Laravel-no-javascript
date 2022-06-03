<?php

namespace App\Http\Controllers;

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

class DisputeController extends Controller
{
	

    public function __construct()
    {
        $this -> middleware('auth');
    }

    private function messagesCheck(){
        if(Gate::denies('has-access', 'messages'))
            abort(403);
    }

    private function disputesCheck()
    {
        if(Gate::denies('has-access', 'disputes'))
            abort(403);
    }


    /**
     * Table with disputes
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index($state = '')
    {
	    $this->disputesCheck();
	    
	    if(!array_key_exists($state, Dispute::$states))
	    {
		    $state = 'open';
	    }
	    
	    return view('admin.disputes', [
		    'allDisputes' => Dispute::where('state', $state)->paginate(config('marketplace.products_per_page', 24)),
		    'state' => $state
	    ]);
    }
	
	/** Escalate dispute if dispute is older enough and dispute is open
	 * @param Purchase $purchase
	 *
	 * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\Foundation\Application|\Illuminate\View\View|mixed
	 */
    public function escalateConfirm(Purchase $purchase)
    {
	    return view('profile.dispute.escalate', [
		    'purchase' => $purchase
	    ]);
    }
	/** Escalate dispute if dispute is older enough and dispute is open
	 * @param Purchase $purchase
	 *
	 * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\Foundation\Application|\Illuminate\View\View|mixed
	 */
    public function makeEscalate(Purchase $purchase)
    {
    	if($purchase->dispute->isAllowedToEscalate())
	    {
		    try {
			    $purchase->escalateDispute();
			    session() -> flash('success', 'Dispute has been Escalated!');
			    
		    } catch (\Exception $e) {
			    $e -> flashError();
		    }
		
	    } else {
    		
		    session() -> flash('error', 'Escalate is allowed after 24 hours after dispute has begun');
	    }
	
	    return redirect()->route('profile.purchases.single', $purchase);
    	
    }
    

    /**
     * Resolve dispute of the purchase
     *
     * @param ResolveDisputeRequest $request
     * @param Purchase $purchase
     * @return \Illuminate\Http\RedirectResponse
     */
    public function resolveDispute(ResolveDisputeRequest $request, Purchase $purchase)
    {
        $this -> disputesCheck();

        try{
            $purchase -> resolveDispute($request -> winner);
            session() -> flash('success', 'You have successfully resolved this dispute!');
        }
        catch (RequestException $e){
            $e -> flashError();
        }

        return redirect() -> back();
    }


}
