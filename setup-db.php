<?php
/**
 * Butter Property - Database Installer & phpMyAdmin Helper
 * butterproperty.com
 */

require_once __DIR__ . '/backend/config/config.php';

$action = $_POST['action'] ?? $_GET['action'] ?? '';
$message = '';
$messageType = 'info';

// Read configuration from POST or defaults
$dbHost = $_POST['db_host'] ?? DB_HOST;
$dbPort = $_POST['db_port'] ?? DB_PORT;
$dbName = $_POST['db_name'] ?? DB_NAME;
$dbUser = $_POST['db_user'] ?? DB_USER;
$dbPass = $_POST['db_pass'] ?? DB_PASS;

$connectionStatus = 'disconnected';
$connectionError = '';
$tableStats = [];

// Test basic connection to MySQL server
try {
    $dsnTest = "mysql:host={$dbHost};port={$dbPort};charset=utf8mb4";
    $pdoTest = new PDO($dsnTest, $dbUser, $dbPass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_TIMEOUT => 2
    ]);
    $connectionStatus = 'connected';

    // Check if database exists
    $stmt = $pdoTest->query("SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME = '{$dbName}'");
    $dbExists = (bool)$stmt->fetch();

    if ($dbExists) {
        $pdoTest->query("USE `{$dbName}`");
        // Count properties
        try {
            $stmt = $pdoTest->query("SELECT COUNT(*) FROM `properties`");
            $tableStats['properties'] = (int)$stmt->fetchColumn();
        } catch (Throwable $e) {
            $tableStats['properties'] = 'Not created';
        }

        // Count leads
        try {
            $stmt = $pdoTest->query("SELECT COUNT(*) FROM `leads`");
            $tableStats['leads'] = (int)$stmt->fetchColumn();
        } catch (Throwable $e) {
            $tableStats['leads'] = 'Not created';
        }
    }
} catch (Throwable $e) {
    $connectionStatus = 'error';
    $connectionError = $e->getMessage();
}

