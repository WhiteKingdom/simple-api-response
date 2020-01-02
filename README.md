<h1 align="center"> simple-api-response </h1>

> 根据 手摸手教你让 Laravel 开发 API 更得心应手 https://learnku.com/articles/25947#9ea6b3 文章所写

方便个人使用

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
2. 在 controller 里添加 use 
```php
 use \Whiteki\SimpleApiResponse\ApiResponse;
```

