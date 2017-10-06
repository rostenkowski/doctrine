# Doctrine

*Doctrine 2 integration for Nette Framework*

[![Build Status](https://travis-ci.org/rostenkowski/doctrine.svg?branch=master)](https://travis-ci.org/rostenkowski/doctrine)
[![Coverage Status](https://coveralls.io/repos/github/rostenkowski/doctrine/badge.svg)](https://coveralls.io/github/rostenkowski/doctrine)
[![Code Climate](https://codeclimate.com/github/rostenkowski/doctrine/badges/gpa.svg)](https://codeclimate.com/github/rostenkowski/doctrine)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/rostenkowski/doctrine/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/rostenkowski/doctrine/?branch=master)

```bash
composer require rostenkowski/doctrine
```
## Usage
```yaml
extensions: 
	doctrine: Rostenkowski\Doctrine\Extension(%debugMode%) 

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
			enabled: yes
```
