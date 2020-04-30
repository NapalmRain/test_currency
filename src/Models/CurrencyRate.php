<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CurrencyRate extends Model{
	protected $table = 'CurrencyRates';
	protected $primaryKey = "id";
	public $timestamps = false;

	public function __construct(array $attributes = []) {
		parent::__construct($attributes);
	}

}