cd $(dirname $0)
dir=$(pwd)
php ../../tools/gen_us.php $dir/$1 |xargs -0 -i curl -X POST -d{} http://0.0.0.0:8002
#hp ../../tools/gen_us.php $dir/$1 |xargs -0 -i curl -X POST -d{} http://cp01-tianlongdumi.epc.baidu.com:8002/
