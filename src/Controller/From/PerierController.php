<?php

namespace App\Controller\From;

date_default_timezone_set('Europe/Paris');

use App\Service\ParserService;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @package App\Controller\From
 * @Route("/perier", name="perier_")
 */
class PerierController extends AbstractController
{
    private ParserService $parserService;

    /**
     * @param ParserService $parserService
     */
    public function __construct(ParserService $parserService) {
        $this->parserService = $parserService;
    }

    /**
     * @Route("/geze", methods={"OPTIONS", "GET"}, name="geze")
     * 
     * @param HttpClientInterface $httpClient
     */
    public function index(HttpClientInterface $httpClient): JsonResponse|array
    {
        $url = 'https://api.rtm.fr//front/spoti/getStationDetails?nomPtReseau=02325';

        $request = file_get_contents($url);
        $request = $httpClient->request(
            'POST',
            $url
        );
        
        $request = $request->getContent();
        $items = $this->parserService->parse($request);

        return new JsonResponse($items);
    }
}
