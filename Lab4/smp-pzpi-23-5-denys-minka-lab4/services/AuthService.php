<?php
include 'stores/credentials.php';
include 'stores/profile.php';

class AuthService
{
	public static function login(string $username, string $password): void
	{
		global $credentials;

		if ($credentials["username"] == $username && $credentials["password"] == $password) {
			$_SESSION['username'] = $username;
			$_SESSION['authorized_at'] = time();

			header("Location: " . "/");
		} else {
			throw new Exception("Імʼя користувача або пароль некоректні");
		}
	}

	public static function logout(): void
	{
		global $credentials;
		global $profile;

		unset($_SESSION['username']);
		unset($_SESSION['authorized_at']);

		header("Location: " . "/");
	}
}
