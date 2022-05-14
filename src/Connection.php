<?php

declare(strict_types=1);

namespace ridvanaltun\Guacamole;

use ridvanaltun\Guacamole\Operation;
use ridvanaltun\Guacamole\Guacamole;

class Connection
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
        $endpoint = '/session/data/' . $this->dataSource . '/connections';

        $res = $this->operation->request('GET', $endpoint);

        return $res === '' ? [] : $res;
    }

    public function listActives()
    {
        $endpoint = '/session/data/' . $this->dataSource . '/activeConnections';

        $res = $this->operation->request('GET', $endpoint);

        return $res === '' ? [] : $res;
    }

    public function details(string $identifier)
    {
        $endpoint = '/session/data/' . $this->dataSource . '/connections/' . $identifier;

        return $this->operation->request('GET', $endpoint);
    }

    public function detailsParameters(string $identifier)
    {
        $endpoint = '/session/data/' . $this->dataSource . '/connections/' . $identifier . '/parameters';

        return $this->operation->request('GET', $endpoint);
    }

    public function history(string $identifier)
    {
        $endpoint = '/session/data/' . $this->dataSource . '/connections/' . $identifier . '/history';

        $res = $this->operation->request('GET', $endpoint);

        return $res === '' ? [] : $res;
    }

    public function kill(array $sessions)
    {
        $endpoint = '/session/data/' . $this->dataSource . '/activeConnections';

        $jsonPatch = [];

        foreach ($sessions as $sessionId) {
            $jsonPatch = array_merge($jsonPatch, [
                "op" => "remove",
                "path" => "/$sessionId"
            ]);
        }

        $this->operation->request('PATCH', $endpoint, [
            'json' => $jsonPatch,
        ]);
    }

    public function sharingProfiles(string $identifier)
    {
        $endpoint = '/session/data/' . $this->dataSource . '/connections/' . $identifier . '/sharingProfiles';

        $res = $this->operation->request('GET', $endpoint);

        return $res === '' ? [] : $res;
    }

    public function createSharingProfiles(string $identifier, string $name, bool $readonly = true, array $attributes = [])
    {
        $endpoint = '/session/data/' . $this->dataSource . '/sharingProfiles';
        $params=[
            'read-only' => $readonly ? 'true' : '',
        ];
        return $this->operation->request('POST', $endpoint, [
            'json' => [
                'primaryConnectionIdentifier' => $identifier,
                'name' => $name,
                'parameters' => (object)$params,
                'attributes' => (object)$attributes,
            ],
        ]);

    }

    public function listSharingProfiles()
    {
        $endpoint = '/session/data/' . $this->dataSource . '/sharingProfiles';

        $res = $this->operation->request('GET', $endpoint);

        return $res === '' ? [] : $res;
    }

    public function createVnc(string $name, string $password, int $port = 5901, array $parameters = [], array $attributes = [])
    {
        $endpoint = '/session/data/' . $this->dataSource . '/connections';

        $params = array_merge($parameters, [
            'port' => $port,
            'password' => $password,
        ]);

        return $this->operation->request('POST', $endpoint, [
            'json' => [
                'name' => $name,
                'protocol' => 'vnc',
                'parameters' => (object)$params,
                'attributes' => (object)$attributes,
            ],
        ]);
    }

    public function createSsh(string $name, string $hostname, string $username, string $password, int $port = 22, array $attributes = [])
    {
        $endpoint = '/session/data/' . $this->dataSource . '/connections';

        $params = [
            'hostname' => $hostname,
            'username' => $username,
            'port' => $port,
            'password' => $password,
        ];

        return $this->operation->request('POST', $endpoint, [
            'json' => [
                'name' => $name,
                'protocol' => 'ssh',
                'parameters' => (object)$params,
                'attributes' => (object)$attributes,
            ],
        ]);
    }

    public function createRdp(string $name, string $hostname, int $port = 3389, array $parameters = [], array $attributes = [])
    {
        $endpoint = '/session/data/' . $this->dataSource . '/connections';

        $params = array_merge($parameters, [
            'port' => $port,
            'hostname' => $hostname,
        ]);

        return $this->operation->request('POST', $endpoint, [
            'json' => [
                'name' => $name,
                'protocol' => 'rdp',
                'parameters' => (object)$params,
                'attributes' => (object)$attributes,
            ],
        ]);
    }

    public function createTelnet(string $name, string $hostname, string $username, string $password, int $port = 23, array $parameters = [], array $attributes = [])
    {
        $endpoint = '/session/data/' . $this->dataSource . '/connections';

        $params = array_merge($parameters, [
            'port' => $port,
            'username' => $username,
            'password' => $password,
            'hostname' => $hostname,
        ]);

        return $this->operation->request('POST', $endpoint, [
            'json' => [
                'name' => $name,
                'protocol' => 'telnet',
                'parameters' => (object)$params,
                'attributes' => (object)$attributes,
            ],
        ]);
    }

    public function createKubernetes(string $name, string $hostname, int $port = 8080, array $parameters = [], array $attributes = [])
    {
        $endpoint = '/session/data/' . $this->dataSource . '/connections';

        $params = array_merge($parameters, [
            'port' => $port,
            'hostname' => $hostname,
        ]);

        return $this->operation->request('POST', $endpoint, [
            'json' => [
                'name' => $name,
                'protocol' => 'kubernetes',
                'parameters' => (object)$params,
                'attributes' => (object)$attributes,
            ],
        ]);
    }

    public function update(string $identifier, string $type, string $name, array $parameters = [], array $attributes = [])
    {
        $endpoint = '/session/data/' . $this->dataSource . '/connections/' . $identifier;

        return $this->operation->request('PUT', $endpoint, [
            'json' => [
                'name' => $name,
                'protocol' => $type,
                'parameters' => (object)$parameters,
                'attributes' => (object)$attributes,
            ],
        ]);
    }

    public function delete(int $connectionId)
    {
        $endpoint = '/session/data/' . $this->dataSource . '/connections/' . $connectionId;

        $this->operation->request('DELETE', $endpoint);
    }

    public function assign(string $username, int $connectionId)
    {
        $endpoint = '/session/data/' . $this->dataSource . '/users/' . $username . '/permissions';

        $res = $this->operation->request('PATCH', $endpoint, [
            'json' => [
                [
                    "op" => "add",
                    "path" => "/connectionPermissions/$connectionId",
                    "value" => "READ"
                ]
            ]
        ]);

        return $res === '' ? true : $res;
    }

    public function revoke(string $username, int $connectionId)
    {
        $endpoint = '/session/data/' . $this->dataSource . '/users/' . $username . '/permissions';

        $res = $this->operation->request('PATCH', $endpoint, [
            'json' => [
                [
                    "op" => "remove",
                    "path" => "/connectionPermissions/$connectionId",
                    "value" => "READ"
                ]
            ]
        ]);

        return $res === '' ? false : $res;
    }
}
