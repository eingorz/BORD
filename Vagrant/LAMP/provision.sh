#!/usr/bin/env bash
set -e
export DEBIAN_FRONTEND=noninteractive

#######################################
#            VARIABLES                #
#######################################

# Web
WEB_ROOT="/var/www/html"
APACHE_SITE_NAME="ctf.conf"
UPLOAD_MAX="200M"
POST_MAX="200M"

# MySQL
INSTALL_MYSQL="yes"
MYSQL_ROOT_PASSWORD=""         # "" = no password
MYSQL_ALLOW_REMOTE_ROOT="yes"  # root can connect from any host

# phpMyAdmin
INSTALL_PHPMYADMIN="yes"
PMA_SKIP_DBCONFIG="yes"        # skip DB config completely
PMA_BLOWFISH_SECRET="ctfisfunlol"

# General
DISABLE_UFW="yes"
ENABLE_SUID_VIM="yes"

#######################################
#            SYSTEM UPDATE            #
#######################################

apt-get update -y
apt-get upgrade -y

#######################################
#         APACHE + PHP INSTALL        #
#######################################

apt-get install -y apache2 \
    php \
    libapache2-mod-php \
    php-mysql \
    php-mbstring \
    php-zip \
    php-gd \
    php-curl \
    php-xml

#######################################
#     SYNCED FOLDER CONFIGURATION     #
#######################################

# The WEB_ROOT is now a synced folder managed by Vagrant.
# Skipping copy and removal of files to preserve the host directory.

#######################################
#        APACHE VHOST SETUP           #
#######################################

a2dissite 000-default.conf

cat <<EOF > /etc/apache2/sites-available/${APACHE_SITE_NAME}
<VirtualHost *:80>
    DocumentRoot ${WEB_ROOT}

    <Directory ${WEB_ROOT}>
        AllowOverride All
        Require all granted
        Options Indexes FollowSymLinks
    </Directory>

    ErrorLog \${APACHE_LOG_DIR}/error.log
    CustomLog \${APACHE_LOG_DIR}/access.log combined
</VirtualHost>
EOF

a2ensite ${APACHE_SITE_NAME}
a2enmod rewrite

#######################################
#          PHP CONFIG (UPLOADS)       #
#######################################

PHP_INI=$(php -r "echo php_ini_loaded_file();")
sed -i "s/upload_max_filesize = .*/upload_max_filesize = ${UPLOAD_MAX}/" ${PHP_INI}
sed -i "s/post_max_size = .*/post_max_size = ${POST_MAX}/" ${PHP_INI}

#######################################
#            MYSQL SETUP               #
#######################################

if [ "$INSTALL_MYSQL" = "yes" ]; then
    apt-get install -y mysql-server

    # Local root user
    if [ -z "$MYSQL_ROOT_PASSWORD" ]; then
        mysql -e "ALTER USER 'root'@'localhost' IDENTIFIED WITH mysql_native_password BY ''; FLUSH PRIVILEGES;"
    else
        mysql -e "ALTER USER 'root'@'localhost' IDENTIFIED WITH mysql_native_password BY '${MYSQL_ROOT_PASSWORD}'; FLUSH PRIVILEGES;"
    fi

    # Remote root
    if [ "$MYSQL_ALLOW_REMOTE_ROOT" = "yes" ]; then
        mysql -e "CREATE USER IF NOT EXISTS 'root'@'%' IDENTIFIED BY '${MYSQL_ROOT_PASSWORD}';"
        mysql -e "GRANT ALL PRIVILEGES ON *.* TO 'root'@'%' WITH GRANT OPTION;"
        mysql -e "FLUSH PRIVILEGES;"
    fi

    # Import SQL files if present
    if [ -d ${WEB_ROOT} ]; then
        find ${WEB_ROOT} -name "*.sql" -type f | sort | while read -r sql_file; do
            echo "Importing $sql_file..."
            if [ -z "$MYSQL_ROOT_PASSWORD" ]; then
                mysql -u root < "$sql_file"
            else
                mysql -u root -p"${MYSQL_ROOT_PASSWORD}" < "$sql_file"
            fi
        done
    fi
fi


#######################################
#        PHPMYADMIN INSTALL           #
#######################################

if [ "$INSTALL_PHPMYADMIN" = "yes" ]; then
    echo "phpmyadmin phpmyadmin/internal/skip-preseed boolean true" | debconf-set-selections
    echo "phpmyadmin phpmyadmin/reconfigure-webserver multiselect" | debconf-set-selections
    [ "$PMA_SKIP_DBCONFIG" = "yes" ] && echo "phpmyadmin phpmyadmin/dbconfig-install boolean false" | debconf-set-selections

    apt-get install -y phpmyadmin

    # Apache alias for /phpmyadmin
    cat <<EOF > /etc/apache2/conf-available/phpmyadmin.conf
Alias /phpmyadmin /usr/share/phpmyadmin

<Directory /usr/share/phpmyadmin>
    Options Indexes FollowSymLinks
    DirectoryIndex index.php
    AllowOverride All
    Require all granted
</Directory>
EOF

    a2enconf phpmyadmin

    # Allow root login with no password for CTF
    if [ -z "$MYSQL_ROOT_PASSWORD" ]; then
        cat <<'EOF' > /etc/phpmyadmin/conf.d/ctf_no_pw.inc.php
<?php
/* Allow login without password for CTF */
$i = 1;
$cfg['Servers'][$i]['host'] = 'localhost';
$cfg['Servers'][$i]['auth_type'] = 'config';
$cfg['Servers'][$i]['user'] = 'root';
$cfg['Servers'][$i]['password'] = '';
$cfg['Servers'][$i]['AllowNoPassword'] = true;
EOF
    fi
fi



#######################################
#             PHP INFO PAGE           #
#######################################

cat <<EOF > ${WEB_ROOT}/info.php
<?php phpinfo(); ?>
EOF

#######################################
#          FIREWALL DISABLE           #
#######################################

[ "$DISABLE_UFW" = "yes" ] && { ufw disable || true; systemctl stop ufw || true; }

#######################################
#        OPTIONAL CTF PRIVESC         #
#######################################

[ "$ENABLE_SUID_VIM" = "yes" ] && chmod 4777 /usr/bin/vim.basic || true

#######################################
#             RESTART                 #
#######################################

systemctl restart apache2

#######################################
#              DONE                   #
#######################################

echo ""
echo "====================================="
echo " BOX READY"
echo "====================================="
echo "Web root: ${WEB_ROOT}"
echo "PHP info: /info.php"
[ "$INSTALL_PHPMYADMIN" = "yes" ] && echo "phpMyAdmin: /phpmyadmin"
echo "MySQL root password: '${MYSQL_ROOT_PASSWORD}'"
