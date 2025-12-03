#!/bin/bash
#########################################################$ Variable Declaration $###################################################
h_domain=$1
dbhost=$2
webpath='/usr/share/nginx/html/uctenant'
nginx_config_path='/etc/nginx'
#########################################################$ Random String genrate $##################################################
rand_string_gen()
{
#echo $1
str=$(head /dev/urandom | tr -dc A-Za-z0-9 | head -c"$1")
echo $str
}

########################################################$ Mysql Data setup $#######################################################
Mysql_db_create()
{
mysql -h $5 -u root -pec0sm0bt -e "create database $3;CREATE USER '$1'@'%' IDENTIFIED BY '$2';GRANT ALL PRIVILEGES ON $3.* TO '$1'@'%';FLUSH PRIVILEGES;"

mysql -h $5 -u root -pec0sm0bt -e "GRANT ALL PRIVILEGES ON ucdb.* TO '$1'@'%';FLUSH PRIVILEGES;"
rm -rf $4/temp_db.sql
cp $4/uctenant_db_dump.sql $4/temp_db.sql
sed -i 's/uctenant1.ecosmob.net/'$6'/g' $4/temp_db.sql
mysql -h $5 -u root -pec0sm0bt $3 < $4/temp_db.sql &
#echo "mysql_username=$username,mysql_password=$password,mysql_dbname=$dbs,mongo_username=$username,mongo_password=$password,mongo_dbname=$dbs"
}
#########################################################$ Mongo DB Setup  $######################################################
mongo_db_create()
{
##LocalVariables
HOSTNAME="$4"
PORT="27017"
USERNAME="admin"
PASSWORD="ec0sm0bt"
DBNAME=$3
NEWUSERNAME=$1
NEWPASSWORD=$2

mongosh --host "$HOSTNAME" --port "$PORT" -u "$USERNAME" -p "$PASSWORD" << EOF

# Create new database
use $DBNAME

# Create new user and grant read/write access to new database
db.createUser({
  user: "$NEWUSERNAME",
  pwd: "$NEWPASSWORD",
  roles: [
    { role: "dbOwner", db: "$DBNAME" }
  ]
})
db.createCollection("channel.variables");
db.createCollection("uctenant.fraud");
db.createCollection("uctenant.cdr");
db.createCollection("uctenant.fax.cdr");
db.createCollection("uctenant.queue.report");
EOF
}
#########################################################$ Nginx Configuration setup $############################################
nginx_config()
{
cp $3/default_config $2/conf.d/$1.conf
sed -i 's/domain/'$1'/g' $2/conf.d/$1.conf
service nginx reload > /dev/null 2>&1
}
########################################################$ Info return to API $#####################################################
print_info()
{
echo "mysql_username=$1,mysql_password=$2,mysql_dbname=$3,mongo_username=$1,mongo_password=$2,mongo_dbname=$3"
}

############################################################$ Mail Script $######################################################

username=$(rand_string_gen 7)
password=$(rand_string_gen 15)
dbs=$(rand_string_gen 8)
dbs=uc_t$dbs
Mysql_db_create $username $password $dbs $webpath $dbhost $h_domain
nginx_config  $h_domain $nginx_config_path $webpath
print_info $username $password $dbs
mongo_db_create $username $password $dbs $dbhost >logger.log 2>&1 &

