![alt text](https://i.imgur.com/pjuawRU.jpg "banner")
# [The SkinSystem](https://github.com/riflowth/SkinSystem) [![License: MIT](https://img.shields.io/badge/License-MIT-green.svg)](https://opensource.org/licenses/MIT)
The **SkinSystem** developed for cracked Minecraft's server allowing to change their player skin into a custom skin.

## Introduction
This plugin makes it more at ease for cracked players and minecraft server's owner to find there *OWN CUSTOM SKIN* without having to search for it they can upload it to the website and have it applied instantly

## How it work ?
The main keys of this system are [SkinsRestorer](https://www.spigotmc.org/resources/skinsrestorer.2124/) and [MineSkin](https://mineskin.org/). First! this system needs to send skin image to the `MineSkin` and then they send back `value` and `signature` of the uploaded skin that's have registered from mojang. Second! we use `SkinsRestorer` to assign `value` and `signature` of the skin that user has uploaded and then BOOM!! The player skin has changed!

## Installation
### Requirements:
- [SkinsRestorer](https://www.spigotmc.org/resources/skinsrestorer.2124/)
- [AuthMe](https://www.spigotmc.org/resources/authmereloaded.6269/) (Optional)
- WebServer
- Database

### Usage:
You can instantly use it by download on [release page](https://github.com/riflowth/SkinSystem/releases) or `clone` this project. After download or clone please follow an instruction below.

1. Put all of `The SkinSystem` to your web-root directory. 
2. Enable MYSQL in SkinRestorer configuration.
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
* **IMPORTANT** Don't forget to input your `mysql_host`, `mysql_port`, `mysql_username`, `mysql_password` into it. If you don't have an database password, You can leave it blank like me.
* **IMPORTANT** the `skinsystem_mysql_database` section. You should fill it same as `Database` section on `SkinsRestorer configuration`.
4. Now have fun with changing your skin !!! Have a good time. !!!

## Examples
<p align="center">If you turn <b>authme</b> section to <b>false</b>, You will see like this down below.</p>
<p align="center">
  <img src="https://i.imgur.com/MBcVRK9.jpg" width="500" title="The SkinSystem">
</p>
<p align="center">If you turn <b>authme</b> section to <b>true</b>, You will see like this down below.</p>
<p align="center">
  <img src="https://i.imgur.com/MSsrweF.jpg" width="500" title="The SkinSystem">
</p>
<p align="center">
  <img src="https://i.imgur.com/33Gkqi4.jpg" width="500" title="The SkinSystem">
</p>

## Contributors
- **[Krid Heprakhone](https://www.facebook.com/ohm.krid)**
- **[lion328](https://github.com/lion328)**
- **[syrainthegreat](http://www.facebook.com/jamespassaxz)**
- **[NutpakornCat](https://github.com/nutpakorn-cat)**
- **[xknat](https://github.com/xknat)**
- **[InventivetalentDev](https://github.com/InventivetalentDev)**

This project will not succeeded if we don't have a help from these people.

## Sponsors
We want to say `Thank you` to all of them.

- **[xknat](https://github.com/xknat)** (SkinsRestorer Team)

If this project makes your life better, *you can give me a pack of candy :)* 

[![paypal](https://www.paypalobjects.com/en_US/i/btn/btn_donateCC_LG.gif)](https://www.paypal.me/wheprakhone)

## License
Our **SkinSystem** is licensed under the MIT License - see the [LICENSE.md](https://github.com/riflowth/SkinSystem/blob/master/LICENSE) file for details.
