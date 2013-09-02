#!/bin/bash

DIR=$(dirname $0)
cd $DIR
DIR=$(pwd)

# clean
rm -rf avisota-contao-nightly-*.tar \
	system/config/localconfig.php \
	composer/cache \
	composer/composer.lock \
	composer/vendor \
	bin \
	system/drivers \
	system/modules

# create empty localconfig
echo '<?php' > system/config/localconfig.php

# update or install composer
cd composer
[ -f composer.phar ] && php composer.phar self-update
[ ! -f composer.phar ] && curl -sS https://getcomposer.org/installer | php

# install packages
php composer.phar install

# clean vcs
find -mindepth 2 -name .git | while read GIT; do rm -rf "$GIT"; done

# move vendor to avisota
cd $DIR
mv composer/vendor system/modules/avisota/vendor
echo "<?php

include(dirname(__DIR__) . '/vendor/autoload.php');
" > system/modules/avisota/config/config.php_
sed '1d' system/modules/avisota/config/config.php >> system/modules/avisota/config/config.php_
sed '1d' system/config/localconfig.php >> system/modules/avisota/config/config.php_
mv system/modules/avisota/config/config.php_ system/modules/avisota/config/config.php

# create archive
tar jcf avisota-contao-nightly-$(date '+%Y-%m-%d').tar.bz2 \
	bin \
	system/drivers \
	system/modules
zip -r -9 avisota-contao-nightly-$(date '+%Y-%m-%d').zip \
	bin \
	system/drivers \
	system/modules
