<?php
# Subversion instruction page.
# 
# Copyright (C) 2017 Ineiev
#
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

require_once('../include/init.php');
require_once('../include/http.php');
require_once('../include/vcs.php');

# TRANSLATORS: This string is used in the context of
# "Browsing the Subversion repository" and "You can browse the Subversion repository",
# "Getting a copy of the Subversion repository", see include/vcs.php.
vcs_page (_('Subversion'), 'svn', $group_id);

?>
