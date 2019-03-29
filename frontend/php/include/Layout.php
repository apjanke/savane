<?php /*-*-PHP-*-*/
#
# Page layout.
#
# Copyright (C) 1999-2000 The SourceForge Crew
# Copyright (C) 2000-2003 Free Software Foundation
# Copyright (C) 2000-2003 Stéphane Urbanoski <s.urbanovski--ac-nancy-metz.fr>
# Copyright (C) 2000-2003 Derek Feichtinger <derek.feichtinger--cern.ch>
#
# Copyright (C) 2000-2006 Mathieu Roy <yeupou--gnu.org>
# Copyright (C) 2004-2006 Yves Perrin <yves.perrin--cern.ch>
# Copyright (C) 2017, 2018 Ineiev
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


# base error library for new objects
require_once(dirname(__FILE__) . '/SavaneError.php');
# left-hand and top menu nav library (requires context to be set)
require_once(dirname(__FILE__).'/sitemenu.php');
require_once(dirname(__FILE__).'/pagemenu.php');
# i18n setup
require_once(dirname(__FILE__).'/i18n.php');
# theme - color scheme informations
require_once(dirname(__FILE__).'/theme.php');
# various utilities - broken_msie...
require_once(dirname(__FILE__).'/utils.php');

class Layout extends SavaneError
{

########################################## BASIC HTML
  var $bgpri = array();

# Constuctor
  function __construct()
  {
    GLOBAL $bgpri;
# Constructor for parent class...
    SavaneError::__construct();

# Setup the priority color array one time only.
    $bgpri[1] = 'priora';
    $bgpri[2] = 'priorb';
    $bgpri[3] = 'priorc';
    $bgpri[4] = 'priord';
    $bgpri[5] = 'priore';
    $bgpri[6] = 'priorf';
    $bgpri[7] = 'priorg';
    $bgpri[8] = 'priorh';
    $bgpri[9] = 'priori';

    $bgpri[11] = 'prioraclosed';
    $bgpri[12] = 'priorbclosed';
    $bgpri[13] = 'priorcclosed';
    $bgpri[14] = 'priordclosed';
    $bgpri[15] = 'prioreclosed';
    $bgpri[16] = 'priorfclosed';
    $bgpri[17] = 'priorgclosed';
    $bgpri[18] = 'priorhclosed';
    $bgpri[19] = 'prioriclosed';

  }

  function box_top ($title, $subclass="", $noboxitem=0)
  {
    $return = '     <div class="box'.$subclass.'">
       <div class="boxtitle">'.$title.'</div><!-- end boxtitle -->';
    if (!$noboxitem)
      {
        $return .= '
       <div class="boxitem">';
      }
    return $return;
  }

# Box Middle, equivalent to html_box1_middle().
  function box_middle ($title, $noboxitem=0)
  {
    $return = '</div><!-- end boxitem -->
       <div class="boxtitle">'.$title.'</div><!-- end boxtitle -->';
    if (!$noboxitem)
      {
        $return .= '
       <div class="boxitem">';
      }
    return $return;
  }

# Box Middle, equivalent to html_box1_middle().
  function box_nextitem ($class)
  {
    return '</div><!-- end boxitem -->
       <div class="'.$class.'">';
  }

# Box Bottom, equivalent to html_box1_bottom().
  function box_bottom ($noboxitem=0)
  {
    $return = '';
    if (!$noboxitem)
      {
        $return .= '</div><!-- end boxitem -->';
      }

    $return .= '
     </div><!-- end box -->
';
    return $return;
  }

# Box Top, equivalent to html_box1_top().
  function box1_top ($title,$echoout=1,$subclass="")
  {
    $return = '<table class="box'.$subclass.'">
                <tr>
                        <td colspan="2" class="boxtitle">'.$title.'</td>
                </tr>
                <tr>
                        <td colspan="2" class="boxitem">';
    if ($echoout)
      print $return;
    else
      return $return;
  }

# Box Middle, equivalent to html_box1_middle().
  function box1_middle ($title,$bgcolor='')
  {
    return '
                        </td>
                </tr>
                <tr>
                        <td colspan="2" class="boxtitle">'.$title.'</td>
                </tr>
                <tr>
                        <td colspan=2 class="boxitem">';
  }

# Box Bottom, equivalent to html_box1_bottom().
  function box1_bottom ($echoout=1)
  {
    $return = '
                        </td>
                </tr>
        </table>';
    if ($echoout)
      print $return;
    else
      return $return;
  }

