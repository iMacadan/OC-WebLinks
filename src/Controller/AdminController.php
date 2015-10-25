<?php

namespace WebLinks\Controller;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use WebLinks\Domain\User;
use WebLinks\Form\Type\LinkType;
use WebLinks\Form\Type\UserType;

class AdminController {
    /**
     * Admin home page controller.
     * 
     * @param Application @app Silex application
     */
    public function indexAction(Application $app) {
        $links = $app['dao.link']->findAll();
        $users = $app['dao.user']->findAll();
        return $app['twig']->render('admin.html.twig', array(
            'links' => $links,
            'users' => $users));
    }
    
    /**
     * Edit link controller.
     * 
     * @param integer $id link id
     * @param Request $request Incoming request
     * @param Application $app Silex application
     */
    public function editLinkAction($id, Request $request, Application $app) {
        $link = $app['dao.link']->find($id);
        $linkForm = $app['form.factory']->create(new LinkType(), $link);
        $linkForm->handleRequest($request);
        if ($linkForm->isSubmitted() && $linkForm->isValid()) {
            $app['dao.link']->save($link);
            $app['session']->getFlashBag()->add('success', 'The link was successfully updated.');
        }
        return $app['twig']->render('link_form.html.twig', array(
            'title' => 'Edit link',
            'linkForm' => $linkForm->createView()));
    }
    
    /**
     * Delete link controller.
     * 
     * @param integer $id Link id
     * @param Application $app Silex application
     */
    public function deleteLinkAction($id, Application $app) {
        $app['dao.link']->delete($id);
        $app['session']->getFlashBag()->add('success', 'The link was successfully removed.');
        // Redirect to admin home page
        return $app->redirect($app['url_generator']->generate('admin'));
    }
 
    /**
     * Add user controller.
     * 
     * @param Request $request Incoming request
     * @param Application $app Silex application
     */
    public function addUserAction(Request $request, Application $app) {
        $user = new User();
        $userForm = $app['form.factory']->create(new UserType(), $user);
        $userForm->handleRequest($request);
        if ($userForm->isSubmitted() && $userForm->isValid()) {
            // generate a salt value
            $salt = substr(md5(time()), 0, 23);
            $user->setSalt($salt);
            $plainPassword = $user->getPassword();
            // find the default encoder
            $encoder = $app['security.encoder.digest'];
            // compute the encoded password
            $password = $encoder->encodePassword($plainPassword, $user->getSalt());
            $user->setPassword($password);
            $app['dao.user']->save($user);
            $app['session']->getFlashBag()->add('success', 'The user was successfully created.');
        }
        return $app['twig']->render('user_form.html.twig', array(
            'title' => 'New user',
            'userForm' => $userForm->createView()));
    }
   
    /**
     * Edit user controller.
     * 
     * @param integer $id User id
     * @param Request $request Incoming request
     * @param Application $app Silex application
     */
    public function editUserAction($id, Request $request, Application $app) {
       $user = $app['dao.user']->find($id);
       $userForm = $app['form.factory']->create(new UserType(), $user);
       $userForm->handleRequest($request);
       if ($userForm->isSubmitted() && $userForm->isValid()) {
           $plainPassword = $user->getPassword();
           // find the encoer for the user
           $encoder = $app['security.encoder_factory']->getEncoder($user);
           // Computer the encoded password
           $password = $encoder->encodePassword($plainPassword, $user->getSalt());
           $user->setPassword($password);
           $app['dao.user']->save($user);
           $app['session']->getFlashBag()->add('success', 'The user was successfully updated.');
       }
       return $app['twig']->render('user_form.html.twig', array(
           'title' => 'Edit user',
           'userForm' => $userForm->createView()));
    }
    
    /**
     * Delete user controller.
     * 
     * @param integer $id User id
     * @param Application $app Silex application
     */
    public function deleteUserAction($id, Application $app) {
        // Delete all associated links
        $app['dao.link']->deleteAllByUser($id);
        // Delete the user
        $app['dao.user']->delete($id);
        $app['session']->getFlashBag()->add('success', 'The user was successfully removed.');
        // Redirect to admin page
        return $app->redirect($app['url_generator']->generate('admin'));        
    }
}