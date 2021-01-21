echo "Starting server setup ..."

echo "Setting locale"
export LANGUAGE=en_US.UTF-8
export LANG=en_US.UTF-8
export LC_ALL=en_US.UTF-8
locale-gen en_US.UTF-8
dpkg-reconfigure locales -f noninteractive

echo "Updating package list and installing any updates ..."
apt-get -qq update
apt-get -qq upgrade --yes

echo "Intalling apache"
apt-get -qq install apache2 --yes

echo "Configuring the apache server to listen to localhost"
echo ServerName localhost | tee -a /etc/apache2/apache2.conf
service apache2 restart

echo "Setting the apache entry point to be /var/www/web/"
sed -i 's@\([ \t]*\)DocumentRoot /var/www/html@\1DocumentRoot /var/www/web@' /etc/apache2/sites-enabled/000-default.conf

echo "Installing PostgreSQL"
apt-get -qq install postgresql --yes

echo "Seting up PostgreSQL to listen to all available IP interfaces"
sed -i "s/#listen_address.*/listen_addresses '*'/" /etc/postgresql/11/main/postgresql.conf

echo "Allowing all incoming connections to the database"
echo "host    all         all         0.0.0.0/0             md5" | tee -a /etc/postgresql/11/main/pg_hba.conf

echo "Creating vagrant user"
su postgres -c "psql -c \"CREATE ROLE vagrant SUPERUSER LOGIN PASSWORD 'vagrant'\" "

echo "Installing php"
apt-get -qq install php php-common php7.3-pgsql --yes


