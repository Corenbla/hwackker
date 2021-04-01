<?php

namespace App\Service ;

use App\Entity\User;
use App\Repository\UserRepository;

class UserService
{

  public function usersAreFollowEachOthers(User $user1, User $user2) : bool
  {
      $user1Followers =$user1->getFollowers();
      $user2Followers =$user2->getFollowers();
      return $user1Followers->contains($user2) && $user2Followers->contains($user1);
  }
}