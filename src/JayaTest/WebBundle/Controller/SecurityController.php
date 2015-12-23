<?php
namespace JayaTest\WebBundle\Controller;
 
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\HttpFoundation\Request;

class SecurityController extends Controller
{
    public function loginAction()
    {
        if ($this->get('request')->attributes->has(SecurityContext::AUTHENTICATION_ERROR)) {
            $error = $this->get('request')->attributes->get(SecurityContext::AUTHENTICATION_ERROR);
        } else {
            $error = $this->get('request')->getSession()->get(SecurityContext::AUTHENTICATION_ERROR);
        }

        return $this->render('JayaTestWebBundle:Security:login.html.twig', [
            'last_username' => $this->get('request')->getSession()->get(SecurityContext::LAST_USERNAME),
            'error' => $error->getMessage()
        ]);
    }

	/**
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function registerAction(Request $request)
    {
        $user = new User();

        $form = $this->createFormBuilder($user)
            ->add('username', 'text', ['label' => 'Имя'])
            ->add('useremail', 'email', ['label' => 'E-Mail'])
            ->add('password', 'password', ['label' => 'Пароль'])
            ->add('save', 'submit', ['label' => 'Зарегистрироваться'])
            ->getForm();

        if ($form->isSubmitted() && $form->isValid()) {
            /*$formData = $request->get('form');

            $role = new Role();
            $role->setName('ROLE_ADMIN');
     
            $this->getDoctrine()->getManager()->persist($role);

            $user->setUsername($form['username']);
            $user->setUseremail($form['useremail']);
            $user->setSalt(md5(time()));
            $user->setCreatedAt(new \DateTime());
     
            $encoder = new MessageDigestPasswordEncoder('sha512', true, 10);
            $password = $encoder->encodePassword($form['password'], $user->getSalt());
            $user->setPassword($password);
     
            $user->getUserRoles()->add($role);
     
            $this->getDoctrine()->getManager()->persist($user);
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('jaya_test_web_homepage');*/
        } else {
        	return $this->render('JayaTestWebBundle:Security:register.html.twig', [
            	'form' => $form->createView()
        	]);
        }
    }
}