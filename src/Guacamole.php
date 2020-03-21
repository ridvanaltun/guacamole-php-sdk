<?php

declare(strict_types=1);

namespace ridvanaltun\Guacamole;

use GuzzleHttp\Client;
use ridvanaltun\Guacamole\Operation;

/**
 * Creates guacamole connections.
 */
class Guacamole
{
    /**
     * Guzzle HTTP client
     *
     * @var Client
     */
    public $client;

    /**
     * User token
     *
     * @var string
     */
    public $token;

    /**
     * Datasource definition
     *
     * @var string
     */
    public $dataSource;

    /**
     * Request handler
     *
     * @var Operation
     */
    private $operation;

    /**
     * Create Guacamole object.
     *
     * @param   string  $host      Guacamole address
     * @param   string  $username  Guacamole username
     * @param   string  $password  Guacamole password
     * @param   array   $options   Guzzle client options
     *
     * @throws  TokenException     If token not generated
     */
    function __construct(string $host, string $username, string $password, array $options = [])
    {
        $opt = array_merge([
            'base_uri' => $host,
        ], $options);

        $this->client  = new Client($opt);

        $this->operation = new Operation($this);

        $token = $this->generateToken($username, $password);

        $this->token = $token['authToken'];
        $this->dataSource = $token['dataSource'];
    }

    /**
     * Generates token
     *
     * @param   string  $username  Guacamole username
     * @param   string  $password  Guacamole password
     *
     * @return  object             Token
     */
    public function generateToken(string $username, string $password) {
		$res = $this->operation->request('POST', '/tokens', [
    		'form_params' => [
    		    'username' 	=> $username,
    		    'password' 	=> $password,
    		]
		], false);

		return $res;
    }

    /**
     * Returns token
     *
     * @return  string  Token
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * Returns data source
     *
     * @return  string  Data source
     */
    public function getDataSource()
    {
        return $this->dataSource;
    }
}
