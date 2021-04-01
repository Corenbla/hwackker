<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\HwackRepository;
use App\Repository\UserRepository;
use App\Service\UserService;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/user")
 * @method User getUser()
 */
class UserController extends AbstractController
{

    /**
     * @Route("/", name="user", methods={"GET"})
     * @param Request $request
     * @param PaginatorInterface $paginator
     * @param UserRepository $userRepository
     * @param HwackRepository $hwackRepository
     * @return Response
     */
    public function index( Request $request, PaginatorInterface  $paginator,UserRepository $userRepository,HwackRepository $hwackRepository): Response
    {
        $currentUser = ( $this->getUser() instanceof User) ? $this->getUser() : null;
        if(is_null($currentUser)){
            $this->redirectToRoute("home");
        }
        $isAdmin = ( $this->getUser() instanceof User) ? $this->getUser()->getIsAdmin() : false;
        $page = $request->query->get('page') ?? null ;
        $search = $request->query->get('search') ?? null ;
        $username = $request->query->get('username') ?? null ;


        if(!empty($search)){
            $allHwacks = $hwackRepository->findByContentLike($search);
            $hwacks = $paginator->paginate($allHwacks,1,100);
            return $this->render('hwack/news.html.twig', [
                'hwacks' => $hwacks,
            ]);
        }
        if(!empty($username)){
            $user = $userRepository->findOneBy(['username'=>$username]);
            if(! $user instanceof User){
                return new Response("User doesn't exist",404);
            }

            $isFollower = $this->isFollowerByUsername($user) ;
            $private = $isAdmin ? $request->query->get('private') ?? false : $isFollower ;
            $hwacks = $hwackRepository->findBy(['author'=> $user->getId(), 'isPrivate'=>$private],['createdAt'=>'desc'],10);

            return $this->render('user/index.html.twig', [
                'user' => $user,
                'hwacks' => $hwacks,
                "isFollower"=>$isFollower

            ]);
        }
        if(!empty($page)){
            $allHwacks = $hwackRepository->findBy(['isPrivate'=>false],['createdAt'=>'desc']);
            $hwacks = $paginator->paginate($allHwacks,$page,100);
            return $this->render('hwack/news.html.twig', [
                'hwacks' => $hwacks,
            ]);
        }
        $hwacks = $currentUser->getHwacks();

        return $this->render('user/index.html.twig', [
            'user'=> $currentUser,
            'hwacks'=> $hwacks
        ]);
    }

    /**
     * @param User $follower
     * @return bool
     */
    public function isFollowerByUsername(User $follower): bool
    {
        $userService = new UserService();
        $user = $this->getUser();
        if($user instanceof User){
            return $userService->usersAreFollowEachOthers($user,$follower);
        }

        return false;
    }
}
