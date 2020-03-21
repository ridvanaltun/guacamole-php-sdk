<?php

declare(strict_types=1);

namespace ridvanaltun\Guacamole;

use ridvanaltun\Guacamole\Operation;
use ridvanaltun\Guacamole\Guacamole;

class User
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
        $endpoint = '/session/data/' . $this->dataSource . '/users';

		$res = $this->operation->request('GET', $endpoint);

		return $res === '' ? [] : $res;
	}

	public function details(string $username = null) {

		if (is_null($username))
		{
			$endpoint = '/session/data/' . $this->dataSource . '/self';
		}
		else
		{
			$endpoint = '/session/data/' . $this->dataSource . '/users/' . $username;
		}

		return $this->operation->request('GET', $endpoint);
	}

    public function permissions(string $username) {
		$endpoint = '/session/data/' . $this->dataSource . '/users/' . $username . '/permissions';

		return $this->operation->request('GET', $endpoint);
	}

	public function effectivePermissions(string $username) {
		$endpoint = '/session/data/' . $this->dataSource . '/users/' . $username . '/effectivePermissions';

		return $this->operation->request('GET', $endpoint);
	}

	public function userGroups(string $username) {
		$endpoint = '/session/data/' . $this->dataSource . '/users/' . $username . '/userGroups';

		return $this->operation->request('GET', $endpoint);
	}

	public function assignUserGroups(string $username, array $userGroups) {
		$endpoint = '/session/data/' . $this->dataSource . '/users/' . $username . '/userGroups';

		$jsonPatch = [];

		foreach ($userGroups as $userGroupIdentifier) {
			$jsonPatch = array_merge($jsonPatch, [
				"op"    => "add",
				"path"  => "/",
			    "value" => "$userGroupIdentifier"
			]);
		}

		$this->operation->request('PATCH', $endpoint, [
			'json' => $jsonPatch,
		]);
	}

	public function revokeUserGroups(string $username, array $userGroups) {
		$endpoint = '/session/data/' . $this->dataSource . '/users/' . $username . '/userGroups';

		$jsonPatch = [];

		foreach ($userGroups as $userGroupIdentifier) {
			$jsonPatch = array_merge($jsonPatch, [
				"op"    => "remove",
				"path"  => "/",
			    "value" => "$userGroupIdentifier"
			]);
		}

		$this->operation->request('PATCH', $endpoint, [
			'json' => $jsonPatch,
		]);
	}

	public function assignConnections(string $username, array $connections) {
		$endpoint = '/session/data/' . $this->dataSource . '/users/' . $username . '/permissions';

		$jsonPatch = [];

		foreach ($connections as $connectionIdentifier) {
			$jsonPatch = array_merge($jsonPatch, [
				"op"    => "add",
				"path"  => "/connectionPermissions/$connectionIdentifier",
			    "value" => "READ"
			]);
		}

		$this->operation->request('PATCH', $endpoint, [
			'json' => $jsonPatch,
		]);
	}

	public function revokeConnections(string $username, array $connections) {
		$endpoint = '/session/data/' . $this->dataSource . '/users/' . $username . '/permissions';

		$jsonPatch = [];

		foreach ($connections as $connectionIdentifier) {
			$jsonPatch = array_merge($jsonPatch, [
				"op"    => "remove",
				"path"  => "/connectionPermissions/$connectionIdentifier",
			    "value" => "READ"
			]);
		}

		$this->operation->request('PATCH', $endpoint, [
			'json' => $jsonPatch,
		]);
	}

	public function updatePassword(string $username, string $oldPassword, string $newPassword) {
		$endpoint = '/session/data/' . $this->dataSource . '/users/' . $username . '/password';

		$this->operation->request('PUT', $endpoint, [
			'json' => [
				'oldPassword' => $oldPassword,
				'newPassword' => $newPassword,
			],
		]);
	}

	public function update(string $username, array $attributes = [], string $newUsername = null) {
		$endpoint = '/session/data/' . $this->dataSource . '/users/' . $username;

		$this->operation->request('PUT', $endpoint, [
			'json' => [
				'username'   => is_null($newUsername) ? $username : $newUsername,
				'attributes' => (object) $attributes,
			],
		]);
	}

	public function history(string $username) {
		$endpoint = '/session/data/' . $this->dataSource . '/users/' . $username . '/history';

		return $this->operation->request('GET', $endpoint);
	}

	public function delete(string $username) {
		$endpoint = '/session/data/' . $this->dataSource . '/users/' . $username;

		$this->operation->request('DELETE', $endpoint);
	}

	public function create(string $username, string $password, array $attributes = []) {
		$endpoint = '/session/data/' . $this->dataSource . '/users';

		return $this->operation->request('POST', $endpoint, [
			'json' => [
				'username'	 => $username,
				'password'	 => $password,
				'attributes' => (object) $attributes,
			]
		]);
	}
}
