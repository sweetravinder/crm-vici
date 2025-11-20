<?php

class PopupManager {

    protected $pdo;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    // Return popup template for a campaign
    public function getPopupForCampaign($campaign_id) {
        $sql = "SELECT t.*
                FROM crm_popup_templates t
                JOIN crm_popup_campaign_map m ON m.template_id = t.id
                WHERE m.campaign_id = :campaign_id
                LIMIT 1";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['campaign_id' => $campaign_id]);
        $template = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$template) {
            return null;
        }

        // Load fields
        $fields = $this->getTemplateFields($template['id']);

        $template['fields'] = $fields;

        return $template;
    }

    // Return fields for the popup template
    public function getTemplateFields($template_id) {
        $sql = "SELECT * FROM crm_popup_template_fields
                WHERE template_id = :tid
                ORDER BY sort_order ASC";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['tid' => $template_id]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Load popup template by ID
    public function getTemplate($id) {
        $stmt = $this->pdo->prepare("SELECT * FROM crm_popup_templates WHERE id = ?");
        $stmt->execute([$id]);
        $tpl = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$tpl) return null;

        $tpl['fields'] = $this->getTemplateFields($id);

        return $tpl;
    }

    // Save API Debug Log
    public function logPopupEvent($agent_id, $campaign_id, $lead_id, $event, $payload) {
        $sql = "INSERT INTO crm_popup_logs (agent_id, campaign_id, lead_id, event_type, payload)
                VALUES (:a, :c, :l, :e, :p)";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            'a' => $agent_id,
            'c' => $campaign_id,
            'l' => $lead_id,
            'e' => $event,
            'p' => json_encode($payload),
        ]);
    }

}
