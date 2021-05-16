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
        $content1 = json_decode($contentJson,true);
        $id = $content1['user_id'];

        $manager = $this->getDoctrine();
        //echo get_class($manager->getRepository(Todo::class)->find(1));
        $user = $manager->getRepository(User::class)->find($id);
        echo($content1['user_id']);
        echo($content1['user_id']);
        //$content = $serializer->deserialize($contentJson,Todo::class,'json');
        //$content->setUser($user);

        $todos = new Todo();
        $todos->setName($content1['name']);
        $todos->setDescription($content1['description']);
        $todos->setUser($user);
        
        $em = $this->getDoctrine()->getManager();
        //$em->persist($user);
        $em->persist($todos);
        $em->flush();

        return new Response();

    }
    #[Route('/todo/update/{id}', methods: ['POST'])]
    public function update(Request $request,int $id)
    {
        $encoders =  [new JsonEncoder()];
        $normalizers = [new ObjectNormalizer()];
        $serializer = new Serializer($normalizers,$encoders);

        $contentJson = $request->getContent();
        $content = $serializer->deserialize($contentJson,Todo::class,'json');

        $entityManager = $this->getDoctrine()->getManager();
        $todo = $entityManager->getRepository(Todo::class)->find($id);

        $todo->setName($content->getName());
        $todo->setDescription($content->getPassword());

        $entityManager->flush($todo);

        return new Response();
    }
    #[Route('/todo/delete/{id}', methods: ['GET'])]
    public function delete(int $id) : Response
    {
        $encoders =  [new JsonEncoder()];
        $normalizers = [new ObjectNormalizer()];
        $serializer = new Serializer($normalizers,$encoders);

        $entityManager = $this->getDoctrine();
        $todo = $entityManager->getRepository(Todo::class)->findById($id);

        $em = $entityManager->getManager();
        $em->remove($todo);
        $em->flush();

        return new Response();
    }
    #[Route('/todo/get/{id}', methods: ['GET'])]
    public function getByLoginAndPassword(Request $request,int $id) : Response
    {
        $encoders =  [new JsonEncoder()];
        $normalizers = [new ObjectNormalizer()];
        $serializer = new Serializer($normalizers,$encoders);

        $contentJson = $request->getContent();
        $content = $serializer->deserialize($contentJson,Todo::class,'json');

        echo get_class($this->getDoctrine()->getRepository(User::class));

        $user = $this->getDoctrine()
            ->getRepository(Todo::class)
            ->findTodoByUser($id);
        //->findByLoginAndPassword($content->getLogin(),$content->getPassword());
        echo $serializer->serialize($user,'json');
        return new Response();
    }
}
