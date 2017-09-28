# Cliente OpenId

## Instalação

Para instalar pelo Composer, primeiro execute:

``` bash
composer config repositories.payments '{"type":"vcs", "url":"http://bitbucket.univicosa.com.br:7990/scm/pag/cliente.git"}'
```

Esse comando adiciona o repositório do Bitbucket ao `composer.json`.

Em seguida execute o seguinte comando:

``` bash
composer config secure-http false
```

Esse comando configura o composer para aceitar conexões via `http`.

Por fim execute o comando `require` a seguir:

```bash
composer require payments/client
```

### Adicionar o _Service Provider_

Em seguida registre o _service provider_ no arquivo `config/app.php`.

```php
'providers' => [
    
    /*
     *    ...
     */
     
    Payments\Client\Providers\ClientServiceProvider::class,
    
    /*
     *    ...
     */
],
```

Para publicar o arquivo de configuração execute o seguinte comando:

```bash
php artisan vendor:publish --tag=payment
```

O arquivo de configuração `config/payment.php` será gerado.

**Obs.:** Não esquecer de alterar o nome da aplicação no arquivo `.env`, a variável `APP_NAME`.