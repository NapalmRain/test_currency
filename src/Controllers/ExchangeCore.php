<?php

namespace App\Controllers;

use App\Models\CurrencyRate;
use DI\Definition\Resolver\DecoratorResolver;
use SimpleXMLElement;

class ExchangeCore {
	//currency - код валюты
	//format - число знаков после запятой
	public static function GetNew($currency, $format = 4) {
		$now = date('d/m/Y'); // Текущая дата

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, 'https://www.cbr.ru/scripts/XML_daily.asp?date_req='.$now);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		$out = curl_exec($ch);
		curl_close($ch);

		$currency = strtoupper($currency);

		$data = new SimpleXMLElement($out);
		$answer = $data->xpath('Valute[CharCode="'.$currency.'"]')[0]->Value;

		if ($answer != null) {
			$result = number_format(str_replace(',', '.', $answer), $format);

			$rate = new CurrencyRate();
			$rate->Date = $now;
			$rate->Currency = $currency;
			$rate->Rate = $result;
			$rate->save();

			return $result;
		}
		return null;
	}
	public static function GetHistory($currency) {
		$currency = strtoupper($currency);
		$curr = CurrencyRate::where('Currency', $currency)->get()->toArray();
		return $curr;
	}

}