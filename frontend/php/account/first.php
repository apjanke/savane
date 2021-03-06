<?php
# First valid login, after account confirmation.
# 
# Copyright (C) 1999-2000 The SourceForge Crew
# Copyright (C) 2002-2006 Mathieu Roy <yeupou--gnu.org>
# Copyright (C) 2007  Sylvain Beucler
# Copyright (C) 2017, 2019 Ineiev
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
register_globals_off();

# TRANSLATORS: the argument is system name (like Savannah).
site_user_header(array('title'=>sprintf(_("Welcome to %s"),$GLOBALS['sys_name']),
                       'context'=>'account'));

# TRANSLATORS: the argument is system name (like Savannah).
print '<p>' . sprintf(_("You are now a registered user on %s."),
                      $GLOBALS['sys_name']) . "</p>\n";

print '<p>'
  .sprintf(
# TRANSLATORS: the argument is system name (like Savannah).
_("As a registered user, you can participate fully in the activities
on the site.  You may now post items to issue trackers in %s, sign on as a
project member, or even start your own project."), $GLOBALS['sys_name'])
  . "</p>\n";

utils_get_content("account/first");

print '<p>' . _("Enjoy the site.") . "</p>\n";

site_user_footer(array());
