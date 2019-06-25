<?php
declare(strict_types=1);

namespace www\extensions\api;

use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Exception\ServerException;
use InvalidArgumentException;
use Psr\Http\Message\ResponseInterface;
use www\extensions\api\exceptions\TodokeeperRequestException;
use www\extensions\api\exceptions\TodokeeperRuntimeException;
use www\extensions\api\exceptions\UnexpectedResponseStatusException;
use yii\base\BaseObject;
use yii\log\Logger;

class Todokeeper extends BaseObject implements TodokeeperInterface
{
    /**
     * @var ClientInterface
     */
    private $client;
    /**
     * @var Logger
     */
    private $logger;

    public $domain;

    /**
     * @param ClientInterface $client
     * @param Logger $logger
     * @param array $config
     */
    public function __construct(ClientInterface $client, Logger $logger, $config = [])
    {
        if (!isset($config['domain'])) {
            throw new InvalidArgumentException('Api domain should be specified');
        }
        parent::__construct($config);
        $this->client = $client;
        $this->logger = $logger;
    }

    public function confirm(string $token) : void
    {
        $response = $this->performRequest('POST', 'registration/confirm', ['confirmationToken' => $token]);
        if ($response->getStatusCode() !== 204) {
            if ($response->getStatusCode() >= 400) {
                throw new TodokeeperRequestException(
                    $this->getFirstErrorFromResponseContent($response->getBody()->getContents())
                );
            }

            throw new UnexpectedResponseStatusException($response->getStatusCode());
        }
    }

    /**
     * @param string $method
     * @param string $route
     * @param array $params
     * @return ResponseInterface
     * @throws GuzzleException
     */
    private function performRequest(string $method, string $route, array $params = null) : ResponseInterface
    {
        $options = $params ? ['json' => $params] : [];
        try {
            $response = $this->client->request($method, $this->domain.'/'.$route, $options);
        } catch (ClientException $e) {
            $response = $e->getResponse();
        } catch (ServerException $e) {
            $response = $e->getResponse();
            $this->logger->log($response->getBody()->getContents(), $this->logger::LEVEL_ERROR);
            throw new TodokeeperRuntimeException('Service unavailable', 0, $e);
        } catch (GuzzleException $e) {
            $this->logger->log($e->getMessage(), $this->logger::LEVEL_ERROR);
            throw new TodokeeperRuntimeException('Unknown error', 0, $e);
        }

        return $response;
    }
//    todo extract to response parser
    private function getFirstErrorFromResponseContent(string $content) : string
    {
        $errors = json_decode($content, true);
        if (!isset($errors[0]['message'])) {
            $this->logger->log(sprintf('Unexpected error content: %s', $content), $this->logger::LEVEL_ERROR);
            return 'Unknown error';
        }

        return $errors[0]['message'];
    }
}
