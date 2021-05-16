<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use phpDocumentor\Reflection\Types\Integer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class UserController extends AbstractController
{
    #[Route('/user', name: 'user')]
    public function index(): Response
    {
        return $this->render('user/index.html.twig', [
            'controller_name' => 'UserController',
        ]);
    }
    #[Route('/user/post', methods: ['POST'])]
    public function post(Request $request) : Response
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
    #[Route('/user/update/{id}', methods: ['POST'])]
    public function update(Request $request,int $id) : Response
    {
        $encoders =  [new JsonEncoder()];
        $normalizers = [new ObjectNormalizer()];
        $serializer = new Serializer($normalizers,$encoders);

        $contentJson = $request->getContent();
        $content = $serializer->deserialize($contentJson,User::class,'json');

        $entityManager = $this->getDoctrine()->getManager();
        $user = $entityManager->getRepository(User::class)->findById($id);

        $user->setLogin($content->getLogin());
        $user->setPassword($content->getPassword());
        $user->setPseudo($content->getPseudo());
        $entityManager->flush($user);
        
        return new Response();



    }
    #[Route('/user/delete/{id}', methods: ['GET'])]
    public function delete(int $id) : Response
    {
        $encoders =  [new JsonEncoder()];
        $normalizers = [new ObjectNormalizer()];
        $serializer = new Serializer($normalizers,$encoders);

        $entityManager = $this->getDoctrine();
        $user = $entityManager->getRepository(User::class)->findById($id);

        $em = $entityManager->getManager();
        $em->remove($user);
        $em->flush();
        
        return new Response();
    }
    #[Route('/user/', methods: ['POST'])] 
    public function getByLoginAndPassword(Request $request) : Response
    {
        $encoders =  [new JsonEncoder()];
        $normalizers = [new ObjectNormalizer()];
        $serializer = new Serializer($normalizers,$encoders);

        $contentJson = $request->getContent();
        $content = $serializer->deserialize($contentJson,User::class,'json');

        echo get_class($this->getDoctrine()->getRepository(User::class));

        $user = $this->getDoctrine()
            ->getRepository(User::class)
            ->findUserByLoginAndPassword($content->getLogin(),$content->getPassword());
        //->findByLoginAndPassword($content->getLogin(),$content->getPassword());
        echo $serializer->serialize($user,'json');
        return new Response();
    }

}
