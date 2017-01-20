<?php
require('../dbconnect.php');

session_start();

$error = array();
if (!empty($_POST)) {
	// エラー項目の確認
	if ($_POST['name'] == '') {
		$error['name'] = 'blank';
	}
	if ($_POST['email'] == '') {
		$error['email'] = 'blank';
	}
	if (strlen($_POST['password']) < 4) {
		$error['password'] = 'length';
	}
	if ($_POST['password'] == '') {
		$error['password'] = 'blank';
	}
	$fileName = $_FILES['image']['name'];
	if (!empty($fileName)) {
		$ext = substr($fileName, -3);
		if ($ext != 'jpg' && $ext != 'gif') {
			$error['image'] = 'type';
		}
	}

	// 重複アカウントのチェック
	if (empty($error)) {
		$sql = sprintf('SELECT COUNT(*) AS cnt FROM members WHERE email="%s"',
			mysqli_real_escape_string($pdo, $_POST['email'])
		);
		$record = mysqli_query($pdo, $sql) or die(mysqli_error($pdo));
		$table = mysqli_fetch_assoc($record);
		//mysqli_fetch_assoc
		if ($table['cnt'] > 0) {
			$error['email'] = 'duplicate';
		}
	}

	if (empty($error)) {
	// 画像をアップロードする　
	$image = date('YmdHis') . $_FILES['image']['name'];
	move_uploaded_file($_FILES['image']['tmp_name'], '../member_picture/' . $image);

	$_SESSION['join'] = $_POST;
	$_SESSION['join']['image'] = $image;
		header('Location: check.php');
		exit();
	}
}

// 書き直し
if (isset($_REQUEST['action']) and $_REQUEST['action'] == 'rewrite') {
// if ($_REQUEST['action'] == 'rewrite') {
	$_POST = $_SESSION['join'];
	$error['rewrite'] = true;
}
?>

<!DOCTYPE html>
<html lang="ja">
	<head>
		<meta charset="UTF-8">
		<title>新規登録</title>
	</head>

	<body>
		<div id="wrap">
			<div id="head">
				<h1>会員登録</h1>
		</div>

	<div id="content">
		<p>次のフォームに必要事項をご記入ください。</p>
		<form action="" method="post" enctype="multipart/form-data">
		<dl>

			<dt>ニックネーム<span class="required">　必須</span></dt>
			<dd>
				<?php if (isset($error['name']) && $error['name'] == 'blank'): ?>
					<input type="text" name="name" size="35" maxlength="255"
				 		value="<?php echo htmlspecialchars($_POST['name'], ENT_QUOTES, 'UTF-8'); ?>" />
					<p class="error">* ニックネームを入力してください</p>
				<?php else: ?>
					<input type="text" name="name" size="35" maxlength="255" value="" />
				<?php endif; ?>
			</dd>

			<dt>メールアドレス<span class="required">必須</span></dt>
			<dd>
				<?php if (isset($error['email']) && $error['email'] == 'blank'): ?>
				<input type="text" name="email" size="35" maxlength="255" value="<?php echo htmlspecialchars($_POST['email'], ENT_QUOTES, 'UTF-8'); ?>" />
				<p class="error">* メールアドレスを入力してください</p>
			<?php else: ?>
				<input type="text" name="email" size="35" maxlength="255" value="">
				<?php endif; ?>
				<?php if (isset($error['email']) && $error['email'] == 'duplicate'): ?>
				<p class="error">* 指定されたメールアドレスはすでに登録されています</p>
				<?php endif; ?>
			</dd>

			<dt>パスワード<span class="required">必須</span></dt>
			<dd>
				<?php if (isset($error['password']) && $error['password'] == 'blank'): ?>
				<input type="password" name="password" size="10" maxlength="20" value="<?php echo htmlspecialchars($_POST['password'], ENT_QUOTES, 'UTF-8'); ?>" />
				<p class="error">* パスワードを入力してください</p>
			<?php else: ?>
				<input type="password" name="password" size="10" maxlength="20" value="">
				<?php endif; ?>
				<?php if (isset($error['password']) && $error['password'] == 'length'): ?>
				<p class="error">* パスワードは4文字以上で入力してください</p>
				<?php endif; ?>
			</dd>

			<dt>写真など</dt>
			<dd>
				<?php if (isset($error['image']) && $error['image'] == 'blank'): ?>
				<input type="file" name="image" size="35" value="test" />
				<p class="error">* 写真などは「.gif」または「.jpg」の画像を指定してください</p>
			<?php else: ?>
				<input type="file" name="image" size="35" value="">
				<?php endif; ?>
				<?php if (!empty($error)): ?>
				<p class="error">* 恐れ入りますが、画像を改めて指定してください</p>
				<?php endif; ?>
			</dd>
		</dl>
		<div><input type="submit" value="入力内容を確認する" /></div>
	</form>
	</div>

	</div>
	</body>
</html>
