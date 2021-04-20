FROM 192.168.6.2:5000/runtimes/php:8.0.3-apache as base

#
#--------------------------------------------------------------------------
# 安装 PHP composer
#--------------------------------------------------------------------------
#
RUN curl -sL https://getcomposer.org/installer | php -- --install-dir /usr/bin --filename composer

#
#--------------------------------------------------------------------------
# 添加源码
#--------------------------------------------------------------------------
#
WORKDIR /scripts
COPY src .

#####################################
# 安装php依赖模块，发布队列
#####################################
RUN composer install
RUN php artisan horizon:publish

#
#--------------------------------------------------------------------------
# 完成
#--------------------------------------------------------------------------
#
FROM 192.168.6.2:5000/runtimes/php:8.0.3-apache

EXPOSE 80

WORKDIR /usr/www/html

COPY --from=base /scripts .

CMD ["apache2-foreground"]