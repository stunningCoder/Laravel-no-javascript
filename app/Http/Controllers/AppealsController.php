<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Purchase;
use App\Appeal;
use App\Dispute;
use App\Http\Requests\Purchase\ResolveDisputeRequest;
use Illuminate\Support\Facades\Gate;

class AppealsController extends Controller
{
 
	public function __construct()
	{
		$this->middleware('auth');
//		$this->middleware('can_appeal_dispute');
	
	}
	

	
	/**  Appeal Dispute
	 * @param Purchase $purchase
	 *
	 * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\Foundation\Application|\Illuminate\View\View|mixed
	 */
	public function make(Purchase $purchase)
	{
	
		return view('profile.dispute.appeal',[
			'purchase' => $purchase
			
		]);
	}
	
	
	/**  Appeal Dispute
	 * @param Purchase $purchase
	 *
	 * @return array|\Illuminate\Contracts\View\Factory|\Illuminate\Foundation\Application|\Illuminate\View\View|mixed
	 */
	public function appealDisputeConfirm(Purchase $purchase)
	{
	
		try {
			$purchase->appealDispute();
			session() -> flash('success', 'Dispute has been Appealed!');
			
		} catch (\Exception $e) {
			$e -> flashError();
		}
		
		
		return redirect()->route('profile.purchases.single', $purchase);
	
	}

	
	
}
