if [ $# -eq 1 ]
then
    vendor/phpunit/phpunit/phpunit --filter=$1
else
    vendor/phpunit/phpunit/phpunit "$@"
fi