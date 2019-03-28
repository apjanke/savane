#!/bin/bash

# Copyright (C) 2019 Andrew Janke (andrew@apjanke.net)
# This file is part of Savane.
#
# Savane is free software: you can redistribute it and/or modify
# it under the terms of the GNU Affero General Public License as
# published by the Free Software Foundation, either version 3 of the
# License, or (at your option) any later version.
#
# Savane is distributed in the hope that it will be useful,
# but WITHOUT ANY WARRANTY; without even the implied warranty of
# MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
# GNU Affero General Public License for more details.
#
# You should have received a copy of the GNU Affero General Public License
# along with this program.  If not, see <http://www.gnu.org/licenses/>.

# run-local-dev-macos-brew.sh
#
# Runs Savane from this local build, on macOS systems using Homebrew for installing
# dependencies.
#
# This depends on the non-standard Homebrew formula "php@5.6" to provide the 
# obsolete version of PHP that Savane needs. This is not available in
# homebrew-core, because it is EOL/unsupported. It can be acquired from 
# Andrew Janke's custom tap:
#
#   brew tap apjanke/personal
#   brew install php@5.6

if ! which brew &> /dev/null; then
	echo &>2 "error: brew is not installed"
	exit 1
fi

PATH=$(brew --prefix php@5.6)/bin:$(brew --prefix gettext)/bin:$PATH
./run-local-dev.sh
