<h1 align="center"> laravel-goeasy </h1>

## laravel-goeasy
[GoEasy](https://www.goeasy.io/)的[Laravel广播](https://laravel.com/docs/10.x/broadcasting)驱动

## 安装需求

* PHP >= 8.0
* Laravel >= 9.x | 10.x

## 安装

```shell
$ composer require vergil-lai/laravel-goeasy
```

## Laravel使用

### 配置

在 `config/broadcasting.php` 的`connections`中添加以下配置：

```php
'goeasy' => [
    'driver' => 'goeasy',
],
```

在`configs/app.php`的`aliases`中加入：

```php
'GoEasy' => \VergilLai\LaravelGoeasy\Facades\GoEasy::class,
```

在[GoEasy](https://www.goeasy.io/)中创建应用，获取`Common Key`和`Subscribe Key`，
或者使用[OTP](https://docs.goeasy.io/2.x/common/otp)的`Rest key`和`Secret key`

在`.env`文件加入：

```dotenv
BROADCAST_DRIVER=goeasy
GOEASY_HOST=https://rest-hz.goeasy.io 
## 或rest-singapore.goeasy.io
GOEASY_COMMON_KEY=your-common-key
GOEASY_SUBSCRIBE_KEY=your-subscribe-key
```
或

```dotenv
GOEASY_REST_KEY=your-rest-key
GOEASY_SECRET_KEY=your-secret-key
```

这样，就可以使用`broadcast`广播事件了。
详情请参阅[Laravel文档](https://laravel.com/docs/10.x/broadcasting)。

### 获取[OTP](https://docs.goeasy.io/2.x/common/otp)和[AccessToken](https://docs.goeasy.io/2.x/common/security/authorization)

使用`GoEasy`Facade方法：

```php
GoEasy::otp();
GoEasy::pubsub()->makeAccessToken('your user id', 'channel name', $readable, $writeable),
```


## 客户端使用

请参阅[GoEasy官方文档](https://docs.goeasy.io/2.x/)。


## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.