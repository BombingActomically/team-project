<?php
include 'auth_check.php';

/*
 * ===========================================================================
 * NOTE: Replace this with a real DB fetch, e.g.:
 *
 * $stmt = $pdo->query("SELECT * FROM system_settings WHERE id = 1");
 * $settings = $stmt->fetch(PDO::FETCH_ASSOC);
 * ===========================================================================
 */
$settings = [
    // General
    'site_name'        => 'Evets',
    'support_email'    => 'support@evets.com',
    'support_phone'    => '+91 98765 43210',
    'timezone'         => 'Asia/Kolkata',
    'maintenance_mode' => false,

    // Event
    'current_fest_name'     => 'TechFest 2026',
    'registration_open'     => true,
    'max_team_size'          => 6,
    'registration_deadline' => '2026-08-10',
    'allow_solo_registration' => true,

    // Payment
    'payment_gateway'   => 'Razorpay',
    'currency'          => 'INR',
    'gateway_key_id'    => 'rzp_live_xxxxxxxxxxxx',
    'gateway_key_secret'=> '',
    'test_mode'         => true,

    // Notifications
    'email_notifications_enabled' => true,
    'sms_notifications_enabled'   => true,
    'smtp_host'   => 'smtp.evets.com',
    'smtp_port'   => 587,
    'smtp_user'   => 'notifications@evets.com',
    'sms_provider' => 'Twilio',

    // Security
    'session_timeout_minutes' => 30,
    'force_2fa_for_admins'    => false,
    'max_login_attempts'      => 5,
];

$errors  = [];
$success = false;
$activeTab = $_POST['active_tab'] ?? 'general';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $section = $_POST['section'] ?? '';

    switch ($section) {

        case 'general':
            $siteName     = trim($_POST['site_name'] ?? '');
            $supportEmail = trim($_POST['support_email'] ?? '');
            $supportPhone = trim($_POST['support_phone'] ?? '');
            $timezone     = trim($_POST['timezone'] ?? '');

            if ($siteName === '') {
                $errors['site_name'] = 'Site name is required.';
            }
            if (!filter_var($supportEmail, FILTER_VALIDATE_EMAIL)) {
                $errors['support_email'] = 'Please enter a valid support email.';
            }
            if ($supportPhone === '') {
                $errors['support_phone'] = 'Support phone is required.';
            }

            if (empty($errors)) {
                $settings['site_name']        = $siteName;
                $settings['support_email']    = $supportEmail;
                $settings['support_phone']    = $supportPhone;
                $settings['timezone']         = $timezone;
                $settings['maintenance_mode'] = isset($_POST['maintenance_mode']);
                // TODO: persist to DB
                $success = true;
            }
            break;

        case 'event':
            $festName   = trim($_POST['current_fest_name'] ?? '');
            $maxTeam    = (int)($_POST['max_team_size'] ?? 0);
            $deadline   = trim($_POST['registration_deadline'] ?? '');

            if ($festName === '') {
                $errors['current_fest_name'] = 'Fest name is required.';
            }
            if ($maxTeam < 1 || $maxTeam > 50) {
                $errors['max_team_size'] = 'Max team size must be between 1 and 50.';
            }
            if ($deadline === '') {
                $errors['registration_deadline'] = 'Registration deadline is required.';
            }

            if (empty($errors)) {
                $settings['current_fest_name']       = $festName;
                $settings['registration_open']        = isset($_POST['registration_open']);
                $settings['max_team_size']             = $maxTeam;
                $settings['registration_deadline']    = $deadline;
                $settings['allow_solo_registration']  = isset($_POST['allow_solo_registration']);
                // TODO: persist to DB
                $success = true;
            }
            break;

        case 'payment':
            $keyId = trim($_POST['gateway_key_id'] ?? '');

            if ($keyId === '') {
                $errors['gateway_key_id'] = 'Gateway Key ID is required.';
            }

            if (empty($errors)) {
                $settings['payment_gateway'] = trim($_POST['payment_gateway'] ?? '');
                $settings['currency']        = trim($_POST['currency'] ?? '');
                $settings['gateway_key_id']  = $keyId;
                if (!empty($_POST['gateway_key_secret'])) {
                    $settings['gateway_key_secret'] = $_POST['gateway_key_secret']; // TODO: encrypt before storing
                }
                $settings['test_mode'] = isset($_POST['test_mode']);
                // TODO: persist to DB
                $success = true;
            }
            break;

        case 'notifications':
            $smtpHost = trim($_POST['smtp_host'] ?? '');
            $smtpPort = trim($_POST['smtp_port'] ?? '');

            if ($smtpHost === '') {
                $errors['smtp_host'] = 'SMTP host is required.';
            }
            if ($smtpPort === '' || !is_numeric($smtpPort)) {
                $errors['smtp_port'] = 'SMTP port must be a number.';
            }

            if (empty($errors)) {
                $settings['email_notifications_enabled'] = isset($_POST['email_notifications_enabled']);
                $settings['sms_notifications_enabled']   = isset($_POST['sms_notifications_enabled']);
                $settings['smtp_host'] = $smtpHost;
                $settings['smtp_port'] = (int)$smtpPort;
                $settings['smtp_user'] = trim($_POST['smtp_user'] ?? '');
                $settings['sms_provider'] = trim($_POST['sms_provider'] ?? '');
                // TODO: persist to DB
                $success = true;
            }
            break;

        case 'security':
            $timeout = (int)($_POST['session_timeout_minutes'] ?? 0);
            $attempts = (int)($_POST['max_login_attempts'] ?? 0);

            if ($timeout < 5 || $timeout > 240) {
                $errors['session_timeout_minutes'] = 'Session timeout must be between 5 and 240 minutes.';
            }
            if ($attempts < 3 || $attempts > 10) {
                $errors['max_login_attempts'] = 'Max login attempts must be between 3 and 10.';
            }

            if (empty($errors)) {
                $settings['session_timeout_minutes'] = $timeout;
                $settings['force_2fa_for_admins']     = isset($_POST['force_2fa_for_admins']);
                $settings['max_login_attempts']       = $attempts;
                // TODO: persist to DB
                $success = true;
            }
            break;
    }
}
?>
<!doctype html>
<html lang="en" data-pc-preset="preset-1" data-pc-sidebar-caption="true" data-pc-direction="ltr" dir="ltr" data-pc-theme="light">

