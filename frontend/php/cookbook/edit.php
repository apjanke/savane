<?php
# This file is part of the Savane project
# <http://gna.org/projects/savane/>
#
# $Id: index.php 4567 2005-06-30 17:19:37Z toddy $
#
#  Copyright 2005 (c) Mathieu Roy <yeupou--gnu.org>
# 
# The Savane project is free software; you can redistribute it and/or
# modify it under the terms of the GNU General Public License
# as published by the Free Software Foundation; either version 2
# of the License, or (at your option) any later version.
#
# The Savane project is distributed in the hope that it will be useful,
# but WITHOUT ANY WARRANTY; without even the implied warranty of
# MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
# GNU General Public License for more details.
#
# You should have received a copy of the GNU General Public License
# along with the Savane project; if not, write to the Free Software
# Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA

#input_is_safe();
#mysql_is_safe();

# This page will behave like index does for most trackers
require_once('../include/init.php');
require_once('../include/trackers/general.php');
require(trackers_bastardinclude("index"));
