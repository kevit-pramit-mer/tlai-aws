#!/bin/bash

DB_USER="root"
DB_PASS="Gv9Xr2mQpLz@7KbYt"
DB_NAME="ucdb"
TABLE_NAME="uc_server_usases"


last_reboot=$(who -b | awk '{print $3 " " $4}')
ipaddr=$(hostname -I | awk '{print $1}')

echo "last reboot time $last_reboot"
echo "Server IP address: $ipaddr"



# MySQL query to insert CPU utilization into the database
MYSQL_QUERY="UPDATE $TABLE_NAME SET last_reboot_time='$last_reboot'  WHERE server_ip='$ipaddr';"
echo "$MYSQL_QUERY"
# Execute MySQL query
mysql -h 172.31.4.239 -u $DB_USER -p$DB_PASS $DB_NAME -e "$MYSQL_QUERY"

# Check if the query was executed successfully
if [ $? -eq 0 ]; then
    echo "server Last reboot time insert successfully"
else
    echo "Error: Failed to insert last roobt time into the database."
fi


