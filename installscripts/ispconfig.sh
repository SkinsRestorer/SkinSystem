# "apt-get update && apt-get install curl -y && curl -s https://raw.githubusercontent.com/riflowth/SkinSystem/master/installscripts/ispconfig.sh | bash -s"
# This script for ispconfig installs apache, php, git, curl, overrides the default apache webpage, and creates mysql databases+user.
echo "installing skinsystem (mysql, apache2, php, git)"
apt-get update
apt-get install mysql-server apache2 libapache2-mod-php php-curl php-mysql php-gd git -y
cd /var/www
git clone https://github.com/riflowth/SkinSystem
cd SkinSystem
git checkout ``git tag | sort -V | grep -v "\-rc" | tail -1``
rm -rf .git
rm -rf .gitignore
rm -rf *.md
cd ..
read -p "Enter your domain name (must be created via ispconfig): " site
USER=$(stat -c '%U' /var/www/$site/web)
GROUP=$(stat -c '%G' /var/www/$site/web)
mv $site/web $site/web.backup$(date -I)
mv SkinSystem $site/web
chmod 775 -R /var/www/$site/web && chown -R $USER:$GROUP /var/www/$site/web
pw=$(head /dev/urandom | tr -dc A-Za-z0-9 | head -c 13 ; echo '')
echo "Creating MySQL user skinsystem:$pw"
echo "CREATE USER 'skinsystem'@'localhost' IDENTIFIED BY '$pw'; \
CREATE DATABASE skinsrestorer; \
GRANT ALL PRIVILEGES ON skinsrestorer . * TO 'skinsystem'@'localhost'; \
CREATE DATABASE authme; \
GRANT ALL PRIVILEGES ON authme . * TO 'skinsystem'@'localhost';" | mysql && echo "MySQL user skinsystem:$pw was created"
echo "Have a nice day, remember to save your credentials!"
read -n 1 -s -r -p "Press any key to continue"
echo ""
clear
