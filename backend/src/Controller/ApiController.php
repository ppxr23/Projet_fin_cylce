<?php
// src/Controller/ApiController.php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Feedback;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\HttpFoundation\Response;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\HttpFoundation\Request;


class ApiController extends AbstractController
{
    private $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    #[Route('/api/deconnexion', name: 'api_deconnexion', methods: ['POST'])]
    public function deconnexion(UserInterface $user, EntityManagerInterface $em): JsonResponse
    {
        $newDate = new \DateTime('now', new \DateTimeZone('Indian/Antananarivo'));
        $user->setLastConnexion($newDate);

        $em->persist($user);
        $em->flush();

        return new JsonResponse([
            'message' => 'Déconnexion réussie',
            'last_connexion' => $user->getLastConnexion()->format('Y-m-d H:i:s')
        ], Response::HTTP_OK);
    }

    #[Route('/api/list_users', name: 'api_list_users', methods: ['GET'])]
    public function list_users(): JsonResponse
    {
        $users = $this->userRepository->findAll();

        $data = [];

        foreach ($users as $user) {
            $data[] = [
                'id' => $user->getId(),
                'email' => $user->getEmail(),
                'name' => $user->getName(),
                'firstname' => $user->getFirstname(),
                'roles' => $user->getRoles(),
                'matricule' => $user->getMatricule(),
                'statut' => $user->isActive(),
                'last_connexion' => $user->getLastConnexion()?->format('Y-m-d H:i:s'),
            ];
        }

        return $this->json($data, Response::HTTP_OK);
    }

    #[Route('/api/delete_user/{id}', name: 'api_delete_user', methods: ['DELETE'])]
    public function deleteUser(User $user, EntityManagerInterface $em): JsonResponse
    {
        $em->remove($user);
        $em->flush();

        return $this->json(['message' => 'Utilisateur supprimé'], Response::HTTP_OK);
    }

    #[Route('/api/add_user', name: 'api_add_user', methods: ['POST'])]
    public function addUser(Request $request, EntityManagerInterface $em, UserPasswordHasherInterface $passwordHasher): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $user = new User();
        $newDate = new \DateTime('now', new \DateTimeZone('Indian/Antananarivo'));
        $hashedPassword = $passwordHasher->hashPassword($user, $data['password'] ?? 'vivetic');
        
        $user->setEmail($data['email'] ?? '');
        $user->setRoles([$data['roles']] ?? '');
        $user->setName($data['name'] ?? '');
        $user->setFirstname($data['firstname'] ?? '');
        $user->setPassword($hashedPassword);
        $user->setStatut($data['statut'] ?? '');
        $user->setDateCreation($newDate ?? '');
        $user->setLastConnexion($newDate ?? '');
        $user->setMatricule($data['matricule'] ?? '');

        $em->persist($user);
        $em->flush();

        return $this->json(['message' => 'Utilisateur ajouté avec succès'], Response::HTTP_CREATED);
    }

    #[Route('/api/update_user/{id}', name: 'api_update_user', methods: ['PUT', 'PATCH'])]
    public function updateUser( int $id, Request $request, EntityManagerInterface $em, UserPasswordHasherInterface $passwordHasher
    ): JsonResponse {
        $user = $em->getRepository(User::class)->find($id);

        if (!$user) {
            return $this->json(['message' => 'Utilisateur introuvable'], Response::HTTP_NOT_FOUND);
        }

        $data = json_decode($request->getContent(), true);

        if (isset($data['email'])) {
            $user->setEmail($data['email']);
        }

        if (isset($data['roles'])) {
            $user->setRoles([$data['roles']]);
        }

        if (isset($data['name'])) {
            $user->setName($data['name']);
        }

        if (isset($data['firstname'])) {
            $user->setFirstname($data['firstname']);
        }

        if (isset($data['statut'])) {
            $user->setStatut($data['statut']);
        }

        if (isset($data['matricule'])) {
            $user->setMatricule($data['matricule']);
        }

        $em->flush();

        return $this->json(['message' => 'Utilisateur mis à jour avec succès'], Response::HTTP_OK);
    }

    #[Route('/api/add_feedback', name: 'api_add_feedback', methods: ['POST'])]
    public function addFeedback(Request $request, EntityManagerInterface $em): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if (!$data) {
            return $this->json(['message' => 'Données invalides'], Response::HTTP_BAD_REQUEST);
        }

        $feedback = new Feedback();
        $now = new \DateTime('now', new \DateTimeZone('Indian/Antananarivo'));

        // Récupération des données envoyées depuis le frontend
        $feedback->setMatriculeConcerned($data['matricule_concerned'] ?? 0);
        $feedback->setMatriculeInsert($data['matricule_insert'] ?? 0);
        $feedback->setDateInserted($now);
        $feedback->setCritere1($data['critere_1'] ?? 0);
        $feedback->setCritere2($data['critere_2'] ?? 0);
        $feedback->setCritere3($data['critere_3'] ?? 0);
        $feedback->setCritere4($data['critere_4'] ?? 0);
        $feedback->setCritere5($data['critere_5'] ?? 0);
        $feedback->setCommentary($data['commentary'] ?? '');
        $feedback->setTypeFeedback($data['type_feedback'] ?? 0);

        $em->persist($feedback);
        $em->flush();

        return $this->json(['message' => 'Feedback ajouté avec succès'], Response::HTTP_CREATED);
    }

}
