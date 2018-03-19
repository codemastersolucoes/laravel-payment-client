# Cliente OpenId

## Instalação

Composer:

```
composer require univicosa/laravel-payment-client
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