<head>
    <title>Evets | Admin</title>
    <!-- [Meta] -->
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta
        name="description"
        content="Datta Able is trending dashboard template made using Bootstrap 5 design framework. Datta Able is available in Bootstrap, React, CodeIgniter, Angular,  and .net Technologies." />
    <meta
        name="keywords"
        content="Bootstrap admin template, Dashboard UI Kit, Dashboard Template, Backend Panel, react dashboard, angular dashboard" />
    <meta name="author" content="CodedThemes" />

    <!-- [Favicon] icon -->
    <link rel="icon" href="assets/images/favicon.svg" type="image/x-icon" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- [Font] Family -->
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@300;400;500;600&display=swap" rel="stylesheet" />
    <!-- [phosphor Icons] https://phosphoricons.com/ -->
    <link rel="stylesheet" href="assets/fonts/phosphor/duotone/style.css" />
    <!-- [Tabler Icons] https://tablericons.com -->
    <link rel="stylesheet" href="assets/fonts/tabler-icons.min.css" />
    <!-- [Feather Icons] https://feathericons.com -->
    <link rel="stylesheet" href="assets/fonts/feather.css" />
    <!-- [Font Awesome Icons] https://fontawesome.com/icons -->
    <link rel="stylesheet" href="assets/fonts/fontawesome.css" />
    <!-- [Material Icons] https://fonts.google.com/icons -->
    <link rel="stylesheet" href="assets/fonts/material.css" />
    <!-- [Template CSS Files] -->
    <link rel="stylesheet" href="assets/css/style.css" id="main-style-link" />

</head>

