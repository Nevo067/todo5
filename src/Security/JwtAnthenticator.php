<?php


namespace App\Security;


use App\Entity\User;
use Firebase\JWT\JWT;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Guard\AbstractGuardAuthenticator;

class JwtAnthenticator extends AbstractGuardAuthenticator
{
    private $em;
    private $params;

    public function __construct(EntityManagerInterface $em, ContainerBagInterface $params)
    {
        $this->$em = $em;
        $this->params = $params;
    }

    /**
     * @param Request $request
     * @param AuthenticationException|null $authException
     * @return Response
     */
    public function start(Request $request, AuthenticationException $authException = null)
    {
        $data = [
            'message'=>'Authentification Required'
        ];
        return new JsonResponse($data,Response::HTTP_UNAUTHORIZED);
    }

    /**
     * @param Request $request
     * @return bool
     */
    public function supports(Request $request)
    {
        // TODO: Implement supports() method.
        return $request->headers->has('Authorization');
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function getCredentials(Request $request)
    {
        return $request->headers->get('Authorization');
    }

    /**
     * @param mixed $credentials
     * @param UserProviderInterface $userProvider
     * @return UserInterface|null
     */
    public function getUser($credentials, UserProviderInterface $userProvider)
    {
        try {
            $credentials = str_replace('Bearer','',$credentials);
            $jwt =  JWT::decode($credentials,$this->params->get('jwt_secret'),['HS256']);

            return $this->em->getRepository(User::class)->findOneBy([
                'login' => $jwt['login'],]);
        }
        catch (\Exception $exception)
        {
            throw new AuthenticationException($exception->getMessage());
        }

    }

    /**
     * @param mixed $credentials
     * @param UserInterface $user
     * @return bool
     */
    public function checkCredentials($credentials, UserInterface $user)
    {
        // TODO: Implement checkCredentials() method.
    }

    /**
     * @param Request $request
     * @param AuthenticationException $exception
     * @return Response|null
     */
    public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
    {
        // TODO: Implement onAuthenticationFailure() method.
        return new JsonResponse(["message"=> $exception->getMessage()],Response::HTTP_UNAUTHORIZED);
    }

    /**
     * @param Request $request
     * @param TokenInterface $token
     * @param string $providerKey
     * @return Response|null
     */
    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $providerKey)
    {
        // TODO: Implement onAuthenticationSuccess() method.
    }

    /**
     * @return bool
     */
    public function supportsRememberMe()
    {
        // TODO: Implement supportsRememberMe() method.
        return false;
    }
}