<?php

declare(strict_types=1);

namespace App\Controller\API;

use App\Entity\Booking;
use App\Repository\StyleRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Request\ParamFetcher;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * Class BookingController
 *
 * @Rest\Route("/api/v1")
 */
class BookingController extends AbstractFOSRestController
{
    /** @var EntityManager */
    private $entityManager;
    /** @var UserRepository */
    private $userRepository;
    /** @var StyleRepository */
    private $styleRepository;
    /** @var ValidatorInterface */
    private $validator;

    /**
     * BookingController constructor.
     */
    public function __construct(
        EntityManagerInterface $entityManager,
        UserRepository $userRepository,
        StyleRepository $styleRepository,
        ValidatorInterface $validator)
    {
        $this->entityManager = $entityManager;
        $this->userRepository = $userRepository;
        $this->styleRepository = $styleRepository;
        $this->validator = $validator;
    }

    /**
     * @Rest\Post("/bookings")
     * @Rest\RequestParam(
     *     name="userID",
     *     requirements="\d+",
     *     nullable=false,
     * )
     * @Rest\RequestParam(
     *     name="styleID",
     *     requirements="\d+",
     *     nullable=false
     * )
     * @Rest\RequestParam(
     *     name="slot",
     *     nullable=false
     * )
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function postBooking(ParamFetcher $paramFetcher): Response
    {
        $user = $this->userRepository->findOneBy(['id' => $paramFetcher->get('userID')]);
        $style = $this->styleRepository->findOneBy(['id' => $paramFetcher->get('styleID')]);
        $slot = new \DateTime($paramFetcher->get('slot'));

        if (null === $user) {
            throw new NotFoundHttpException('No User Matching That Criteria');
        }

        if (null === $style) {
            throw new NotFoundHttpException('No Styles Matching That Criteria');
        }

        $booking = new Booking();
        $booking->setUser($user);
        $booking->setStyle($style);
        $booking->setTimeSlot($slot);

        $constraintViolationList = $this->validator->validate($booking);

        if (count($constraintViolationList) > 0) {
            $view = $this->view($constraintViolationList, Response::HTTP_BAD_REQUEST);
        } else {
            $this->entityManager->persist($booking);
            $this->entityManager->flush();

            $view = $this->view(null, Response::HTTP_CREATED);
        }

        return $this->handleView($view);
    }
}
