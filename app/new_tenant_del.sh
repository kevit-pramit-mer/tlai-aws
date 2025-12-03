#!/bin/bash

DB_USER="root"
DB_PASS="Gv9Xr2mQpLz7KbYt"
DB_NAME="ucdb"
TABLE_NAME="uc_tenant_configs"
DB_HOST="72.60.40.106"

new_domain=$(echo "select count(*) from uc_tenants where is_deleted = '1'" | mysql -N -h $DB_HOST -u $DB_USER -p$DB_PASS $DB_NAME;)
echo $new_domain

if [ "$new_domain" -gt "0" ]; then
{
	nginx_path="/etc/nginx/conf.d"
domain=$(echo "select organisation_domain from uc_tenants where is_deleted = '1' ORDER BY createdAt DESC LIMIT 1" | mysql -N -h $DB_HOST -u $DB_USER -p$DB_PASS $DB_NAME;)
echo "$domain"

tenant_id=$(echo "select tenant_id from uc_tenants where is_deleted = '1'  ORDER BY createdAt DESC LIMIT 1" | mysql -N -h $DB_HOST -u $DB_USER -p$DB_PASS $DB_NAME;)
echo "$tenant_id"
query_res=$(echo "update uc_tenants set is_deleted = '2' where organisation_domain = '$domain'" | mysql -N -h $DB_HOST -u $DB_USER -p$DB_PASS $DB_NAME;)

query_res=$(echo "update uc_tenant_configs set is_deleted = '2' where tenant_id = '$tenant_id'" | mysql -N -h $DB_HOST -u $DB_USER -p$DB_PASS $DB_NAME;)

if [ -f "$nginx_path/$domain.conf" ]
then
rm -rf  $nginx_path/$domain.conf
sudo service nginx reload
else
sudo service nginx reload
fi
}
fi

