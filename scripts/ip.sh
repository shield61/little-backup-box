#!/usr/bin/env bash

# Author: Dmitri Popov, dmpop@linux.com

#######################################################################
# This program is free software: you can redistribute it and/or modify
# it under the terms of the GNU General Public License as published by
# the Free Software Foundation, either version 3 of the License, or
# (at your option) any later version.

# This program is distributed in the hope that it will be useful,
# but WITHOUT ANY WARRANTY; without even the implied warranty of
# MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
# GNU General Public License for more details.

# You should have received a copy of the GNU General Public License
# along with this program.  If not, see <http://www.gnu.org/licenses/>.
#######################################################################

WORKING_DIR=$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)
CONFIG="${WORKING_DIR}/config.cfg"
dos2unix "$CONFIG"
source "$CONFIG"

ping -c1 google.com &>/dev/null
while [ $? != 0 ]; do
    sleep 10
    ping -c1 google.com &>/dev/null
done
IP=$(hostname -I | cut -d' ' -f1)

if [ $DISP = true ]; then
  oled r
  oled +a "$IP"
  oled s
fi

if [ ! -z $SMTP_SERVER ] && [ $NOTIFY = true ]; then
    curl --url 'smtps://'$SMTP_SERVER':'$SMTP_PORT --ssl-reqd \
        --mail-from $MAIL_USER \
        --mail-rcpt $MAIL_TO \
        --user $MAIL_USER':'$MAIL_PASSWORD \
        -T <(echo -e 'From: '$MAIL_USER'\nTo: '$MAIL_TO'\nSubject: Little Backup Box: IP '$IP'\n\n'$IP'\n')
fi
