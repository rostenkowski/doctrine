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
  default:
    connection:
      ...
    entity: 
      - %appDir%/entities 
```
#### SQLite   
```yaml
doctrine:
  default:
    connection:
      driver: pdo_sqlite 
      path: %appDir%/db.sqlite 
    ... 
```

#### PostgreSQL 
```yaml
doctrine:
  default:
    connection:
      driver: pdo_pgsql
      host: 127.0.0.1  
      dbname: database
      user: user
      password: ***
    ...
```

## Custom Logger 

- mandatory `factory` must be or must return a class implementing the `Doctrine\DBAL\Logging\SQLLogger` interface
- optional `args` are passed to the factory or constructor

```yaml
doctrine:
  default:
    ...
    logger:
      enabled: yes
      factory: SomeNamespace\CustomLogger 
      args: [ some, parameters ]        
```
## Debugger Bar

- custom debugger panel width and height can be set this way: 

```yaml
doctrine:
  default:
    ...
    debugger:
      enabled: yes
      width: 960px
      height: 720px
```
