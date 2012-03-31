echo "-- Setting environment variables --";
basedir=$WORKSPACE;
PHING_HOME=$basedir/lib/phing;
PHP_CLASSPATH=$PHING_HOME/classes;
phing=$PHING_HOME/bin/phing;
phpcodesniffer=$basedir/lib/phpcodesniffer/;
phpcpd=$basedir/lib/phpcpd/;
phpdepend=$basedir/lib/phpdepend/;
phpmd=$basedir/lib/phpmd/;
phpunit=$basedir/lib/phpunit/;
phpdoc=$basedir/lib/phpdoc/;
pear=$basedir/lib/pear/;
tar=$basedir/lib/archive_tar/;

PATH=$PATH:$phpcodesniffer:$phpcpd:$phpdepend:$phpmd:$phpunit:$pear:$tar:$phpdir:$phpdoc;
echo "-- Starting the execution of Phing (Project Blank) --";

$phing build;
#$phing build generate.report.doc generate.report.sniffer;

#$phing run.report.test.unit.only;
#$phing run.report.test.integrated.only;
#$phing run.report.tests.only;

#$phing generate.report.sniffer.only;
#$phing generate.report.doc.only;

#$phing generate.report.all;

#$phing prepare.database;