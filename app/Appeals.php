<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Uuids;
use Illuminate\Support\Facades\DB;
use App\Events\Purchase\ProductDisputed;
use App\Exceptions\RequestException;

class Appeals extends Model
{
	use Uuids;
	public $incrementing = false;
	protected $primaryKey = 'id';
	protected $keyType = 'string';
	
	/**
	 * States
	 *
	 * @var array
	 */
	public static $states = [
		'open' => 'Open',
		'solved' => 'Solved',
	];
	
	const DEFAULT_STATE = 'open';
	const MAXIMUM_APPEALS = 1;
	

	
}
