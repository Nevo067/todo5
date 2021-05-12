<?php

namespace App\Controller;

use App\Entity\Todo;
use App\Entity\User;
use PhpParser\Node\Stmt\Echo_;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class TodoController extends AbstractController
{
    #[Route('/todo', name: 'todo')]
    public function index(): Response
    {
        return $this->render('todo/index.html.twig', [
            'controller_name' => 'TodoController',
        ]);
    }
    #[Route('/todo/post', methods: ['POST'])]
    public function post(Request $request)
    {
        $encoders =  [new JsonEncoder()];
        $normalizers = [new ObjectNormalizer()];

        $serializer = new Serializer($normalizers,$encoders);
        $contentJson = $request->getContent();
        echo($contentJson);
        $content = $serializer->deserialize($contentJson,User::class,'json');

        #
        #$user1 = new User();
        #$user1->setLogin("11");
        #$user1->setPassword("11");
        #$user1->setPseudo("11");
        
        $em = $this->getDoctrine()->getManager();
        $em->persist($content);
        $em->flush();

        return new Response();

    }
}