// Handle Auto-Install Action
if ($action === 'install' && $connectionStatus === 'connected') {
    try {
        $sqlScript = file_get_contents(__DIR__ . '/butter_property.sql');
        if (empty($sqlScript)) {
            throw new Exception("butter_property.sql not found or empty.");
        }

        // Execute batch SQL
        $pdoTest->exec($sqlScript);
        $message = "Database '{$dbName}' successfully created and seeded with 6 luxury portfolio properties and initial CRM leads!";
        $messageType = 'success';

        // Refresh stats
        $pdoTest->query("USE `{$dbName}`");
        $tableStats['properties'] = (int)$pdoTest->query("SELECT COUNT(*) FROM `properties`")->fetchColumn();
        $tableStats['leads'] = (int)$pdoTest->query("SELECT COUNT(*) FROM `leads`")->fetchColumn();
    } catch (Throwable $e) {
        $message = "Installation error: " . $e->getMessage();
        $messageType = 'error';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Database Setup & phpMyAdmin Integration | Butter Property</title>
  <link rel="icon" type="image/png" href="./BUTTER-PROPERTY-LO-FF.png">
  <script src="https://cdn.tailwindcss.com"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,500;0,700;1,400&family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <style>
    body {
      font-family: 'Plus Jakarta Sans', sans-serif;
      background-color: #0F0914;
      color: #FAFAF9;
    }
    .font-serif-luxury {
      font-family: 'Playfair Display', Georgia, serif;
    }
  </style>
</head>
<body class="min-h-screen py-10 px-4 sm:px-6 lg:px-8 flex flex-col justify-between">

  <div class="max-w-4xl mx-auto w-full space-y-8">
    
    <!-- Header -->
    <div class="text-center space-y-3">
      <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-[#7323DC]/20 border border-[#7323DC]/40 text-[#FDDB00] text-xs font-bold uppercase tracking-wider">
        <i class="fa-solid fa-database"></i>
        <span>MySQL &amp; phpMyAdmin Setup Center</span>
      </div>
      <h1 class="text-3xl sm:text-4xl font-bold font-serif-luxury tracking-tight text-white">
        Butter Property Database Configuration
      </h1>
      <p class="text-stone-400 text-xs sm:text-sm max-w-xl mx-auto">
        Configure and seed the <code class="text-[#FDDB00]">butter_property</code> database in phpMyAdmin for full relational inventory management and investor deal-flow CRM.
      </p>
    </div>

    <!-- Alert Banner -->
    <?php if (!empty($message)): ?>
    <div class="p-4 rounded-lg <?= $messageType === 'success' ? 'bg-emerald-950/80 border border-emerald-500/50 text-emerald-200' : 'bg-red-950/80 border border-red-500/50 text-red-200' ?> text-xs flex items-center gap-3">
      <i class="fa-solid <?= $messageType === 'success' ? 'fa-circle-check text-emerald-400' : 'fa-circle-exclamation text-red-400' ?> text-lg shrink-0"></i>
      <span class="leading-relaxed"><?= htmlspecialchars($message) ?></span>
    </div>
    <?php endif; ?>

    <!-- Two-Path Choice Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

      <!-- OPTION 1: Import via phpMyAdmin (Manual) -->
      <div class="bg-[#1D0838]/80 border border-purple-800/40 rounded-xl p-6 sm:p-7 space-y-5 flex flex-col justify-between shadow-xl">
        <div class="space-y-4">
          <div class="flex items-center justify-between">
            <span class="w-8 h-8 rounded-lg bg-[#FDDB00] text-stone-950 flex items-center justify-center font-bold text-sm">
              1
            </span>
            <span class="text-[10px] uppercase font-bold tracking-wider px-2.5 py-1 rounded bg-stone-800 text-stone-300">
              phpMyAdmin UI
            </span>
          </div>

          <h3 class="text-xl font-bold text-white font-serif-luxury">
            Import into phpMyAdmin
          </h3>

          <p class="text-xs text-stone-300 leading-relaxed">
            Follow these 3 easy steps to import the database using your existing phpMyAdmin web panel:
          </p>

          <ol class="text-xs text-stone-300 space-y-2.5 list-decimal list-inside leading-relaxed bg-black/40 p-4 rounded-lg border border-white/5">
            <li>Open <a href="http://localhost/phpmyadmin" target="_blank" class="text-[#FDDB00] underline font-semibold">http://localhost/phpmyadmin</a> in your browser.</li>
            <li>Click the <strong>"SQL"</strong> tab at the top.</li>
            <li>Paste the contents of <code class="text-[#FDDB00]">butter_property.sql</code> or click the <strong>"Import"</strong> tab and choose the file.</li>
            <li>Click <strong>"Go"</strong> to execute and create all tables.</li>
          </ol>
        </div>

        <div class="pt-4 border-t border-purple-900/50 flex flex-col gap-2.5">
          <a 
            href="./butter_property.sql" 
            download="butter_property.sql"
            class="w-full py-3 bg-[#FDDB00] hover:bg-[#FFE524] text-stone-950 font-bold text-xs uppercase tracking-wider rounded text-center transition-all flex items-center justify-center gap-2"
          >
            <i class="fa-solid fa-file-arrow-down text-sm"></i>
            <span>Download butter_property.sql</span>
          </a>

          <a 
            href="http://localhost/phpmyadmin" 
            target="_blank" 
            rel="noopener noreferrer"
            class="w-full py-2.5 bg-stone-800 hover:bg-stone-700 text-white font-bold text-xs uppercase tracking-wider rounded text-center transition-all flex items-center justify-center gap-2 border border-stone-700"
          >
            <i class="fa-solid fa-arrow-up-right-from-square text-xs"></i>
            <span>Open phpMyAdmin Panel</span>
          </a>
        </div>
      </div>

      <!-- OPTION 2: 1-Click Browser Auto-Install -->
      <div class="bg-[#1D0838]/80 border border-purple-800/40 rounded-xl p-6 sm:p-7 space-y-5 flex flex-col justify-between shadow-xl">
        <div class="space-y-4">
          <div class="flex items-center justify-between">
            <span class="w-8 h-8 rounded-lg bg-[#7323DC] text-white flex items-center justify-center font-bold text-sm">
              2
            </span>
            <span class="text-[10px] uppercase font-bold tracking-wider px-2.5 py-1 rounded <?= $connectionStatus === 'connected' ? 'bg-emerald-900/80 text-emerald-300' : 'bg-red-900/80 text-red-300' ?>">
              <?= $connectionStatus === 'connected' ? '● MySQL Connected' : '○ Connection Needed' ?>
            </span>
          </div>

          <h3 class="text-xl font-bold text-white font-serif-luxury">
            1-Click Direct Auto-Seed
          </h3>

          <p class="text-xs text-stone-300 leading-relaxed">
            Run the automated installation script directly from this page to create the database and seed the tables.
          </p>

          <form action="setup-db.php" method="POST" class="space-y-3 bg-black/40 p-4 rounded-lg border border-white/5 text-xs">
            <input type="hidden" name="action" value="install">
            
            <div class="grid grid-cols-2 gap-2.5">
              <div>
                <label class="block text-stone-400 text-[10px] uppercase font-semibold mb-1">Host</label>
                <input type="text" name="db_host" value="<?= htmlspecialchars($dbHost) ?>" class="w-full px-2.5 py-1.5 rounded bg-stone-900 border border-stone-700 text-white text-xs">
              </div>
              <div>
                <label class="block text-stone-400 text-[10px] uppercase font-semibold mb-1">Port</label>
                <input type="text" name="db_port" value="<?= htmlspecialchars($dbPort) ?>" class="w-full px-2.5 py-1.5 rounded bg-stone-900 border border-stone-700 text-white text-xs">
              </div>
            </div>

            <div class="grid grid-cols-2 gap-2.5">
              <div>
                <label class="block text-stone-400 text-[10px] uppercase font-semibold mb-1">User</label>
                <input type="text" name="db_user" value="<?= htmlspecialchars($dbUser) ?>" class="w-full px-2.5 py-1.5 rounded bg-stone-900 border border-stone-700 text-white text-xs">
              </div>
              <div>
                <label class="block text-stone-400 text-[10px] uppercase font-semibold mb-1">Password</label>
                <input type="password" name="db_pass" value="<?= htmlspecialchars($dbPass) ?>" placeholder="root or empty" class="w-full px-2.5 py-1.5 rounded bg-stone-900 border border-stone-700 text-white text-xs">
              </div>
            </div>

            <div>
              <label class="block text-stone-400 text-[10px] uppercase font-semibold mb-1">Database Name</label>
              <input type="text" name="db_name" value="<?= htmlspecialchars($dbName) ?>" class="w-full px-2.5 py-1.5 rounded bg-stone-900 border border-stone-700 text-white text-xs">
            </div>

            <?php if ($connectionStatus === 'error'): ?>
            <div class="p-2.5 rounded bg-red-950/60 border border-red-900 text-[11px] text-red-300">
              <strong>MySQL Note:</strong> <?= htmlspecialchars($connectionError) ?><br>
              <span class="text-stone-400">Make sure MySQL/MariaDB or XAMPP/MAMP is running. Alternatively, use Option 1 in phpMyAdmin.</span>
            </div>
            <?php endif; ?>

            <button 
              type="submit" 
              class="w-full py-3 bg-[#7323DC] hover:bg-[#5E1BB8] text-white font-bold text-xs uppercase tracking-wider rounded transition-all flex items-center justify-center gap-2 shadow-lg"
            >
              <i class="fa-solid fa-bolt text-[#FDDB00]"></i>
              <span>Execute 1-Click Database Setup</span>
            </button>
          </form>
        </div>

        <!-- Live Database Stats -->
        <?php if (!empty($tableStats)): ?>
        <div class="pt-4 border-t border-purple-900/50 flex justify-between items-center text-xs">
          <span class="text-stone-400">Live Database Status:</span>
          <span class="font-bold text-emerald-400">
            <?= $tableStats['properties'] ?> Properties • <?= $tableStats['leads'] ?> Leads
          </span>
        </div>
        <?php endif; ?>
      </div>

    </div>

    <!-- Quick Navigation Footer -->
    <div class="flex flex-wrap items-center justify-between gap-4 pt-6 border-t border-stone-800 text-xs text-stone-400">
      <div class="flex items-center gap-2">
        <span>Butter Property Advisory</span>
        <span>•</span>
        <span class="text-stone-500">butterproperty.com</span>
      </div>

      <div class="flex items-center gap-4">
        <a href="property.php?id=dxb-01" class="text-[#FDDB00] hover:underline flex items-center gap-1 font-semibold">
          <span>View Dynamic Property Page (PHP)</span>
          <span>&rarr;</span>
        </a>
        <span class="text-stone-700">•</span>
        <a href="index.html" class="hover:text-white">Home</a>
        <a href="admin.html" class="hover:text-white">Admin Portal</a>
      </div>
    </div>

  </div>

</body>
</html>

