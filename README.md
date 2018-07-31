![alt text](https://i.imgur.com/pjuawRU.jpg "banner")
# The SkinSystem [![License: MIT](https://img.shields.io/badge/License-MIT-green.svg)](https://opensource.org/licenses/MIT)
The SkinSystem for make minecraft's server can change their player skin with custom skin.

## Introduction
This project has been developing for minecraft server's owners that turn online-mode to false the cause of this configuration is if player plays minecraft with non-premium account they can't upload any skin like a premium account with this reason the developers of this project try to improve that variation.

## Installation
### Requirements
- [SkinRestorer](https://www.spigotmc.org/resources/skinsrestorer.2124/)
- WebServer
- Database

### Usage
You can instantly use it by download an [release verion](https://github.com/riflowth/SkinSystem/releases). If you don't. Please follow an instruction below.

1. Put all of `The SkinSystem` to your web-root directory. 
2. Turn on database using in SkinRestorer configuration.
```
minecraftserver/plugins/SkinRestorer/config.yml
```
```
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

## Contributors
- **[Alphabet Romeo (RiFlowTH)](https://www.facebook.com/alphabet.romeo.90)** - Developer and Maintainer
- **[Waritnan Sookbuntherng (lion328)](https://github.com/lion328)** - Developer

## Sponsors
We want to say `Thank you` to all of them.

- **[xknat](https://github.com/xknat)** (SkinRestorer Team)

If this project make your life better, you can give me a pack of candy :) 

[![paypal](https://www.paypalobjects.com/en_US/i/btn/btn_donateCC_LG.gif)](https://www.paypal.me/wheprakhone)

## License
The SkinSystem is licensed under the MIT License - see the [LICENSE.md](https://github.com/riflowth/SkinSystem/blob/master/LICENSE) file for details.
