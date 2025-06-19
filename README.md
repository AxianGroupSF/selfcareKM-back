# SELFCARE COMORES

Selfcare B2B KM is a management application dedicated to the supervision and administration of mobile telephony offers for professional fleets. It enables users to autonomously manage (selfcare) subscriptions, usage, and options related to SMS and voice services, while providing a clear interface for management teams.

## Installation

Requirements (lts version recommended): 

- php 8.4 or higher (don't forget to upgrade your composer to latest version : 2.8 or higher)
- mariadb 11.4 or higher
- docker & docker compose (optional : include phpMyadmin, adminer, mariadb)

1. Step 1 : 
 Clone project into your local using this url :  https://github.com/AxianGroupSF/selfcareKM-back.git

2. Step 2 : 
 Inside project folder make cli : 
 - touch .env
 - touch .env.local (contact an administrator for db config and jwt credentials)
 - composer dump-env local
 - composer i

## Usage

To launch db admin and mariadb if u use docker run command  : 
docker compose up -d

Generate keypair for lexik auth :
php bin/console lexik:jwt:generate-keypair

Update database schema :
php bin/console d:s:u --force

Clear symfony cache:
php bin/console cache:clear

Launch a server symfony : 
symfony serve




