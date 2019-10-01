---

<p align="center">
  <a href="https://github.com/riflowth/SkinSystem">
    <img src="https://i.imgur.com/pjuawRU.jpg" alt="SkinSystem" width="600">
  </a>
</p>

<p align="center">
  The <b>SkinSystem</b>; developed for cracked <strong>Minecraft servers</strong>, allowing for changes of player skins to something more <strong>custom</strong>.
</p>

<p align="center">
  <a href="https://opensource.org/licenses/MIT"><img src="https://img.shields.io/github/license/riflowth/SkinSystem.svg" alt="MIT License"></a>
  <a href="https://github.com/riflowth/SkinSystem/releases"><img src="https://img.shields.io/github/release/riflowth/skinsystem.svg" alt="Release"></a>
  <a href="https://github.com/riflowth/SkinSystem/releases"><img src="https://img.shields.io/github/downloads/riflowth/SkinSystem/total.svg" alt="Download"></a>
  <a href="https://www.paypal.me/wheprakhone"><img src="http://ionicabizau.github.io/badges/paypal.svg" alt="PayPal Donate"></a>
  <a href="https://www.spigotmc.org/resources/the-skinsystem.59403/"><img src="https://img.shields.io/badge/view%20on-spigotmc-orange.svg" alt="View on spigotmc.org"</a>
  <a href="https://discord.gg/HTMGaFV"><img src="https://img.shields.io/discord/186794372468178944.svg?color=blue&label=discord&logo=discord" alt="Join the chat"></a>
  <a href="https://gitter.im/SkinSystem/community"><img src="https://badges.gitter.im/SkinSystem/community.svg" alt="Join the chat"></a>
</p>

---

## :eyeglasses: Examples

<p align="center">
  <img src="https://i.imgur.com/5baEOlG.png" alt="The SkinSystem Preview" height="230">
  <img src="https://i.imgur.com/AbZgB5n.png" alt="The SkinSystem Preview" height="230">
  <img src="https://i.imgur.com/grNDVYA.png" alt="The SkinSystem Preview" height="230">
  <p align="center"><b>Installation</b> view / <b>SkinUploader</b> view / <b>Authen</b> view (<i>when you use this system with Authme</i>)</p>
</p>

---

## :memo: Requirements

- [SkinsRestorer](https://www.spigotmc.org/resources/skinsrestorer.2124/)
- [AuthMe](https://www.spigotmc.org/resources/authmereloaded.6269/) (Optional)
- WebServer
- Database

---

## :wrench: Installation

:grey_exclamation: If you use **Ubuntu 18.04**+, you may run **this command** to *automagically* install apache+mysql+git+curl, set apache2 webpage, and generate MySQL credentials:

```bash
sudo bash -c "apt-get update && apt-get install curl -y && curl -s https://raw.githubusercontent.com/riflowth/SkinSystem/master/installscripts/UbuntuInstall.sh | bash -s"
```

#### **otherwise**, follow **these directions**:

1. Install MySQL
2. Install and configure a webserver that supports php, php-curl, php-mysql, and php-gd. (apache2 may be used)
3. Make sure your webserver is accessible.
4. Create databases `skinsrestorer` and `authme`. [Commands](https://gist.github.com/ITZVGcGPmO/a3dffa0db198919ae002efcad444ae34)
5. Download the latest **release** version from [**here!**](https://github.com/riflowth/SkinSystem/releases)
6. Put all of `The SkinSystem` into your **web-root** directory.

### :star: Let's start!!!

* **IMPORTANT** Enable **Database** in your **AuthMe** configuration. (Optional)

* **IMPORTANT** Enable **Database** in your **SkinsRestorer** configuration. 
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
If you don't have a database password, You should fill `''` into `Password` section.

1. Load The SkinSystem from your webserver. You should be shown an installation page looking something like this:

<p align="center">
  <img src="https://i.imgur.com/naeNvbO.png" alt="Installation view" height="240">
  <img src="https://i.imgur.com/zIKwLTu.png" alt="Installation view" height="240">
  <p align="center">( <b>Light</b> theme / <b>Dark</b> theme )</p>
</p>

2. Select **config.yml** for SkinsRestorer(and AuthMe), making sure database is enabled and working.
3. Now have fun with *changing* your skin! **Have a good time**. :smiley:

* **IMPORTANT** You can change the configuration later at `skinsystem/config.nogit.php`
* **You can use this [template](https://github.com/riflowth/SkinSystem/wiki/Configuration) for making config file manually**.

---

# :hammer: Collaborators

[![](https://avatars3.githubusercontent.com/u/42472574?s=80&v=4)](https://www.facebook.com/Vectier) | [![](https://avatars3.githubusercontent.com/u/1367069?s=80&v=4)](https://github.com/lion328) | [![](https://avatars3.githubusercontent.com/u/24414483?s=80&v=4)](https://github.com/aljaxus) | [![](https://avatars2.githubusercontent.com/u/43493339?s=80&v=4)](https://github.com/SkinsRestorer/SkinsRestorerX) | [![](https://avatars2.githubusercontent.com/u/6525296?s=80&v=4)](https://github.com/InventivetalentDev) | [![](https://avatars2.githubusercontent.com/u/42504016?s=80&v=4)](https://github.com/ITZVGcGPmO)
-|-|-|-|-|-
[@VectierThailand](https://www.facebook.com/VectierThailand) | [@lion328](https://github.com/lion328) | [@aljaxus](https://github.com/aljaxus) | [@SkinsRestorer](https://github.com/SkinsRestorer/SkinsRestorerX) | [@InventivetalentDev](https://github.com/InventivetalentDev) | [@ITZVGcGPmO](https://github.com/ITZVGcGPmO)

---

## :thumbsup: Donations

If The SkinSystem makes your life better, *you can give me a pack of candy :)*

- [**PayPal**](https://www.paypal.me/wheprakhone)

or leave your thumbs up on [Our team Facebook](https://www.facebook.com/Vectier) :thumbsup:

### [:trophy: Hall of Donators](DONATIONS.md)

---

## :pencil: License

Our **SkinSystem** is licensed under the MIT License - see the [LICENSE.md](https://github.com/riflowth/SkinSystem/blob/master/LICENSE) file for details.

---
