![alt text](https://i.imgur.com/pjuawRU.jpg "banner")
# [The SkinSystem](https://github.com/riflowth/SkinSystem) [![License: MIT](https://img.shields.io/badge/License-MIT-green.svg)](https://opensource.org/licenses/MIT)
The **SkinSystem** developed for Minecraft's server can change their player skin into custom skin.

## Introduction
This plugin should make it for cracked players and some minecraft server's owner more at ease to find there *OWN CUSTOM SKIN* without having to search for it they can upload it to the website and have it applied instantly

## How it work ?
The main keys of this system are [SkinRestorer](https://www.spigotmc.org/resources/skinsrestorer.2124/) and [MineSkin](https://mineskin.org/). First! this system needs to send skin image to the `MineSkin` and then they send back `value` and `signature` of the uploaded skin that's have registered from mojang. Second! we use `SkinRestorer` to assign `value` and `signature` of the skin that user has been uploaded and then BOOM!! player skin has change!

## Installation
### Requirements:
- [SkinRestorer](https://www.spigotmc.org/resources/skinsrestorer.2124/)
- [AuthMe](https://www.spigotmc.org/resources/authmereloaded.6269/) (Optional)
- WebServer
- Database

### Usage:
You can instantly use it by clone this project. After clone please follow an instruction below.

1. Put all of `The SkinSystem` to your web-root directory. 
2. Turn on database using in SkinRestorer configuration.
```
minecraftserver/plugins/SkinRestorer/config.yml
```
```YML
MySQL:
  Enabled: true
  Host: localhost
  Port: 3306
  Database: skinsystem
  SkinTable: Skins
  PlayerTable: Players
  Username: root
  Password: ''
```
Configuration option:
* Turn on database by change `false` to `true` on Enabled section.
* An database section, You can input a name anything you want.
* **IMPORTANT** Don't forget to input your `Host`, `Port`, `Username`, `Password` into it. If you don't have an database password, You should fill `''` into `Password` section like me.
3. After input the SkinSystem into your web-root directory let's check `config.php`. You will see like this down below.
```PHP
  /* MySQL Configuration */
  "mysql_host" => "localhost",
  "mysql_port" => "3306",
  "mysql_username" => "root",
  "mysql_password" => "",
  /* Authme Configuration */
  "authme" => false,
  "authme_mysql_database" => "authme",
  /* SkinSystem Configuration */
  "server_name" => "Mc-Server",
  "skinsystem_mysql_database" => "skinsystem",
  "skinhistory" => true
```
Configuration option:
* If you want to work with [AuthMe](https://www.spigotmc.org/resources/authmereloaded.6269/) for authenication. You can change `false` to `true` on `authme` section.
* Change `authme_mysql_database` to the correct as you used on `authme`.
* **IMPORTANT** Don't forget to input your `mysql_host`, `mysql_port`, `mysql_username`, `mysql_passwor` into it. If you don't have an database password, You can leave it blank like me.
* **IMPORTANT** the `skinsystem_mysql_database` section. You should fill it same as `Database` section on `SkinRestorer configuration`.
4. Now have fun with changing your skin !!! Have a good time. !!!

## Contributors
- **[Krid Heprakhone](https://www.facebook.com/ohm.krid)**
- **[lion328](https://github.com/lion328)**
- **[syrainthegreat](https://github.com/syrainthefreat)**
- **[NutpakornCat](https://github.com/nutpakorn-cat)**
- **[InventivetalentDev](https://github.com/InventivetalentDev)**

## Sponsors
We want to say `Thank you` to all of them.

- **[xknat](https://github.com/xknat)** (SkinRestorer Team)

If this project make your life better, *you can give me a pack of candy :)* 

[![paypal](https://www.paypalobjects.com/en_US/i/btn/btn_donateCC_LG.gif)](https://www.paypal.me/wheprakhone)

## License
Our **SkinSystem** is licensed under the MIT License - see the [LICENSE.md](https://github.com/riflowth/SkinSystem/blob/master/LICENSE) file for details.
