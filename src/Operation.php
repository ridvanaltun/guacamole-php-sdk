<?php

declare(strict_types=1);

namespace ridvanaltun\Guacamole;

use ridvanaltun\Guacamole\Guacamole;

/**
 * Request handler
 */
class Operation
{
	/**
	 * Guacamole API prefix
	 *
	 * @var string
	 */
	private $apiPrefix = '/api';

	/**
     * Guzzle HTTP client
     *
     * @var GuzzleHttp\Client
     */
	private $client;

	/**
     * User token
     *
     * @var string
     */
	private $token;

	/**
     * Datasource definition
     *
     * @var string
     */
	public $dataSource;

	/**
	 * Creates operation
	 *
	 * @param   Guacamole  $server  Guacamole server object
	 */
	function __construct(Guacamole $server)
	{
		$this->client = $server->client;
		$this->token = $server->token;
		$this->dataSource = $server->dataSource;
	}

	/**
	 * Bind token
	 *
	 * @param   array  $options  Request options
	 *
	 * @return  array            New request options
	 */
    private function withAuthToken(array $options) {
		return array_merge(
			$options, [
				'query' => ['token' => $this->token],
			]
		);
    }

    private function send(string $method, string $endpoint = '', array $options = []) {
	    $response = $this->client->request($method, $endpoint, $options);
		return json_decode($response->getBody()->getContents(), true) ?: (string) $response->getBody()->getContents();
	}

    public function request(string $method = 'GET', string $endpoint = '/', array $options = [], bool $useToken = true) {
    	$response = null;
		$target = $this->apiPrefix . $endpoint;

		if ($useToken) {
			$response = $this->send($method, $target, $this->withAuthToken($options));
		} else {
			$response = $this->send($method, $target, $options);
		}

		return $response;
   }
}
