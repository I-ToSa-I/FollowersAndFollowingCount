<?php

namespace DCS\FollowersAndFollowingCount\XF\Pub\Controller;

use XF\Mvc\ParameterBag;

class Member extends XFCP_Member {

    public function actionView(ParameterBag $params) {

        $parent = parent::actionView($params);
        $user = $this->assertViewableUser($params->user_id);

        /** @var \XF\Repository\UserFollow $userFollowRepo */
        $userFollowRepo = $this->repository('XF:UserFollow');
        $followingCount = 0;
        if ($user->Profile->following)
        {
            $userFollowingFinder = $userFollowRepo->findFollowingForProfile($user);
            $userFollowingFinder->order($userFollowingFinder->expression('RAND()'));

            $followingCount = $userFollowingFinder->total();
        }

        $userFollowersFinder = $userFollowRepo->findFollowersForProfile($user);
        $userFollowersFinder->order($userFollowersFinder->expression('RAND()'));

        $followersCount = $userFollowersFinder->total();


        if($parent instanceof \XF\Mvc\Reply\View)
        {
            $parent->setParams([
                'followingCount' => $followingCount,
                'followersCount' => $followersCount
            ]);

        }

        return $parent;

    }

}