<?php

namespace IP2LocationIO;

function ipErrorHandler($errNo, $errStr) {
	echo "Error: " . $errStr . PHP_EOL;
}

set_error_handler("IP2LocationIO\ipErrorHandler");

/**
 * IP2Location.io IP Geolocation module.
 */
class IPGeolocation
{

	private $iplIOApiKey = '';

	public function __construct($config)
	{
		$this->iplIOApiKey = $config->apiKey;
	}

	/**
	 * Lookup given IP address for an enriched data set.
	 *
	 * @param array $params parameters of ip address lookup
	 *
	 * @return object IP2Location.io IP geolocation result in JSON object
	 */
	public function lookup($ip, $language = '')
	{
		$queries = [
			'key'            => $this->iplIOApiKey,
			'format'         => 'json',
			'ip'             => (isset($ip)) ? $ip : '',
			'lang'           => (isset($language)) ? $language : '',
			'source'         => 'sdk-php-iplio',
			'source_version' => Configuration::VERSION,
		];

		$http = new Http();
		$response = $http->get('https://api.ip2location.io/?', $queries);

		if (($json = json_decode($response)) === null) {
			return false;
		}

		if (isset($json->error)) {
			trigger_error($json->error->error_message);
		}

		return $json;
	}
}

class_alias('IP2LocationIO\IPGeolocation', 'IP2LocationIO_IPGeolocation');
