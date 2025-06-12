<?php
include 'stores/profile.php';

class UsersService
{
	public static function updateProfile(array $data): void {
		$age = self::calculateAge($data['date_of_birth']);

		if (strlen(trim($data['first_name'])) == 0) {
			throw new Exception('Поле з імʼям не заповнено');
		}

		if (strlen(trim($data['last_name'])) == 0) {
			throw new Exception('Поле з прізвищем не заповнено');
		}

		if (strlen(trim($data['bio'])) == 0) {
			throw new Exception('Поле з прізвищем не заповнено');
		}

		if ($age < 16) {
			throw new Exception('Користувач занадто юний');
		}

		global $profile;

		$_SESSION['user'] = [
			'first_name' => $data['first_name'],
			'last_name' => $data['last_name'],
			'date_of_birth' => $data['date_of_birth'],
			'bio' => $data['bio'],
		];
	}

	private static function calculateAge(string $date): int {
		$birthDate = new DateTime($date);
		$today = new DateTime();

		return $today->diff($birthDate)->y;
	}
}