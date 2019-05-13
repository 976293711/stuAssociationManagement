## WislonJ 社团管理后台

## 环境部署

#### 0. 部署LNMP，[参考这篇博客](https://blog.csdn.net/STFPHP/article/details/53492723)

#### 1. 安装依赖包：```composer install```

#### 2. 执行自动加载命令：```composer dump-autoload```

#### 3. 配置.env
- ```cp .env.example .env```
- 执行```php artisan key:generat```
- 配置```APP_NAME=meizu-wxxcx```
- 配置```APP_URL=该主机使用的域名```

#### 4. 配置数据库
- 新建数据库 ```meizu-wxxcx```（编码为：utf8mb4）
- 数据库信息配置到.env
- 执行迁移: ```php artisan migrate```

#### 5. 配置小程序AppID、AppSecret：```cp config/wxxcx.php.example config/wxxcx.php```

#### 6. 配置nginx，配置hosts
```
server {
        listen       80;
        server_name  meizu-wxxcx.wen;

        root /Users/wenjiachengy/www/meizu-wxxcx/public;
        index index.php index.html index.htm;

        location / {
            # 修改为 Laravel 转发规则，否则PHP无法获取$_GET信息，提示404错误
            try_files $uri $uri/ /index.php?$query_string;
        }

        error_page   500 502 503 504  /50x.html;
        location = /50x.html {
            root   html;
        }

        location ~ \.php$ {
            fastcgi_split_path_info ^(.+\.php)(/.+)$;
            fastcgi_intercept_errors on;
            fastcgi_pass   127.0.0.1:9001;
            fastcgi_index  index.php;
            fastcgi_param  SCRIPT_FILENAME  /Users/wenjiachengy/www/meizu-wxxcx/public$fastcgi_script_name;
            include        /usr/local/etc/nginx/fastcgi_params;
        }
    }
```

#### 7. 新建存储录音文件夹：mkdir -p /disk/meizu-wxxcx/record

#### 8. .env配置相关（根据实际情况调整）

```ACTIVITY_START_TIME``` 活动开始时间

```ACTIVITY_END_TIME``` 活动结束时间

```RECORD_LIMIT``` 是否限制每天一次录音

```QUEUE_DRIVER=redis``` 队列异步处理，sync为同步执行

### 9. 运行队列
```php artisan queue:work --daemon --quiet --queue=high,low --delay=3 --sleep=3```

### 10. 运行Supervisor 守护队列进程（刷新Supervisor 的配置信息）
```sudo apt-get install supervisor``` ubuntu下安装supervisor命令
```在/etc/supervisor/conf.d 目录下创建laravel-worker.conf 文件```
```
#记得删除注释
[program:laravel-worker]
process_name=%(program_name)s_%(process_num)02d
command=php ~/artisan queue:work sqs --sleep=3 --tries=3 --daemon
#(common 注意 使用artisan路径的绝对值)
autostart=true
autorestart=true
#(登录用户)
user=forge
numprocs=8
redirect_stderr=true
#(注意路径权限)
stdout_logfile=/home/forge/app.com/worker.log
```
```
配置好上面的conf 文件后 输入下面的命令
sudo supervisord -c /etc/supervisord.conf  (启动服务端的supervisord服务相当于mysqld)
sudo supervisorctl -c /etc/supervisor/supervisord.conf (启动supervisord的客户端相当于mysql)
sudo supervisorctl reread (重新加载配置文件)
sudo supervisorctl update 
sudo supervisorctl start laravel-worker:*   (启动程序名为laravel-worker:*的服务)

```
```[参考这篇博客] (http://laravelacademy.org/post/3252.html)```
