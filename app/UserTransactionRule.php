<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserTransactionRule extends Model
{
	//

	protected $casts = [
		'resource_type'         => 'array',
		'transaction_user_type' => 'array',
	];

	protected $fillable = [
		'user_type', 'resource_type', 'transaction_user_type',
	];

}
