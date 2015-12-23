<?php
namespace JayaTest\WebBundle\Controller;
 
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\HttpFoundation\Request;
use JayaTest\CoreBundle\Entity\User;
use JayaTest\CoreBundle\Entity\Role;
use JayaTest\CoreBundle\Entity\Item;
use Symfony\Component\Security\Core\Encoder\MessageDigestPasswordEncoder;

class SecurityController extends Controller
{
    public function loginAction()
    {
        if ($this->get('request')->attributes->has(SecurityContext::AUTHENTICATION_ERROR)) {
            $error = $this->get('request')->attributes->get(SecurityContext::AUTHENTICATION_ERROR);
        } else {
            $error = $this->get('request')->getSession()->get(SecurityContext::AUTHENTICATION_ERROR);
        }

        $params = [
            'last_username' => $this->get('request')->getSession()->get(SecurityContext::LAST_USERNAME),
            ];

        if (isset($error)) {
        	$params['error'] = $error->getMessage();
        }

        return $this->render('JayaTestWebBundle:Security:login.html.twig', $params);
    }

	/**
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function registerAction(Request $request)
    {
    	$em = $this->getDoctrine()->getManager();

        $user = new User();

        $form = $this->createFormBuilder($user, ['attr' => ['class' => 'form-inline']])
            ->add('username', 'text', ['label' => 'Имя', 'attr' => ['class' => 'form-control']])
            ->add('useremail', 'email', ['label' => 'E-Mail', 'attr' => ['class' => 'form-control']])
            ->add('password', 'password', ['label' => 'Пароль', 'attr' => ['class' => 'form-control']])
            ->add('save', 'submit', ['label' => 'Зарегистрироваться', 'attr' => ['class' => 'btn btn-primary']])
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $formData = $request->get('form');

            $role = new Role();
            $role->setName('ROLE_ADMIN');
     
            $em->persist($role);

            $user->setUserName($formData['username']);
            $user->setUserEmail($formData['useremail']);
            $user->setSalt(md5(time()));
            $user->setCreatedAt();
     		$user->setStatus(1);

            $encoder = new MessageDigestPasswordEncoder('sha512', true, 10);
            $password = $encoder->encodePassword($formData['password'], $user->getSalt());
            $user->setPassword($password);
     
            $user->getUserRoles()->add($role);
     
            $item = new Item();
     		$item->setUsername('system');
            $item->setUserEmail('system@system.com');
            $item->setMessage(sprintf('Приветствуем нового пользователя %s', $formData['username']));
            $item->setCreatedAt();

            $em->persist($user);
            $em->persist($item);
            $em->flush();

            return $this->redirectToRoute('jaya_test_web_homepage');
        } else {
        	return $this->render('JayaTestWebBundle:Security:register.html.twig', [
            	'form' => $form->createView()
        	]);
        }
    }
}