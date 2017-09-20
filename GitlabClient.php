<?php namespace aaronschmied\gitlab;

use yii\base\Component as BaseComponent;

use Gitlab\Client;

class GitlabClient extends BaseComponent {

    /**
     * Constant for authentication method. Indicates the default, but deprecated
     * login with username and token in URL.
     */
    const AUTH_URL_TOKEN = 'url_token';
    /**
     * Constant for authentication method. Indicates the new login method with
     * with username and token via HTTP Authentication.
     */
    const AUTH_HTTP_TOKEN = 'http_token';
    /**
     * Constant for authentication method. Indicates the OAuth method with a key
     * obtain using Gitlab's OAuth provider.
     */
    const AUTH_OAUTH_TOKEN = 'oauth_token';

    /**
     * Url to your gitlab instance
     * @var string
     */
    public $url;

    /**
     * The type of token provided
     * @var string
     */
    public $authMethod = Client::AUTH_HTTP_TOKEN;

    /**
     * [public description]
     * @var [type]
     */
    public $token;

    /**
     * The api client
     * @var \Gitlab\Client
     */
    private $client;

    /**
     * @inheritdoc
     */
    public function init() {
        parent::init();
        $this->initClient();
    }

    /**
     * Initializes the gitlab client
     * @method initClient
     * @return void
     */
    private function initClient() {
        $this->client = Client::create($this->url);
        $this->client->authenticate($this->token, $this->authMethod);
    }

    /**
     * Get the client
     * @method getClient
     * @return \Gitlab\Client
     */
    public function getClient() {
        if (is_null($this->client)) {
            $this->initClient();
        }
        return $this->client;
    }

    /**
     * Access a property of the client
     * @method __get
     * @param  string $key The propertys name
     * @return mixed
     */
    public function __get($key) {
        if ($this->canGetProperty($key)) {
            return $this->$key;
        }
        return $this->getClient()->api($key);
    }
}
