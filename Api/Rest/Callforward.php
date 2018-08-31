<?php
namespace FreePBX\modules\Callforward\Api\Rest;
use FreePBX\modules\Api\Rest\Base;
class Callforward extends Base {
	public function setupRoutes($app) {
		$app->get('/users', function ($request, $response, $args) {
			\FreePBX::Modules()->loadFunctionsInc('callforward');
			return $response->withJson(callforward_get_extension());
		})->add($this->checkAllReadScopeMiddleware());

		$app->get('/users/{id}', function ($request, $response, $args) {
			\FreePBX::Modules()->loadFunctionsInc('callforward');
			return $response->withJson(callforward_get_extension($args['id']));
		})->add($this->checkAllReadScopeMiddleware());

		$app->get('/users/{id}/ringtimer', function ($request, $response, $args) {
			\FreePBX::Modules()->loadFunctionsInc('callforward');
			return $response->withJson(callforward_get_ringtimer($args['id']));
		})->add($this->checkAllReadScopeMiddleware());

		$app->put('/users/{id}', function ($request, $response, $args) {
			\FreePBX::Modules()->loadFunctionsInc('callforward');
			$params = $request->getParsedBody();
			foreach (callforward_get_extension($args['id']) as $type => $value) {
				if (isset($params[$type])) {
					callforward_set_number($args['id'], $params[$type], $type);
				}
			}
			return $response->withJson(true);
		})->add($this->checkAllWriteScopeMiddleware());

		$app->put('/users/{id}/ringtimer', function ($request, $response, $args) {
			\FreePBX::Modules()->loadFunctionsInc('callforward');
			$params = $request->getParsedBody();
			return $response->withJson(callforward_set_ringtimer($args['id'], $params['ringtimer']));
		})->add($this->checkAllWriteScopeMiddleware());
	}
}
