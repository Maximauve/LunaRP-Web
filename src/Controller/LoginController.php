<?php

namespace App\Controller;

use App\Entity\User;
use App\Service\UserApiService;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authenticator\Passport\SelfValidatingPassport;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;

// use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class LoginController extends AbstractController
{
	private string $apiUrl;
	private $userApi;

	public function __construct(UserApiService $userApiService, string $apiUrl)
	{
		$this->apiUrl = $apiUrl;
		$this->userApi = $userApiService;
	}

	#[Route('/login', name: 'app_login', methods: ['GET'])]
	public function index(): Response
	{

		// $error = $authenticationUtils->getLastAuthenticationError();
		// $lastUsername = $authenticationUtils->getLastUsername();

		return $this->render('login/index.html.twig', [
			// 'error' => $error,
			'last_email' => "",
		]);
	}

	#[Route('/login', name: 'app_auth', methods: ['POST'])]
	public function APILogin(Request $request)
	{
		$email = $request->request->get('email');
		$password = $request->request->get('password');

		try {
			$data = $this->userApi->login($email, $password);
		} catch (\Exception $e) {
			$this->addFlash('error', $e->getMessage());
			return $this->render('login/index.html.twig', [
				'last_email' => $email,
			]);
		}

		// Store the JWT in local storage
		$username = $data['username'];
		$email = $data['email'];
		$jwt = $data['token'];
		$id = $data['id'];
		$role = $data['role'];
		if (isset($data["userId"]) && $data["userId"] != null) {
			$img = $this->apiUrl . "/local-files/" . $data["userId"];
		} else {
			$img = null;
		}

		$user = new User($id, $username, $email, $jwt, $role, $img);

		$request->getSession()->set('user', $user);

		// Redirect to homepage or another page
		return $this->redirectToRoute('app_home');
	}

	#[Route('/logout', name: 'app_logout', methods: ['GET'])]
	public function logout(Request $request)
	{
		$this->addFlash('success', 'Vous avez bien été déconnecté.');
		$request->getSession()->remove('user');
		return $this->redirectToRoute('app_login');
	}
}
