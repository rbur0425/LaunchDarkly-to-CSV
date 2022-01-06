<?php
// include('./.keys.php');

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

class Functions
{
    public static function readInput($argv)
    {
        if (count($argv) != 4) {
            echo "Sorry, you have the wrong number of arguments. Please enter the arguments <environment> <feature flag key> <output file name>.\n";
            exit(1);
        }

        if (!in_array($argv[1], explode(",", $_ENV['environments']))) {
            echo "Not a vaid environment. Please enter a valid environment. Please enter the arguments <environment> <feature flag key> <output file>.\n";
            exit(1);
        }

        if (strpos($argv[3], ".csv") === false) {
            echo "Output file must be a csv.\n";
            exit(1);
        }

        return $argv;
    }

    private function config()
    {
        return \LaunchDarklyApi\Configuration::getDefaultConfiguration()->setApiKey('Authorization', $_ENV['launchdarkly_api_key']);
    }

    private function userConfig()
    {
        // Configure API key authorization: ApiKey
        $config = $this->config();

        $apiInstance = new \LaunchDarklyApi\Api\UsersApi(
            new \GuzzleHttp\Client(),
            $config
        );

        return $apiInstance;
    }

    public function getUsers($proj_key, $env_key, $limit, $searchAfter = null)
    {
        $apiInstance = $this->userConfig();
        $result = $apiInstance->getUsers($proj_key, $env_key, $limit, $searchAfter);

        return json_decode($result, true);
    }

    public function getLastUser($result)
    {
        return substr($result['_links']['next']['href'], (strpos($result['_links']['next']['href'], "searchAfter=") + 12));
    }

    private function parseUserKey($userObject, $env_key)
    {
        return $userObject['user']['key'];
    }

    public function listUsers($users)
    {
        foreach ($users as $user) {
            $userList[] = $user['user']['key'];
        }

        return $userList;
    }

    private function settingsConfig()
    {
        $config = $this->config();

        $apiInstance = new \LaunchDarklyApi\Api\UserSettingsApi(
            new \GuzzleHttp\Client(),
            $config
        );

        return $apiInstance;
    }

    public function getUserFlagSetting($proj_key, $env_key, $user, $featureFlag)
    {
        $apiInstance = $this->settingsConfig();

        $result = $apiInstance->getUserFlagSetting($proj_key, $env_key, $user, $featureFlag);
        $jsonResult = json_decode($result, true);

        return json_encode($jsonResult['_value']);
    }
}
