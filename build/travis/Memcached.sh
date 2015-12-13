set -ev

INI_PATH=`php -r "echo php_ini_loaded_file();"`

docker run -d -p 11211:11211 memcached

# install ext-memcached requirements:
# "error: memcached support requires ZLIB"
# "error: memcached support requires libmemcached"
sudo apt-get -y install zlib1g-dev libmemcached-dev
printf "no --disable-memcached-sasl\n" | pecl install memcached

echo 'extension="memcached.so"' >> $INI_PATH
