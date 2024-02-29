# Curriculum #

This Moodle plugin allows the seamless integration of Curricula into the Moodle LMS.
Curricula is a laravell applicable, for more information see this repository: https://github.com/joachimdieterich/laravel-curriculum-adminlte3
Moodle is a learning management system, see https://moodle.org.
The plugin expects both requirements to be installed and working, you set the Curricula-URL in the admin menu after installing the plugin.

## Installing via uploaded ZIP file ##

1. Log in to your Moodle site as an admin and go to _Site administration >
   Plugins > Install plugins_.
2. Upload the ZIP file with the plugin code. You should only be prompted to add
   extra details if your plugin type is not automatically detected.
3. Check the plugin validation report and finish the installation.

## Installing manually ##

The plugin can be also installed by putting the contents of this directory to

    {your/moodle/dirroot}/mod/curriculum

Afterwards, log in to your Moodle site as an admin and go to _Site administration >
Notifications_ to complete the installation.

Alternatively, you can run

    $ php admin/cli/upgrade.php

to complete the installation from the command line.

## Configuration ##

To connect your LMS to Curriculum configure the plugin at _{your/moodle/dirroot}/admin/settings.php?section=mod_curriculum_settings_.
There you need to add the client_id and client_secret provided by Curriculum. We recommend using ssl to pass along data between servers.
Finally add your servers url to the last field, without pre- and postfix.

## License ##

2022 michaelpollak <moodle@michaelpollak.org>

This program is free software: you can redistribute it and/or modify it under
the terms of the GNU General Public License as published by the Free Software
Foundation, either version 3 of the License, or (at your option) any later
version.

This program is distributed in the hope that it will be useful, but WITHOUT ANY
WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A
PARTICULAR PURPOSE.  See the GNU General Public License for more details.

You should have received a copy of the GNU General Public License along with
this program.  If not, see <https://www.gnu.org/licenses/>.
