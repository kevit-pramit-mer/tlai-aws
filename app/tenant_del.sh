#!/bin/bash
###################################################* Variable Declaration *############################################ 
sql_backup_path='/opt/'
webpath='/etc/nginx/'
sql_user=$1
sql_pass=$2
sql_dbname=$3
mongo_user=$5
mongo_pass=$6
mongo_dbname=$7
tenant_uuid=$8
domain=$9
nos_args=$#
dbhost=$4
#################################################* No of Argument Check *###############################################
arg_check()
{
if [ "$1" -ne 9 ]
then
echo "Argument incomplete"
exit;       	
else
echo ""
fi
}
################################################* Database Verification *#################################################
db_check()
{
DB_EXISTS=$(mysql -h $2 -u root -pec0sm0bt  -e "SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME='$1'")
if [ -z  "$DB_EXISTS" ];
then
    echo "DATABASE DOES NOT EXIST"
    exit;
else
    echo "DOES EXIST"
fi
}
################################################* Database Dump and file writing *########################################
db_dump()
{
localpath=$8$7
mkdir $localpath
mysqldump -h $9 -u root -pec0sm0bt --routines $3 > $localpath/$3.sql
#touch $localpath/dbinfo.txt
#printf 'Mysql_Username: '$1'\nMysql_password: '$2'\nMysql_DB_name: '$3'\nMongo_Username: '$4'\nMongo_password: '$5'\nMongo_DB_name: '$6'\nTenant_uuid: '$7'\nDomain: '$8'\n***************************Info Added Successfully***********************' >> $localpath/dbinfo.txt
mysql -h $9 -u root -pec0sm0bt -e "drop database $3;DROP USER '$1'@'%'"
mongodump --host $9 --username=$4 --password=$5 --db=$6 --out=$localpath
mongosh --host $9 --port "27017" -u $4 --authenticationDatabase $6 -p $5 << EOF
use $6
db.dropDatabase()
EOF
}
db_info_write()
{
localpath=$9$7
cp /usr/share/nginx/html/uctenant/defauldb.info $localpath/db.info
sed -i 's/my_U/'$1'/g' $localpath/db.info
sed -i 's/my_p/'$2'/g' $localpath/db.info
sed -i 's/my_db/'$3'/g' $localpath/db.info
sed -i 's/mongo_u/'$4'/g' $localpath/db.info
sed -i 's/mongo_p/'$5'/g' $localpath/db.info
sed -i 's/mongo_db/'$6'/g' $localpath/db.info
sed -i 's/t_uid/'$7'/g' $localpath/db.info
sed -i 's/domain/'$8'/g' $localpath/db.info
}
###################################################* Delete Nginx configuration *################################################
nginx_config_del()
{
rm -f $1conf.d/$2.conf
#rm -f $1sites-available/$2
systemctl reload nginx
}
############################################################*Main Script*###################################################
arg_check $nos_args
db_check $sql_dbname $dbhost
db_dump $sql_user $sql_pass $sql_dbname $mongo_user $mongo_pass $mongo_dbname $tenant_uuid $sql_backup_path $dbhost
db_info_write $sql_user $sql_pass $sql_dbname $mongo_user $mongo_pass $mongo_dbname $tenant_uuid $domain $sql_backup_path
nginx_config_del $webpath $domain