  function generic_header_start ($params)
  {
    global $G_USER, $G_SESSION;

# Avoid any cache by setting an expire time in the past, without
# distinction.
# On Savane there are many forms, the content changes frequently,
# it is probably better to avoid any cache problem that way.
# We could use the Lastest-Modification header, but it would require
# an extra call to time().
    $context = '';
    if (!empty($params['context']))
      {
        $context = $params['context'];
# Only make the test if context is set.
# Then look for usual trackers (bugs, patch...), project admin part
# and personal area (/my)
        if ("news" == $context
            || "support" == $context
            || "bugs" == $context
            || "task" == $context
            || "my" == $context
            || "myitems" == $context
            || "mygroups" == $context
            || preg_match ("/^a/", $context))
          {
            header("Expires: Thu, 22 Dec 1977 15:00:00 GMT");
            dbg("Expires is set.");
          }
      }
    $title = context_title($context, isset($params['group']) ? $params['group'] : '');
    if (!empty($params['title']) && $title)
      {
        $params['title'] = sprintf("%s: %s", $title, $params['title']);
      }
    elseif ($title)
      {
        $params['title'] = $title;
      }
    $params['title'] = sprintf("%s [%s]", $params['title'], $GLOBALS['sys_name']);
    $theme = SV_THEME;
    # Path of printer.css changed to internal/printer.css.
    if ($theme == "printer")
      $theme = "internal/printer";

    print '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
         "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="'.SV_LANG.'" xml:lang="'
          .SV_LANG.'">
<head>
  <meta http-equiv="content-type" content="text/html; charset=utf-8" />
  <title>'.$params['title'].'</title>
  <meta name="Generator" content="Savane '.$GLOBALS['savane_version']
          .', see '.$GLOBALS['savane_url'].'" />
  <meta http-equiv="Content-Script-Type" content="text/javascript" />
  <link rel="stylesheet" type="text/css" href="'.$GLOBALS['sys_home']
          .'css/'.$theme.'.css" />
';
    # We unfortunately have to maintain some browser-specifics hacks, since
    # MSIE is total crap in regards to standards. This should be an exception.
    # We should not implement non-standard things, we can only correct things
    # for the mosted widely deployed web browser that does not understand
    # W3C standards.
    # (ignore in printer mode, as we have no left and top menu and that menus
    # actually do not matter).
    if (utils_is_broken_msie() && empty($_GET['printer']))
      {
        print '  <link rel="stylesheet" type="text/css" href="'
              .$GLOBALS['sys_home'].'css/internal/msie-dirtyhacks.css" />
';
      }

    # If the user want the stone age menu, we must add the appropriate
    # additional CSS.
    if (!empty($GLOBALS['stone_age_menu']))
      {
        print '  <link rel="stylesheet" type="text/css" href="'
              .$GLOBALS['sys_home'].'css/internal/stone-age-menu.css" />
';
      }
    if (!empty($params['css']))
      print '  <link rel="stylesheet" type="text/css" href="'
            . $params['css'] . '" />' . "\n";

    print '  <link rel="icon" type="image/png" href="'.$GLOBALS['sys_home']
          .'images/'.SV_THEME.'.theme/icon.png" />
';
    utils_get_content("page_header");
  }
  function generic_header_end ($params)
  {
    print '
</head>';
  }

  function generic_footer ($params)
  {
    print '<p class="footer">';
    utils_get_content("page_footer");
# TRANSLATORS: the argument is version of Savane (like 3.2).
    print ' </p>
 <div align="right"><p>'.utils_link($GLOBALS['savane_url'],
  sprintf(_("Powered by Savane %s"), $GLOBALS['savane_version'])).'</p></div>';

# yeupou--gnu.org 2005-09-16:
# Dirty hack to get rid of serious issue in MSIE handling of
# CSS.
# If we are using MSIE, we havent closed main and realbody yet,
# because the footer wouldnt be visible otherwise.
    if (is_broken_msie() && empty($_GET['printer']))
      {
        print '
<!-- closing now main and realbody MSIE DIRTY HACK --></div></div>';
      }
    print '
</body>
</html>';
  }

  function header ($params)
  {
    $this->generic_header_start($params);
    $this->generic_header_end($params);

    print '
<body>
<div class="realbody">
';
    sitemenu($params);


    print '<div id="top" class="main">
';
    pagemenu($params);

  }

  function footer ($params)
  {
    print '
  <p class="backtotop">
  '.utils_link("#top", '<img src="'.$GLOBALS['sys_home'].'images/'.SV_THEME
   .'.theme/arrows/top.orig.png" border="0" alt="'._("Back to the top").'" />').'
  </p>';

# yeupou--gnu.org 2005-09-16:
# Dirty hack to get rid of serious issue in MSIE handling of
# CSS.
# If we are using MSIE, we cannot close main, because the footer
# will never be visible otherwise.
    if (!is_broken_msie() || !empty($_GET['printer']))
      {
        print '
</div><!-- end main -->
<br class="clear" />
</div><!-- end realbody -->
';
      }
    else
      {
        print '
<!-- not closing yet main and realbody MSIE DIRTY HACK -->
';
      }

    $this->generic_footer ($params);

  }

# ######################################### LEFT MENU

# Most of it is in sitemenu.php

# title of left menu part
  function menuhtml_top ($title)
  {
    print '
        <li class="menutitle">
           '.$title.'
        </li><!-- end menutitle -->';
  }

# left menu entry
  function menu_entry ($link, $title, $available=1, $help=0)
  {
    print '
        <li class="menuitem">
           '.utils_link($link, $title, "menulink", $available, $help).'
        </li><!-- end menuitem -->';
  }

# end of left menu part
  function menuhtml_bottom ()
  {

  }

# ######################################### TOP MENU
# It is in pagemenu.php
}
?>