<?php

namespace App\Controller;

use App\Entity\Todo;
use App\Entity\User;
use App\Repository\UserRepository;
use PhpParser\Node\Stmt\Echo_;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\AbstractObjectNormalizer;
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
        $id = $content1['user'];

        $manager = $this->getDoctrine();
        //echo get_class($manager->getRepository(Todo::class)->find(1));
        $user = $manager->getRepository(User::class)->find($id);
        //$content = $serializer->deserialize($contentJson,Todo::class,'json');
        //$content->setUser($user);

        $todos = new Todo();
        $todos->setName($content1['name']);
        $todos->setDescription($content1['description']);
        $todos->setUser($user);
        
        $em = $this->getDoctrine()->getManager();

        $em->persist($todos);
        $em->flush();

        echo json_encode($todos->serialise());

        return new Response();

    }
    #[Route('/todo/test/post', methods: ['POST'])]
    public function testPostTodo(Request $request,UserRepository $userRepository)
    {
        $contentJson = $request->getContent();
        echo $contentJson;
        echo 1;
        $content1 = json_decode($contentJson,true);
        var_dump($content1);
        $todo = new Todo();
        $todo->deserialise($userRepository,$content1);
        var_dump($todo->serialise());

        $em = $this->getDoctrine()->getManager();
        //$em->persist($user);
        $em->persist($todo);
        $em->flush();

        return new Response();


    }
    #[Route('/todo/update/{id}', methods: ['POST'])]
    public function update(Request $request,int $id,UserRepository $userRepository)
    {

        $contentJson = $request->getContent();
        $newTodo = new Todo();
        $content = json_decode($contentJson);
        $newTodo->deserialise($userRepository,$content);

        $entityManager = $this->getDoctrine()->getManager();
        $todo = $entityManager->getRepository(Todo::class)->find($id);

        $todo->setName($newTodo->getName());
        $todo->setDescription($newTodo->getDescription());
        //echo json_encode($todo->serialise());
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
        $todo = $entityManager->getRepository(Todo::class)->find($id);
        echo ($todo != NULL);
        $em = $entityManager->getManager();
        $em->remove($todo);
        $em->flush();

        return new Response();
    }
    #[Route('/todo/get/{id}', methods: ['GET'])]
    public function getTodoByIdUser(Request $request,int $id,UserRepository $userRepository) : Response
    {
        $encoders =  [new JsonEncoder()];
        $normalizers = [new ObjectNormalizer()];
        $serializer = new Serializer($normalizers,$encoders);

        $contentJson = $request->getContent();
//        $content = $serializer->deserialize($contentJson,Todo::class,'json');
        /*
        $user = $userRepository->find($id);
        dump($user);
        die();
        */


        $todos = $this->getDoctrine()
            ->getRepository(Todo::class)
            ->findTodoByUser($id);
        //echo $serializer->normalize($user, 'json', [AbstractObjectNormalizer::ENABLE_MAX_DEPTH => true]);
        //->findByLoginAndPassword($content->getLogin(),$content->getPassword());
        /*
        echo $serializer->serialize($todos, 'json', ['circular_reference_handler'
        => function ($object) {
                return $object->getId();
            }
        ]);
        */

        $todosJson = [];
        foreach ($todos as  $todo)
        {
            array_push($todosJson,$todo->serialise());
        }
        echo json_encode($todosJson);


        


        return new Response();
    }
    #[Route('api/test', methods: ['GET'])]
    public function test()
    {
        return $this->json([
            'message' => 'test!',
        ]);
    }

}
