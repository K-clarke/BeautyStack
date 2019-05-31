<?php

declare(strict_types=1);

namespace App\Controller\API;

use App\Entity\Style;
use App\Repository\StyleRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Request\ParamFetcher;
use FOS\RestBundle\View\View;
use JMS\Serializer\SerializerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * Class StyleController
 *
 * @Rest\Route("/api/v1")
 */
class StyleController extends AbstractFOSRestController
{
    /** @var StyleRepository */
    private $styleRepository;
    /** @var SerializerInterface */
    private $serializer;
    /** @var EntityManager */
    private $entityManager;
    /** @var ValidatorInterface */
    private $validator;

    /**
     * StyleController constructor.
     * @param EntityManagerInterface $entityManager
     * @param StyleRepository $styleRepository
     * @param SerializerInterface $serializer
     * @param ValidatorInterface $validator
     */
    public function __construct(
        EntityManagerInterface $entityManager,
        StyleRepository $styleRepository,
        SerializerInterface $serializer,
        ValidatorInterface $validator)
    {
        $this->styleRepository = $styleRepository;
        $this->serializer = $serializer;
        $this->validator = $validator;
        $this->entityManager = $entityManager;
    }

    /**
     * @Rest\Get("/styles/{id}", name="get_item_style")
     *
     * @param $id
     * @return Response
     */
    public function getStyles($id): Response
    {
        $style = $this->styleRepository->findOneBy(['id' => $id]);

        if (null === $style) {
            throw new NotFoundHttpException(sprintf('No Style Matching ID %d', $id));
        }

        $view = View::create($style, Response::HTTP_OK);
        $view->getContext()->enableMaxDepth();

        return $this->handleView($view);
    }

    /**
     * @Rest\Get("/styles", name="get_collection_style")
     * @Rest\QueryParam(
     *     name="search",
     *     nullable=true,
     *     description="The keyword to search for."
     * )
     * @param ParamFetcher $paramFetcher
     * @return Response
     */
    public function getCollectionStyles(ParamFetcher $paramFetcher): Response
    {
        $searchCriteria = $paramFetcher->get('search');

        if (null == $searchCriteria) {
            $styles = $this->styleRepository->findAll();
        } else {
            $styles = $this->styleRepository->findBySearchCriteria($searchCriteria);

            if (null == $styles) {
                throw new NotFoundHttpException('No Styles Matching That Search Criteria');
            }
        }

        $view = $this->view($styles, Response::HTTP_OK);

        return $this->handleView($view);
    }

    /**
     * @Rest\Post("/styles", name="post_item_style")
     * @ParamConverter("style", converter="fos_rest.request_body")
     *
     * @param Style $style
     * @return Response
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function postStyles(Style $style): Response
    {
        $constraintViolationList = $this->validator->validate($style);

        if (count($constraintViolationList) > 0) {
            $view = $this->view($constraintViolationList, Response::HTTP_BAD_REQUEST);
        } else {
            $this->entityManager->persist($style);
            $this->entityManager->flush();

            $view = $this->view(null, Response::HTTP_CREATED);
        }

        return $this->handleView($view);
    }

    /**
     * @Rest\Delete("/styles/{id}", name="delete_item_style")
     *
     * @param $id
     *
     * @return Response
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function deleteStyle($id): Response
    {
        $style = $this->styleRepository->findOneBy(['id' => $id]);

        if (null === $style) {
            throw new NotFoundHttpException('Style Not Found');
        }

        $this->entityManager->remove($style);
        $this->entityManager->flush();

        $view = $this->view(null, Response::HTTP_NO_CONTENT);

        return $this->handleView($view);
    }
}
