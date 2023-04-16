<?php

namespace App\Entity;

use Symfony\Component\Security\Core\User\UserInterface;

class User implements UserInterface
{

	private $id;

	private $username;

	private $email;

	private $roles = [];

	private $role;

	private $jwt;

	private $profilePicture;

	/**
	 * @var string The hashed password
	 */
	private $password;

	public function __construct(int $id, string $username, string $email, string $jwt, string $role, ?string $profile_picture)
	{
		$this->id = $id;
		$this->username = $username;
		$this->email = $email;
		$this->jwt = $jwt;
		$this->role = $role;
		$this->profilePicture = $profile_picture;
	}

	public function getRole(): ?string
	{
		return $this->role;
	}

	public function getId(): ?int
	{
		return $this->id;
	}

	public function getProfilePicture(): ?string
	{
		return $this->profilePicture;
	}

	public function setProfilePicture(string $profile_picture): self
	{
		$this->profilePicture = $profile_picture;

		return $this;
	}

	public function getJwt(): ?string
	{
		return $this->jwt;
	}

	public function getEmail(): ?string
	{
		return $this->email;
	}

	public function setEmail(string $email): self
	{
		$this->email = $email;

		return $this;
	}

	public function getUsername()
	{
		return $this->username;
	}

	public function setUsername($username): self
	{
		$this->username = $username;

		return $this;
	}

	public function getSalt()
	{
		return 'coucou';
	}

	/**
	 * A visual identifier that represents this user.
	 *
	 * @see UserInterface
	 */
	public function getUserIdentifier(): string
	{
		return (string) $this->email;
	}

	/**
	 * @see UserInterface
	 */
	public function getRoles(): array
	{
		$roles = $this->roles;
		// guarantee every user at least has ROLE_USER
		$roles[] = 'ROLE_USER';

		return array_unique($roles);
	}

	public function setRoles(array $roles): self
	{
		$this->roles = $roles;

		return $this;
	}

	/**
	 * @see PasswordAuthenticatedUserInterface
	 */
	public function getPassword(): string
	{
		return $this->password;
	}

	public function setPassword(string $password): self
	{
		$this->password = $password;

		return $this;
	}

	/**
	 * @see UserInterface
	 */
	public function eraseCredentials()
	{
		// If you store any temporary, sensitive data on the user, clear it here
		// $this->plainPassword = null;
	}

	public function apiRequest(): array
	{
		return [];
	}
}
