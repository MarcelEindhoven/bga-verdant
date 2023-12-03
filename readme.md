# Board game arena game
A puzzly spatial card and token drafting game of houseplant collection and care.
Designed by Molly Johnson, Robert Melvin, Aaron Mesburne, Kevin Russ and Shawn Stankewich.

## GitHub
git clone https://github.com/MarcelEindhoven/bga-verdant.git
git config user.email "Marcel.Eindhoven@Gmail.com"
git config user.name "MarcelEindhoven"

## Deploy
For example, use WinSCP (https://winscp.net/download/WinSCP-6.1.2-Setup.exe) to synchronise the files in export to
1.studio.boardgamearena.com
Port 22
User MarcelEindhoven

## Development site boardgame arena:
user MarcelEindhoven0
https://studio.boardgamearena.com/controlpanel
https://studio.boardgamearena.com/studio
https://studio.boardgamearena.com/studiogame?game=verdant

## Development
### PHP
Installing PHP is tricky. For example, you cannot simply install PHP in "Program Files" because that directory name contains a space.
The messages you get assume you are already an expert in PHP terminology.

PHP version of BGA according to phpversion(): 7.4.3-4ubuntu2.180
Corresponding PHPunit version: 9

First download a PHP package without words like debug, develop or test in the package name. Possibly useful links
- https://www.sitepoint.com/how-to-install-php-on-windows/
- https://windows.php.net/downloads/releases/php-7.4.33-Win32-vc15-x64.zip
- https://www.ionos.com/digitalguide/server/configuration/php-composer-installation-on-windows-10/

### Composer
When PHP is available in the PATH, installation is straightforward
- https://getcomposer.org/Composer-Setup.exe
- In the git directory, type "composer install" to download all PHP packages into the vendor directory

### Visual studio code
Install visual studio code (https://code.visualstudio.com/docs?dv=win)

Extensions:
- PHP Intelephense 
- HTML CSS Support
- StandardJS - JavaScript Standard Style
- Git History
- Git Tree Compare
- GitHub Pull Requests and Issues
- Compare Folders




