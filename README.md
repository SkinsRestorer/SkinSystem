<p align="center">
  <a href="https://github.com/SkinsRestorer/SkinSystem">
    <img src="https://i.imgur.com/pjuawRU.jpg" alt="SkinSystem" width="600">
  </a>
</p>

<p align="center">
  The <b>SkinSystem</b>; developed for cracked <strong>Minecraft servers</strong>, allowing for changes of player skins to something more <strong>custom</strong>.
</p>

<p align="center">
  <a href="https://opensource.org/licenses/MIT"><img src="https://img.shields.io/github/license/SkinsRestorer/SkinSystem.svg" alt="MIT License"></a>
  <a href="https://github.com/SkinsRestorer/SkinSystem/releases"><img src="https://img.shields.io/github/release/SkinsRestorer/SkinSystem.svg" alt="Release"></a>
  <a href="https://github.com/SkinsRestorer/SkinSystem/releases"><img src="https://img.shields.io/github/downloads/SkinsRestorer/SkinSystem/total.svg" alt="Download"></a>
  <a href="https://skinsrestorer.net/donate"><img src="https://ionicabizau.github.io/badges/paypal.svg" alt="PayPal Donate"></a>
  <a href="https://www.spigotmc.org/resources/the-skinsystem.59403/"><img src="https://img.shields.io/badge/view%20on-spigotmc-orange.svg" alt="View on spigotmc.org"></a>
  <a href="https://skinsrestorer.net/discord"><img src="https://discord.com/api/guilds/186794372468178944/embed.png" alt="Join the chat"></a>
</p>

---

## :eyeglasses: Examples

<p align="center">
  <img src="https://i.imgur.com/5baEOlG.png" alt="The SkinSystem Preview" height="230">
  <img src="https://i.imgur.com/AbZgB5n.png" alt="The SkinSystem Preview" height="230">
  <img src="https://i.imgur.com/grNDVYA.png" alt="The SkinSystem Preview" height="230">
  <p align="center">
    <b>Installation</b> view / <b>SkinUploader</b> view / <b>Authen</b> view (<i>when you use this system with Authme</i>)
  </p>
</p>

---

## :memo: Requirements

- [SkinsRestorer](https://www.spigotmc.org/resources/skinsrestorer.2124/)
- [AuthMe](https://www.spigotmc.org/resources/authmereloaded.6269/) (Optional)
- Web Server
- Database

---

## :wrench: Installation

:grey_exclamation: If you use **Ubuntu 18.04**+, you may run **this command** to *automagically* install
apache+mysql+git+curl, set apache2 webpage, and generate MySQL credentials:

```bash
sudo bash -c "apt-get update && apt-get install curl -y && curl -s https://raw.githubusercontent.com/SkinsRestorer/SkinSystem/main/installscripts/UbuntuInstall.sh | bash -s"
```

#### **otherwise**, follow **these directions**:

1. Install MySQL
2. Install and configure a webserver that supports php, php-curl, php-mysql, and php-gd. (apache2 may be used)
3. Make sure your webserver is accessible.
4. Create databases `skinsrestorer` and `authme`
   . [Commands](https://gist.github.com/ITZVGcGPmO/a3dffa0db198919ae002efcad444ae34)
5. Download the latest **release** version from [**here!**](https://github.com/SkinsRestorer/SkinSystem/releases)
6. Put all of `The SkinSystem` into your **web-root** directory.

### :star: Let's start!!!

* **IMPORTANT** Enable **Database** in your **AuthMe** configuration. (Optional)

* **IMPORTANT** Enable **Database** in your **SkinsRestorer** configuration. Example configuration:

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

* **IMPORTANT** You can change the configuration later at `SkinSystem/config.nogit.php`
* **You can use this [template](https://github.com/SkinsRestorer/SkinSystem/wiki/Configuration) for making config file
  manually**.

---

## :family: Authors

See [Contributors](https://github.com/SkinsRestorer/SkinsRestorerX/graphs/contributors) for a list of people that have
supported this project by contributing.

---

## :thumbsup: Donations

If The SkinSystem makes your life better, *you can give me a pack of candy :)*

- [**PayPal**](https://skinsrestorer.net/donate)

### [:trophy: Hall of Donators](DONATIONS.md)

---

## :pencil: License

Our **SkinSystem** is licensed under the MIT License - see
the [LICENSE.md](https://github.com/SkinsRestorer/SkinSystem/blob/main/LICENSE) file for details.

---
