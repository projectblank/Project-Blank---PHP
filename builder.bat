@echo off

echo -- Setting environment variables --

set basedir=%cd%
set PHING_HOME=%basedir%/lib/phing
set PHP_CLASSPATH=%PHING_HOME%/classes
set phing=%PHING_HOME%/bin/phing.bat
set phpdir=c:/xampp/php/
set phpcodesniffer=%basedir%/lib/phpcodesniffer/
set phpcpd=%basedir%/lib/phpcpd/
set phpdepend=%basedir%/lib/phpdepend/
set phpmd=%basedir%/lib/phpmd/
set phpunit=%basedir%/lib/phpunit/
set phpdoc=%basedir%/lib/phpdoc/
set pear=%basedir%/lib/pear/
set tar=%basedir%/lib/archive_tar/

set PATH=%phpcodesniffer%;%phpcpd%;%phpdepend%;%phpmd%;%phpunit%;%pear%;%tar%;%phpdir%;%phpdoc%

echo -- Starting the execution of Phing (Project Blank) --

%phing% build
rem %phing% build generate.report.doc generate.report.sniffer

rem %phing% run.report.test.unit.only
rem %phing% run.report.test.integrated.only
rem %phing% run.report.tests.only

rem %phing% generate.report.sniffer.only
rem %phing% generate.report.doc.only

rem %phing% generate.report.all

rem %phing% prepare.database