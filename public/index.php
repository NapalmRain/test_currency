<?php
declare(strict_types=1);

use App\Controllers\ExchangeCore;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;
use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Events\Dispatcher;
use Illuminate\Container\Container;

require __DIR__ . '/../vendor/autoload.php';

define('PATH', __DIR__.'/..');

function BasePath($path) {
	return PATH.'/'.$path;
}

$app = AppFactory::create();

$capsule = new Capsule();
$capsule->addConnection([
	                        'driver' => 'sqlite',
	                        'host' => '127.0.0.1',
	                        'database' => BasePath('db/data.db'),
	                        'username' => '',
	                        'password' => '',
	                        'charset' => 'utf8',
	                        'collation' => 'utf8_unicode_ci',
	                        'prefix' => '',
                        ]
);
$capsule->setEventDispatcher(new Dispatcher(new Container()));
$capsule->setAsGlobal();
$capsule->bootEloquent();


$app->post('/exchange-rates', function (Request $request, Response $response, array $args) {
	$data = ExchangeCore::GetNew($request->getParsedBody()['code']);
	$response->getBody()->write(json_encode(['Rate' =>$data]));
	return $response->withHeader('Content-Type', 'application/json');
});

$app->post('/exchange-rates/history/', function (Request $request, Response $response, array $args) {
	$data = ExchangeCore::GetHistory($request->getParsedBody()['code']);
	$response->getBody()->write(json_encode(['Rates' =>$data]));
	return $response->withHeader('Content-Type', 'application/json');
});

$app->run();