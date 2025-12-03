#!/bin/bash

DB_USER="root"
DB_PASS="Gv9Xr2mQpLz7KbYt"
DB_NAME="ucdb"
TABLE_NAME="uc_tenant_configs"
DB_HOST="72.60.40.106"

new_domain=$(echo "select count(*) from uc_tenants where is_created = '0'" | mysql -N -h $DB_HOST -u $DB_USER -p$DB_PASS $DB_NAME;)
echo $new_domain

if [ "$new_domain" -gt "0" ]; then
{
	web_path="/usr/share/nginx/html/uctenant"
	nginx_path="/etc/nginx"
domain=$(echo "select organisation_domain from uc_tenants where is_created = '0' limit 1" | mysql -N -h $DB_HOST -u $DB_USER -p$DB_PASS $DB_NAME;)
echo "$domain"

query_res=$(echo "update uc_tenants set is_created = '1' where organisation_domain = '$domain'" | mysql -N -h $DB_HOST -u $DB_USER -p$DB_PASS $DB_NAME;)
cp $web_path/default_config $nginx_path/conf.d/$domain.conf
sed -i 's/domain/'$domain'/g' $nginx_path/conf.d/$domain.conf
sudo service nginx reload > /dev/null 2>&1
}
fi

