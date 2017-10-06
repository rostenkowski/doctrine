# Doctrine 2 for Nette 3.0

[![Build Status](https://travis-ci.org/rostenkowski/doctrine.svg?branch=master)](https://travis-ci.org/rostenkowski/doctrine)
[![Coverage Status](https://coveralls.io/repos/github/rostenkowski/doctrine/badge.svg)](https://coveralls.io/github/rostenkowski/doctrine)
[![Code Climate](https://codeclimate.com/github/rostenkowski/doctrine/badges/gpa.svg)](https://codeclimate.com/github/rostenkowski/doctrine)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/rostenkowski/doctrine/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/rostenkowski/doctrine/?branch=master)


## Installation

```bash
composer require rostenkowski/doctrine
```
## Configuration

```yaml
extensions: 
  doctrine: Rostenkowski\Doctrine\Extension 

doctrine:
  default:
    connection:
      driver: pdo_sqlite 
      path: %appDir%/db.sqlite 
      host:  
      dbname: 
      user: 
      password: 
    entity: 
      - %appDir%/entities 
    logger:
      enabled: yes
    debugger:
      enabled: no
```

## Advanced log setup
```yaml
doctrine:
  default:
    # ...
    logger:
      enabled: yes
      factory: MyNamespace\MyLogger # should create Doctrine\DBAL\Logging\SQLLogger  
      args: [ '...', '...' ]        # optional arguments passed to the factory         
```
