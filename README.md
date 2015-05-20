MingLibrary for Phalcon
======

## Version

PHP 5.4.x/5.5.x/5.6.x

Phalcon 1.x/2.x  


## YAML

~~~
sudo yum install libyaml libyaml-devel

sudo pecl install YAML
sudo vim /etc/php.d/yaml.ini
extension=yaml.so
~~~


## Phalcon YAML

~~~
ming:
  swf:
    dir: DIR PATH
  bitmap:
    dir: DIR PATH
~~~


## Phalcon config.php

~~~
$yml = yaml_parse_file(XXX.yml);
~~~


