# Doctrine 2 for Nette 3.0

[![Build Status](https://travis-ci.org/rostenkowski/doctrine.svg?branch=master)](https://travis-ci.org/rostenkowski/doctrine)
[![Coverage Status](https://coveralls.io/repos/github/rostenkowski/doctrine/badge.svg)](https://coveralls.io/github/rostenkowski/doctrine)
[![Code Climate](https://codeclimate.com/github/rostenkowski/doctrine/badges/gpa.svg)](https://codeclimate.com/github/rostenkowski/doctrine)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/rostenkowski/doctrine/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/rostenkowski/doctrine/?branch=master)


## Installation

```bash
composer require rostenkowski/doctrine
```
## Setup

```yaml
extensions: 
  doctrine: Rostenkowski\Doctrine\Extension

doctrine:
  entity: 
    - %appDir%/entities 
```
#### SQLite   
```yaml
doctrine:
  connection:
    driver: pdo_sqlite 
    path: %appDir%/db.sqlite 
```

#### PostgreSQL 
```yaml
doctrine:
  connection:
    driver: pdo_pgsql
    host: 127.0.0.1  
    dbname: database
    user: user
    password: ***
```

## Custom Logger 

Mandatory `factory` must be or must return a class implementing the `Doctrine\DBAL\Logging\SQLLogger` interface. 
Optional `args` are passed to the factory or constructor.

```yaml
doctrine:
  logger:
    enabled: yes
    factory: SomeNamespace\CustomLogger 
    args: [ some, parameters ]        
```
## Debugger Bar

Custom debugger panel width and height can be set.  

```yaml
doctrine:
  debugger:
    enabled: yes
    width: 960px
    height: 720px
```

![Screenshot](https://cdn.pbrd.co/images/GNMxfwu.png)
