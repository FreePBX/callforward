<?php
namespace FreePBX\modules\Callforward\Api\Rest;
use FreePBX\modules\Api\Rest\Base;
class Callforward extends Base {
	protected $module = 'callforward';
	public function setupRoutes($app) {

		/**
		 * @verb GET
		 * @return - a list of users' callforward settings
		 * @uri /callforward/users
		 */
		$app->get('/users', function ($request, $response, $args) {
			\FreePBX::Modules()->loadFunctionsInc('callforward');
			$response->getBody()->write(json_encode(callforward_get_extension()));
			return $response->withHeader('Content-Type', 'application/json');
		})->add($this->checkAllReadScopeMiddleware());

		/**
		 * @verb GET
		 * @returns - a users' callforward settings
		 * @uri /callforward/users/:id
		 */
		$app->get('/users/{id}', function ($request, $response, $args) {
			\FreePBX::Modules()->loadFunctionsInc('callforward');
			$response->getBody()->write(json_encode(callforward_get_extension($args['id'])));
			return $response->withHeader('Content-Type', 'application/json');
		})->add($this->checkAllReadScopeMiddleware());

		/**
		 * @verb GET
		 * @returns - a users' callforward settings
		 * @uri /callforward/users/:id/ringtimer
		 */
		$app->get('/users/{id}/ringtimer', function ($request, $response, $args) {
			\FreePBX::Modules()->loadFunctionsInc('callforward');
			$response->getBody()->write(json_encode(callforward_get_ringtimer($args['id'])));
			return $response->withHeader('Content-Type', 'application/json');
		})->add($this->checkAllReadScopeMiddleware());

		/**
		 * @verb PUT
		 * @uri /callforward/users/:id
		 */
		$app->put('/users/{id}', function ($request, $response, $args) {
			\FreePBX::Modules()->loadFunctionsInc('callforward');
			$params = $request->getParsedBody();
			foreach (callforward_get_extension($args['id']) as $type => $value) {
				if (isset($params[$type])) {
					callforward_set_number($args['id'], $params[$type], $type);
				}
			}
			$response->getBody()->write(json_encode(true));
			return $response->withHeader('Content-Type', 'application/json');
		})->add($this->checkAllWriteScopeMiddleware());

		/**
		 * @verb PUT
		 * @uri /callforward/users/:id/ringtimer
		 */
		$app->put('/users/{id}/ringtimer', function ($request, $response, $args) {
			\FreePBX::Modules()->loadFunctionsInc('callforward');
			$params = $request->getParsedBody();
			$response->getBody()->write(callforward_set_ringtimer($args['id'] ?? '', $params['ringtimer'] ?? ''));
			return $response->withHeader('Content-Type', 'application/json');
		})->add($this->checkAllWriteScopeMiddleware());
	}
}
