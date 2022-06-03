<?php

namespace App;

use App\Exceptions\RequestException;
use App\Traits\Uuids;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use App\Events\Purchase\ProductDisputeEscalated;

/**
 * This is the model class for table "disputes".
 *
 * @property string $id
 * @property string $disputed_by_user_id
 * @property string $escalated_by_user_id
 * @property string $appeal_by_user_id
 * @property string $winner_id
 * @property string $state
 * @property integer $appeals_count
 */

class Dispute extends Model
{
    use Uuids;
    public $incrementing = false;
    protected $primaryKey = 'id';
    protected $keyType = 'string';
	
	/**
	 * State of the disputes
	 *
	 * @var array
	 */
	public static $states = [
		'open' => 'Open Disputes',
		'escalated' => 'Escalated Disputes',
		'appeals' => 'Appeals Disputes',
		'closed' => 'Closed Disputes',
	];
	
	const DEFAULT_STATE = 'open';
	const ESCALATED_STATE = 'escalated';
	const APPEAL_STATE = 'appeals';
	
	const ESCALATE_AFTER = 60; //seconds

    /**
     * Messages of the dispute
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function messages()
    {
        return $this -> hasMany(\App\DisputeMessage::class, 'dispute_id')->orderBy('created_at' , 'desc');
    }


    /**
     * Purchase
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function purchase()
    {
        return $this -> hasOne(\App\Purchase::class, 'dispute_id', 'id');
    }

    /**
     * Returns if the user can post message on the dispute
     *
     * @return bool
     */
    public function canPostMessage() : bool
    {
        // if it is not logged in
        if(auth() -> check() == false) return false;

        // Define when the user can post
        if(auth() -> user() -> isAdmin()) return true;
        if($this -> purchase -> isVendor()) return true;
        if($this -> purchase -> isBuyer()) return true;

        return false;

    }

    /**
     * Posts new message to this dispute
     *
     * @param string $message
     * @throws RequestException
     */
    public function newMessage(string $message)
    {
        if(!auth() -> check())
            throw new RequestException('You must be logged in to send new message!');

        if(!$this -> canPostMessage())
            throw new RequestException('You can\'t post messages to this dispute');
	
	
	    \Illuminate\Support\Facades\Log::debug("Before fresh Dispute state $this->state");
	    
        //Locking new message for Buyer and Vendor
/*        if( $this -> purchase -> isVendor() || $this -> purchase -> isBuyer() ) {
        	
	        $isEscalatedOrAppealed = Dispute::find($this->id);
	        $isEscalatedOrAppealed->refresh();
	
	        \Illuminate\Support\Facades\Log::debug("AFter fresh Dispute state $isEscalatedOrAppealed->state");
	        
	        if ( $isEscalatedOrAppealed->state == Dispute::ESCALATED_STATE || $isEscalatedOrAppealed->state == \App\Dispute::APPEAL_STATE ) {
		        throw new RequestException('Can\'t send new messages when Dispute is Escalated!');
	        }
	        
        }*/

        if($this -> isResolved())
            throw new RequestException('Can\'t post new messages when it is resolved');

        $newMessage = new DisputeMessage;
        $newMessage -> message = $message;
        $newMessage -> setDispute($this);
        $newMessage -> setAuthor(auth() -> user());
        $newMessage -> save();

    }
    
    public function getEscalatedBy()
    {
    	return ($this->escalated_by_user_id) ? User::find($this->escalated_by_user_id)->username : $this->escalated_by_user_id;
    }
    
    public function getAppealedBy()
    {
    	return ($this->appeal_by_user_id) ? User::find($this->appeal_by_user_id)->username : $this->appeal_by_user_id;
    }
    
    public function isAppealing()
    {
    	return $this->state == \App\Dispute::APPEAL_STATE;
    }
    

    public function isAllowedToAppeal()
    {
    	return (\App\Appeals::where('purchase_id', $this->purchase->id)->count() < \App\Appeals::MAXIMUM_APPEALS) && $this->state != \App\Dispute::APPEAL_STATE;
    }
    
	
	/** Dispute can be escalated if is older enough and state is open
	 * @return bool
	 */
    public function isAllowedToEscalate(): bool
    {
    	return (time() - Dispute::ESCALATE_AFTER) >  strtotime($this->created_at) && $this->state == Dispute::DEFAULT_STATE;
    }
    
    public function isEscalated()
	{
		
		return $this->state == Dispute::ESCALATED_STATE;
	}
	
	public function lastReplyFrom()
	{
		$last_msg = DisputeMessage::where('dispute_id', $this->id)->orderBy('created_at', 'desc')->first();
		
		return (isset($last_msg)) ? User::find($last_msg->author_id)->username : 'no messages';
	}

    /**
     * Returns if the dispute is resolved
     *
     * @return bool
     */
    public function isResolved()
    {
        return $this -> winner_id != null;
    }

    /**
     * Returns the winner of the dispute, can be null
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function winner()
    {
        return $this -> hasOne(\App\User::class, 'id', 'winner_id');
    }

    /**
     * Returns true if logged user is the winner of this purchase
     *
     * @return bool
     */
    public function isWinner() : bool
    {
        // if user is logged in
        if(auth()->check())
            return auth()->user()->id == $this->winner->id;
        return false;
    }


    /**
     * Time differencfe since the opened dispute
     *
     * @return string
     */
    public function timeDiff()
    {
        return Carbon::parse($this -> created_at) -> diffForHumans();
    }


}
