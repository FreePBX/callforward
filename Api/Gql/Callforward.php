<?php

namespace FreePBX\modules\Callforward\Api\Gql;

use GraphQL\Type\Definition\Type;
use FreePBX\modules\Api\Gql\Base;

class Callforward extends Base {
	protected $module = 'callforward';

	public function postInitializeTypes() {
		if($this->checkAllReadScope()) {
			$user = $this->typeContainer->get('coreuser');

			$user->addFieldCallback(function() {
				return [
					'callforward_unconditional' => [
						'type' => Type::string(),
						'description' => 'Call Forward No Answer/Unavailable',
						'resolve' => function($user) {
							if(!isset($user['extension'])) {
								return null;
							}
							return $this->getNumberByType($user['extension'], 'CFU');
						}
					],
					'callforward_busy' => [
						'type' => Type::string(),
						'description' => 'Call Forward No Answer/Unavailable',
						'resolve' => function($user) {
							if(!isset($user['extension'])) {
								return null;
							}
							return $this->getNumberByType($user['extension'], 'CFU');
						}
					],
					'callforward_all' => [
						'type' => Type::string(),
						'description' => 'Call Forward All',
						'resolve' => function($user) {
							if(!isset($user['extension'])) {
								return null;
							}
							return $this->getNumberByType($user['extension'], 'CF');
						}
					],
					'callforward_ringtimer' => [
						'type' => Type::int(),
						'description' => 'Call Forward Ring Timer',
						'resolve' => function($user) {
							if(!isset($user['extension'])) {
								return null;
							}
							return $this->freepbx->Callforward->getRingtimerByExtension($user['extension']);
						}
					]
				];
			});
		}
	}

	private function getNumberByType($extension, $type) {
		$number = $this->freepbx->Callforward->getNumberByExtension($extension, $type);
		return $number !== false ? $number : '';
	}

}
