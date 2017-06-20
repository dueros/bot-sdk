cd $(dirname $0)
dir=$(pwd)
#php ../../tools/gen_us.php $dir/$1 |xargs -0 -i curl -X POST -d{} http://cp01-yuanpeng.epc.baidu.com:8900/api/kvbot
#php ../../tools/gen_us.php $dir/$1 |xargs -0 -i curl -X POST -d{} http://cp01-yuanpeng.epc.baidu.com:8777/kvbot
#php ../../tools/gen_us.php $dir/$1 |xargs -0 -i curl -X POST -d{} http://nj03-wise-2www322.nj03.baidu.com:8185/api/kvbot
php ../../tools/gen_us.php $dir/$1 |xargs -0 -i curl -X POST -d{} http://faqbot.bceapp.com/kvbot
