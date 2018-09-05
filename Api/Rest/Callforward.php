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
			return $response->withJson(callforward_get_extension());
		})->add($this->checkAllReadScopeMiddleware());

		/**
		 * @verb GET
		 * @returns - a users' callforward settings
		 * @uri /callforward/users/:id
		 */
		$app->get('/users/{id}', function ($request, $response, $args) {
			\FreePBX::Modules()->loadFunctionsInc('callforward');
			return $response->withJson(callforward_get_extension($args['id']));
		})->add($this->checkAllReadScopeMiddleware());

		/**
		 * @verb GET
		 * @returns - a users' callforward settings
		 * @uri /callforward/users/:id/ringtimer
		 */
		$app->get('/users/{id}/ringtimer', function ($request, $response, $args) {
			\FreePBX::Modules()->loadFunctionsInc('callforward');
			return $response->withJson(callforward_get_ringtimer($args['id']));
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
			return $response->withJson(true);
		})->add($this->checkAllWriteScopeMiddleware());

		/**
		 * @verb PUT
		 * @uri /callforward/users/:id/ringtimer
		 */
		$app->put('/users/{id}/ringtimer', function ($request, $response, $args) {
			\FreePBX::Modules()->loadFunctionsInc('callforward');
			$params = $request->getParsedBody();
			return $response->withJson(callforward_set_ringtimer($args['id'], $params['ringtimer']));
		})->add($this->checkAllWriteScopeMiddleware());
	}
}