<body>

    <!-- [ Pre-loader ] start -->
    <div class="loader-bg fixed inset-0 bg-white dark:bg-themedark-cardbg z-[1034]">
        <div class="loader-track h-[5px] w-full inline-block absolute overflow-hidden top-0">
            <div class="loader-fill w-[300px] h-[5px] bg-primary-500 absolute top-0 left-0 animate-[hitZak_0.6s_ease-in-out_infinite_alternate]"></div>
        </div>
    </div>
    <!-- [ Pre-loader ] End -->
    <!-- [ Sidebar Menu ] start -->
    <?php include_once("Sidebar.php"); ?>
    <!-- [ Sidebar Menu ] end -->
    <!-- [ Header Topbar ] start -->
    <?php include_once("Header.php"); ?>
    <!-- [ Header ] end -->



<!-- [ Main Content ] start -->
<div class="pc-container">
    <div class="pc-content">

        <!-- Page Header -->
        <div class="page-header">
            <div class="page-block">
                <div class="page-header-title">
                    <h5 class="mb-0 font-medium">System Settings</h5>
                </div>

                <ul class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="index.php">Home</a>
                    </li>
                    <li class="breadcrumb-item">
                        Admin Settings
                    </li>
                    <li class="breadcrumb-item" aria-current="page">
                        System Settings
                    </li>
                </ul>
            </div>
        </div>

        <?php if ($success): ?>
            <div class="alert alert-success d-flex align-items-center" role="alert">
                <i class="ti ti-circle-check me-2"></i>
                Settings updated successfully.
            </div>
        <?php endif; ?>

        <?php if (!empty($errors)): ?>
            <div class="alert alert-danger" role="alert">
                <strong>Please fix the following:</strong>
                <ul class="mb-0">
                    <?php foreach ($errors as $err): ?>
                        <li><?php echo htmlspecialchars($err); ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <div class="row">
            <div class="col-md-12">
                <div class="card shadow-sm border-0">

                    <div class="card-header">
                        <!-- Tab Nav -->
                        <ul class="nav nav-tabs card-header-tabs" id="settingsTab" role="tablist">

                            <li class="nav-item" role="presentation">
                                <button
                                    class="nav-link <?php echo $activeTab === 'general' ? 'active' : ''; ?>"
                                    id="general-tab" data-bs-toggle="tab" data-bs-target="#general"
                                    type="button" role="tab">
                                    <i class="ti ti-settings"></i> General
                                </button>
                            </li>

                            <li class="nav-item" role="presentation">
                                <button
                                    class="nav-link <?php echo $activeTab === 'event' ? 'active' : ''; ?>"
                                    id="event-tab" data-bs-toggle="tab" data-bs-target="#event"
                                    type="button" role="tab">
                                    <i class="ti ti-calendar-event"></i> Event
                                </button>
                            </li>

                            <li class="nav-item" role="presentation">
                                <button
                                    class="nav-link <?php echo $activeTab === 'payment' ? 'active' : ''; ?>"
                                    id="payment-tab" data-bs-toggle="tab" data-bs-target="#payment"
                                    type="button" role="tab">
                                    <i class="ti ti-credit-card"></i> Payment
                                </button>
                            </li>

                            <li class="nav-item" role="presentation">
                                <button
                                    class="nav-link <?php echo $activeTab === 'notifications' ? 'active' : ''; ?>"
                                    id="notifications-tab" data-bs-toggle="tab" data-bs-target="#notifications"
                                    type="button" role="tab">
                                    <i class="ti ti-bell"></i> Notifications
                                </button>
                            </li>

                            <li class="nav-item" role="presentation">
                                <button
                                    class="nav-link <?php echo $activeTab === 'security' ? 'active' : ''; ?>"
                                    id="security-tab" data-bs-toggle="tab" data-bs-target="#security"
                                    type="button" role="tab">
                                    <i class="ti ti-shield-lock"></i> Security
                                </button>
                            </li>

                        </ul>
                    </div>

                    <div class="card-body">
                        <div class="tab-content" id="settingsTabContent">

                            <!-- ============ GENERAL ============ -->
                            <div class="tab-pane fade <?php echo $activeTab === 'general' ? 'show active' : ''; ?>" id="general" role="tabpanel">

                                <form action="" method="post" class="row g-3 needs-validation" novalidate>
                                    <input type="hidden" name="section" value="general">
                                    <input type="hidden" name="active_tab" value="general">

                                    <div class="col-md-6">
                                        <label class="form-label" for="site_name">
                                            Site / Platform Name <span class="text-danger">*</span>
                                        </label>
                                        <input
                                            type="text"
                                            class="form-control <?php echo isset($errors['site_name']) ? 'is-invalid' : ''; ?>"
                                            name="site_name" id="site_name"
                                            value="<?php echo htmlspecialchars($settings['site_name']); ?>" required>
                                        <div class="invalid-feedback">
                                            <?php echo isset($errors['site_name']) ? htmlspecialchars($errors['site_name']) : 'Site name is required.'; ?>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label" for="timezone">Timezone</label>
                                        <select class="form-select" name="timezone" id="timezone">
                                            <?php foreach (['Asia/Kolkata', 'Asia/Dubai', 'UTC', 'America/New_York', 'Europe/London'] as $tz): ?>
                                                <option value="<?php echo $tz; ?>" <?php echo $settings['timezone'] === $tz ? 'selected' : ''; ?>>
                                                    <?php echo $tz; ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label" for="support_email">
                                            Support Email <span class="text-danger">*</span>
                                        </label>
                                        <input
                                            type="email"
                                            class="form-control <?php echo isset($errors['support_email']) ? 'is-invalid' : ''; ?>"
                                            name="support_email" id="support_email"
                                            value="<?php echo htmlspecialchars($settings['support_email']); ?>" required>
                                        <div class="invalid-feedback">
                                            <?php echo isset($errors['support_email']) ? htmlspecialchars($errors['support_email']) : 'Please enter a valid email.'; ?>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label" for="support_phone">
                                            Support Phone <span class="text-danger">*</span>
                                        </label>
                                        <input
                                            type="text"
                                            class="form-control <?php echo isset($errors['support_phone']) ? 'is-invalid' : ''; ?>"
                                            name="support_phone" id="support_phone"
                                            value="<?php echo htmlspecialchars($settings['support_phone']); ?>" required>
                                        <div class="invalid-feedback">
                                            <?php echo isset($errors['support_phone']) ? htmlspecialchars($errors['support_phone']) : 'Support phone is required.'; ?>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" name="maintenance_mode" id="maintenance_mode"
                                                <?php echo $settings['maintenance_mode'] ? 'checked' : ''; ?>>
                                            <label class="form-check-label" for="maintenance_mode">
                                                Maintenance Mode
                                            </label>
                                            <div class="form-text">When enabled, the public site shows a maintenance page to all visitors.</div>
                                        </div>
                                    </div>

                                    <div class="col-12 text-end">
                                        <button type="submit" class="btn btn-primary">
                                            <i class="ti ti-device-floppy"></i> Save General Settings
                                        </button>
                                    </div>
                                </form>

                            </div>

                            <!-- ============ EVENT ============ -->
                            <div class="tab-pane fade <?php echo $activeTab === 'event' ? 'show active' : ''; ?>" id="event" role="tabpanel">

                                <form action="" method="post" class="row g-3 needs-validation" novalidate>
                                    <input type="hidden" name="section" value="event">
                                    <input type="hidden" name="active_tab" value="event">

                                    <div class="col-md-6">
                                        <label class="form-label" for="current_fest_name">
                                            Current Fest Name <span class="text-danger">*</span>
                                        </label>
                                        <input
                                            type="text"
                                            class="form-control <?php echo isset($errors['current_fest_name']) ? 'is-invalid' : ''; ?>"
                                            name="current_fest_name" id="current_fest_name"
                                            value="<?php echo htmlspecialchars($settings['current_fest_name']); ?>" required>
                                        <div class="invalid-feedback">
                                            <?php echo isset($errors['current_fest_name']) ? htmlspecialchars($errors['current_fest_name']) : 'Fest name is required.'; ?>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label" for="registration_deadline">
                                            Registration Deadline <span class="text-danger">*</span>
                                        </label>
                                        <input
                                            type="date"
                                            class="form-control <?php echo isset($errors['registration_deadline']) ? 'is-invalid' : ''; ?>"
                                            name="registration_deadline" id="registration_deadline"
                                            value="<?php echo htmlspecialchars($settings['registration_deadline']); ?>" required>
                                        <div class="invalid-feedback">
                                            <?php echo isset($errors['registration_deadline']) ? htmlspecialchars($errors['registration_deadline']) : 'Deadline is required.'; ?>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label" for="max_team_size">
                                            Max Team Size <span class="text-danger">*</span>
                                        </label>
                                        <input
                                            type="number"
                                            class="form-control <?php echo isset($errors['max_team_size']) ? 'is-invalid' : ''; ?>"
                                            name="max_team_size" id="max_team_size"
                                            min="1" max="50"
                                            value="<?php echo (int)$settings['max_team_size']; ?>" required>
                                        <div class="invalid-feedback">
                                            <?php echo isset($errors['max_team_size']) ? htmlspecialchars($errors['max_team_size']) : 'Enter a value between 1 and 50.'; ?>
                                        </div>
                                    </div>

                                    <div class="col-md-6 d-flex flex-column justify-content-center">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" name="registration_open" id="registration_open"
                                                <?php echo $settings['registration_open'] ? 'checked' : ''; ?>>
                                            <label class="form-check-label" for="registration_open">Registrations Open</label>
                                        </div>
                                        <div class="form-check form-switch mt-2">
                                            <input class="form-check-input" type="checkbox" name="allow_solo_registration" id="allow_solo_registration"
                                                <?php echo $settings['allow_solo_registration'] ? 'checked' : ''; ?>>
                                            <label class="form-check-label" for="allow_solo_registration">Allow Solo Registrations</label>
                                        </div>
                                    </div>

                                    <div class="col-12 text-end">
                                        <button type="submit" class="btn btn-primary">
                                            <i class="ti ti-device-floppy"></i> Save Event Settings
                                        </button>
                                    </div>
                                </form>

                            </div>

                            <!-- ============ PAYMENT ============ -->
                            <div class="tab-pane fade <?php echo $activeTab === 'payment' ? 'show active' : ''; ?>" id="payment" role="tabpanel">

                                <div class="alert alert-warning d-flex align-items-start gap-2">
                                    <i class="ti ti-alert-triangle mt-1"></i>
                                    <div class="small">
                                        Gateway credentials are sensitive. The secret key field is left blank for security — entering a new value will overwrite the stored one.
                                    </div>
                                </div>

                                <form action="" method="post" class="row g-3 needs-validation" novalidate>
                                    <input type="hidden" name="section" value="payment">
                                    <input type="hidden" name="active_tab" value="payment">

                                    <div class="col-md-6">
                                        <label class="form-label" for="payment_gateway">Payment Gateway</label>
                                        <select class="form-select" name="payment_gateway" id="payment_gateway">
                                            <?php foreach (['Razorpay', 'Stripe', 'PayU', 'CCAvenue'] as $gw): ?>
                                                <option value="<?php echo $gw; ?>" <?php echo $settings['payment_gateway'] === $gw ? 'selected' : ''; ?>>
                                                    <?php echo $gw; ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label" for="currency">Currency</label>
                                        <select class="form-select" name="currency" id="currency">
                                            <?php foreach (['INR', 'USD', 'EUR', 'GBP'] as $cur): ?>
                                                <option value="<?php echo $cur; ?>" <?php echo $settings['currency'] === $cur ? 'selected' : ''; ?>>
                                                    <?php echo $cur; ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label" for="gateway_key_id">
                                            Gateway Key ID <span class="text-danger">*</span>
                                        </label>
                                        <input
                                            type="text"
                                            class="form-control <?php echo isset($errors['gateway_key_id']) ? 'is-invalid' : ''; ?>"
                                            name="gateway_key_id" id="gateway_key_id"
                                            value="<?php echo htmlspecialchars($settings['gateway_key_id']); ?>" required>
                                        <div class="invalid-feedback">
                                            <?php echo isset($errors['gateway_key_id']) ? htmlspecialchars($errors['gateway_key_id']) : 'Key ID is required.'; ?>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label" for="gateway_key_secret">Gateway Key Secret</label>
                                        <input
                                            type="password"
                                            class="form-control"
                                            name="gateway_key_secret" id="gateway_key_secret"
                                            placeholder="•••••••••••• (leave blank to keep current)"
                                            autocomplete="new-password">
                                    </div>

                                    <div class="col-md-12">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" name="test_mode" id="test_mode"
                                                <?php echo $settings['test_mode'] ? 'checked' : ''; ?>>
                                            <label class="form-check-label" for="test_mode">Test Mode</label>
                                            <div class="form-text">Payments are simulated and no real money is charged while enabled.</div>
                                        </div>
                                    </div>

                                    <div class="col-12 text-end">
                                        <button type="submit" class="btn btn-primary">
                                            <i class="ti ti-device-floppy"></i> Save Payment Settings
                                        </button>
                                    </div>
                                </form>

                            </div>

                            <!-- ============ NOTIFICATIONS ============ -->
                            <div class="tab-pane fade <?php echo $activeTab === 'notifications' ? 'show active' : ''; ?>" id="notifications" role="tabpanel">

                                <form action="" method="post" class="row g-3 needs-validation" novalidate>
                                    <input type="hidden" name="section" value="notifications">
                                    <input type="hidden" name="active_tab" value="notifications">

                                    <div class="col-md-6">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" name="email_notifications_enabled" id="email_notifications_enabled"
                                                <?php echo $settings['email_notifications_enabled'] ? 'checked' : ''; ?>>
                                            <label class="form-check-label" for="email_notifications_enabled">Enable Email Notifications</label>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" name="sms_notifications_enabled" id="sms_notifications_enabled"
                                                <?php echo $settings['sms_notifications_enabled'] ? 'checked' : ''; ?>>
                                            <label class="form-check-label" for="sms_notifications_enabled">Enable SMS Notifications</label>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label" for="smtp_host">
                                            SMTP Host <span class="text-danger">*</span>
                                        </label>
                                        <input
                                            type="text"
                                            class="form-control <?php echo isset($errors['smtp_host']) ? 'is-invalid' : ''; ?>"
                                            name="smtp_host" id="smtp_host"
                                            value="<?php echo htmlspecialchars($settings['smtp_host']); ?>" required>
                                        <div class="invalid-feedback">
                                            <?php echo isset($errors['smtp_host']) ? htmlspecialchars($errors['smtp_host']) : 'SMTP host is required.'; ?>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label" for="smtp_port">
                                            SMTP Port <span class="text-danger">*</span>
                                        </label>
                                        <input
                                            type="number"
                                            class="form-control <?php echo isset($errors['smtp_port']) ? 'is-invalid' : ''; ?>"
                                            name="smtp_port" id="smtp_port"
                                            value="<?php echo (int)$settings['smtp_port']; ?>" required>
                                        <div class="invalid-feedback">
                                            <?php echo isset($errors['smtp_port']) ? htmlspecialchars($errors['smtp_port']) : 'SMTP port must be a number.'; ?>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label" for="smtp_user">SMTP Username</label>
                                        <input
                                            type="text"
                                            class="form-control"
                                            name="smtp_user" id="smtp_user"
                                            value="<?php echo htmlspecialchars($settings['smtp_user']); ?>">
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label" for="sms_provider">SMS Provider</label>
                                        <select class="form-select" name="sms_provider" id="sms_provider">
                                            <?php foreach (['Twilio', 'MSG91', 'Textlocal'] as $p): ?>
                                                <option value="<?php echo $p; ?>" <?php echo $settings['sms_provider'] === $p ? 'selected' : ''; ?>>
                                                    <?php echo $p; ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>

                                    <div class="col-12 text-end">
                                        <button type="submit" class="btn btn-primary">
                                            <i class="ti ti-device-floppy"></i> Save Notification Settings
                                        </button>
                                    </div>
                                </form>

                            </div>

                            <!-- ============ SECURITY ============ -->
                            <div class="tab-pane fade <?php echo $activeTab === 'security' ? 'show active' : ''; ?>" id="security" role="tabpanel">

                                <form action="" method="post" class="row g-3 needs-validation" novalidate>
                                    <input type="hidden" name="section" value="security">
                                    <input type="hidden" name="active_tab" value="security">

                                    <div class="col-md-6">
                                        <label class="form-label" for="session_timeout_minutes">
                                            Session Timeout (minutes) <span class="text-danger">*</span>
                                        </label>
                                        <input
                                            type="number"
                                            class="form-control <?php echo isset($errors['session_timeout_minutes']) ? 'is-invalid' : ''; ?>"
                                            name="session_timeout_minutes" id="session_timeout_minutes"
                                            min="5" max="240"
                                            value="<?php echo (int)$settings['session_timeout_minutes']; ?>" required>
                                        <div class="invalid-feedback">
                                            <?php echo isset($errors['session_timeout_minutes']) ? htmlspecialchars($errors['session_timeout_minutes']) : 'Enter a value between 5 and 240.'; ?>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label" for="max_login_attempts">
                                            Max Login Attempts <span class="text-danger">*</span>
                                        </label>
                                        <input
                                            type="number"
                                            class="form-control <?php echo isset($errors['max_login_attempts']) ? 'is-invalid' : ''; ?>"
                                            name="max_login_attempts" id="max_login_attempts"
                                            min="3" max="10"
                                            value="<?php echo (int)$settings['max_login_attempts']; ?>" required>
                                        <div class="invalid-feedback">
                                            <?php echo isset($errors['max_login_attempts']) ? htmlspecialchars($errors['max_login_attempts']) : 'Enter a value between 3 and 10.'; ?>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" name="force_2fa_for_admins" id="force_2fa_for_admins"
                                                <?php echo $settings['force_2fa_for_admins'] ? 'checked' : ''; ?>>
                                            <label class="form-check-label" for="force_2fa_for_admins">Require Two-Factor Authentication for All Admins</label>
                                        </div>
                                    </div>

                                    <div class="col-12 text-end">
                                        <button type="submit" class="btn btn-primary">
                                            <i class="ti ti-device-floppy"></i> Save Security Settings
                                        </button>
                                    </div>
                                </form>

                            </div>

                        </div>
                    </div>

                </div>
            </div>
        </div>

    </div>
