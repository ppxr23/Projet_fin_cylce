<?php
// src/Controller/ApiController.php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Feedback;
use App\Repository\UserRepository;
use App\Repository\AbsenceRepository;
use App\Repository\SanctionRepository;
use App\Repository\RetardRepository;
use App\Repository\VigieRepository;
use App\Repository\NoteRepository;
use App\Repository\FeedbackRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\HttpFoundation\Request;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ApiController extends AbstractController
{
    #[Route('/api/deconnexion', name: 'api_deconnexion', methods: ['POST'])]
    public function deconnexion(UserInterface $user, EntityManagerInterface $em): JsonResponse
    {
        if (!$user instanceof User) {
            return $this->json(['error' => 'Utilisateur invalide'], Response::HTTP_BAD_REQUEST);
        }

        $now = new \DateTimeImmutable('now', new \DateTimeZone('Indian/Antananarivo'));
        $user->setLastConnexion($now);

        $em->persist($user);
        $em->flush();

        return $this->json([
            'message' => 'Déconnexion réussie',
            'last_connexion' => $user->getLastConnexion()?->format('Y-m-d H:i:s'),
        ], Response::HTTP_OK);
    }

    #[Route('/api/list_users', name: 'api_list_users', methods: ['GET'])]
    public function list_users(UserRepository $userRepository): JsonResponse
    {
        $users = $userRepository->findAll();
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
    public function addUser(
        Request $request,
        EntityManagerInterface $em,
        UserPasswordHasherInterface $passwordHasher
    ): JsonResponse {
        $data = json_decode($request->getContent(), true);

        if (!is_array($data)) {
            return $this->json(['error' => 'Données invalides'], Response::HTTP_BAD_REQUEST);
        }

        $user = new User();
        $now = new \DateTimeImmutable('now', new \DateTimeZone('Indian/Antananarivo'));
        $hashedPassword = $passwordHasher->hashPassword($user, $data['password'] ?? 'vivetic');

        $user->setEmail($data['email'] ?? '');
        $user->setRoles($data['roles'] ?? []);
        $user->setName($data['name'] ?? '');
        $user->setFirstname($data['firstname'] ?? '');
        $user->setPassword($hashedPassword);
        $user->setStatut($data['statut'] ?? 0);
        $user->setDateCreation($now);
        $user->setLastConnexion($now);
        $user->setMatricule($data['matricule'] ?? '');
        $user->setVigie(0);

        $em->persist($user);
        $em->flush();

        return $this->json(['message' => 'Utilisateur ajouté avec succès'], Response::HTTP_CREATED);
    }

    #[Route('/api/update_user/{id}', name: 'api_update_user', methods: ['PUT','PATCH'])]
    public function updateUser(
        int $id,
        Request $request,
        EntityManagerInterface $em,
        UserPasswordHasherInterface $passwordHasher
    ): JsonResponse {
        $user = $em->getRepository(User::class)->find($id);
        if (!$user) {
            return $this->json(['error' => 'Utilisateur introuvable'], Response::HTTP_NOT_FOUND);
        }

        $data = json_decode($request->getContent(), true);
        if (!is_array($data)) {
            return $this->json(['error' => 'Données invalides'], Response::HTTP_BAD_REQUEST);
        }

        if (isset($data['email'])) $user->setEmail($data['email']);
        if (isset($data['roles'])) $user->setRoles($data['roles']);
        if (isset($data['name'])) $user->setName($data['name']);
        if (isset($data['firstname'])) $user->setFirstname($data['firstname']);
        if (isset($data['statut'])) $user->setStatut($data['statut']);
        if (isset($data['matricule'])) $user->setMatricule($data['matricule']);
        if (isset($data['password'])) {
            $user->setPassword($passwordHasher->hashPassword($user, $data['password']));
        }

        $em->flush();

        return $this->json(['message' => 'Utilisateur mis à jour avec succès'], Response::HTTP_OK);
    }

    #[Route('/api/add_feedback', name: 'api_add_feedback', methods: ['POST'])]
    public function addFeedback(Request $request, EntityManagerInterface $em): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        if (!is_array($data)) {
            return $this->json(['error' => 'Données invalides'], Response::HTTP_BAD_REQUEST);
        }

        $feedback = new Feedback();
        $now = new \DateTimeImmutable('now', new \DateTimeZone('Indian/Antananarivo'));

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

    #[Route('/api/list_user_rh', name: 'api_list_user_rh', methods: ['POST'])]
    public function list_user_rh(UserRepository $userRepository, Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        if (!is_array($data)) {
            return $this->json(['error' => 'Données invalides'], Response::HTTP_BAD_REQUEST);
        }

        $users = $userRepository->get_all_user_actif($data['matricule'], $data['roles'], $data['all'] ?? false);
        return $this->json($users, Response::HTTP_OK);
    }

    #[Route('/api/count_user_rh', name: 'api_count_user_rh', methods: ['POST'])]
    public function count_user_rh(UserRepository $userRepository, Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        if (!is_array($data)) return $this->json(['error' => 'Données invalides'], Response::HTTP_BAD_REQUEST);

        $count = count($userRepository->get_all_user_actif($data['matricule'], $data['roles'], $data['all'] ?? false));
        return $this->json($count, Response::HTTP_OK);
    }

    #[Route('/api/down', name: 'api_down')]
    public function downloadExcel(NoteRepository $noteRepository, Request $request): StreamedResponse
    {
        $data = json_decode($request->getContent(), true, 512, JSON_THROW_ON_ERROR);

        $typeRapport = $data['params']['typeRapport'] ?? 1;
        $roles = $data['roles'] ?? '';
        $matricule = $data['matricule'] ?? '';

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        if ($typeRapport == 1) {
            $notes = $noteRepository->get_all_notes();
            $sheet->setTitle('Performance par équipe');
            $sheet->setCellValue('A1', 'Equipe');
            $sheet->setCellValue('B1', 'Note');

            $row = 2;
            foreach ($notes as $item) {
                $sheet->setCellValue('A'.$row, $item['name']);
                $sheet->setCellValue('B'.$row, $item['moyenne']);
                $row++;
            }

            foreach (range('A', 'B') as $col) $sheet->getColumnDimension($col)->setAutoSize(true);
        } else {
            $notes = $roles === 'RH' ? $noteRepository->get_all_notes_pers() : $noteRepository->get_all_notes_team($matricule, $roles);

            $sheet->setTitle('Performance par collaborateur');
            $sheet->setCellValue('A1', 'Matricule');
            $sheet->setCellValue('B1', 'Nom');
            $sheet->setCellValue('C1', 'Prénom');
            $sheet->setCellValue('D1', 'Note');

            $row = 2;
            foreach ($notes as $item) {
                $sheet->setCellValue('A'.$row, $item['matricule']);
                $sheet->setCellValue('B'.$row, $item['name']);
                $sheet->setCellValue('C'.$row, $item['firstname']);
                $sheet->setCellValue('D'.$row, $item['moyenne']);
                $row++;
            }

            foreach (range('A', 'D') as $col) $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        $writer = new Xlsx($spreadsheet);

        $response = new StreamedResponse(function() use ($writer) {
            $writer->save('php://output');
        });

        $response->headers->set('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        $response->headers->set('Content-Disposition', 'attachment; filename="export.xlsx"');
        $response->headers->set('Cache-Control', 'max-age=0');

        return $response;
    }

    #[Route('/api/all_anomalie', name: 'api_all_anomalie', methods: ['POST'])]
    public function all_anomalie(UserRepository $userRepository, Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        if (!is_array($data)) return $this->json(['error' => 'Données invalides'], Response::HTTP_BAD_REQUEST);

        $all = $userRepository->get_anomalie($data['matricule'], $data['roles']);
        return $this->json($all, Response::HTTP_OK);
    }

    #[Route('/api/all_feedback', name: 'api_all_feedback', methods: ['POST'])]
    public function all_feedback(FeedbackRepository $feedbackRepository, Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        if (!is_array($data)) return $this->json(['error' => 'Données invalides'], Response::HTTP_BAD_REQUEST);

        $all = $feedbackRepository->get_feedback($data['matricule'], $data['roles']);
        return $this->json($all, Response::HTTP_OK);
    }
}
