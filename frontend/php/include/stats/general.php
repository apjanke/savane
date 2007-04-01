<?php
# This file is part of the Savane project
# <http://gna.org/projects/savane/>
#
# $Id$
#
#  Copyright 2004-2004 (c) Mathieu Roy <yeupou--gnu.org>
#                          Yves Perrin <yves.perrin--cern.ch>
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

function stats_get_generic($res_count)
{
  if (db_numrows($res_count) > 0) 
    {
      $row_count = db_fetch_array($res_count);
      return $row_count['count'];
    } 
  else 
    {
      return _("Error");
    }
}

function stats_getprojects_active($type_id="") 
{
  return stats_getprojects($type_id);
}

function stats_getprojects_bytype_active($type_id) 
{
  return stats_getprojects_active($type_id);
}

function stats_getprojects_pending() 
{
  return stats_get_generic(db_query("SELECT count(*) AS count FROM groups WHERE status='P'"));
}

function stats_getprojects_total() 
{
  return stats_getprojects();
}

function stats_getprojects($type_id="", $is_public="",$period="") 
{
  $params = array();
  if ($type_id)
    {
      $type_id = " AND type=?";
      array_push($params, $type_id);
    }
  if ($is_public != "")
    {
      $is_public = " AND is_public=?";
      array_push($params, $is_public);
    }
  if ($period)
    {
      $period = " AND ?";
      array_push($params, $period);
    }

  return stats_get_generic(
    db_execute("SELECT count(*) AS count FROM groups WHERE status='A' $type_id $is_public $period", $params));
}

function stats_getusers($period="") 
{
  $param = array();
  if ($period)
    {
      $period = " AND ?";
      $param = array($period);
    }

  return stats_get_generic(
    db_execute("SELECT count(*) AS count FROM user WHERE status='A' $period", $param));
}

function stats_getitems($tracker, $only_open="",$period="")
{
  $params = array();
  if ($only_open)
    {
      $only_open = " AND status_id=?";
      array_push($params, $only_open);
    }

  if ($period)
    {
      $period = " AND ?";
      array_push($params, $period);
    }
  
 
  return stats_get_generic(
    db_execute("SELECT count(*) AS count FROM $tracker WHERE group_id<>'100' AND spamscore < 5 $only_open $period",
	       $params));
}

function stats_getthemeusers($theme="") 
{
  return stats_get_generic(db_execute("SELECT count(*) AS count FROM user WHERE status='A' AND theme=?",
				      array($theme)));
}
