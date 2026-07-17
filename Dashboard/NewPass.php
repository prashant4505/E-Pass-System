<?php
declare(strict_types=1);
require_once __DIR__ . '/../includes/bootstrap.php';

$admin = requireLogin();

$editId = isset($_GET['id']) ? (int) $_GET['id'] : 0;
$pass = null;
if ($editId) {
    $stmt = $pdo->prepare('SELECT * FROM tblpass WHERE ID = ?');
    $stmt->execute([$editId]);
    $pass = $stmt->fetch();
    if (!$pass) {
        flash('error', 'That pass could not be found.');
        redirect('Passes.php');
    }
}

$errors = [];
$form = $pass ?: [
    'Name' => '', 'Mobile' => '', 'email' => '', 'IdentityType' => '', 'IdentityCardNo' => '',
    'Category' => '', 'FromDate' => '', 'ToDate' => '',
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrfCheck();

    $form = [
        'Name'           => trim((string) $_POST['name']),
        'Mobile'         => trim((string) $_POST['mobile']),
        'email'          => trim((string) $_POST['mail']),
        'IdentityType'   => trim((string) $_POST['identity']),
        'IdentityCardNo' => trim((string) $_POST['idnum']),
        'Category'       => trim((string) $_POST['category']),
        'FromDate'       => trim((string) $_POST['fromDate']),
        'ToDate'         => trim((string) $_POST['toDate']),
    ];
    $editId = (int) ($_POST['id'] ?? 0);

    if (in_array('', $form, true)) {
        $errors[] = 'All fields are required.';
    }
    if ($form['email'] !== '' && !filter_var($form['email'], FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Enter a valid email address.';
    }
    if (!preg_match('/^\d{7,15}$/', $form['Mobile'])) {
        $errors[] = 'Mobile number must be 7-15 digits.';
    }
    if (!isValidDate($form['FromDate']) || !isValidDate($form['ToDate'])) {
        $errors[] = 'Enter valid from/to dates.';
    } elseif ($form['ToDate'] < $form['FromDate']) {
        $errors[] = 'To Date cannot be before From Date.';
    }

    if (!$errors) {
        if ($editId) {
            $stmt = $pdo->prepare(
                'UPDATE tblpass SET Name=?, Mobile=?, email=?, IdentityType=?, IdentityCardNo=?, Category=?, FromDate=?, ToDate=? WHERE ID=?'
            );
            $stmt->execute([
                $form['Name'], $form['Mobile'], $form['email'], $form['IdentityType'],
                $form['IdentityCardNo'], $form['Category'], $form['FromDate'], $form['ToDate'], $editId,
            ]);
            flash('success', 'Pass updated successfully.');
            redirect('Passes.php');
        }

        do {
            $passNumber = random_int(100000000, 999999999);
            $exists = $pdo->prepare('SELECT 1 FROM tblpass WHERE PassNumber = ?');
            $exists->execute([$passNumber]);
        } while ($exists->fetchColumn());

        $stmt = $pdo->prepare(
            'INSERT INTO tblpass (PassNumber, Name, Mobile, email, IdentityType, IdentityCardNo, Category, FromDate, ToDate, CreatedBy)
             VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)'
        );
        $stmt->execute([
            $passNumber, $form['Name'], $form['Mobile'], $form['email'], $form['IdentityType'],
            $form['IdentityCardNo'], $form['Category'], $form['FromDate'], $form['ToDate'], $admin['ID'],
        ]);
        flash('success', "Pass created successfully — pass number $passNumber.");
        redirect('Passes.php');
    }

    foreach ($errors as $error) {
        flash('error', $error);
    }
}

$categories = $pdo->query('SELECT CategoryName FROM tblcategory ORDER BY CategoryName')->fetchAll();

$pageTitle = $editId ? 'Edit Pass' : 'New Pass';
$activeNav = 'new-pass';
require __DIR__ . '/../includes/layout_start.php';
?>

<div class="page-center" style="min-height:auto; padding:0 0 24px;">
	<div class="card card-wide">
		<h2><?= $editId ? 'Edit Pass' : 'Pass Details' ?></h2>
		<form method="post" action="NewPass.php<?= $editId ? '?id=' . $editId : '' ?>">
			<?= csrfField() ?>
			<input type="hidden" name="id" value="<?= (int) $editId ?>">
			<div class="field">
				<label for="name">Full Name</label>
				<input type="text" id="name" name="name" value="<?= e($form['Name']) ?>" placeholder="Name" required>
			</div>
			<div class="field">
				<label for="mobile">Contact Number</label>
				<input type="tel" id="mobile" name="mobile" value="<?= e($form['Mobile']) ?>" placeholder="Mobile" required>
			</div>
			<div class="field">
				<label for="mail">Email Address</label>
				<input type="email" id="mail" name="mail" value="<?= e($form['email']) ?>" placeholder="abc@gmail.com" required>
			</div>
			<div class="field">
				<label for="identity">Identity Type</label>
				<select id="identity" name="identity" required>
					<option value="">Choose Identity Type</option>
					<?php foreach (['Voter Card','PAN Card','Adhar Card','Student Card','Driving License','Passport','Any Official Card','Any Other Govt Issued Doc'] as $opt): ?>
					<option value="<?= e($opt) ?>" <?= $form['IdentityType'] === $opt ? 'selected' : '' ?>><?= e($opt) ?></option>
					<?php endforeach; ?>
				</select>
			</div>
			<div class="field">
				<label for="idnum">Identity Card No.</label>
				<input type="text" id="idnum" name="idnum" value="<?= e($form['IdentityCardNo']) ?>" required>
			</div>
			<div class="field">
				<label for="category">Category</label>
				<select id="category" name="category" required>
					<option value="">Choose Category</option>
					<?php foreach ($categories as $row): ?>
					<option value="<?= e($row['CategoryName']) ?>" <?= $form['Category'] === $row['CategoryName'] ? 'selected' : '' ?>><?= e($row['CategoryName']) ?></option>
					<?php endforeach; ?>
				</select>
			</div>
			<div class="field">
				<label for="fromDate">From Date</label>
				<input type="date" id="fromDate" name="fromDate" value="<?= e($form['FromDate']) ?>" required>
			</div>
			<div class="field">
				<label for="toDate">To Date</label>
				<input type="date" id="toDate" name="toDate" value="<?= e($form['ToDate']) ?>" required>
			</div>
			<button type="submit" class="btn btn-primary btn-block"><?= $editId ? 'Save Changes' : 'Add Pass' ?></button>
		</form>
	</div>
</div>

<?php require __DIR__ . '/../includes/layout_end.php'; ?>
