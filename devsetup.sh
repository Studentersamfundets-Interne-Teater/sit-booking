echo "Moving into project folder"
cd /var/www

echo "Removing auto-generated Apache files"
rm -r html

echo "Installing Composer and using it to install Twig"
php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
php composer-setup.php
php -r "unlink('composer-setup.php');"
php composer.phar install

echo "Creating sitbooking database"
createdb -E UTF8 -T template0 --locale=en_US.utf8 -O vagrant sitbooking

echo "Seting up the tables in the database"
psql -d sitbooking -f sql/create_tables.sql

echo "Creating the database configuration file to be read by the server "
cat > include/db_config.php << EOF
<?php
\$dbhost = "localhost";
\$dbname = "sitbooking";
\$dbuser = "vagrant";
\$dbpassword = "vagrant";
EOF

echo "All done, you can now visit localhost:8080 to see the site"