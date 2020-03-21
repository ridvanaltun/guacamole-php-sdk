<?php

declare(strict_types=1);

namespace ridvanaltun\Guacamole;

use ridvanaltun\Guacamole\Operation;
use ridvanaltun\Guacamole\Guacamole;

class UserGroup
{
    private $operation;
    private $dataSource;

    function __construct(Guacamole $server)
    {
        $this->operation = new Operation($server);
        $this->dataSource = $this->operation->dataSource;
    }

    public function list()
    {
        $endpoint = '/session/data/' . $this->dataSource . '/userGroups';

		$res = $this->operation->request('GET', $endpoint);

		return $res === '' ? [] : $res;
    }

    public function details(string $identifier)
    {
        $endpoint = '/session/data/' . $this->dataSource . '/userGroups/' . $identifier;

		return $this->operation->request('GET', $endpoint);
    }

    public function create(string $identifier, array $attributes = [])
    {
        $endpoint = '/session/data/' . $this->dataSource . '/userGroups';

		return $this->operation->request('POST', $endpoint, [
            'json' => [
                'identifier' => $identifier,
                'attributes' => (object) $attributes,
            ],
        ]);
    }

    public function update(string $identifier, array $attributes = [], string $newIdentifier = null)
    {
        $endpoint = '/session/data/' . $this->dataSource . '/userGroups/' . $identifier;

		$this->operation->request('PUT', $endpoint, [
            'json' => [
                'identifier' => is_null($newIdentifier) ? $identifier : $newIdentifier,
                'attributes' => (object) $attributes,
            ],
        ]);
    }

    public function delete(string $identifier)
    {
        $endpoint = '/session/data/' . $this->dataSource . '/userGroups/' . $identifier;

		$this->operation->request('DELETE', $endpoint);
    }

    public function addMembers(string $identifier, array $members) {
		$endpoint = '/session/data/' . $this->dataSource . '/userGroups/' . $identifier . '/memberUsers';

		$jsonPatch = [];

		foreach ($members as $member) {
			$jsonPatch = array_merge($jsonPatch, [
				"op" => "add",
			    "path" => "/",
			    "value" => "/$member",
			]);
		}

		$this->operation->request('PATCH', $endpoint, [
			'json' => $jsonPatch,
		]);
    }

    public function addMembersOfUserGroups(string $identifier, array $userGroups) {
		$endpoint = '/session/data/' . $this->dataSource . '/userGroups/' . $identifier . '/memberUserGroups';

		$jsonPatch = [];

		foreach ($userGroups as $userGroup) {
			$jsonPatch = array_merge($jsonPatch, [
				"op" => "add",
			    "path" => "/",
			    "value" => "/$userGroup",
			]);
		}

		$this->operation->request('PATCH', $endpoint, [
			'json' => $jsonPatch,
		]);
    }

    public function addUserGroups(string $identifier, array $userGroups) {
		$endpoint = '/session/data/' . $this->dataSource . '/userGroups/' . $identifier . '/userGroups';

		$jsonPatch = [];

		foreach ($userGroups as $userGroup) {
			$jsonPatch = array_merge($jsonPatch, [
				"op" => "add",
			    "path" => "/",
			    "value" => "/$userGroup",
			]);
		}

		$this->operation->request('PATCH', $endpoint, [
			'json' => $jsonPatch,
		]);
	}
}
