<?php

namespace JayaTest\WebBundle\Controller;

use JayaTest\CoreBundle\Entity\Item;
use JayaTest\CoreBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    /**
     * @param Item|null $itemEntity
     *
     * @return \Symfony\Component\Form\Form
     */
    private function getAddForm($itemEntity = null)
    {
        $item = isset($itemEntity) && $itemEntity instanceof Item ? $itemEntity : new Item();

        $form = $this->createFormBuilder($item)
            ->setAction($this->generateUrl('jaya_test_create_item'))
            ->add('username', 'text', ['label' => 'Имя'])
            ->add('useremail', 'email', ['label' => 'E-Mail'])
            ->add('message', 'textarea', ['label' => 'Сообщение'])
            ->add('save', 'submit', ['label' => 'Добавить сообщение'])
            ->getForm();

        return $form;
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction()
    {
        $items = $this->getDoctrine()->getManager()->getRepository('JayaTestCoreBundle:Item')->findAll();

        $form = $this->getAddForm();

        return $this->render('JayaTestWebBundle:Default:index.html.twig', [
            'items' => $items,
            'form' => $form->createView()
        ]);
    }

    /**
     * @param Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function newAction(Request $request)
    {
        $item = new Item();

        $form = $this->getAddForm($item);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $formData = $request->get('form');
            $item->setUserName($formData['username']);
            $item->setUserEmail($formData['useremail']);
            $item->setMessage($formData['message']);
            $item->setCreatedAt(new \DateTime());

            $this->getDoctrine()->getManager()->persist($item);
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('jaya_test_web_homepage');
        } else {
            return $this->redirectToRoute('jaya_test_web_homepage');
        }
    }

    public function registerAction(Request $request)
    {
        $user = new User();

        $form = $this->createFormBuilder($user)
            ->add('username', 'text', ['label' => 'Имя'])
            ->add('useremail', 'email', ['label' => 'E-Mail'])
            ->add('password', 'password', ['label' => 'Пароль'])
            ->add('save', 'submit', ['label' => 'Зарегистрироваться'])
            ->getForm();
    }
}
