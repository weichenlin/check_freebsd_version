#!/usr/bin/env -S php -d allow_url_fopen=1 -d disable_functions=""
<?php

if (!extension_loaded("openssl")) {
    echo "this program require php-openssl\n";
    exit(1);
}

error_reporting(E_ERROR);

// find current version like 10.1-RELEASE-p10
//$kVersion = shell_exec('freebsd-version -k');
$uVersion = trim(shell_exec('freebsd-version -u'));

// download most recent newvers.sh from FreeBSD svn
$version = substr($uVersion, 0, strpos($uVersion, '-'));
$newvers_content = file_get_contents("https://svnweb.freebsd.org/base/releng/$version/sys/conf/newvers.sh?view=co&content-type=text%2Fplain");
if (false === $newvers_content) {
    echo "can not download data from FreeBSD SVN, try again later?\n";
    exit(1);
}

// find string like BRANCH="RELEASE-p21"
$branch = preg_replace('#.*BRANCH="(RELEASE-p\d+)".*#s', '$1', $newvers_content);
$newVersion = "$version-$branch";

if ($newVersion === $uVersion) {
    echo "FreeBSD are up to date\n";
    exit(0);
} else {
    echo "new version $newVersion available, current version $uVersion\n";
    exit(2);
}

// todo: add unsupported check
// https://www.freebsd.org/security/unsupported.html
