/* Module Name  :  user 
 *  * Project name : CallTech PBX
 *  */
/** @file
 *  * @description :  Construct user dialplan module  
 *  * @author      :  Dhruv Gupta
 *  *                 dhruv.gupta@ecosmob.com 
 *  *                 Ecosmob Technologies Pvt. Ltd.
 *  * @date        :  01/05/2018
 *  */

#include "../aaa_server.h"
#include "../utilities/redisTools.h"

/** function of User service      
 *  @return dialplan string OR error message
 **/

char* park_timeout_action(char *destination_type, char* destination_id) {
    int is_timeout = 1;
    char *query = NULL;
    char *failover_number = NULL;
    char *tmp_str = NULL;

    if (strcasecmp(destination_type,"NULL") == 0 || strcasecmp(destination_type,"") == 0) {
        is_timeout = 0;
    } else if(strcasecmp(destination_type,"1") == 0 || strcasecmp(destination_type,"4") == 0) {
        asprintf(&query,"SELECT `em_extension_number` FROM `ct_extension_master` WHERE `em_id` = '%s' limit 1" ,destination_id);
        writelog(LOG_DEBUG,"Query to get details of extension number  : %s",query);
        failover_number=sql2str(query);
        safe_free(query);
    } else if(strcasecmp(destination_type,"2") == 0) {
        asprintf(&query,"SELECT `aam_extension` FROM `auto_attendant_master` WHERE `aam_id` = '%s' limit 1" ,destination_id);
        writelog(LOG_DEBUG,"Query to get details of IVR number  : %s",query);
        failover_number=sql2str(query);
        safe_free(query);
    } else if(strcasecmp(destination_type,"3") == 0) {
        asprintf(&query,"SELECT `qm_extension` FROM `ct_queue_master` WHERE `qm_id` = '%s' limit 1" ,destination_id);
        writelog(LOG_DEBUG,"Query to get details of queue number  : %s",query);
        failover_number=sql2str(query);
        safe_free(query);
    } else if(strcasecmp(destination_type,"5") == 0) {
        asprintf(&query,"SELECT `rg_extension` FROM `ct_ring_group` WHERE `rg_id` = '%s' limit 1" ,destination_id);
        writelog(LOG_DEBUG,"Query to get details of ring group number  : %s",query);
        failover_number=sql2str(query);
        safe_free(query);
    } else if(strcasecmp(destination_type,"6") == 0) {
        failover_number = destination_id;
    }

    if (is_timeout == 1) {
        if(strcasecmp(destination_type,"4") == 0) {
            voicemail_action(failover_number);
        } else {
            asprintf(&tmp_str,"%s XML",failover_number);
    	    insert_action("transfer",tmp_str);
    	    safe_free(tmp_str);
        }
    }
}

