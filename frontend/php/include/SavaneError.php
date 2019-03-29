<?php /*-*-PHP-*-*/
# Define SavaneError class.
# 
# Copyright (C) 1999-2000 The SourceForge Crew
# Copyright (C) 2000-2001 Free Software Foundation
# Copyright (C) 2004      John Doe <john.doe-at--dude.org>
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


class SavaneError {

        var $error_state;
        var $error_message;

        function __construct() {
                $this->error_state=false;
        }

        function setError($string) {
                $this->error_state=true;
                $this->error_message=$string;
        }

        function getErrorMessage() {
                return $this->error_message;
        }

        function isError() {
                return $this->error_state;
        }

}
?>
