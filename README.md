<h1 align="center"> simple-api-response </h1>

> 根据 手摸手教你让 Laravel 开发 API 更得心应手 https://learnku.com/articles/25947#9ea6b3 文章所写

> sql日志记录为 https://github.com/overtrue/laravel-query-logger 

方便个人使用

## Include
- make:api-controller
- make:repository
- make:service

## Installing

```shell
$ composer require whiteki/simple-api-response
```

## Usage

1. 修改 bootstrap/app.php 文件下绑定的异常处理类
```php
$app->singleton(
    Illuminate\Contracts\Debug\ExceptionHandler::class,
    \Whiteki\SimpleApiResponse\Exceptions\Handler::class
);
```
2. 在 controller 里 use trait
```php
use \Whiteki\SimpleApiResponse\ApiResponse;
```
3. 新增 channel sqllog
```php
'sqllog' => [
    'driver' => 'daily',
    'path' => storage_path('logs/sql.log'),
    'level' => 'debug',
    'days' => 14,
]
```
