

#概述<br>
Token-Project的管理后台，包括会员管理、资产管理、交易记录、提现管理等功能。<br>
<br>
<br>
#运行环境需求<br>
该项目基于Laravel5.7开发，运行环境需满足如下要求：<br>
PHP >= 7.1.3<br>
OpenSSL PHP 扩展<br>
PDO PHP 扩展<br>
Mbstring PHP 扩展<br>
Tokenizer PHP 扩展<br>
XML PHP 扩展<br>
Ctype PHP 扩展<br>
JSON PHP 扩展<br>
<br>
Laravel5.7相关文档：https://learnku.com/docs/laravel/5.7/installation/2242#server-requirements<br>
<br>
<br>
#安装部署<br>
1、克隆项目文件到服务器<br>
2、将public目录设置为WEB站点根目录，public/index.php为入口文件<br>
3、Linux系统需要设置storage目录和bootstrap/cache可写权限<br>
4、运行composer install命令安装插件<br>
5、执行迁移文件命令php artisan migrate，添加三个系统表到数据库<br>
6、导入database/admin.sql到数据库，会添加一条管理员数据，账号密码见SQL文件中的注释<br>
7、访问项目地址，输入管理员账号密码进行登录<br>
<br>
Laravel5.7相关文档：https://learnku.com/docs/laravel/5.7/installation/2242#224e2c<br>
                   https://learnku.com/docs/laravel/5.7/migrations/2291#running-migrations<br>