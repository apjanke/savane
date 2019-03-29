# Based on gettext-0.16.1/gettext-tools/examples/hello-php
# (public domain)

# General automake options.
AUTOMAKE_OPTIONS = gnits
ACLOCAL_AMFLAGS = -I autotools/m4

# The list of subdirectories containing Makefiles.
SUBDIRS = backend etc frontend lib autotools/m4 po local

EXTRA_DIST = bootstrap AUTHORS.Savannah AUTHORS.SourceForge BUILD-DEB \
	REQUIREMENTS INSTALL.verbose \
	db debian devel doc update tests

database:
	( \
	  echo "CREATE DATABASE $(SV_DB_NAME) DEFAULT CHARACTER SET utf8;"; \
	  echo "USE $(SV_DB_NAME);"; \
	  cat db/mysql/table_*.structure; \
	  cat db/mysql/table_*.initvalues; \
	  cat db/mysql/bootstrap.sql; \
	) | $(SV_DB_CMD) $(SV_DB_OPTIONS)
# TODO: create the siteadmin group here

dist-hook:
	find $(distdir) -type d -name '.svn' -o -name '*~' | xargs -r rm -rf

# TODO: This _should_ be done as part of the default "make" target,
# but I can't figure out how to get that to work. -apjanke
EXTRA_PROGRAMS = localconf
localconf_SOURCES = local2/etc-savane/.savane.conf.php.orig
.PHONY: localconf
localconf:
	cp local2/etc-savane/.savane.conf.php.orig local2/etc-savane/.savane.conf.php
	cp local2/etc-savane/savane-dev-db.php.orig local2/etc-savane/savane-dev-db.php
