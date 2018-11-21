![alt text](https://i.imgur.com/pjuawRU.jpg "banner")
# [The SkinSystem](https://github.com/riflowth/SkinSystem) [![License: MIT](https://img.shields.io/github/license/riflowth/SkinSystem.svg)](https://opensource.org/licenses/MIT) [![Release](https://img.shields.io/github/release/riflowth/skinsystem.svg)](https://github.com/riflowth/SkinSystem/releases) [![Download](https://img.shields.io/github/downloads/riflowth/SkinSystem/total.svg)](https://github.com/riflowth/SkinSystem/releases)
The **SkinSystem** developed for cracked Minecraft's server allowing to change their player skin into a custom skin.

- **For other languages, you should go to a [wiki page](https://github.com/riflowth/SkinSystem/wiki)**
- **สำหรับผู้ที่ต้องการอ่านข้อมูลภาษาไทย สามารถเข้าไปอ่านได้ที่นี่ [คลิก](https://github.com/riflowth/SkinSystem/wiki)**

## Introduction
This plugin makes it more at ease for cracked players and Minecraft server's owner to find their *OWN CUSTOM SKIN* without having to search for it they can upload it to the website and have it applied instantly.

## How does it work?
The main key of this system are [SkinsRestorer](https://www.spigotmc.org/resources/skinsrestorer.2124/) and [MineSkin](https://mineskin.org/). First! this system needs to send skin image to the `MineSkin` and then they send back `value` and `signature` of the uploaded skin that's have registered from Mojang. Second! we use `SkinsRestorer` to assign `value` and `signature` of the skin that user has uploaded and then BOOM!! The player skin has changed!

## Installation
#### Requirements:
- [SkinsRestorer](https://www.spigotmc.org/resources/skinsrestorer.2124/)
- [AuthMe](https://www.spigotmc.org/resources/authmereloaded.6269/) (Optional)
- WebServer
- Database

#### Usage:
You can instantly use it by download on [release page](https://github.com/riflowth/SkinSystem/releases) or `clone` this project. After download or clone please follow an instruction below.

1. Put all of `The SkinSystem` in your web-root directory.
2. It is recommended to run the system on a separate domain/subdomain in the root folder of the host
- |_ Right: https://host.tld/index.php
- |_ Wrong: https://host.tld/skinSystem/index.php

3. Enable MYSQL in SkinRestorer configuration, located here: `minecraftserver/plugins/SkinRestorer/config.yml`
Example configuration:
```YML
MySQL:
  Enabled: true
  Host: localhost
  Port: 3306
  Database: skinsrestorer
  SkinTable: Skins
  PlayerTable: Players
  Username: root
  Password: ''
```
* Configuration option:
  * Turn on a database by change `false` to `true` on the Enabled section.
  * The Database section, You can input a name anything you want.
  * **IMPORTANT** Don't forget to input your `Host`, `Port`, `Username`, `Password` into it. If you don't have a database password, You should fill `''` into `Password` section like me.

4. After putting The SkinSystem into your web-root directory you should see The SkinSystem on your website. You will see like this down below. So let's configure it correctly to get your started.

<p align="center">
  <img src="https://i.imgur.com/mLYt2p8.jpg" width="500" title="The SkinSystem">
</p>
<p align="center">
  <img src="https://i.imgur.com/AsrGEIY.jpg" width="500" title="The SkinSystem">
</p>
<p align="center">
  <img src="https://i.imgur.com/zwUkJvz.jpg" width="500" title="The SkinSystem">
</p>
<p align="center">
  <img src="https://i.imgur.com/M7Z4kej.jpg" width="500" title="The SkinSystem">
</p>

* **IMPORTANT** This is a configuration that uses on The SkinSystem. Please fill in a blank correctly.
* **IMPORTANT** You can change it later on `skinsystem/lib/config.nogit.php`

5. Now have fun with changing your skin! Have a good time. :smiley:

## Examples
<p align="center">If you turn <b>the Authme</b> section to <b>false</b>, You will see like this down below.</p>
<p align="center">
  <img src="https://i.imgur.com/MBcVRK9.jpg" width="500" title="The SkinSystem">
</p>
<p align="center">If you turn <b>the Authme</b> section to <b>true</b>, You will see like this down below.</p>
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
- **[aljaxus](https://github.com/LazosPlaying)**

This project will not succeed if we don't have a help from these people.

## Sponsors
We want to say `Thank you` to all of them.
- **[Vectier Thailand](https://www.facebook.com/VectierThailand/)** (Our team)
- **[xknat](https://github.com/xknat)** (SkinsRestorer Team)
- **Thanaphon Rangbunlue** (MC-SurvivialCity)

If this project makes your life better, *you can give me a pack of candy :)*

or leave your thumbs up on [Our team Facebook](https://www.facebook.com/VectierThailand/) :thumbsup:

<a href="https://www.paypal.me/wheprakhone"><img src="https://raw.githubusercontent.com/riflowth/SkinSystem/master/src/donate-paypal.png" height="48px" width="auto"></a> <-- Click here

## License
Our **SkinSystem** is licensed under the MIT License - see the [LICENSE.md](https://github.com/riflowth/SkinSystem/blob/master/LICENSE) file for details.
