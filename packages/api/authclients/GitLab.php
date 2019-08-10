<?php
namespace presentator\api\authclients;

use yii\authclient\OAuth2;

/**
 * GitLab allows authentication via GitLab OAuth2.
 *
 * In order to use GitLab OAuth2 you must register your application at <https://gitlab.com/profile/applications>.
 *
 * Example application configuration:
 *
 * ```php
 * 'components' => [
 *     'authClientCollection' => [
 *         'class' => \yii\authclient\Collection::class,
 *         'clients' => [
 *             'gitlab' => [
 *                 'class'        => \presentator\api\authclients\GitLab::class,
 *                 'domain'       => 'https://gitlab.com'
 *                 'clientId'     => 'gitlab_client_id',
 *                 'clientSecret' => 'gitlab_client_secret',
 *             ],
 *         ],
 *     ],
 *     // ...
 * ]
 * ```
 *
 * @see https://docs.gitlab.com/ee/api/oauth2.html
 * @see https://gitlab.com/profile/applications
 *
 * @author Dmitriy Kuts <me@exileed.com>
 * @author Gani Georgiev <gani.georgiev@gmail.com>
 */
class GitLab extends OAuth2
{
    /**
     * Domain/base url to the GitLab instance.
     *
     * @var string
     */
    public $domain = 'https://gitlab.com';

    /**
     * {@inheritdoc}
     *
     * Will be auto prefixed with `$domain` on init.
     */
    public $authUrl = '/oauth/authorize';

    /**
     * {@inheritdoc}
     *
     * Will be auto prefixed with `$domain` on init.
     */
    public $tokenUrl = '/oauth/token';

    /**
     * {@inheritdoc}
     *
     * Will be auto prefixed with `$domain` on init.
     */
    public $apiBaseUrl = '/api/v4';

    /**
     * {@inheritdoc}
     */
    public $scope = 'read_user';

    /**
     * {@inheritdoc}
     */
    public function init()
    {
        parent::init();

        if (!$this->domain) {
            throw new InvalidConfigException('GitLab Oauth2 domain must be set.');
        }

        // normalize props
        $this->domain     = rtrim($this->domain, '/');
        $this->authUrl    = $this->domain . $this->authUrl;
        $this->tokenUrl   = $this->domain . $this->tokenUrl;
        $this->apiBaseUrl = $this->domain . $this->apiBaseUrl;
    }

    /**
     * {@inheritdoc}
     */
    protected function initUserAttributes()
    {
        return $this->api('user', 'GET');
    }

    /**
     * {@inheritdoc}
     */
    protected function defaultName()
    {
        return 'gitlab';
    }

    /**
     * {@inheritdoc}
     */
    protected function defaultTitle()
    {
        return 'GitLab';
    }
}
