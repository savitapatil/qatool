rm -rF vendor
composer install

#Clone repository 
url=$1;
reponame=$(echo $url | awk -F/ '{print $NF}' | sed -e 's/.git$//');
#remove repo if already exist
rm -Rf $reponame;

git clone $url $reponame;


cd $reponame;


mkdir reports


cd reports

mkdir codesniffer phpmd copypaste phpdepend phpmetrics

cd ../..

#PHP code sniffer
php vendor/bin/phpcs --standard=phprcs.xml $reponame/app > $reponame/reports/codesniffer/phpcs.txt
php vendor/bin/phpcs --report=source --standard=phprcs.xml -s $reponame/app > $reponame/reports/codesniffer/phpcssummary.csv
php vendor/bin/phpcs --standard=phprcs.xml -s $reponame/app > $reponame/reports/codesniffer/phpcssummary2.txt
#PHP Mess detector
php vendor/bin/phpmd $reponame/app text phprmd.xml > $reponame/reports/phpmd/phpmd.txt
#PHP Copy-paste detector
php vendor/bin/phpcpd $reponame/app > $reponame/reports/copypaste/phpcpd.txt
#PHP Depend
php vendor/bin/pdepend --summary-xml=$reponame/reports/phpdepend/pdepend.xml --jdepend=$reponame/reports/phpdepend/jdepend.svg --overview-pyramid=$reponame/reports/phpdepend/pyramid.svg $reponame/app
#PHP Metrics
php vendor/bin/phpmetrics --report-html=$reponame/reports/phpmetrics/phpmetrics.html --report-xml=$reponame/reports/phpmetrics/phpmetrics.xml --violations-xml=$reponame/reports/phpmetrics/violations.xml $reponame/app
echo "execute script";

php trim.php $reponame
php excel.php $reponame


echo "end script";
exec bash;

