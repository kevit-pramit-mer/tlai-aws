# WebRTC Calling Deployment Guide

## Overview
This guide provides step-by-step instructions to enable WebRTC browser-to-browser calling in your VoIP system. After implementing these changes, browser extensions (like 1611 and 1555) can call each other directly with incoming call alerts working properly.

---

## Server Information
- **Public IP:** 13.200.87.192
- **Private IP:** 172.31.7.125
- **SIP Domain:** tenant1.teleaon.ai
- **WebSocket Port:** 5068 (wss://tenant1.teleaon.ai:5068)
- **Web Application:** Running on tenant1.teleaon.ai

---

## Problem Summary (Before Fix)
- WebSocket clients could register but calls failed with "480 Temporarily Unavailable"
- Recipients never received incoming call alerts/popups
- FreeSWITCH stored wrong Contact URIs: `sip:1555@0.0.0.0:5060` instead of WebSocket addresses
- OpenSIPS mid_registrar was rewriting WebSocket contacts and forwarding to FreeSWITCH

## Solution Summary (After Fix)
- WebSocket clients register LOCALLY in OpenSIPS only (not in FreeSWITCH)
- WebSocket contacts preserved with proper `transport=ws` parameter
- WebSocket-to-WebSocket calls route directly through OpenSIPS without FreeSWITCH
- Non-WebSocket clients still use mid_registrar and FreeSWITCH normally

---

## Prerequisites
- Docker and Docker Compose installed
- Access to server (13.200.87.192) via SSH or terminal
- Root/sudo access
- Backup of current configuration files
- SSL certificate for tenant1.teleaon.ai (required for WSS)

---

## Part 1: Backup Current Configuration

### Step 1: Create Backup Directory
```bash
# Navigate to your project directory
cd /path/to/your/project

# Create backup folder with timestamp
mkdir -p backups/$(date +%Y%m%d_%H%M%S)
BACKUP_DIR="backups/$(date +%Y%m%d_%H%M%S)"
```

### Step 2: Backup Critical Files
```bash
# Backup OpenSIPS configuration
cp opensips/opensips.cfg $BACKUP_DIR/opensips.cfg.bak

# Backup Docker Compose
cp docker-compose.yml $BACKUP_DIR/docker-compose.yml.bak

# Backup FreeSWITCH configs
cp portal_fs/freeswitch_files/odbc.ini $BACKUP_DIR/odbc.ini.bak
cp portal_fs/freeswitch_files/vars.xml $BACKUP_DIR/vars.xml.bak

# Backup Yii2 configs
cp app/config/params.php $BACKUP_DIR/params.php.bak
cp app/config/web.php $BACKUP_DIR/web.php.bak
cp app/views/auth/main.php $BACKUP_DIR/main.php.bak
cp app/modules/ecosmob/extension/web/js/mainSIP.js $BACKUP_DIR/mainSIP.js.bak

echo "✓ Backup completed in $BACKUP_DIR"
```

---

## Part 2: Application Configuration Changes

### Step 3: Add SIP Domain and WebSocket Parameters
**File:** `app/config/params.php`

Add these lines at the end of the params array:

```php
// Add these lines before the closing bracket ];
'SIP_DOMAIN' => 'tenant1.teleaon.ai',
'WSS_URL' => 'wss://tenant1.teleaon.ai:5068',  // Secure WebSocket URL
```

**Command to apply:**
```bash
nano app/config/params.php
# Or use vi/vim
vi app/config/params.php

# Add both lines, save and exit
# For nano: Ctrl+X, Y, Enter
# For vi: Press i to insert, ESC, :wq to save
```

**Note:** If using WSS (secure WebSocket), ensure you have a valid SSL certificate for tenant1.teleaon.ai.

### Step 4: Disable Asset Caching
**File:** `app/config/web.php`

Find the `'assetManager' => [` section and add these two parameters:

```php
'assetManager' => [
    'bundles' => require(__DIR__ . '/' . (YII_ENV_PROD ? 'assets-prod.php' : 'assets-dev.php')),
    'forceCopy' => true,           // Add this line
    'appendTimestamp' => true,      // Add this line
],
```

**Command to apply:**
```bash
nano app/config/web.php

# Find the assetManager section and add the two lines
# Save and exit
```

### Step 5: Pass SIP Domain and WebSocket URL to JavaScript
**File:** `app/views/auth/main.php`

Find the section where JavaScript variables are defined (around line 20-30) and add:

```php
<!-- Add this JavaScript block in the <head> or before other script tags -->
<script type="text/javascript">
    <?php 
    $sipDomain = Yii::$app->params['SIP_DOMAIN'] ?? 'tenant1.teleaon.ai';
    $domainName = Yii::$app->params['DOMAIN_NAME'] ?? 'tenant1.teleaon.ai';
    $wssUrl = Yii::$app->params['WSS_URL'] ?? 'wss://tenant1.teleaon.ai:5068';
    ?>
    var sipDomain = '<?= $sipDomain ?>';
    var domainName = '<?= $domainName ?>';
    var wssUrl = '<?= $wssUrl ?>';
    console.log('DEBUG: sipDomain =', sipDomain, 'domainName =', domainName, 'wssUrl =', wssUrl);
</script>
```

**Command to apply:**
```bash
nano app/views/auth/main.php

# Add the script block in the appropriate location
# Save and exit
### Step 6: Update SIP.js to Use Dynamic Domain and WSS URL
**File:** `app/modules/ecosmob/extension/web/js/mainSIP.js`

**Find and replace these sections:**

#### Section A: Update transportOptions (around line 10-15)
**OLD:**
```javascript
const transportOptions = {
    server: 'ws://localhost:5064'
};
```

**NEW:**
```javascript
const transportOptions = {
    server: wssUrl  // Uses wss://tenant1.teleaon.ai:5068 from params.php
};
```

#### Section B: Update URI creation (around line 20-30)
**OLD:**
```javascript
var uri = SIP.UserAgent.makeURI(`sip:${extensionNumber}@localhost:8080`);
```

**NEW:**
```javascript
var uri = SIP.UserAgent.makeURI(`sip:${extensionNumber}@${sipDomain}`);
console.log(`mainSIP.js - Created URI: ${uri} using sipDomain: ${sipDomain}`);
```

#### Section C: Update call initiation (around line 300-350)
**OLD:**
```javascript
const target = SIP.UserAgent.makeURI(`sip:${destinationNumber}@localhost:8080`);
```

**NEW:**
```javascript
const target = SIP.UserAgent.makeURI(`sip:${destinationNumber}@${sipDomain}`);
```

**Command to apply:**
```bash
nano app/modules/ecosmob/extension/web/js/mainSIP.js

# Search for "localhost:5064" and replace with wssUrl variable
# Search for "localhost:8080" and replace with sipDomain variable
# Use Ctrl+W in nano to search, or / in vi

# Make all three changes, save and exit
```
# Make both changes, save and exit
```

---

## Part 3: Docker Network Configuration

### Step 7: Update FreeSWITCH Network Settings
**File:** `docker-compose.yml`

**Find the `freeswitch` service and make these changes:**

**OLD:**
```yaml
freeswitch:
  image: your-freeswitch-image
  network_mode: host
  # ... other settings
```

**NEW:**
```yaml
freeswitch:
  image: your-freeswitch-image
  hostname: tlai-freeswitch  # Add this
  # Remove: network_mode: host
  networks:                   # Add this
    - backend
  ports:                      # Add these port mappings
    - "5070:5060"
    - "5071:5071"
    - "5081:5081"
    - "5061:5061"
    - "16384-16484:16384-16484/udp"
  # ... other settings
```

**Command to apply:**
```bash
nano docker-compose.yml

# Find the freeswitch section
# Remove network_mode: host
# Add hostname, networks, and ports as shown above
# Save and exit
```

### Step 8: Update FreeSWITCH Database Connection
**File:** `portal_fs/freeswitch_files/odbc.ini`

**Change the Server from 127.0.0.1 to container hostname:**

**Find both `[fs_c5]` and `[fs_core]` sections:**

**OLD:**
```ini
[fs_c5]
Driver = MySQL
SERVER = 127.0.0.1
PORT = 3306
# ... rest

[fs_core]
Driver = MySQL
SERVER = 127.0.0.1
PORT = 3306
# ... rest
```

**NEW:**
```ini
[fs_c5]
Driver = MySQL
SERVER = tlai-mysql      # Changed from 127.0.0.1
PORT = 3306
# ... rest

[fs_core]
Driver = MySQL
SERVER = tlai-mysql      # Changed from 127.0.0.1
PORT = 3306
# ... rest
```

**Command to apply:**
```bash
nano portal_fs/freeswitch_files/odbc.ini

# Find SERVER = 127.0.0.1 in both sections
# Replace with SERVER = tlai-mysql (or your MySQL container name)
# Save and exit
```

### Step 9: Update FreeSWITCH Bind IP
**File:** `portal_fs/freeswitch_files/vars.xml`

**Find the bind_server_ip variable:**

**OLD:**
```xml
<X-PRE-PROCESS cmd="set" data="bind_server_ip=auto"/>
```

**NEW:**
```xml
<X-PRE-PROCESS cmd="set" data="bind_server_ip=0.0.0.0"/>
```

**Command to apply:**
```bash
#### A. Update FreeSWITCH source IP check (around line 540)
**OLD:**
```
if ($si == "127.0.0.1" && ( $sp == "5071" || $sp == "5081" )){
```

**NEW:**
```
}else if ($si == "172.31.7.125" ||  $si == "tlai-freeswitch"){
```

**Note:** The IP `172.31.7.125` is your FreeSWITCH private IP address. If your FreeSWITCH runs in a Docker container with a different IP, check with:
```bash
docker inspect tlai-freeswitch | grep IPAddress
```
**File:** `opensips/opensips.cfg`

**Change all references from 127.0.0.1 to container hostname:**

#### A. Update FreeSWITCH source IP check (around line 540)
**OLD:**
```
if ($si == "127.0.0.1" && ( $sp == "5071" || $sp == "5081" )){
```

**NEW:**
```
}else if ($si == "172.18.0.11" ||  $si == "tlai-freeswitch"){
```

**Note:** Replace `172.18.0.11` with your FreeSWITCH container IP if different.

#### B. Update all FreeSWITCH routing destinations

**Search and replace throughout the file:**
- `sip:127.0.0.1:5071` → `sip:tlai-freeswitch:5071`
- `sip:127.0.0.1:5081` → `sip:tlai-freeswitch:5081`
- `sip:127.0.0.1:5061` → `sip:tlai-freeswitch:5061`

**Command to apply:**
```bash
# Backup first
cp opensips/opensips.cfg opensips/opensips.cfg.pre-edit

# Use sed to replace all occurrences
sed -i 's/sip:127\.0\.0\.1:5071/sip:tlai-freeswitch:5071/g' opensips/opensips.cfg
sed -i 's/sip:127\.0\.0\.1:5081/sip:tlai-freeswitch:5081/g' opensips/opensips.cfg
sed -i 's/sip:127\.0\.0\.1:5061/sip:tlai-freeswitch:5061/g' opensips/opensips.cfg

# Verify changes
grep "tlai-freeswitch" opensips/opensips.cfg
```

### Step 11: Configure WebSocket Local Registration (CRITICAL)
**File:** `opensips/opensips.cfg`

**Find the `route[REGISTER_ROUTE]` section and replace it entirely:**

**Location:** Around line 466

**NEW CODE:**
```
route[REGISTER_ROUTE] {
        xlog("L_INFO","[$ci] [REGISTER_ROUTE] [$rm] forwarding REGISTER to main registrar (ci=$ci) \n");
        
        # For WebSocket clients: use LOCAL registrar only (no FreeSWITCH registration)
        if ($socket_in(proto)  == 'WS' || $socket_in(proto)  == 'WSS'|| $socket_in(proto)  == 'ws' ||  $socket_in(proto)  == 'wss'){
                xlog("L_INFO","[$ci] [$rm] [REGISTER_ROUTE] WebSocket REGISTER - storing locally only\n");
                setflag("SRC_WS");
                # Save to local location table with WebSocket contact preserved
                if (!save("location")) {
                        xlog("L_ERR", "[$ci] [$rm] [REGISTER_ROUTE] Failed to save WebSocket registration\n");
                        sl_send_reply(500, "Server Internal Error");
                }
                exit;
        }
        
        # For non-WebSocket clients: use mid_registrar to forward to FreeSWITCH
        if(mid_registrar_save("location")) {
                $var(rc) = $retcode;
                xlog ("L_INFO","[REGISTER_ROUTE] [$rm] mid_registrar_save function Success with reference to returned code by last invoked function -> $rc   $retcode  $var(rc) ");
                if ($(var(rc){s.int}) == 2) {
                        xlog("L_INFO","[REGISTER_ROUTE] [$rm] Return Code : [$var(rc)] This REGISTER has been absorbed!\n");
                        drop();
                }
        }
        $avp(user) = $hdr(User-Agent);
        remove_hf("User-Agent");
        append_hf("User-Agent: $avp(user) ::client_ip=$si");
        
        if($socket_in(proto) == "tls" || $socket_in(proto) == "TLS"){
                $avp(extra_ct_params) = ";transport=tls";
                if ($(ru{uri.transport}) == "tls" || $(ru{uri.transport}) == "TLS" ) {
                        xlog("L_INFO","The username of our uri has transport tls don't need to add 111111111 \n");
                        $ru = "sip:tlai-freeswitch:5061";
                }else {
                        xlog("L_INFO","The username of our uri has not transport so needs to add tls 22222222 \n");
                        $ru = "sip:tlai-freeswitch:5061;transport=tls";
                }
        } else if($socket_in(proto) == "tcp" || $socket_in(proto) == "TCP"){
                $avp(extra_ct_params) = ";transport=tcp";
                xlog("L_INFO", "[$ci] [REGISTER_ROUTE] [$rm] Register packet forwarding to Freeswitch with [$socket_in(proto)] protocol \n");
                $ru = "sip:tlai-freeswitch:5071";
                force_send_socket("tcp:0.0.0.0:5060");
        } else if($socket_in(proto) == "udp" || $socket_in(proto) == "UDP"){
                xlog("L_INFO","[$ci] [$rm] [REGISTER_ROUTE] UDP Registration packet sending to Freeswitch \n");
                $ru = "sip:tlai-freeswitch:5071";
                force_send_socket("udp:0.0.0.0:5060");
        }
        route(RELAY);
        exit;
}
```

**Manual steps:**
```bash
nano opensips/opensips.cfg

# Find route[REGISTER_ROUTE]
# Delete everything from route[REGISTER_ROUTE] { to the closing }
# Paste the NEW CODE above
# Save and exit
```

### Step 12: Configure WebSocket INVITE Routing (CRITICAL)
**File:** `opensips/opensips.cfg`

**Find the `route[INVITE_ROUTE]` section and add WebSocket handling at the beginning:**

**Location:** Around line 524

**Add this code at the BEGINNING of route[INVITE_ROUTE], before existing checks:**

```
route[INVITE_ROUTE] {
        xlog("L_INFO", "[$ci] [$rm] [INVITE_ROUTE] \n");
        
        # Handle calls FROM WebSocket clients
        if ($socket_in(proto)  == 'WS' || $socket_in(proto)  == 'WSS'|| $socket_in(proto)  == 'ws' ||  $socket_in(proto)  == 'wss'){
                xlog("L_INFO","[$ci] [$rm] [INVITE_ROUTE] INVITE from WebSocket client\n");
                
                # First check if destination is registered locally (another WebSocket client)
                if (lookup("location")) {
                        xlog("L_INFO","[$ci] [$rm] [INVITE_ROUTE] Destination found locally (WebSocket to WebSocket), forwarding directly\n");
                        setflag("SRC_WS");
                        setbflag("DST_WS");
                        route(RELAY);
                        exit;
                }
                
                # Destination not local, forward to FreeSWITCH
                xlog("L_INFO","[$ci] [$rm] [INVITE_ROUTE] Destination not local, forwarding to FreeSWITCH\n");
                $ru="sip:tlai-freeswitch:5071";
                setflag("SRC_WS");
                route(RELAY);
                exit;
        }
        
        # Keep existing logic below...
        if (check_source_address(1) && $si != "127.0.0.1") {
```

**Manual steps:**
```bash
nano opensips/opensips.cfg

# Find route[INVITE_ROUTE] {
# Add the WebSocket handling code RIGHT AFTER the opening {
# Keep all existing code below it
# Save and exit
```

### Step 13: Update FreeSWITCH Return Path Routing
**File:** `opensips/opensips.cfg`

**Find the section handling calls FROM FreeSWITCH (around line 540):**

**Replace the mid_registrar_lookup logic:**

**OLD:**
```
if (!mid_registrar_lookup("location")) {
        if (is_myself("$td")) {
                xlog("L_INFO","[$ci][LOCATION] [LOOKUP-FAILED] [DOMAIN IS LOCAL] [$td] ") ;
                sl_send_reply(404, "Not Found");
                exit;
        }
}
```

**NEW:**
```
# First try local registrar lookup for WebSocket clients
if (!lookup("location")) {
        xlog("L_INFO","[$ci][LOCATION] [LOCAL-LOOKUP-FAILED] trying mid_registrar\n");
        # If not found locally, try mid_registrar (for non-WebSocket clients)
        if (!mid_registrar_lookup("location")) {
                if (is_myself("$td")) {
                        xlog("L_INFO","[$ci][LOCATION] [LOOKUP-FAILED] [DOMAIN IS LOCAL] [$td] ") ;
                        sl_send_reply(404, "Not Found");
                        exit;
                } else {
                        $avp(to_header)= $(hdr(to){nameaddr.uri}) ;
                        $ru= $avp(to_header) ;
                        xlog("L_INFO", " [$ci] [$rm] [INVITE_ROUTE] Call Routing To Outbound Carrier : [$td]  \n");
                }
        } else {
                xlog("L_INFO", "[$ci] [$rm] [INVITE_ROUTE] Found in MIDREGISTRAR, forwarding to ru : [$ru] and du :[$du] \n");
        }
} else {
        xlog("L_INFO", "[$ci] [$rm] [INVITE_ROUTE] Found in LOCAL registrar (WebSocket client), forwarding to ru : [$ru] \n");
        setbflag("DST_WS");
}

# Set WebSocket destination flag if needed
if (isflagset("SRC_WS") || $rP == 'WSS' || $rP == 'wss' || $rP == 'WS' || $rP == 'ws') {
        xlog("L_INFO","[$ci] [INVITE_ROUTE] [$rm] Destination is WebSocket [$rP] \n");
        setbflag("DST_WS") ;
}
```

**Manual steps:**
```bash
nano opensips/opensips.cfg

# Find the section with: }else if ($si == "172.18.0.11" ||  $si == "tlai-freeswitch"){
# Replace the mid_registrar_lookup logic with the NEW code above
# Save and exit
```

---

## Part 5: Deployment

### Step 14: Stop All Services
```bash
cd /path/to/your/project

# Stop all containers
docker-compose down

# Verify all stopped
docker ps
```

### Step 15: Rebuild and Start Services
```bash
# Start services
docker-compose up -d

# Check container status
docker-compose ps

# Verify OpenSIPS started successfully
docker logs tlai-opensips --tail 50

# Verify FreeSWITCH started successfully
### Step 16: Verify Network Configuration
```bash
# Get FreeSWITCH container IP (should be 172.31.7.125 or similar)
docker inspect tlai-freeswitch | grep IPAddress

# Verify OpenSIPS can reach FreeSWITCH
docker exec -it tlai-opensips ping -c 3 tlai-freeswitch

# Verify FreeSWITCH can reach MySQL
docker exec -it tlai-freeswitch ping -c 3 tlai-mysql

# Verify WebSocket port is listening on 5068
netstat -an | grep 5068

# Verify server can be reached from outside
# From your local machine:
# telnet 13.200.87.192 5068
```

### Step 16b: Configure SSL for Secure WebSocket (WSS)
Since we're using `wss://tenant1.teleaon.ai:5068`, ensure SSL is properly configured:

```bash
# Verify SSL certificate exists for tenant1.teleaon.ai
### Step 18: Test Registration
1. **Open browser and navigate to your application:**
   - URL: `https://tenant1.teleaon.ai` or `http://13.200.87.192`

2. **Login with extension 1611**

3. **Check browser console (F12):**
   ```
   Should see:
   - "DEBUG: sipDomain = tenant1.teleaon.ai domainName = tenant1.teleaon.ai wssUrl = wss://tenant1.teleaon.ai:5068"
   - "Connecting wss://tenant1.teleaon.ai:5068"
   - "WebSocket opened wss://tenant1.teleaon.ai:5068"
   - "UserAgent started"
   - "Registration transitioned to state Registered"
   ```

4. **If WebSocket connection fails, check:**
   - Port 5068 is open in firewall
   - SSL certificate is valid
   - OpenSIPS is listening on port 5068

5. **Open second browser tab/window**

6. **Login with extension 1555**

7. **Verify registration**
apt-get install certbot

# Get certificate for tenant1.teleaon.ai
certbot certonly --standalone -d tenant1.teleaon.ai

# Certificates will be in:
# /etc/letsencrypt/live/tenant1.teleaon.ai/fullchain.pem
# /etc/letsencrypt/live/tenant1.teleaon.ai/privkey.pem
```
# Verify FreeSWITCH can reach MySQL
docker exec -it tlai-freeswitch ping -c 3 tlai-mysql
```

---

## Part 6: Testing

### Step 17: Clear Browser Cache
On the client browser:
```
1. Press Ctrl + Shift + R (Windows/Linux) or Cmd + Shift + R (Mac)
2. Or open Developer Tools (F12)
3. Right-click Refresh button → "Empty Cache and Hard Reload"
```

### Step 18: Test Registration
1. **Open browser and navigate to your application**
2. **Login with extension 1611**
3. **Check browser console (F12):**
   ```
   Should see:
   - "DEBUG: sipDomain = tenant1.teleaon.ai"
   - "UserAgent started"
   - "Registration transitioned to state Registered"
   ```

4. **Open second browser tab/window**
5. **Login with extension 1555**
6. **Verify registration**

### Step 19: Verify WebSocket Registrations in OpenSIPS
```bash
# Check OpenSIPS logs for WebSocket registration
docker logs tlai-opensips --tail 100 | grep "WebSocket REGISTER"

# You should see lines like:
# "WebSocket REGISTER - storing locally only"
```

### Step 20: Verify Contact URIs NOT in FreeSWITCH
```bash
# Check FreeSWITCH registrations
docker exec -it tlai-freeswitch fs_cli -x "show registrations"

# WebSocket extensions (1611, 1555) should NOT appear here
# Only SIP trunk registrations should appear
```

### Step 21: Test Call Flow
1. **From extension 1611 browser, dial 1555**
2. **Expected result:**
   - 1611: Shows "Calling..." with ringback tone
   - 1555: Receives incoming call notification/alert
   - 1555: Shows popup with "Accept" and "Reject" buttons
3. **Click Accept on 1555**
4. **Expected result:**
   - Both parties connected
   - Audio working in both directions
5. **Hang up from either side**

### Step 22: Check Call Logs
```bash
# OpenSIPS logs
docker logs tlai-opensips --tail 200 | grep "INVITE from WebSocket"
# Should show: "Destination found locally (WebSocket to WebSocket)"
**Check:**
```bash
# Verify OpenSIPS is running
docker ps | grep opensips

# Check OpenSIPS logs
docker logs tlai-opensips --tail 50

# Verify WebSocket port 5068 is accessible
netstat -an | grep 5068

# Check if port is open in firewall (AWS Security Group)
# In AWS Console: EC2 → Security Groups → Check inbound rules for port 5068

# Test WebSocket connection from browser console:
# new WebSocket('wss://tenant1.teleaon.ai:5068')
```

**Fix:**
- Ensure OpenSIPS container is running
- Check firewall/security group allows port 5068 (both TCP and WebSocket)
- Verify SSL certificate is valid for tenant1.teleaon.ai
- Check OpenSIPS is configured to listen on wss://0.0.0.0:5068
- Verify browser console for specific WebSocket errors (SSL, timeout, refused)

# Check OpenSIPS logs
docker logs tlai-opensips --tail 50

# Verify WebSocket port is accessible
netstat -an | grep 5064
```

**Fix:**
- Ensure OpenSIPS container is running
- Check firewall allows port 5064
- Verify browser console for WebSocket errors

### Issue 2: Call Doesn't Ring at Recipient
**Symptoms:** Caller connects but recipient has no alert

**Check:**
```bash
# Verify local lookup is working
docker logs tlai-opensips | grep "Destination found locally"

# Should see this log when calling
```

**Fix:**
- Verify Step 11 and 12 were applied correctly
- Ensure both extensions registered successfully
- Check browser console on recipient for incoming INVITE

### Issue 3: Audio Not Working
**Symptoms:** Call connects but no audio

**Check:**
- Browser microphone permissions granted
- Check browser console for WebRTC errors
- Verify ICE candidates in SDP

**Fix:**
```bash
# Check if RTPE engine is running
docker ps | grep rtpengine

# Restart if needed
docker-compose restart rtpengine
```

### Issue 4: 480 Temporarily Unavailable
**Symptoms:** Call fails with 480 error

**Check:**
```bash
# Verify registration
docker logs tlai-opensips | grep "1555"

# Check if lookup("location") is finding the user
docker logs tlai-opensips | grep "lookup"
```

**Fix:**
- Ensure WebSocket registration went to local registrar
- Verify route[INVITE_ROUTE] has WebSocket check FIRST
- Re-register both extensions

### Issue 5: OpenSIPS Won't Start
**Symptoms:** Container restarts continuously

**Check:**
```bash
# Check for config errors
docker logs tlai-opensips

# Look for: "ERROR:core:parse_opensips_cfg: bad config file"
```

**Fix:**
```bash
# Validate config syntax
docker run --rm -v $(pwd)/opensips:/etc/opensips opensips/opensips opensips -c -f /etc/opensips/opensips.cfg

# If errors, restore from backup
cp $BACKUP_DIR/opensips.cfg.bak opensips/opensips.cfg
```

---

## Part 8: Verification Checklist

### Network Architecture
```
Internet (13.200.87.192)
    ↓
Browser (WSS) ←→ OpenSIPS (WSS Port 5068) ←→ FreeSWITCH (Private IP: 172.31.7.125)
                       ↓
                 Local Registrar
                 (WebSocket contacts)
                       ↓
                 Mid-Registrar
                 (SIP trunk contacts)

Server: tenant1.teleaon.ai
Public IP: 13.200.87.192
Private IP: 172.31.7.125
``` ] Call from 1611 to 1555 shows "Destination found locally"
- [ ] Extension 1555 receives incoming call alert
- [ ] Accept button works and call connects
- [ ] Audio works in both directions
- [ ] Hangup works from either side
- [ ] Missed call recorded if not answered

---

## Part 9: Important Notes

### Network Architecture
```
Browser (WebSocket) ←→ OpenSIPS (WS Port 5064) ←→ FreeSWITCH (UDP 5071)
                              ↓
                        Local Registrar
                        (WebSocket contacts)
                              ↓
                        Mid-Registrar
                        (SIP trunk contacts)
```

### Call Flow Scenarios

**Scenario 1: WebSocket → WebSocket (1611 calls 1555)**
```
1611 Browser → OpenSIPS → lookup(location) → finds 1555 locally → 1555 Browser
(No FreeSWITCH involved)
```

**Scenario 2: WebSocket → PSTN (1611 calls external)**
```
### Security Considerations
1. **WebSocket Security:**
   - ✅ Using WSS (WebSocket Secure) on port 5068
   - Ensure SSL certificate is valid and auto-renewed (Let's Encrypt)
   - Implement proper authentication for WebSocket connections
   - Rate limit registration attempts

2. **AWS Security Group / Firewall Rules:**
   - ✅ Allow port 5068 (WSS/TCP) for WebSocket connections
   - ✅ Allow port 80/443 for web application (tenant1.teleaon.ai)
   - Allow SIP ports as needed (5060, 5070, 5071, 5081)
   - Restrict SSH (port 22) to known IP ranges
   - Configure AWS Security Group for instance at 13.200.87.192

   **Required inbound rules in AWS Security Group:**
   ```
   Type            Protocol    Port Range    Source
   HTTPS           TCP         443           0.0.0.0/0
   HTTP            TCP         80            0.0.0.0/0
   Custom TCP      TCP         5068          0.0.0.0/0  (WebSocket)
   Custom TCP      TCP         5060          0.0.0.0/0  (SIP)
   Custom UDP      UDP         5060          0.0.0.0/0  (SIP)
   Custom UDP      UDP         16384-16484   0.0.0.0/0  (RTP)
   SSH             TCP         22            Your-IP/32
   ```

3. **SIP Authentication:**
   - Keep strong passwords for extensions
   - Implement fail2ban for brute force protection
   - Monitor authentication failures in OpenSIPS logs

4. **SSL Certificate Management:**
   - Use Let's Encrypt for free SSL certificates
   - Set up auto-renewal with certbot
   - Monitor certificate expiration dates

### Security Considerations
1. **WebSocket Security:**
   - Use WSS (WebSocket Secure) in production
   - Implement proper authentication
   - Rate limit registration attempts

2. **Firewall Rules:**
   - Allow port 5064 for WebSocket
   - Restrict to known IP ranges if possible

3. **SIP Authentication:**
   - Keep strong passwords for extensions
   - Implement fail2ban for brute force protection

### Performance Tuning
- Monitor OpenSIPS memory usage
- Adjust location table timeout if needed
- Configure proper log rotation

---
## Additional Resources

### Useful Commands
```bash
# Monitor OpenSIPS live logs
docker logs -f tlai-opensips

# Monitor FreeSWITCH live logs
docker logs -f tlai-freeswitch

# Check FreeSWITCH registrations
docker exec -it tlai-freeswitch fs_cli -x "show registrations"

# Check FreeSWITCH calls
docker exec -it tlai-freeswitch fs_cli -x "show channels"

# Restart single service
docker-compose restart opensips

# Test WebSocket connectivity from command line
wscat -c wss://tenant1.teleaon.ai:5068

# Check if SSL certificate is valid
openssl s_client -connect tenant1.teleaon.ai:5068 -showcerts

# Monitor network connections on port 5068
ss -tulpn | grep 5068

# Test from browser console
var ws = new WebSocket('wss://tenant1.teleaon.ai:5068');
ws.onopen = () => console.log('WebSocket connected!');
ws.onerror = (e) => console.error('WebSocket error:', e);
```

### Server Access
```bash
# SSH to server (replace with your key)
ssh -i your-key.pem ubuntu@13.200.87.192

# Or if using password authentication
ssh root@13.200.87.192
```
# Restart services
docker-compose up -d
## Summary

This deployment guide converts your VoIP system to support WebRTC browser calling with proper incoming call notifications. The key changes:

1. **WebSocket clients register locally in OpenSIPS** (not FreeSWITCH)
2. **Local lookup first for WebSocket destinations** (direct browser-to-browser)
3. **FreeSWITCH handles only non-WebSocket and PSTN calls**
4. **Proper Contact URI preservation** (transport=ws maintained)
5. **Secure WebSocket (WSS)** connection on port 5068

After successful deployment, browser extensions can call each other directly with incoming call alerts, notifications, and full audio support.

---

## Your Specific Configuration

**Server Details:**
- Domain: tenant1.teleaon.ai
- Public IP: 13.200.87.192
- Private IP: 172.31.7.125
- WebSocket: wss://tenant1.teleaon.ai:5068
- SIP Domain: tenant1.teleaon.ai

**Access URLs:**
- Web Application: https://tenant1.teleaon.ai
- WebSocket Endpoint: wss://tenant1.teleaon.ai:5068
- SSH Access: ssh user@13.200.87.192

**Post-Deployment Quick Test:**
```bash
# From your local machine, test WebSocket
curl -i -N -H "Connection: Upgrade" -H "Upgrade: websocket" \
  -H "Sec-WebSocket-Version: 13" -H "Sec-WebSocket-Key: test" \
  https://tenant1.teleaon.ai:5068

# Expected: HTTP/1.1 101 Switching Protocols
```

Good luck with your deployment! 🚀
docker logs -f tlai-opensips

# Monitor FreeSWITCH live logs
docker logs -f tlai-freeswitch

# Check FreeSWITCH registrations
docker exec -it tlai-freeswitch fs_cli -x "show registrations"

# Check FreeSWITCH calls
docker exec -it tlai-freeswitch fs_cli -x "show channels"

# Restart single service
docker-compose restart opensips
```

### Log Locations
- OpenSIPS: `docker logs tlai-opensips`
- FreeSWITCH: `docker logs tlai-freeswitch`
- Application: Check your PHP/Nginx error logs

---

## Support

If you encounter issues:
1. Check the Troubleshooting section above
2. Review logs for specific error messages
3. Verify each configuration change was applied correctly
4. Test with a simple browser-to-browser call first
5. Ensure network connectivity between containers

---

## Summary

This deployment guide converts your VoIP system to support WebRTC browser calling with proper incoming call notifications. The key changes:

1. **WebSocket clients register locally in OpenSIPS** (not FreeSWITCH)
2. **Local lookup first for WebSocket destinations** (direct browser-to-browser)
3. **FreeSWITCH handles only non-WebSocket and PSTN calls**
4. **Proper Contact URI preservation** (transport=ws maintained)

After successful deployment, browser extensions can call each other directly with incoming call alerts, notifications, and full audio support.

Good luck with your deployment! 🚀
