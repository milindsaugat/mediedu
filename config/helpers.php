<?php
/**
 * MedicEdu Global — Helper Functions & Security Utilities
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/**
 * Sanitize user input
 */
function clean($data) {
    if (is_array($data)) {
        return array_map('clean', $data);
    }
    return htmlspecialchars(trim($data), ENT_QUOTES, 'UTF-8');
}

/**
 * Generate CSRF Token
 */
function csrf_token() {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

/**
 * Verify CSRF Token
 */
function csrf_verify($token) {
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}

/**
 * Set Flash Message
 */
function set_flash($type, $message) {
    $_SESSION['flash'] = [
        'type'    => $type, // 'success', 'danger', 'warning', 'info'
        'message' => $message
    ];
}

/**
 * Display Flash Message HTML
 */
function get_flash() {
    if (isset($_SESSION['flash'])) {
        $flash = $_SESSION['flash'];
        unset($_SESSION['flash']);
        $icon = $flash['type'] === 'success' ? 'ri-checkbox-circle-fill' : 'ri-error-warning-fill';
        return '<div class="alert alert-' . $flash['type'] . '"><i class="' . $icon . '"></i> ' . $flash['message'] . '</div>';
    }
    return '';
}

/**
 * Format Date/Time
 */
function format_date($datetime, $format = 'd M Y, h:i A') {
    if (empty($datetime)) return '—';
    $time = strtotime($datetime);
    return date($format, $time);
}

/**
 * Lead Status Badge HTML
 */
function status_badge($status) {
    $status = strtolower($status);
    switch ($status) {
        case 'new':
            return '<span class="badge badge-primary"><i class="ri-flashlight-line"></i> New</span>';
        case 'contacted':
            return '<span class="badge badge-info"><i class="ri-phone-line"></i> Contacted</span>';
        case 'in_progress':
            return '<span class="badge badge-warning"><i class="ri-time-line"></i> In Progress</span>';
        case 'admitted':
            return '<span class="badge badge-success"><i class="ri-check-double-line"></i> Admitted</span>';
        case 'rejected':
            return '<span class="badge badge-danger"><i class="ri-close-circle-line"></i> Rejected</span>';
        default:
            return '<span class="badge badge-secondary">' . ucfirst($status) . '</span>';
    }
}

/**
 * Get Site Setting Value from DB
 */
function get_setting($key, $default = '') {
    $db = getDB();
    if (!$db) return $default;
    try {
        $stmt = $db->prepare("SELECT setting_value FROM settings WHERE setting_key = ? LIMIT 1");
        $stmt->execute([$key]);
        $row = $stmt->fetch();
        return $row ? $row['setting_value'] : $default;
    } catch (Exception $e) {
        return $default;
    }
}
