# PocketMine-Panel
<p>This is a very simple Web panel for PocketMine-MP server. It lets you start, stop and restart the server, and view server log, without access to Linux shell.</p>
<p>This panel has no ability to view players, ban players, add plugins, etc. Maybe later, these functions will be added.</p>
# Installation
<p>Installation instructions are for Debian family distributions (Debian, Ubuntu). On other distributions, such as CentOS / RedHat, you need to use instructions for these operation systems to get all scripts working.</p>
<ol><li>First, you need root access to your server to be able to get all necessary permissions.</li>
<li>You need a working PocketMine-MP server. You can download it here: https://www.pocketmine.net/. Setup instructions are complete and clean, so don't ignore it. Do not install PocketMine-MP server as root. The best way is to use /home/yourusername/pocketmine-mp/ directory to install the server.</li>
<li>You need Apache / PHP working on your server.</li>
<li>Upload contents of this repository to your web server's directory, but not www root. Something like www.yourminecraftserver.com/cp/ will work fine. Make sure you password protect /cp/ directory with .htaccess to prevent unauthorized start and stop of your PocketMine-MP server.</li>
<li>You need to add your username to /etc/sudoers in 'User privilege specification' section. Example: <br>root    ALL=(ALL:ALL) ALL
<br>arik    ALL=(ALL:ALL) ALL</li>
<li>After that, add www-data user (www-data is default username which runs Apache on Debian. On other distributions it may be different, such as: apache, httpd) to /etc/sudoers. Be sure paths to programs are correct - they may be different for other Linux distributions.These paths are for Debian:<br>
www-data ALL=(ALL) NOPASSWD: /home/arik/pocketmine-mp/start.sh, NOPASSWD: /bin/ps, NOPASSWD: /bin/kill, NOPASSWD: /usr/bin/tail<br>This will allow www-data to run the following programs: /home/arik/pocketmine-mp/start.sh, ps and tail which are required.</li>
</ol>
# Compatibility
<p>This control panel works on Linux and tested on Debian distribution. It will not run on Windows - i am not a Windows specialist. Maybe later I will add Windows support.</p>
# Screenshots
<p>This panel is very simple. It uses Bootstrap from Twitter.</p>
<p><img src="http://i.imgur.com/IOYVZ0g.png" /></p>
