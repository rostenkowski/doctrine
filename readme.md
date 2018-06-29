# Doctrine 

Latest [Doctrine](http://www.doctrine-project.org/) integration for [Nette Framework](https://nette.org) 

[![Build Status](https://travis-ci.org/rostenkowski/doctrine.svg?branch=master)](https://travis-ci.org/rostenkowski/doctrine)
[![Coverage Status](https://coveralls.io/repos/github/rostenkowski/doctrine/badge.svg)](https://coveralls.io/github/rostenkowski/doctrine)
[![Code Climate](https://codeclimate.com/github/rostenkowski/doctrine/badges/gpa.svg)](https://codeclimate.com/github/rostenkowski/doctrine)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/rostenkowski/doctrine/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/rostenkowski/doctrine/?branch=master)
![PHP version from PHP-Eye](https://img.shields.io/php-eye/rostenkowski/doctrine.svg)




## Installation
```bash
composer require rostenkowski/doctrine
```
## Usage

```yaml
extensions: 
  doctrine: Rostenkowski\Doctrine\Extension
```
### Mapped entities

```yaml
doctrine:
  entities: 
    - %appDir%/entities
    - %baseDir%/libs/more-entities
```
### SQLite Connection   
```yaml
doctrine:
  connection:
    driver: pdo_sqlite 
    path: %appDir%/db.sqlite 
```

### PostgreSQL Connection
```yaml
doctrine:
  connection:
    driver: pdo_pgsql
    host: 127.0.0.1  
    dbname: database
    user: user
    password: ***
```

### Setup Custom Logger 
Mandatory `factory` must be or must return a class implementing the `Doctrine\DBAL\Logging\SQLLogger` interface. 
Optional `args` are passed to the factory or constructor.
```yaml
doctrine:
  logger:
    enabled: yes
    factory: SomeNamespace\CustomLogger 
    args: [ some, parameters ]        
```
### Tracy Debugger Bar
Custom debugger panel width and height can be set.  
```yaml
doctrine:
  debugger:
    enabled: yes
    width: 960px
    height: 720px
```

![Screenshot](https://cdn.pbrd.co/images/GNMxfwu.png)
