# STATUS MESSAGES
## DESCRIPTION
Application that periodically triggers an API that sends SMS messages 
to clients after receipt generation, receipt payment and subject registration


## BUILD WITH 
- PHP
- ORACLE
- LARAVEL

## PREREQUISITES AND FEATURES
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

### Install all dependences of this project
composer install

### Set environment variables
Configure .env file with all credentials what project numeneed.

### Configure Linux crontab
On Ubuntu or whatelse Linux server what you are using configure the crontab to execute periodically the next command:
php artisan schedule:run  

## PROJECT USE
To use this project the only one that is necesary is the next command: 
php artisan schedule:run  

## CONTRIBUTORS
```
- Cristian Felipe Delgado Quiroga / Fullstack Developer
- Yilder Nicolas Perdomo Valderrama / DevOps Developer
```




  