char* user()
{
    char *hup_dialplan=NULL;
    char s[2]=";";
    char *ext=NULL;
    char *last_module=NULL;
    char *domain_name=NULL;
    char *dial_string=NULL;
    char *dial_url=NULL;
    char *callee_frwd=NULL;
    char *callee_voic=NULL;
    int ring_timeout=30;
    char *caller_status=NULL;
    char *dialed_string=NULL;
    char *tmp_str=NULL; 
    char *callee_ring_timeout=NULL;
    char *callee_auto_recording=NULL;
    char *caller_auto_recording=NULL;
    char *caller_bypass_media=NULL;
    char *hold_music_media=NULL;
    char *hold_music_media_callee=NULL;
    char *did_call=NULL;
   // char *caller_langauge=NULL;
    char *query=NULL;
    char *query_res=NULL;
    char *refer_by=NULL;
    char *transfer_ext=NULL;
    char *campaign_week_off=NULL;
    char *campaign_holiday_off=NULL;
    char *rdnis = NULL;
    // char *lot_number = NULL;
    char lot_number[10] = "";
    int is_park = 0;

    char callback_ring_time[4]="";
    char return_to_originator[4]="";
    char destination_type[10]="";
    char destination_id[20]="";
    int i = 0;
    char* token=NULL;
    char *old_direction=NULL;
    char *check_service=NULL;

    ext=json_parser(channel_variables,"extension_number");
    callee_voic=json_parser(channel_variables,"callee_voicemail_service");
    callee_frwd=json_parser(channel_variables,"callee_forwarding_service");
    last_module = json_parser(channel_variables,"last_module");
    caller_status=json_parser(channel_variables,"caller_status");
    caller_auto_recording=json_parser(channel_variables,"caller_auto_recording");
    writelog(LOG_DEBUG,"Caller auto recording : %s\n",caller_auto_recording);
    callee_auto_recording=json_parser(channel_variables,"callee_auto_recording"); 
    writelog(LOG_DEBUG,"Callee auto recording : %s\n",callee_auto_recording);
    callee_ring_timeout=json_parser(channel_variables,"callee_ring_timeout");
    caller_bypass_media=json_parser(channel_variables,"caller_bypass_media");
//    caller_langauge=json_parser(channel_variables,"caller_langauge");
    hold_music_media=json_parser(channel_variables,"hold_music_media");
    hold_music_media_callee=json_parser(channel_variables,"hold_music_media_callee");
    rdnis=json_parser(channel_variables,"rdnis");
    did_call=json_parser(channel_variables,"did_call");
    refer_by= json_parser(channel_variables,"sip_h_Referred-By");
    transfer_ext= json_parser(channel_variables,"sip_h_X-transfer_ext");
    campaign_week_off= json_parser(channel_variables,"campaign_week_off");
    campaign_holiday_off= json_parser(channel_variables,"campaign_holiday_off");
    check_service=json_parser(channel_variables,"service_type");

    if (callee_ring_timeout && strcasecmp(callee_ring_timeout,"nill")) {
        ring_timeout=atoi (callee_ring_timeout);    
        safe_free_ch_var(callee_ring_timeout);
    }

    

/********************Added for Default MOH ***************************************/
    if ((!strcasecmp(hold_music_media,"NULL"))||(!strcasecmp(hold_music_media_callee,"NULL"))) {

	    asprintf(&query,"SELECT `gwc_value` FROM `global_web_config` WHERE gwc_key = 'moh_file'");
	    query_res = sql2str(query);
	    writelog(LOG_DEBUG,"Query to get global moh_file : %s",query);
	    safe_free(query);
	    if(strcasecmp(query_res,"QUERY_FAIL") == 0 ){
		    writelog(LOG_INFO,"There is no Default MOH File");
//		    return ret;
	    }else{
		    writelog(LOG_INFO,"Default MOH_File:%s",query_res);
		    if (!strcasecmp(hold_music_media,"NULL")){
			channel_variables= json_add(channel_variables,"hold_music_media",query_res);
			hold_music_media=json_parser(channel_variables,"hold_music_media");
		    }
		    if (!strcasecmp(hold_music_media_callee,"NULL")){
			channel_variables=json_add(channel_variables,"hold_music_media_callee",query_res);
			hold_music_media_callee=json_parser(channel_variables,"hold_music_media_callee");
		    }
		    safe_free(query_res);
	    }
    }
/*******************************************************************************/
    channel_variables = json_add(channel_variables,"last_module","User");
    writelog(LOG_DEBUG,"Constructing dialplan for USER call %s",last_module);

    if (!strcasecmp(last_module,"nill")) {
        insert_exten("CallTech User");
        insert_con("${destination_number}","(.*)");
        insert_action("set","hangup_after_bridge=true");
        insert_action("set","continue_on_fail=true");
        insert_action("ring_ready","");
        insert_action("set","ringback=$${us-ring}");
//        insert_action("set","instant_ringback=true");
        insert_action("set","inherit_codec=true");
//        insert_action("set","ignore_early_media=false");
        insert_action("export","custom_domain=${domain_name}");
            if((strcasecmp(transfer_ext,"") == 0) && (strcasecmp(refer_by,"") == 0)){
                if(strcasecmp(check_service,"nill")==0){
                    if (strcasecmp(did_call,"NULL") == 0 || strcasecmp(did_call,"") == 0){
                        insert_action("export","service_type=EXTENSION_EXTENSION");
                    }else{
                      	insert_action("export","service_type=DID_EXTENSION");
                    }
                }
            }else{
            
                if (strcasecmp(did_call,"NULL") == 0 || strcasecmp(did_call,"")== 0 || ){
                    insert_action("export","service_type=EXTENSION_TRANSFER");
                }else {
                    if(strcasecmp(did_call,"false") == 0){
                        insert_action("export","service_type=CAMPAIGN_TRANSFER");
                    }
                }else{
                    insert_action("export","service_type=DID_TRANSFER");
                }
            }
        
            if(strcasecmp(campaign_week_off,"INTERNAL") == 0){
                insert_action("export","service_type=DID_CAMPAIGN_WEEKOFF_EXTENSION");
            }
	        if(strcasecmp(campaign_holiday_off,"INTERNAL") == 0){
	            insert_action("export","service_type=DID_CAMPAIGN_HOLIDAY_EXTENSION");
	        }

	    if (strcasecmp(hold_music_media,"NULL")){
	    	asprintf(&tmp_str,"hold_music=/media/admin/audio-libraries/%s",hold_music_media);
	    	insert_action("set",tmp_str);
	    	safe_free(tmp_str);
	    }
	    if (strcasecmp(hold_music_media_callee,"NULL")){
	    	asprintf(&tmp_str,"nolocal:hold_music=/media/admin/audio-libraries/%s",hold_music_media_callee);
	    	insert_action("export",tmp_str);
	    	safe_free(tmp_str);
	    }
        if(!strcasecmp(caller_bypass_media,"true")){
            insert_action("set","bypass_media=true");
        }
        else if(!strcasecmp(caller_bypass_media,"bypass_after_bridge")){
            insert_action("set","bypass_media_after_bridge=true");
        }
        else if(!strcasecmp(caller_bypass_media,"proxy_media")){
            insert_action("set","proxy_media=true");
        }
        else if (!strcasecmp(caller_auto_recording,"1") || !strcasecmp(caller_auto_recording,"2")) {
            asprintf(&tmp_str,"execute_on_answer=record_session $${recordings_dir}/${caller_tenant_uuid}/${caller_id_number}_${destination_number}_${uuid}.wav");
            insert_action("set","recording_follow_transfer=true");
            insert_action("set",tmp_str);
            safe_free(tmp_str);
        }
        else if (!strcasecmp(callee_auto_recording,"1") || !strcasecmp(callee_auto_recording,"2")) {
            asprintf(&tmp_str,"nolocal:execute_on_answer=record_session $${recordings_dir}/${caller_tenant_uuid}/${caller_id_number}_${destination_number}_${uuid}.wav");
            insert_action("export","nolocal:recording_follow_transfer=true");
            insert_action("export",tmp_str);
            safe_free(tmp_str);
        }
        
    }
	insert_action("export","sip_h_X-ChatServer_id=${sip_h_X-ChatServer-id}");
	insert_action("export","sip_h_X-cc_queue_id=${sip_h_X-cc_queue_id}");
	insert_action("export","sip_h_X-cc_queue=${sip_h_X-cc_queue}");
	insert_action("export","sip_h_X-auto_answer=no");
    insert_action("export","sip_h_X-caller_id_number=${caller_id_number}");
	asprintf(&tmp_str,"sip_h_X-transfer_ext=%s",ext);
    insert_action("export",tmp_str);
    safe_free(tmp_str);
	asprintf(&tmp_str,"cc_export_vars=sip_h_X-cc_queue,sip_h_X-ChatServer_id,sip_h_X-cc_queue_id,sip_h_X-transfer_ext,sip_h_X-auto_answer,sip_h_X-caller_id_number");
	insert_action("export",tmp_str);
        safe_free(tmp_str);


    set_fs_channel_var(NULL,"origination_caller_id_name","caller_extension_name",2);
    set_fs_channel_var(NULL,"originate_timeout","caller_call_timeout",0);
    set_fs_channel_var(NULL,"rtp_secure_media","caller_srtp",0);
    set_fs_channel_var(NULL,"caller_type","caller_type",0);

    // need to fetch park_ext from channel variables

    if(*rdnis == '*') {

        asprintf(&query,"SELECT feature_code FROM `ct_feature_master` WHERE feature_name='PARK_GROUP'");
        writelog(LOG_DEBUG,"Query to get park feature code : %s",query);
        query_res = sql2str(query);
        safe_free(query);

        if(strcasecmp(query_res,"QUERY_FAIL") == 0) {
            writelog(LOG_ERROR,"PARK feature code query failed.\n");
        } else {
            strcpy(lot_number,get_parking_lot_extension(rdnis, query_res));
            if (strcasecmp(lot_number, "ERROR") != 0) {
                is_park = 1;
            }
            safe_free(query_res);
        }

        if (is_park == 1) {
            
            asprintf(&query,"SELECT call_back_ring_time,destination_type,destination_id,return_to_origin FROM `parking_lot` where park_ext='%s' AND status IS NOT NULL LIMIT 1;",lot_number);
            writelog(LOG_DEBUG,"Query to get parking lot details : %s",query);
            query_res = sql_callback(query,"single");
            writelog(LOG_DEBUG,"QUERY result for parking lot details - %s\n", query_res);
            safe_free(query);

            if( strcasecmp(query_res,"QUERY_FAIL") == 0) {
                safe_free(query_res);
                return 0;
            } else {
                i=0;
                token = strtok(query_res, s);

                while( token != NULL ){

                    switch(i){
                        case 0 :
                            my_strncpy(callback_ring_time,token,sizeof(callback_ring_time));
                            break;
                        case 1 :
                            my_strncpy(destination_type,token,sizeof(destination_type));
                            break;
                        case 2 :
                            my_strncpy(destination_id,token,sizeof(destination_id));
                            break;
                        case 3 :
                            my_strncpy(return_to_originator,token,sizeof(return_to_originator));
                            break;
                    }
                    i++;
                    token = strtok(NULL, s);
                }

                safe_free(query_res);
            }

            ring_timeout = atoi(callback_ring_time);
        }
    }

    

    if(strcasecmp(callee_frwd,"1")==0 && is_park == 0){
        writelog(LOG_DEBUG,"Individual forwarding set for user");
        indi_frwd(ext,callee_voic);
    } else if(strcasecmp(callee_frwd,"2")==0 && is_park == 0){
        writelog(LOG_DEBUG,"FMFM forwarding set for user");
        fmfm(ext,callee_voic);
    } else {
        writelog(LOG_DEBUG,"NO forwardinfg set for user");
        insert_action("set","last_module=User");

        before_bridge_action(ext);

        //asprintf(&dial_string,"{local_var_clobber=true}[leg_timeout=%d]${sofia_contact(%s@${domain_name})}",ring_timeout,ext);
        asprintf(&dial_string,"{local_var_clobber=true}[leg_timeout=%d]user/%s@${domain_name}",ring_timeout,ext);
        writelog(LOG_DEBUG,"Constructing dialplan for USER call");
        insert_action("bridge",dial_string);
        safe_free(dial_string);


// code to execute failure cases in park timeout start

    // add condition here
    if (is_park == 1 && strcasecmp(destination_type,"NULL") != 0 && strcasecmp(return_to_originator,"1") == 0) {
        insert_con("${originate_disposition}",all_cause_con);
        insert_action("log","ENTERING PARK FAILOVER CONDITION.");
        park_timeout_action(destination_type,destination_id);

        if(strcmp(callee_voic,"1")==0) {
            char *caller_langauge=NULL; 
            caller_langauge=json_parser(channel_variables,"caller_langauge");

            if(strcmp(caller_langauge,"2")==0) {
	            insert_anti_action("export","sound_prefix=$${sounds_dir}/es/mx/maria");
                insert_anti_action("export","default_language=es");
            }else{
                insert_anti_action("export","default_language=en");
            }

            insert_anti_action("set","forward_to=voicemail");
            asprintf(&tmp_str, "default ${domain_name} %s",ext);	
            insert_anti_action("answer","");
            insert_anti_action("voicemail",tmp_str);
            safe_free(tmp_str);
        } 
    } else {
        if(strcmp(callee_voic,"1")==0) {
            if(strcasecmp(campaign_week_off,"VOICEMAIL") == 0){
                insert_action("export","service_type=DID_CAMPAIGN_WEEKOFF_VOICEMAIL");
            }else if(strcasecmp(campaign_holiday_off,"VOICEMAIL") == 0){
	            insert_action("export","service_type=DID_CAMPAIGN_HOLIDAY_VOICEMAIL");
	        }else{
                if (strcasecmp(did_call,"NULL") == 0 || strcasecmp(did_call,"") == 0) {
	                insert_action("export","service_type=EXTENSION_VOICEMAIL");
                }else{
                    insert_action("export","service_type=DID_VOICEMAIL");
                }
            }
	        voicemail_action(ext);
        } 
    }

// code to execute failure cases in park timeout end

        // if(strcmp(callee_voic,"1")==0) {
        //     voicemail_action(ext);
        // }        
    }

    if (!strcasecmp(last_module,"nill")) {
        hup_dialplan = printing();
    }
done :
    safe_free_ch_var(ext);
    safe_free_ch_var(callee_frwd);
    safe_free_ch_var(callee_voic);
    safe_free_ch_var(caller_status);
    safe_free_ch_var(last_module);
    safe_free_ch_var(caller_auto_recording);
    safe_free_ch_var(callee_auto_recording);
    safe_free_ch_var(caller_bypass_media);

    return hup_dialplan;
}

void voicemail_action(char *number){

    char *vm_str=NULL;
    char *caller_langauge=NULL; 	
    caller_langauge=json_parser(channel_variables,"caller_langauge");
	writelog(LOG_INFO,"caller langauge %s",caller_langauge);

    if(strcmp(caller_langauge,"2")==0) {
	insert_action("export","sound_prefix=$${sounds_dir}/es/mx/maria");
        insert_action("export","default_language=es");
    }else{
        insert_action("export","default_language=en");
    }
		
    insert_action("set","forward_to=voicemail");
    asprintf(&vm_str, "default ${domain_name} %s",number);	
    insert_action("answer","");
    insert_action("voicemail",vm_str);
    safe_free(vm_str);

}


