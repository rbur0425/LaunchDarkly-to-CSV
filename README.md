# About This Project
This project stemmed from the problem of trying to figure out which user was included in the percentage rollouts in LaunchDarkly. This project gets all the users in your project and then iterates through them based on the feature flag you pass through in the command line. This is a command line application that will output to the CSV file you choose.

Is not compatible with PHP 8.1! Please use PHP 7.4

# Example Usage

`php src/main.php <environment> <feature flag key> <output file name>`

An full example may look like this - 

`php src/main.php dev new.login.flag.enabled login_flag_users.csv`

# Requirements
It is required for you to have PHP CLI 7.1 or greater installed 

# Installation

1. Clone repo
2. Rename .env.example to .env and add your LaunchDarkly API Key
An example is
`launchdarkly_api_key="<YOUR_API_KEY>"`
3. Add your LD PRojects to environments
An example is
`environments="dev,uat,production"`
4. Run composer install
5. Run this from the command line using the format above

# TO DO

1. Add tests
2. Look at downgrading to sdk 5.3 so we can get users object in call
3. add doc blocks