<?php

declare(strict_types=1);

namespace App\Controller\API;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Request\ParamFetcher;
use JMS\Serializer\SerializerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * Class UserController
 *
 * @Rest\Route("/api/v1")
 */
class UserController extends AbstractFOSRestController
{
    /** @var EntityManagerInterface */
    private $entityManager;
    /** @var UserRepository */
    private $userRepository;
    /** @var SerializerInterface */
    private $serializer;
    /** @var ValidatorInterface */
    private $validator;

    /**
     * UserController constructor.
     * @param EntityManagerInterface $entityManager
     * @param UserRepository $userRepository
     * @param SerializerInterface $serializer
     * @param ValidatorInterface $validator
     */
    public function __construct(
        EntityManagerInterface $entityManager,
        UserRepository $userRepository,
        SerializerInterface $serializer,
        ValidatorInterface $validator)
    {
        $this->entityManager = $entityManager;
        $this->userRepository = $userRepository;
        $this->serializer = $serializer;
        $this->validator = $validator;
    }

    /**
     * @Rest\Get("/users/{id}", name="get_item_user")
     *
     * @param $id
     * @return Response
     */
    public function getUsers($id): Response
    {
        $user = $this->userRepository->findOneBy(['id' => $id]);

        if (null === $user) {
            throw new NotFoundHttpException('User Not Found');
        }

        $view = $this->view($user, Response::HTTP_OK);

        return $this->handleView($view);
    }

    /**
     * @Rest\Get("/users", name="get_collection_user")
     * @Rest\QueryParam(
     *     name="role",
     *     requirements="[a-zA-Z0-9_]*",
     *     nullable=true,
     *     description="The Role to search for.")
     * @param ParamFetcher $paramFetcher
     * @return Response
     */
    public function GetCollectionUsers(ParamFetcher $paramFetcher): Response
    {
        $role = $paramFetcher->get('role');

        if (null == $role) {
            $users = $this->userRepository->findAll();
        } else {
            $users = $this->userRepository->findByrole($role);
        }

        if (null == $users) {
            throw new NotFoundHttpException('No Users Matching That Search Criteria');
        }

        $view = $this->view($users, Response::HTTP_OK);

        return $this->handleView($view);
    }

    /**
     * @Rest\Post("/users", name="post_item_user")
     * @ParamConverter("user", converter="fos_rest.request_body")
     * @param User $user
     * @return Response
     */
    public function postUsers(User $user): Response
    {
        $constraintViolationList = $this->validator->validate($user);

        if (count($constraintViolationList) > 0) {
            $view = $this->view($constraintViolationList, Response::HTTP_BAD_REQUEST);
        } else {
            $this->entityManager->persist($user);
            $this->entityManager->flush();

            $view = $this->view(null, Response::HTTP_CREATED);
        }

        return $this->handleView($view);
    }

    /**
     * @Rest\Patch("/users/{email}/promote", name="put_item_user")
     * @param $email
     * @return Response
     */
    public function promoteUser($email): Response
    {
        $user = $this->userRepository->findOneBy(['email' => $email]);

        if (null == $user) {
            throw new NotFoundHttpException('No Users Matching That Search Criteria');
        }

        $user->setRoles(['ROLE_CLIENT', 'ROLE_PRO']);
        $this->entityManager->flush();
        $view = $this->view(null, Response::HTTP_NO_CONTENT);

        return $this->handleView($view);
    }

    /**
     * @Rest\Delete("/users/{id}", name="delete_item_user")
     *
     * @param $id
     * @return Response
     */
    public function deleteUser($id): Response
    {
        $user = $this->userRepository->findOneBy(['id' => $id]);

        if (null === $user) {
            throw new NotFoundHttpException('User Not Found');
        }

        $this->entityManager->remove($user);
        $this->entityManager->flush();

        $view = $this->view(null, Response::HTTP_NO_CONTENT);

        return $this->handleView($view);
    }
}
