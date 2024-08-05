# STATUS MESSAGES
## DESCRIPTION
Application that periodically triggers an API that sends SMS messages to clients

## BUILD WITH 
- PHP
- ORACLE
- LARAVEL

## PREREQUISITES 
- PHP 7.4
- 12c Instant Client
- Text editor (vim or nano)
- composer package gesture

## SETTINGS
- Set path of Instant Client on environment variables
- Uncomment oci8_12c extension on php.ini

## INSTALLATION
### Clone repository: 
git clone https://github.com/fabricaSoftwareCUN/App-StatusMessages.git

### Install all dependences on your project with the next command:
composer install

### Set environment variables::
Configure .env file with all credentials what project need.

### Configure Linux crontab
On Ubuntu or whatelse Linux server what you are using configure the crontab to execute periodically the next command:
php artisan schedule:run  



  
