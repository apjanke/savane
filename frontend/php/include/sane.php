<?php
# This file is part of the Savane project
# <http://gna.org/projects/savane/>
#
# $Id: title.php 4975 2005-11-15 17:25:35Z yeupou $
#
#  Copyright 2005-2006 (c) Mathieu Roy <yeupou--gnu.org>
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


# The point of this library is to reach the point where Savane will 
# no longer needs register globals set to on.
#
# This library will:
#            - do sanitization checks
#            - provide functions to access user input in a sane way


###########################################################
# Sanitization checks
###########################################################

# Unset variables that users are not allowed to set in any cases
unset($feedback_html);

# Catch recurrent globals like **_id and set give them global status with
# sane_all.
#
# Page that calls register_globals_off() will actually get these 
# unregistered.
# But it is not a big deal, these pages will have initialize this.
# The point of doing this right now is to have these initialized cleanly 
# because they are used in include/pre.php

$to_sanitize = array("user_id",
		     "group_id", 
		     "group",
		     "item_id", 
		     "forum_id", 
		     "msg_id",
		     "export_id");

foreach ($to_sanitize as $var)
{
  unset($$var);
  if (sane_isset($var))
    {
      $$var = sane_all($var);
    }
}


# Set group_name only if group was set
unset($group_name);
if (!empty($group))
{ $group_name = $group; }

# Keep only numerical characters in the item_id
# (Set both the global and the _REQUEST vars, because the global may be
# unregistered by register_globals_off())
if (isset($item_id) && !ctype_digit($item_id))
{
  preg_match("/(\d+)/", $item_id, $match);
  sane_set("item_id", $match[0]);
}

# Keep only numerical characters in the export_id
# (Set both the global and the _REQUEST vars, because the global may be
# unregistered by register_globals_off())
if (isset($export_id) && !ctype_digit($export_id))
{
  preg_match("/(\d+)/", $export_id, $match);
  sane_set("export_id", $match[0]);
}


# Keep only numerical characters in the group_id
# (Set both the global and the _REQUEST vars, because the global may be
# unregistered by register_globals_off())
if (isset($group_id) && !ctype_digit($group_id))
{
  preg_match("/(\d+)/", $group_id, $match);
  sane_set("group_id", $match[0]);
}

# Keep only numerical characters in the user_id
# (Set both the global and the _REQUEST vars, because the global may be
# unregistered by register_globals_off())
if (isset($user_id) && !ctype_digit($user_id) && !is_array($user_id))
{
  preg_match("/(\d+)/", $user_id, $match);
  sane_set("user_id", $match[0]);
}



###########################################################
# Functions to access user input
###########################################################

# Return the input as-is, without unwanted magic_quotes_gpc effect
function stripslashesgpc($val)
{
  if (get_magic_quotes_gpc()) 
    return stripslashes($val);
  return $val;
}


// Check the existence of a series of input parameters, then return an
// array suitable for extract()
// Ex: extract(sane_import('post',
//       array('insert_group_name', 'rand_hash',
//             'form_full_name', 'form_unix_name')));
function sane_import($method, $names) {
  if ($method == 'get')
    $input_array =& $_GET;
  else if ($method == 'post')
    $input_array =& $_POST;
  else if ($method == 'cookie')
    $input_array =& $_COOKIE;
  else
    $input_array =& $_REQUEST;

  $values = array();
  foreach ($names as $input_name) {
    if (isset($input_array[$input_name])) {
      $values[$input_name] = stripslashesgpc($input_array[$input_name]);
    } else {
      $values[$input_name] = null;
    }
  }

  return $values;
}

# Backward security function. This will sanitize input already passed via
# register globals.
# 
# In theory, this function should "disappear" from the code and be replaced by
# sane_XXX functions.
#
# This function should be used whenever user input is used:
#        - get
#        - post
#        - cookies
# This will escape the strings appropriately.

// Beuc: I'm using another, saner approach with sane_import, where the
// string is unquoted so that we manipulate the actual values (with
// correct results for str_len, etc.). The escaping is done in
// SQL-related functions, which is a good thing to do anyway. As the
// dovecot guys put it (http://dovecot.org/doc/securecoding.txt),
// "Don't rely on input validation. Maybe you missed something. Maybe
// someone calls your function somewhere else where you didn't
// originally intend it.  Maybe someone makes the input validation
// less restrictive for some reason.  Point is, it's not an excuse to
// cause a security hole just because input wasn't what you expected
// it to be.". Plus, addslashes() is not meant to escape SQL strings,
// mysql_real_escape_string() is. Short: don't use that function.
function safeinput ($string)
{
  # If magic_quotes is on, count on it to escape data
  if (get_magic_quotes_gpc()) 
    {
      return $string;
    } 

  return addslashes($string);
}

# Function to obtain user input that come from undefined method.
# This should be used only where user can legitimately send data by
# different methods.
# (this is why it is called sane_all, to avoid having it used everywhere)
# This does not take uploads depending on PHP version, so use sane_upload()
# instead, if necessary.
function sane_all($varname)
{
  if (sane_isset($varname))
    return safeinput($_REQUEST[$varname]);
  else
    return '';
}

# Function to obtain user input submitted as url args
# (like thispage.php?arg=userinput)
function sane_get($varname) 
{
  if (isset($_GET[$varname]))
    return safeinput($_GET[$varname]);
  else
    return '';
}

# Function to obtain user input submitted while posting a form
function sane_post($varname) 
{
  if (isset($_POST[$varname]))
    return safeinput($_POST[$varname]);
  else
    return '';
}

# Function to obtain user input submitted in a cookie
function sane_cookie($varname) 
{
  if (isset($_COOKIE[$varname]))
    return safeinput($_COOKIE[$varname]);
  else
    return '';
}

# Does an isset. Not really necessary, just for cohesion sake
function sane_isset($varname)
{
  return isset($_REQUEST[$varname]);
}


# Function to obtain user input submitted in a cookie
function sane_upload($varname, $subvarname=false) 
{
  if (!$subvarname)
    { return safeinput($_FILES[$varname]); }

  return  safeinput($_FILES[$varname][$subvarname]);
}

# Function to set a variable in both $_REQUEST and global.
# The global may be deleted by a call to register_globals_off(),
# so the $_REQUEST will remain and should be safe
# (this function should be used only to set safe values! Normally
# it should be used only in include/ like pre.php)
function sane_set($varname, $value)
{
  $GLOBALS[$varname] = $value;
  $_REQUEST[$varname] = $value;
}


# Noop function to mark a page as input-sanitized.
# Warning: MySQL calls are not necessarily secure.
function register_globals_off()
{
  # This is unsecure: you can switch off existing globals
  # - unless that's the very first thing you do in the script
  # - and it's not always the case
#  foreach ($_REQUEST as $key => $value)
#    { 
#      unset($GLOBALS[$key]); 
#    }
}      

# Tag: mysql queries are safe here
#function mysql_is_safe() {
#}
# Tag: input is safe new/Beuc-style (no slashes)
#function input_is_safe() {
#}
# Those tags are used by devel/sv_check_security.pl
