<?php

namespace FreePBX\modules\Callforward\Api\Gql;

use GraphQL\Type\Definition\Type;
use FreePBX\modules\Api\Gql\Base;

class Callforward extends Base {
	protected $module = 'callforward';


	public function mutationCallback() {
		if($this->checkAllWriteScope()) {
		}
	}

	public function queryCallback() {
		if($this->checkAllReadScope()) {
		}
	}

	public function postInitializeTypes() {
	}
	/*
	public function postInitTypes() {
		if($this->checkAllReadScope()) {
			$user = $this->typeContainer->get('user');
			$user->addField('callforward_unconditional', [
				'type' => Type::string(),
				'description' => 'Call Forward No Answer/Unavailable',
				'resolve' => function($user) {
					return $this->getNumberByType($user['extension'], 'CFU');
				}
			]);
			$user->addField('callforward_busy', [
				'type' => Type::string(),
				'description' => 'Call Forward Busy',
				'resolve' => function($user) {
					return $this->getNumberByType($user['extension'], 'CFB');
				}
			]);
			$user->addField('callforward_all', [
				'type' => Type::string(),
				'description' => 'Call Forward All',
				'resolve' => function($user) {
					return $this->getNumberByType($user['extension'], 'CF');
				}
			]);
			$user->addField('callforward_ringtimer', [
				'type' => Type::int(),
				'description' => 'Call Forward Ring Timer',
				'resolve' => function($user) {
					return $this->freepbx->Callforward->getRingtimerByExtension($user['extension']);
				}
			]);
		}
	}

	private function getNumberByType($extension, $type) {
		$number = $this->freepbx->Callforward->getNumberByExtension($extension, $type);
		return $number !== false ? $number : '';
	}
	*/
}
