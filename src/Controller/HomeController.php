<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Movie;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Exception\HttpBadRequestException;
use Slim\Interfaces\RouteCollectorInterface;
use Twig\Environment;

/**
 * Class HomeController.
 */
class HomeController
{
    /**
     * @var RouteCollectorInterface
     */
    private $routeCollector;

    /**
     * @var Environment
     */
    private $twig;

    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * HomeController constructor.
     *
     * @param RouteCollectorInterface $routeCollector
     * @param Environment             $twig
     * @param EntityManagerInterface  $em
     */
    public function __construct(RouteCollectorInterface $routeCollector, Environment $twig, EntityManagerInterface $em)
    {
        $this->routeCollector = $routeCollector;
        $this->twig = $twig;
        $this->em = $em;
    }

    /**
     * @param ServerRequestInterface $request
     * @param ResponseInterface      $response
     *
     * @return ResponseInterface
     *
     * @throws HttpBadRequestException
     */
    public function index(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $class = new \ReflectionClass($this);
        try {
            $data = $this->twig->render('home/index.html.twig', [
                'trailers' => $this->fetchData(),
                'class' => $class->getShortName(),
                'method' => $class->getMethod(__FUNCTION__)->name,
                'date' => (new \DateTime())
            ]);
        } catch (\Exception $e) {
            throw new HttpBadRequestException($request, $e->getMessage(), $e);
        }

        $response->getBody()->write($data);

        return $response;
    }

    /**
     *
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     *
     * @return ResponseInterface
     */
    public function show(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {

        $id = (int) $request->getAttribute('id');
        $data = $this->twig->render('home/show.html.twig', [
            'trailer' => $this->findMovie($id),
        ]);

        $response->getBody()->write($data);

        return $response;
    }

    /**
     * @return Collection
     */
    protected function fetchData(): Collection
    {
        $data = $this->em->getRepository(Movie::class)
            ->findAll();

        return new ArrayCollection($data);
    }

    /**
     * @param int $id
     *
     * @return object
     */
    protected function findMovie(int $id): object
    {
        return $this->em->getRepository(Movie::class)->find($id);
    }
}
