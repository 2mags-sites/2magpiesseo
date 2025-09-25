<?php
/**
 * SendGrid Mailer Class
 * Handles email sending via SendGrid API
 */

class SendGridMailer {
    private $api_key;
    private $from_email;
    private $from_name;
    private $to_email;
    private $bcc_emails;

    public function __construct() {
        $this->api_key = EnvLoader::get('SENDGRID_API_KEY');
        $this->from_email = EnvLoader::get('DEFAULT_FROM_EMAIL', 'noreply@example.com');
        $this->from_name = EnvLoader::get('DEFAULT_FROM_NAME', 'Contact Form');
        $this->to_email = EnvLoader::get('CONTACT_FORM_TO', 'info@example.com');
        $this->bcc_emails = EnvLoader::get('BCC_RECIPIENTS', '');

        if (empty($this->api_key)) {
            throw new Exception('SendGrid API key not configured');
        }
    }

    /**
     * Send contact form submission
     */
    public function sendContactForm($data) {
        $subject = 'New Contact Form Submission from ' . $data['name'];

        // Build email content
        $html_content = $this->buildHtmlEmail($data);
        $text_content = $this->buildTextEmail($data);

        // Prepare SendGrid payload
        $payload = [
            'personalizations' => [[
                'to' => [['email' => $this->to_email]],
                'subject' => $subject
            ]],
            'from' => [
                'email' => $this->from_email,
                'name' => $this->from_name
            ],
            'reply_to' => [
                'email' => $data['email'],
                'name' => $data['name']
            ],
            'content' => [
                [
                    'type' => 'text/plain',
                    'value' => $text_content
                ],
                [
                    'type' => 'text/html',
                    'value' => $html_content
                ]
            ]
        ];

        // Add BCC if configured
        if (!empty($this->bcc_emails)) {
            $bcc_array = $this->parseBccEmails();
            if (!empty($bcc_array)) {
                $payload['personalizations'][0]['bcc'] = $bcc_array;
            }
        }

        // Send via SendGrid API
        return $this->sendToSendGrid($payload);
    }

    /**
     * Build HTML email template
     */
    private function buildHtmlEmail($data) {
        $html = '<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            border-radius: 10px 10px 0 0;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
        }
        .content {
            background: #ffffff;
            padding: 30px;
            border: 1px solid #e0e0e0;
            border-radius: 0 0 10px 10px;
        }
        .field {
            margin-bottom: 20px;
            padding-bottom: 20px;
            border-bottom: 1px solid #f0f0f0;
        }
        .field:last-child {
            border-bottom: none;
        }
        .label {
            font-weight: 600;
            color: #666;
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 5px;
        }
        .value {
            color: #333;
            font-size: 16px;
            white-space: pre-wrap;
        }
        .footer {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #f0f0f0;
            font-size: 12px;
            color: #999;
            text-align: center;
        }
        .metadata {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
            margin-top: 20px;
            font-size: 12px;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>New Contact Form Submission</h1>
    </div>
    <div class="content">';

        // Add form fields
        $html .= '<div class="field">
            <div class="label">Name</div>
            <div class="value">' . htmlspecialchars($data['name']) . '</div>
        </div>';

        $html .= '<div class="field">
            <div class="label">Email Address</div>
            <div class="value"><a href="mailto:' . htmlspecialchars($data['email']) . '">' .
            htmlspecialchars($data['email']) . '</a></div>
        </div>';

        if (!empty($data['phone'])) {
            $html .= '<div class="field">
                <div class="label">Phone Number</div>
                <div class="value"><a href="tel:' . htmlspecialchars($data['phone']) . '">' .
                htmlspecialchars($data['phone']) . '</a></div>
            </div>';
        }

        if (!empty($data['service'])) {
            $html .= '<div class="field">
                <div class="label">Service Interested In</div>
                <div class="value">' . htmlspecialchars($data['service']) . '</div>
            </div>';
        }

        $html .= '<div class="field">
            <div class="label">Message</div>
            <div class="value">' . nl2br(htmlspecialchars($data['message'])) . '</div>
        </div>';

        // Add metadata
        $html .= '<div class="metadata">
            <strong>Submission Details:</strong><br>
            Date/Time: ' . $data['timestamp'] . '<br>
            IP Address: ' . $data['ip'] . '
        </div>';

        $html .= '</div>
    <div class="footer">
        This email was sent from the contact form on your website.
    </div>
</body>
</html>';

        return $html;
    }

    /**
     * Build plain text email
     */
    private function buildTextEmail($data) {
        $text = "NEW CONTACT FORM SUBMISSION\n";
        $text .= "============================\n\n";

        $text .= "Name: " . $data['name'] . "\n";
        $text .= "Email: " . $data['email'] . "\n";

        if (!empty($data['phone'])) {
            $text .= "Phone: " . $data['phone'] . "\n";
        }

        if (!empty($data['service'])) {
            $text .= "Service: " . $data['service'] . "\n";
        }

        $text .= "\nMessage:\n" . $data['message'] . "\n";

        $text .= "\n---\n";
        $text .= "Submitted: " . $data['timestamp'] . "\n";
        $text .= "IP Address: " . $data['ip'] . "\n";

        return $text;
    }

    /**
     * Parse BCC email addresses
     */
    private function parseBccEmails() {
        $bcc_array = [];
        $emails = explode(',', $this->bcc_emails);

        foreach ($emails as $email) {
            $email = trim($email);
            if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $bcc_array[] = ['email' => $email];
            }
        }

        return $bcc_array;
    }

    /**
     * Send email via SendGrid API
     */
    private function sendToSendGrid($payload) {
        $ch = curl_init('https://api.sendgrid.com/v3/mail/send');

        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Authorization: Bearer ' . $this->api_key,
            'Content-Type: application/json'
        ]);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);

        $response = curl_exec($ch);
        $status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $curl_error = curl_error($ch);
        curl_close($ch);

        if ($curl_error) {
            throw new Exception('cURL Error: ' . $curl_error);
        }

        // SendGrid returns 202 for successful queued emails
        if ($status_code === 202) {
            return true;
        }

        // Log error response for debugging
        if ($response) {
            $error_data = json_decode($response, true);
            $error_message = $error_data['errors'][0]['message'] ?? 'Unknown SendGrid error';
            throw new Exception('SendGrid Error: ' . $error_message);
        }

        return false;
    }
}
?>