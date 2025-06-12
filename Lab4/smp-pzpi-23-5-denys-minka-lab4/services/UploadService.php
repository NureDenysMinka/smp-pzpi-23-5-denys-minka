<?php
require_once 'stores/profile.php';

class UploadService
{
	private static array $ALLOWED_EXTENSIONS = ['jpg', 'jpeg', 'png'];
	private static string $TARGET_DIR = './assets/';

	public static function uploadImage(): void {
		$imageSize = getimagesize($_FILES["avatar"]["tmp_name"]);

		if (!$imageSize) {
			throw new Exception("Файл не є зображенням");
		}

		if ($_FILES["avatar"]["size"] > 2 * 1024 * 1024) {
			throw new Exception("Файл занадто великий");
		}

		$targetFile = self::$TARGET_DIR . basename($_FILES["avatar"]["name"]);

		$imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

		if (!in_array($imageFileType, self::$ALLOWED_EXTENSIONS)) {
			throw new Exception("Дозволені лише розширення " . implode(', ', self::$ALLOWED_EXTENSIONS));
		}

		move_uploaded_file($_FILES["avatar"]["tmp_name"], $targetFile);

		global $profile;

		$_SESSION['avatar_path'] = $targetFile;
	}
}