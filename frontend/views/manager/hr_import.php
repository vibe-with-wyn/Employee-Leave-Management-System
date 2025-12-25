<?php
require_once __DIR__ . '/../../../backend/src/Database.php';
require_once __DIR__ . '/../../../backend/src/Session.php';
require_once __DIR__ . '/../../../backend/models/Auth.php';
require_once __DIR__ . '/../../../backend/middlewares/AuthMiddleware.php';
require_once __DIR__ . '/../../../backend/utils/redirect.php';
require_once __DIR__ . '/../../../backend/controllers/HRImportController.php';

// Initialize session and middleware
Session::start();
$authMiddleware = new AuthMiddleware();
if (!$authMiddleware->handle() || Session::get('role') !== 'manager') {
    redirect('/', 'Unauthorized access.', 'error');
}

$controller = new HRImportController();
$managerId = Session::get('user_id');
$manager = $controller->getManager($managerId);

// Flash messages
$message = Session::get('message');
Session::set('message', null);

// Import results
$importResults = Session::get('import_results') ?? [];
Session::set('import_results', null);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HR Data Import - Leave Management System</title>
    <link rel="stylesheet" href="../../assets/css/manager_dashboard.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        .toast-container {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 1000;
        }

        .toast {
            background: #fff;
            border-radius: 4px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
            padding: 12px 20px;
            margin-bottom: 10px;
            opacity: 0;
            transition: opacity 0.3s ease;
            max-width: 400px;
        }

        .toast.visible {
            opacity: 1;
        }

        .toast-success {
            border-left: 4px solid #28a745;
        }

        .toast-error {
            border-left: 4px solid #dc3545;
        }

        .import-results {
            margin-top: 20px;
        }

        .import-results .summary {
            display: flex;
            gap: 20px;
            margin-bottom: 10px;
            font-size: 1.2rem;
        }

        .import-results .summary .success {
            color: #28a745;
            font-weight: bold;
        }

        .import-results .summary .skipped {
            color: #dc3545;
            font-weight: bold;
        }

        .import-results h3 {
            font-size: 1.3rem;
            margin: 10px 0;
        }

        .import-results .result-table,
        .import-results .error-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        .import-results .result-table th,
        .import-results .result-table td,
        .import-results .error-table th,
        .import-results .error-table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        .import-results .result-table th,
        .import-results .error-table th {
            background: #f4f4f4;
            font-weight: bold;
        }

        .import-results .result-table tr:nth-child(even),
        .import-results .error-table tr:nth-child(even) {
            background: #f9f9f9;
        }

        .import-results .error-table .action {
            color: #666;
            font-style: italic;
        }

        .tooltip-container {
            position: relative;
            display: inline-block;
            margin-left: 8px;
        }

        .tooltip-icon {
            color: #4a90e2;
            font-size: 1rem;
            cursor: pointer;
        }

        .tooltip-text {
            visibility: hidden;
            width: 300px;
            background-color: #fff;
            color: #1f2937;
            text-align: center;
            border-radius: 6px;
            padding: 8px;
            position: absolute;
            z-index: 9999;
            top: -120%;
            left: 50%;
            transform: translateX(-50%);
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
            font-size: 0.9rem;
            line-height: 1.4;
            white-space: normal;
            word-wrap: break-word;
            transition: top 0.1s ease;
        }

        .tooltip-text.bottom {
            top: auto;
            bottom: 125%;
        }

        .tooltip-text::after {
            content: '';
            position: absolute;
            bottom: -10px;
            left: 50%;
            margin-left: -5px;
            border-width: 5px;
            border-style: solid;
            border-color: #fff transparent transparent transparent;
        }

        .tooltip-text.bottom::after {
            bottom: auto;
            top: -10px;
            border-color: transparent transparent #fff transparent;
        }

        .tooltip-container:hover .tooltip-text {
            visibility: visible;
        }

        .card-container {
            position: relative;
            min-height: 0;
        }

        .card {
            position: relative;
            min-height: 0;
            overflow: visible !important;
        }

        .form-group {
            overflow: visible !important;
        }

        /* Credentials Modal Styles */
        .credentials-modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 10000;
            align-items: center;
            justify-content: center;
        }

        .credentials-modal.active {
            display: flex;
        }

        .credentials-modal-content {
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.2);
            max-width: 800px;
            width: 90%;
            max-height: 80vh;
            overflow: hidden;
            display: flex;
            flex-direction: column;
        }

        .credentials-modal-header {
            padding: 20px;
            border-bottom: 2px solid #e5e7eb;
            background: linear-gradient(135deg, #4a90e2 0%, #357abd 100%);
            color: white;
        }

        .credentials-modal-header h2 {
            font-size: 1.5rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
        }

        .credentials-modal-header p {
            font-size: 0.9rem;
            opacity: 0.9;
        }

        .credentials-modal-body {
            padding: 20px;
            overflow-y: auto;
            flex: 1;
        }

        .credentials-warning {
            background: #fff3cd;
            border-left: 4px solid #ffc107;
            padding: 12px;
            margin-bottom: 20px;
            border-radius: 4px;
            font-size: 0.9rem;
            color: #856404;
        }

        .credentials-warning strong {
            display: block;
            margin-bottom: 5px;
        }

        .credentials-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        .credentials-table th,
        .credentials-table td {
            border: 1px solid #e5e7eb;
            padding: 10px;
            text-align: left;
            font-size: 0.9rem;
        }

        .credentials-table th {
            background: #f9fafb;
            font-weight: 600;
            color: #1f2937;
        }

        .credentials-table td {
            color: #374151;
        }

        .credentials-table .password-cell {
            font-family: 'Courier New', monospace;
            font-weight: 600;
            color: #4a90e2;
        }

        .credentials-modal-footer {
            padding: 20px;
            border-top: 1px solid #e5e7eb;
            display: flex;
            gap: 10px;
            justify-content: flex-end;
        }

        .credentials-btn {
            padding: 10px 20px;
            border: none;
            border-radius: 6px;
            font-size: 0.9rem;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .credentials-btn-primary {
            background: #4a90e2;
            color: white;
        }

        .credentials-btn-primary:hover {
            background: #357abd;
        }

        .credentials-btn-secondary {
            background: #6b7280;
            color: white;
        }

        .credentials-btn-secondary:hover {
            background: #4b5563;
        }

        @media (max-width: 768px) {
            .credentials-modal-content {
                width: 95%;
                max-height: 90vh;
            }

            .credentials-table {
                font-size: 0.8rem;
            }

            .credentials-table th,
            .credentials-table td {
                padding: 8px;
            }

            .credentials-modal-footer {
                flex-direction: column;
            }

            .credentials-btn {
                width: 100%;
            }
        }
    </style>
</head>
<body class="background-gradient font-poppins">
    <div class="container">
        <aside class="sidebar sidebar-collapsed">
            <div class="sidebar-header">
                <button class="sidebar-toggle"><i class="fas fa-bars"></i></button>
            </div>
            <nav class="sidebar-nav">
                <a href="manager_dashboard.php" class="sidebar-link">
                    <i class="fas fa-home"></i>
                    <span class="sidebar-text">Home</span>
                </a>
                <a href="manage_requests.php" class="sidebar-link">
                    <i class="fas fa-tasks"></i>
                    <span class="sidebar-text">Manage Requests</span>
                </a>
                <a href="leave_history.php" class="sidebar-link">
                    <i class="fas fa-history"></i>
                    <span class="sidebar-text">Leave History</span>
                </a>
                <a href="reporting.php" class="sidebar-link">
                    <i class="fas fa-chart-bar"></i>
                    <span class="sidebar-text">Reporting Module</span>
                </a>
                <a href="hr_import.php" class="sidebar-link active">
                    <i class="fas fa-upload"></i>
                    <span class="sidebar-text">HR Data Import</span>
                </a>
                <a href="settings.php" class="sidebar-link sidebar-link-bottom">
                    <i class="fas fa-cog"></i>
                    <span class="sidebar-text">Settings</span>
                </a>
            </nav>
        </aside>

        <main class="main-content">
            <header class="header">
                <div class="header-center">
                    <div class="search-bar">
                        <form class="search-form">
                            <i class="fas fa-search search-icon"></i>
                            <input type="text" class="search-input" placeholder="Search...">
                        </form>
                    </div>
                </div>
                <div class="header-right">
                    <div class="profile-container">
                        <button class="profile-button">
                            <img src="../../assets/images/profile-placeholder.png" alt="Profile" class="profile-image">
                            <span class="profile-name"><?php echo htmlspecialchars($manager['first_name'] ?? 'Manager'); ?></span>
                        </button>
                        <div class="profile-dropdown">
                            <a href="settings.php" class="dropdown-item">Account Settings</a>
                            <a href="/employee-leave-management-system/backend/controllers/LogoutController.php?action=logout" class="dropdown-item">Logout</a>
                        </div>
                    </div>
                </div>
            </header>

            <div class="main padding-20">
                <div class="greeting-text">
                    <h1 class="greeting-title">HR Data Import</h1>
                    <p>Upload a CSV file to add employee data.</p>
                </div>

                <div class="card-container">
                    <h2>Import Employee Data</h2>
                    <div class="card">
                        <form action="../../../backend/controllers/HRImportController.php?action=import_csv" method="POST" enctype="multipart/form-data">
                            <div class="form-group">
                                <label for="csv_file">Upload CSV File
                                    <span class="tooltip-container">
                                        <i class="fas fa-info-circle tooltip-icon"></i>
                                        <span class="tooltip-text">CSV must include headers: FIRST_NAME, LAST_NAME, EMAIL, PHONE_NUMBER, HIRE_DATE, MANAGER_ID, DEPARTMENT_ID, ROLE</span>
                                    </span>
                                </label>
                                <input type="file" id="csv_file" name="csv_file" accept=".csv" required>
                            </div>
                            <button type="submit" class="submit-button">Import CSV</button>
                        </form>
                    </div>
                </div>

                <?php if (!empty($importResults)): ?>
                    <div class="card-container import-results">
                        <h2>Import Results</h2>
                        <div class="summary">
                            <div class="success">Employees Added Successfully: <?php echo $importResults['success_count']; ?></div>
                            <div class="skipped">Records Skipped: <?php echo $importResults['skipped_count']; ?></div>
                        </div>
                        <?php if (!empty($importResults['success_rows'])): ?>
                            <h3>Successfully Imported Employees</h3>
                            <table class="result-table">
                                <thead>
                                    <tr>
                                        <th>Employee ID</th>
                                        <th>First Name</th>
                                        <th>Last Name</th>
                                        <th>Email</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($importResults['success_rows'] as $row): ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars($row['employee_id']); ?></td>
                                            <td><?php echo htmlspecialchars($row['first_name']); ?></td>
                                            <td><?php echo htmlspecialchars($row['last_name']); ?></td>
                                            <td><?php echo htmlspecialchars($row['email']); ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        <?php endif; ?>
                        <?php if (!empty($importResults['errors'])): ?>
                            <h3>Issues Encountered</h3>
                            <table class="error-table">
                                <thead>
                                    <tr>
                                        <th>Email</th>
                                        <th>Issue</th>
                                        <th>Action Needed</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($importResults['errors'] as $error): ?>
                                        <?php
                                        $email = 'Unknown';
                                        $issue = $error;
                                        $action = 'Review CSV data and try again.';
                                        if (preg_match('/Duplicate email \(([^)]+)\)/', $error, $matches)) {
                                            $email = $matches[1];
                                            $issue = 'Duplicate email already exists.';
                                            $action = 'Use a unique email address.';
                                        } elseif (preg_match('/Missing required fields/', $error, $matches)) {
                                            $issue = 'Missing required fields (e.g., first_name, last_name, email).';
                                            $action = 'Ensure all required fields are filled.';
                                        } elseif (preg_match('/Invalid email \(([^)]+)\)/', $error, $matches)) {
                                            $email = $matches[1];
                                            $issue = 'Invalid email address format.';
                                            $action = 'Verify the email address format.';
                                        } elseif (preg_match('/Invalid department_id \(([^)]+)\)/', $error, $matches)) {
                                            $email = preg_match('/email \(([^)]+)\)/', $error, $emailMatch) ? $emailMatch[1] : 'Unknown';
                                            $issue = "Invalid department_id ({$matches[1]}).";
                                            $action = 'Ensure the department_id exists in the departments table.';
                                        } elseif (preg_match('/Invalid manager_id \(([^)]+)\)/', $error, $matches)) {
                                            $email = preg_match('/email \(([^)]+)\)/', $error, $emailMatch) ? $emailMatch[1] : 'Unknown';
                                            $issue = "Invalid manager_id ({$matches[1]}).";
                                            $action = 'Ensure the manager_id exists or leave blank.';
                                        } elseif (strpos($error, 'Insufficient columns') !== false) {
                                            $issue = 'Incorrect number of columns in CSV row.';
                                            $action = 'Ensure all rows have the correct number of columns.';
                                        }
                                        ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars($email); ?></td>
                                            <td><?php echo htmlspecialchars($issue); ?></td>
                                            <td class="action"><?php echo htmlspecialchars($action); ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            </div>
        </main>
    </div>

    <!-- Credentials Modal -->
    <div id="credentials-modal" class="credentials-modal">
        <div class="credentials-modal-content">
            <div class="credentials-modal-header">
                <h2>🔐 Import Successful - Employee Credentials</h2>
                <p>Testing credentials for <?php echo $importResults['success_count'] ?? 0; ?> newly imported employees</p>
            </div>
            <div class="credentials-modal-body">
                <div class="credentials-warning">
                    <strong>⚠️ Testing Purpose Only</strong>
                    In production, these credentials should be sent via email to each employee. This modal is for testing/demonstration purposes only.
                </div>
                
                <?php if (!empty($importResults['credentials'])): ?>
                    <table class="credentials-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Password</th>
                                <th>Role</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($importResults['credentials'] as $cred): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($cred['employee_id']); ?></td>
                                    <td><?php echo htmlspecialchars($cred['name']); ?></td>
                                    <td><?php echo htmlspecialchars($cred['email']); ?></td>
                                    <td class="password-cell"><?php echo htmlspecialchars($cred['password']); ?></td>
                                    <td><?php echo htmlspecialchars($cred['role']); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php endif; ?>
            </div>
            <div class="credentials-modal-footer">
                <button class="credentials-btn credentials-btn-primary" onclick="downloadCredentials()">
                    <i class="fas fa-download"></i> Download as Text File
                </button>
                <button class="credentials-btn credentials-btn-secondary" onclick="closeCredentialsModal()">
                    Close
                </button>
            </div>
        </div>
    </div>

    <script src="../../assets/js/manager_dashboard.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const form = document.querySelector('form');
            form.addEventListener('submit', function (event) {
                const fileInput = document.getElementById('csv_file');
                if (!fileInput.files.length) {
                    event.preventDefault();
                    showToast('Please select a CSV file.', 'error', 5000);
                    return;
                }
                const file = fileInput.files[0];
                if (!file.name.endsWith('.csv')) {
                    event.preventDefault();
                    showToast('Only CSV files are allowed.', 'error', 5000);
                    return;
                }
                if (file.size > 5 * 1024 * 1024) {
                    event.preventDefault();
                    showToast('File size exceeds 5MB limit.', 'error', 5000);
                    return;
                }
            });

            <?php if ($message): ?>
                showToast('<?php echo addslashes(htmlspecialchars($message['text'])); ?>', '<?php echo htmlspecialchars($message['type']); ?>', 5000);
            <?php endif; ?>

            <?php if (!empty($importResults)): ?>
                showToast('CSV import completed with <?php echo $importResults['success_count']; ?> employees added.', 'success', 5000);
            <?php endif; ?>

            const tooltipIcon = document.querySelector('.tooltip-icon');
            if (tooltipIcon) {
                tooltipIcon.addEventListener('mouseover', function () {
                    const tooltip = this.nextElementSibling;
                    const rect = tooltip.getBoundingClientRect();
                    const viewportHeight = window.innerHeight;

                    if (rect.top < 10 || rect.bottom > viewportHeight - 10) {
                        tooltip.classList.add('bottom');
                        tooltip.style.top = 'auto';
                    } else {
                        tooltip.classList.remove('bottom');
                        tooltip.style.top = '-120%';
                    }
                });

                tooltipIcon.addEventListener('mouseout', function () {
                    const tooltip = this.nextElementSibling;
                    tooltip.classList.remove('bottom');
                    tooltip.style.top = '-120%';
                });
            }
        });

        // Credentials Modal Functions
        function showCredentialsModal() {
            const modal = document.getElementById('credentials-modal');
            if (modal) {
                modal.classList.add('active');
            }
        }

        function closeCredentialsModal() {
            const modal = document.getElementById('credentials-modal');
            if (modal) {
                modal.classList.remove('active');
            }
        }

        function downloadCredentials() {
            const credentials = <?php echo json_encode($importResults['credentials'] ?? []); ?>;
            if (credentials.length === 0) {
                showToast('No credentials to download.', 'error', 5000);
                return;
            }

            let content = '='.repeat(80) + '\n';
            content += 'EMPLOYEE CREDENTIALS - FOR TESTING PURPOSES ONLY\n';
            content += '='.repeat(80) + '\n\n';
            content += 'IMPORTANT: In production, these credentials should be sent via email.\n';
            content += 'This file is generated for testing/demonstration purposes.\n\n';
            content += 'Generated on: ' + new Date().toLocaleString() + '\n';
            content += 'Total Employees: ' + credentials.length + '\n\n';
            content += '='.repeat(80) + '\n\n';

            credentials.forEach((cred, index) => {
                content += `Employee #${index + 1}\n`;
                content += '-'.repeat(80) + '\n';
                content += `ID:       ${cred.employee_id}\n`;
                content += `Name:     ${cred.name}\n`;
                content += `Email:    ${cred.email}\n`;
                content += `Password: ${cred.password}\n`;
                content += `Role:     ${cred.role}\n\n`;
            });

            content += '='.repeat(80) + '\n';
            content += 'END OF CREDENTIALS\n';
            content += '='.repeat(80) + '\n';

            const blob = new Blob([content], { type: 'text/plain' });
            const url = window.URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url;
            a.download = 'employee_credentials_' + new Date().getTime() + '.txt';
            document.body.appendChild(a);
            a.click();
            document.body.removeChild(a);
            window.URL.revokeObjectURL(url);

            showToast('Credentials downloaded successfully!', 'success', 5000);
        }

        // Show modal if credentials exist
        <?php if (!empty($importResults['credentials'])): ?>
            document.addEventListener('DOMContentLoaded', function() {
                showCredentialsModal();
            });
        <?php endif; ?>

        // Close modal on outside click
        document.getElementById('credentials-modal')?.addEventListener('click', function(e) {
            if (e.target === this) {
                closeCredentialsModal();
            }
        });
    </script>
</body>
</html>