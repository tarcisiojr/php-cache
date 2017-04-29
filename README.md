# PHP-CACHE
[![Build Status](https://travis-ci.org/tarcisiojr/php-cache.svg?branch=master)](https://travis-ci.org/tarcisiojr/php-cache)
[![codecov](https://codecov.io/gh/tarcisiojr/php-cache/branch/master/graph/badge.svg)](https://codecov.io/gh/tarcisiojr/php-cache)
[![Latest Stable Version](https://poser.pugx.org/tarcisiojr/php-cache/v/stable)](https://packagist.org/packages/tarcisiojr/php-cache)
[![Total Downloads](https://poser.pugx.org/tarcisiojr/php-cache/downloads)](https://packagist.org/packages/tarcisiojr/php-cache)
[![Latest Unstable Version](https://poser.pugx.org/tarcisiojr/php-cache/v/unstable)](https://packagist.org/packages/tarcisiojr/php-cache)
[![composer.lock](https://poser.pugx.org/tarcisiojr/php-cache/composerlock)](https://packagist.org/packages/tarcisiojr/php-cache)

Biblioteca para baseada na ideia do pacote [once]('https://github.com/spatie/once'). 
Esta biblioteca permite cachear métodos podendo utilizar diversas estratégias para o comportamento do cache. 

Segue abaixo um exemplo simples de utilização:

```php
<?php

class ClasseQualquer  {

    public function gerarNroAleatorio() {
        return Cache::create(function () {
            return rand(1, 100);
        })
            ->once()        // Cacheia o valor uma única vez.
            ->statefull()   // Cache existente apenas em nivel de request.
            ->scope(false)  // Cache do método entre instâncias.
            ->ttl(10)       // Tempo do cache sera de 10 segundos.
            ->get();        // Executa a obtenção do valor
    }
}

```

## Instalação

Editar seu arquivo composer.json e adicionar a sequinte dependência:
```json

```

## Interface para sistemas de Cache

Algumas estratégias de ache utilizam um sistema próprio para cachear os valores. Estes sistemas podem ser alterados 
configurando a própria estrategia através de uma implementação da interface ```PHP\Cache\API\CacheSystem```.

Como recurso básico existem duas implementações:

* ```PHP\Cache\Core\System\FileCacheSystem```: neste sistema de cache persisente, onde os valores são salvos em um arquivo 
JSON configurado, os valores serão matidos enquanto o arquivo não for excluídos e/ou seus valores expurgados.

* ```PHP\Cache\Core\System\StaticArrayCacheSystem```: neste sistema os valores são persistidos apenas durante a execução 
do script, utilizando-se um array estático para mantê-lo.


## Uso

Configure os sistemas de cache, se desejar:

* ```PHP\Cache\Core\Cache::setStateCacheSystem(CacheSystem) ```: cache utilizado para armazenar os estados das estratégias.
Caso deseje manter este estado através das execuções dos scripts, este deve ser um sistema de cache persistente.

* ```PHP\Cache\Core\Strategy\StatefullCacheStrategy::setCacheSystem(CacheSystem)```: cache persistente utilizado pela estratégia Statefull.
 
* ```PHP\Cache\Core\Strategy\StatelessCacheStrategy::setCacheSystem(CacheSystem)```: cache de request utilizado pela estratégia Stateless.

Em seguida basta selecionar o método (podendo ser static ou não) e/ou função a ser cacheada, utilize o método estático
```PHP\Cache\Core\Cache::create``` para criar uma instância do cache.

A partir da instância de cache você poderá selecionar a estrágias de armazenado e expiração, bem como escopo.

Por fim, para o obter o valor cachead (ou não) basta executar o método ```get()```.

Exemplo:

```php
<?php

class ClasseQualquer  {

    public function gerarNroAleatorio() {
        return Cache::create(function () {
            return rand(1, 100);
        })
            ->once()        // Cacheia o valor uma única vez.
            ->statefull()   // Cache existente apenas em nivel de request.
            ->scope(false)  // Cache do método entre instâncias.
            ->ttl(10)       // Tempo do cache sera de 10 segundos.
            ->get();        // Executa a obtenção do valor
    }
}

```

Respeitando as estratégias configuradas, todas a vezes que método ```gerarNroAleatorio``` da classe ```ClasseQualquer``` 
 for executado, a partir da segunda execução, o valor retornado será idêntico ao primeiro valor retornado. Este valor se
  será expirado após 10 segundos devido a extratégia ```ttl(10)```, portanto após este tempo será retornado um novo valor.
