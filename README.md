---

<p align="center">
  <a href="https://github.com/riflowth/SkinSystem">
    <img src="https://i.imgur.com/pjuawRU.jpg" alt="SkinSystem" width="600">
  </a>
</p>

<p align="center">
  The <b>SkinSystem</b> developed for cracked <strong>Minecraft's server</strong> allowing to change their player skin into a <strong>custom skin</strong>.
</p>

<p align="center">
  <a href="https://opensource.org/licenses/MIT"><img src="https://img.shields.io/github/license/riflowth/SkinSystem.svg" alt="MIT License"></a>
  <a href="https://github.com/riflowth/SkinSystem/releases"><img src="https://img.shields.io/github/release/riflowth/skinsystem.svg" alt="Release"></a>
  <a href="https://github.com/riflowth/SkinSystem/releases"><img src="https://img.shields.io/github/downloads/riflowth/SkinSystem/total.svg" alt="Download"></a>
  <a href="https://www.paypal.me/wheprakhone"><img src="http://ionicabizau.github.io/badges/paypal.svg" alt="PayPal Donate"></a>
</p>

---

## :memo: Requirements

- [SkinsRestorer](https://www.spigotmc.org/resources/skinsrestorer.2124/)
- [AuthMe](https://www.spigotmc.org/resources/authmereloaded.6269/) (Optional)
- WebServer
- Database

---

## :eyeglasses: Examples

<p align="center">
  <img src="https://i.imgur.com/naeNvbO.png" alt="The SkinSystem Preview" width="350">
  <img src="https://i.imgur.com/X3aSrnB.png" alt="The SkinSystem Preview" width="350">
  <img src="https://i.imgur.com/thQrsxu.png" alt="The SkinSystem Preview" width="350">
  <p align="center"><b>Installation</b> view / <b>SkinUploader</b> view / <b>Authen</b> view (<i>when you use this system with Authme</i>)</p>
</p>

---

## :wrench: Installation

* **IMPORTANT** Don't forget to *enable* **Database** in your **SkinsRestorer** configuration, located here: `minecraftserver/plugins/SkinRestorer/config.yml`
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

### :star: Let's start!!!

1. Download **release** version from [**here!**](https://github.com/riflowth/SkinSystem/releases)
2. Put all of `The SkinSystem` in your **web-root** directory.
3. After putting `The SkinSystem` into your **web-root** directory you should see `The SkinSystem` on your website. You will see like this down below.

<p align="center">
  <img src="https://i.imgur.com/naeNvbO.png" alt="Installation view">
</p>

4. Just choose **config.yml** to install this system!
5. Now have fun with *changing* your skin! **Have a good time**. :smiley:

* **IMPORTANT** You can change configuration later on `skinsystem/config.nogit.php`

---

# :hammer: Collaborators

[![](https://avatars3.githubusercontent.com/u/42472574?s=80&v=4)](https://www.facebook.com/VectierThailand) | [![](https://avatars3.githubusercontent.com/u/1367069?s=80&v=4)](https://github.com/lion328) | [![](https://avatars3.githubusercontent.com/u/24414483?s=80&v=4)](https://github.com/aljaxus) | [![](https://avatars2.githubusercontent.com/u/43493339?s=80&v=4)](https://github.com/SkinsRestorer/SkinsRestorerX) | [![](https://avatars2.githubusercontent.com/u/6525296?s=80&v=4)](https://github.com/InventivetalentDev)
-|-|-|-|-
[@VectierThailand](https://www.facebook.com/VectierThailand) | [@lion328](https://github.com/lion328) | [@aljaxus](https://github.com/aljaxus) | [@SkinsRestorer](https://github.com/SkinsRestorer/SkinsRestorerX) | [@InventivetalentDev](https://github.com/InventivetalentDev)

---

## :thumbsup: Donations

If The SkinSystem makes your life better, *you can give me a pack of candy :)*
or leave your thumbs up on [Our team Facebook](https://www.facebook.com/VectierThailand) :thumbsup:

- PayPal: https://www.paypal.me/wheprakhone

### [:trophy: Hall of Donators](DONATIONS.md)

---

## :pencil: License

Our **SkinSystem** is licensed under the MIT License - see the [LICENSE.md](https://github.com/riflowth/SkinSystem/blob/master/LICENSE) file for details.

---
