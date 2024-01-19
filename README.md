<h1 align="center">PHP SMS</h1> 
<p align="center">
    <a href="http://www.a3-mall.com">
        <img src="https://img.shields.io/badge/Website-A3Mall-important.svg" />
    </a>
<a href="http://www.a3-mall.com">
        <img src="https://img.shields.io/badge/Licence-GPL3.0-green.svg" />
    </a>
    <a href="http://www.a3-mall.com">
        <img src="https://img.shields.io/badge/Edition-v1.0-blue.svg" />
    </a>
</p>
<p align="center">    
    <b>如果本SMS开发包对您有所帮助，您可以点右上角 "Star" 支持一下 谢谢！</b>
</p>

#### 环境要求
- PHP >= 7.2.5
- Composer

#### 安装
```
composer require "xzncit/sms:^1.0"
```

#### 基本使用
```php
include "vendor/autoload.php";

use xzncit\SMS;

try {
    // SMS
    $app = SMS::create("tencent",[ ... ]);
    
    // 上传资源
    $response = $app->send([]);
    
    // 返回信息
    var_dump($response);
}catch (\Exception $ex){
    echo("error: ".$ex->getMessage());
}

```

#### 文档
文档整理中...

 **bug反馈**

如果您使用过程中发现BUG或者其他问题都欢迎大家提交Issue,或者发送邮件给我 158373108@qq.com，我们将及时修复并更新。
