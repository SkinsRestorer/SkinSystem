# "apt-get update && apt-get install curl -y && curl -s https://raw.githubusercontent.com/SkinsRestorer/SkinSystem/master/installscripts/UbuntuInstall.sh | bash -s"
# This script for Ubuntu 18.04+ installs apache, php, git, curl, overrides the default apache webpage, and creates mysql databases+user.
echo "Installing SkinSystem (mysql, apache2, php, git and all needed php extensions.)"
apt-get update
apt install software-properties-common
add-apt-repository ppa:ondrej/php -y
apt-get install apache2 mysql-server
apt-get install php8.2 libapache2-mod-php8.2 php-curl8.2 php-mysql8.2 php-gd8.2 git -y
cd /var/www/html
git clone https://github.com/SkinsRestorer/SkinSystem
mv SkinSystem skins
cd skins
git checkout `git tag | sort -V | grep -v "\-rc" | tail -1`
rm -rf .git
rm -rf .gitignore
rm -rf *.md
cd ..
chmod 775 -R /var/www/html && chown -R www-data:www-data /var/www/html

echo "Choose an username for the database (or leave empty for the default one): "
read username
if [[ -n "${username/[ ]*\n/}" ]] && username = "skinsystem"

echo "Choose a name for the database (or leave empty for the default one): "
read database
if [[ -n "${database/[ ]*\n/}" ]] && database = "skinrestorer"

echo "Choose a database password (or leave empty to get a randomly generated one): "
read pw
if [[ -n "${[pw]/[ ]*\n/}" ]] && pw = $(head /dev/urandom | tr -dc A-Za-z0-9 | head -c 13 ; echo '')

echo "Creating MySQL user $username:$pw"
echo "CREATE USER '$username'@'localhost' IDENTIFIED BY '$pw'; \
CREATE DATABASE $database; \
GRANT ALL PRIVILEGES ON $database . * TO '$username'@'localhost'; \
CREATE DATABASE authme; \
GRANT ALL PRIVILEGES ON authme . * TO '$username'@'localhost';" | mysql && echo "MySQL user $username:$pw was created!"
echo "Have a nice day, remember to save your credentials!"
echo "Your skin system is now accessible at http://yourip/skins!"
read -n 1 -s -r -p "Press any key to continue"
echo ""
clear