</div>
<!-- [ Main Content ] end -->
    <?php include_once("Footer.php"); ?>
    <!-- Required Js -->
    <script src="assets/js/plugins/simplebar.min.js"></script>
    <script src="assets/js/plugins/popper.min.js"></script>
    <script src="assets/js/icon/custom-icon.js"></script>
    <script src="assets/js/plugins/feather.min.js"></script>
    <script src="assets/js/component.js"></script>
    <script src="assets/js/theme.js"></script>
    <script src="assets/js/script.js"></script>

    <div class="floting-button fixed bottom-[50px] right-[30px] z-[1030]">
    </div>


    <script>
        layout_change('false');
    </script>


    <script>
        layout_theme_sidebar_change('dark');
    </script>


    <script>
        change_box_container('false');
    </script>

    <script>
        layout_caption_change('true');
    </script>

    <script>
        layout_rtl_change('false');
    </script>

    <script>
        preset_change('preset-1');
    </script>

    <script>
        main_layout_change('vertical');
    </script>

    <!-- ===================== System Settings: per-form validation ===================== -->
    <script>
        (function () {
            'use strict';

            document.querySelectorAll('form.needs-validation').forEach(function (form) {

                // Clear invalid state as fields become valid
                form.querySelectorAll('.form-control, .form-select').forEach(function (field) {
                    field.addEventListener('input', function () {
                        if (field.checkValidity()) {
                            field.classList.remove('is-invalid');
                        }
                    });
                    field.addEventListener('change', function () {
                        if (field.checkValidity()) {
                            field.classList.remove('is-invalid');
                        }
                    });
                });

                form.addEventListener('submit', function (e) {
                    if (!form.checkValidity()) {
                        e.preventDefault();
                        e.stopPropagation();

                        const firstInvalid = form.querySelector(':invalid');
                        if (firstInvalid) {
                            firstInvalid.classList.add('is-invalid');
                            firstInvalid.focus();
                        }
                    }
                    form.classList.add('was-validated');
                }, false);
            });
        })();
    </script>

</body>

</html>