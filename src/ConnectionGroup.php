<?php

declare(strict_types=1);

namespace ridvanaltun\Guacamole;

use ridvanaltun\Guacamole\Operation;
use ridvanaltun\Guacamole\Guacamole;

class ConnectionGroup
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
        $endpoint = '/session/data/' . $this->dataSource . '/connectionGroups';

		$res = $this->operation->request('GET', $endpoint);

		return $res === '' ? [] : $res;
    }

    public function listTree()
    {
        $endpoint = '/session/data/' . $this->dataSource . '/connectionGroups/ROOT/tree';

		$res = $this->operation->request('GET', $endpoint);

		return $res === '' ? [] : $res;
    }

    public function details(string $identifier)
    {
        $endpoint = '/session/data/' . $this->dataSource . '/connectionGroups/' . $identifier;

		return $this->operation->request('GET', $endpoint);
    }

    public function create(string $name, string $type = 'ORGANIZATIONAL', array $attributes = [])
    {
        $endpoint = '/session/data/' . $this->dataSource . '/connectionGroups';

		return $this->operation->request('POST', $endpoint, [
            'json' => [
                'name' => $name,
                'type' => $type,
                'attributes' => (object) $attributes,
            ],
        ]);
    }

    public function update(string $identifier, string $name, string $type = 'ORGANIZATIONAL', array $attributes = [])
    {
        $endpoint = '/session/data/' . $this->dataSource . '/connectionGroups/' . $identifier;

		$this->operation->request('PUT', $endpoint, [
            'json' => [
                'name'       => $name,
                'type'       => $type,
                'attributes' => (object) $attributes,
            ],
        ]);
    }

    public function delete(string $identifier)
    {
        $endpoint = '/session/data/' . $this->dataSource . '/connectionGroups/' . $identifier;

		$this->operation->request('DELETE', $endpoint);
    }
}
