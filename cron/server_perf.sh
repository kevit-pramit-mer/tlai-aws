#!/bin/bash

DB_USER="root"
DB_PASS="Gv9Xr2mQpLz@7KbYt"
DB_NAME="ucdb"
TABLE_NAME="uc_server_usases"

ports_check()
{
	PORT_STATUS=$(netstat -tuln | grep -E "^tcp.*:$1")
	if [[ -n "$PORT_STATUS" ]]; then
    echo "1"  # Port is running
else
    echo "0"  # Port is not running
fi
}

CPU_UTILIZATION=$(top -bn1 | grep "Cpu(s)" | awk '{print $2}' | awk -F. '{print $1}')
RAM_UTILIZATION=$(echo "scale=2;$(free -m | awk '/^Mem:/{print $3}') / 1024" | bc)
DISK_UTILIZATION=$(df -h | awk '$NF=="/"{print $3}' | sed 's/G//')
TOTAL_DISK=$(df -h | awk '$NF=="/"{print $2}' | sed 's/G//')
LOAD_ONE_MIN=$(uptime | awk -F'load average:' '{print $2}' | awk -F',' '{print $1}' | tr -d ' ')
LOAD_TEN_MIN=$(uptime | awk -F'load average:' '{print $2}' | awk -F',' '{print $2}' | tr -d ' ')
LOAD_FIFTEEN_MIN=$(uptime | awk -F'load average:' '{print $2}' | awk -F',' '{print $3}' | tr -d ' ')
mariadb_status=$(ports_check 3306)
#mariadb_status=2
#nginx_status=$(ports_check 80)
nginx_status=2
mongodb_status=$(ports_check 27018)
#mongodb_status=2
#tele_status=$(ports_check 5071)
tele_status=2
ipaddr=$(hostname -I | awk '{print $1}')

echo "sever cpu utilization $CPU_UTILIZATION"
#echo "$CPU_UTILIZATION"
echo "Server RAM utilization: $RAM_UTILIZATION"
echo "server DISK utilization: $DISK_UTILIZATION"
echo "CPU LOAD AVG ONE MINS: $LOAD_ONE_MIN"
echo "CPU LOAD AVG TEN MINS: $LOAD_TEN_MIN"
echo "CPU LOAD AVG fifteen MINS: $LOAD_FIFTEEN_MIN"
echo "MAriDB status: $mariadb_status"
echo "NGINX status: $nginx_status"
echo "mongo status: $mongodb_status"
echo "Voip server: $tele_status"
echo "Server IP address: $ipaddr"



# MySQL query to insert CPU utilization into the database
MYSQL_QUERY="UPDATE $TABLE_NAME SET cpu_utilisation=$CPU_UTILIZATION, ram_utilisation=$RAM_UTILIZATION, disc_utilisation=$DISK_UTILIZATION, total_disc='$TOTAL_DISK', load_1_min=$LOAD_ONE_MIN,load_10_min=$LOAD_TEN_MIN,load_15_min=$LOAD_FIFTEEN_MIN,mongo_db='$mongodb_status',maria_db='$mariadb_status',nginx='$nginx_status',telephony='$tele_status' WHERE server_ip='$ipaddr';"
echo "$MYSQL_QUERY"
# Execute MySQL query
mysql -h 172.31.4.239 -u $DB_USER -p$DB_PASS $DB_NAME -e "$MYSQL_QUERY"

# Check if the query was executed successfully
if [ $? -eq 0 ]; then
    echo "CPU utilization inserted into the database successfully."
else
    echo "Error: Failed to insert CPU utilization into the database."
fi


