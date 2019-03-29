#!/bin/bash
#
# init_local_data_dir.sh <path>
#
# Initializes a data directory hierarchy for a local Savane instance.

root="$1"
if [[ -z "$root" ]]; then
	echo &>2 "error: you must supply a directory to create"
	exit 1
fi

if [[ -e "$root" ]]; then
	echo &>2 "error: directory already exists, not clobbering: $root"
	exit 1
fi

echo "Creating Savane data directories at $root..."

# There's nothing special about this particular dir hierarchy arrangement;
# it's just a convention that apjanke made up.
mkdir -p "$root/var"
mkdir -p "$root/var/uploads"
mkdir -p "$root/inc"
mkdir -p "$root/appdatadir"
mkdir -p "$root/appdatadir/trackers_attachments"

cat <<EOSTR
Created Savane data directories at $root.

Now add this to your local2/etc-savane/.savane.conf.php file:

\$sys_appdatadir = "$root/appdatadir";
\$sys_upload_dir = "$root/var/uploads";
\$sys_trackers_attachments_dir = "\$sys_appdatadir/trackers_attachments";

(Run "make localconf" if local2/etc-savane/.savane.conf.php does not exist.)

EOSTR
