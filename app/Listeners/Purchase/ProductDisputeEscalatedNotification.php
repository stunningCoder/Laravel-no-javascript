<?php

namespace App\Listeners\Purchase;

use App\Events\Purchase\ProductDisputeEscalated;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\User;

class ProductDisputeEscalatedNotification
{
 
	
	/**
	 * User request is being performed on
	 *
	 * @var User
	 */
	public $user;
	
	/**
	 * Handle the event.
	 *
	 * @param  ProductDisputeEscalated  $event
	 * @return void
	 */
	public function handle(ProductDisputeEscalated $event)
	{
		if ($event->initiator == $event->vendor->user) {
			/**
			 * Notify Buyer
			 */
			$content = 'Dispute for Purchase for a product you bought is being Escalated. Dispute messages are disabled.';
			$routeName = 'profile.purchases.single';
			$routeParams = serialize(['purchase' => $event->purchase->id]);
			$event->buyer->notify($content, $routeName, $routeParams);
		}
		
		if ($event->initiator == $event->buyer) {
			/**
			 * Notify vendor
			 */
			$content = 'Dispute for Purchase for a product you sold  is being Escalated. Dispute messages are disabled.';
			$routeName = 'profile.sales.single';
			$routeParams = serialize(['sale' => $event->purchase->id]);
			$event->vendor->user->notify($content, $routeName, $routeParams);
		}
		
		/**
		 * Notify moderators and admin
		 */
		
	}
	
}
