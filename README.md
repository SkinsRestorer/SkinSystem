![alt text](https://i.imgur.com/pjuawRU.jpg "banner")
# [The SkinSystem](https://github.com/riflowth/SkinSystem) [![License: MIT](https://img.shields.io/badge/License-MIT-green.svg)](https://opensource.org/licenses/MIT)
The `SkinSystem` for make minecraft's server can change their player skin with custom skin.

## Introduction
This project has been developing for minecraft server's owners that turn online-mode to false the cause of this configuration is if player plays minecraft with non-premium account they can't upload any skin like a premium account with this reason the developers of this project try to improve that variation.

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
3. After input the SkinSystem into your web-root directory let's check `config.php`. You will see like a below.
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
4. Let's fun with changing your skin !!! Have a good time. !!!

## Contributors
- **[Alphabet Romeo (RiFlowTH)](https://www.facebook.com/alphabet.romeo.90)** - Developer and Maintainer
- **[Waritnan Sookbuntherng (lion328)](https://github.com/lion328)** - Developer

## Sponsors
We want to say `Thank you` to all of them.

- **[xknat](https://github.com/xknat)** (SkinRestorer Team)

If this project make your life better, *you can give me a pack of candy :)* 

[![paypal](https://www.paypalobjects.com/en_US/i/btn/btn_donateCC_LG.gif)](https://www.paypal.me/wheprakhone)

## License
The SkinSystem is licensed under the MIT License - see the [LICENSE.md](https://github.com/riflowth/SkinSystem/blob/master/LICENSE) file for details.
