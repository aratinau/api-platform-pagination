<?php


namespace App\Controller;

use ApiPlatform\Core\Bridge\Doctrine\Orm\Paginator;
use App\Repository\MovieRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class CustomMovieAction
 * @package App\Controller
 */
class CustomMovieAction extends AbstractController
{
    private $movieRepository;

    public function __construct(MovieRepository $movieRepository)
    {
        $this->movieRepository = $movieRepository;
    }

    public function __invoke(Request $request): Paginator
    {
        $page = (int) $request->query->get('page', 1);
        $order = $request->query->get('order', []);

        return $this->movieRepository->getCustom($page, $order);
    }
}
