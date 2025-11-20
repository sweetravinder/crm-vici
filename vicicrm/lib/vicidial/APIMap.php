<?php

class APIMap {

    public static function getAgentAPI() {
        return [

            // -------------------------
            // AUTH / LOGIN
            // -------------------------
            'login' => [
                'description' => 'Agent Login',
                'params' => [
                    'user', 'pass',
                    'phone_login', 'phone_pass',
                    'campaign',
                    'format'
                ]
            ],

            'logout' => [
                'description' => 'Logout Agent',
                'params' => ['user', 'session_id', 'format']
            ],

            // -------------------------
            // STATES
            // -------------------------
            'pause_agent' => [
                'description' => 'Pause agent with or without reason',
                'params' => ['user', 'pause_code', 'status', 'session_id']
            ],

            'resume_agent' => [
                'description' => 'Set agent to READY',
                'params' => ['user', 'session_id']
            ],

            // -------------------------
            // CALL CONTROL
            // -------------------------
            'external_hangup' => [
                'description' => 'Hangup active call',
                'params' => ['user', 'uniqueid', 'session_id']
            ],

            'external_status' => [
                'description' => 'Submit Disposition',
                'params' => [
                    'user', 'session_id', 'uniqueid',
                    'lead_id', 'status', 'comments',
                    'callback', 'callback_datetime',
                    'next_agent'
                ]
            ],

            // -------------------------
            // LEAD / FIELD UPDATES
            // -------------------------
            'update_fields' => [
                'description' => 'Update lead fields live',
                'params' => [
                    'user', 'lead_id',
                    'first_name', 'last_name',
                    'address1', 'address2',
                    'city', 'state', 'postal_code',
                    'email', 'phone_number',
                    'custom_fields'
                ]
            ],

            // -------------------------
            // TRANSFER / CONFERENCE
            // -------------------------
            'external_transfer' => [
                'description' => 'Transfer call (blind/warm)',
                'params' => [
                    'user', 'uniqueid',
                    'transfer_to', 'blind_transfer',
                    'consultative'
                ]
            ],

            'transfer_conference' => [
                'description' => '3-way conference',
                'params' => ['user', 'uniqueid', 'conf_exten']
            ],

            // -------------------------
            // PARKING
            // -------------------------
            'park_call' => [
                'description' => 'Park current call',
                'params' => ['user', 'uniqueid', 'park_exten']
            ],

            'grab_call' => [
                'description' => 'Retrieve parked call',
                'params' => ['user']
            ],

            // -------------------------
            // DTMF
            // -------------------------
            'send_dtmf' => [
                'description' => 'Send DTMF tones to call',
                'params' => [
                    'user', 'dtmf_string',
                    'uniqueid', 'session_id'
                ]
            ],

            // -------------------------
            // RECORDING
            // -------------------------
            'recording' => [
                'description' => 'Start/Stop Recording',
                'params' => [
                    'user', 'uniqueid',
                    'record', 'format'
                ]
            ],

            // -------------------------
            // STATUS
            // -------------------------
            'agent_status' => [
                'description' => 'Get agent live status',
                'params' => ['user']
            ],

            'webserver_status' => [
                'description' => 'Get agent event details',
                'params' => ['user']
            ]
        ];
    }


    // =====================================================
    // NON-AGENT API MAP
    // =====================================================
    public static function getNonAgentAPI() {
        return [

            // -------------------------
            // LEADS
            // -------------------------
            'add_lead' => [
                'description' => 'Add new lead to list',
                'params' => [
                    'phone_number', 'phone_code',
                    'list_id', 'first_name', 'last_name',
                    'vendor_lead_code', 'address1',
                    'address2', 'city', 'state',
                    'postal_code', 'email',
                    'multi_alt_phones',
                    'custom_fields',
                    'add_to_hopper',
                    'search_method',
                    'dnc_check'
                ]
            ],

            'update_lead' => [
                'description' => 'Update any lead field',
                'params' => [
                    'lead_id', 'vendor_lead_code',
                    'search_method', 'phone_number',
                    'list_id', 'custom_fields'
                ]
            ],

            'search_lead' => [
                'description' => 'Lookup lead by any criteria',
                'params' => [
                    'lead_id', 'vendor_lead_code',
                    'phone_number', 'search_method'
                ]
            ],

            // -------------------------
            // CALLBACKS
            // -------------------------
            'add_callback' => [
                'description' => 'Create callback',
                'params' => [
                    'lead_id', 'callback_datetime',
                    'callback_type', 'user', 'comments'
                ]
            ],

            'update_callback' => [
                'description' => 'Update callback',
                'params' => [
                    'callback_id', 'callback_datetime',
                    'callback_type', 'comments'
                ]
            ],

            // -------------------------
            // MANUAL DIAL / HOPPER
            // -------------------------
            'manual_dial' => [
                'description' => 'Manual dial from CRM',
                'params' => [
                    'phone_number', 'agent_user',
                    'campaign', 'lead_id', 'search_method'
                ]
            ],

            'add_lead_to_hopper' => [
                'description' => 'Push lead into hopper',
                'params' => ['lead_id', 'campaign_id']
            ],

            'hopper' => [
                'description' => 'View hopper contents',
                'params' => ['campaign_id']
            ],

            // -------------------------
            // DNC
            // -------------------------
            'add_to_dnc' => [
                'description' => 'Add number to DNC',
                'params' => ['phone_number']
            ],

            // -------------------------
            // CAMPAIGN / LIST MANAGEMENT
            // -------------------------
            'campaign_info' => [
                'description' => 'Get campaign settings',
                'params' => ['campaign_id']
            ],

            'list_stats' => [
                'description' => 'List performance stats',
                'params' => ['list_id']
            ],

            'list_reset' => [
                'description' => 'Reset list called status',
                'params' => ['list_id']
            ],

            // -------------------------
            // UTILITY / SYSTEM
            // -------------------------
            'server_info' => [
                'description' => 'Get VICIdial server info',
                'params' => []
            ]
        ];
    }

}
