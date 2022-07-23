<?php

namespace App\Controller;

use App\Entity\Message;
use App\Entity\Topic;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ForumController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index(EntityManagerInterface $em): Response
    {
        /*$topic = new Topic();
        $topic->setDate(new \DateTime('12-02-2022'));
        $topic->setTitle('Topic');
        $topic->setContent('dsofhksdhfksdlif');
        $topic->setUsername('Worgen');
        $em->persist($topic);
        $em->flush();*/

        $topics = $em->getRepository(Topic::class)->findAll();

        return $this->render('forum/index.html.twig', [
            'topics' => $topics
        ]);
    }

    /**
     * @Route("view/{id}", name="view", options={"expose"=true})
     */
    public function view($id, EntityManagerInterface $em): Response
    {

        $topic = $em->getRepository(Topic::class)->findOneBy(['id' => $id]);
        $messages = $em->getRepository(Message::class)->findBy(['topic' => $id]);

        return $this->render('forum/topic.html.twig', [
            'topic' => $topic,
            'messages' => $messages
        ]);
    }


    /**
     * @Route("new_message/{id}", name="new_message", options={"expose"=true})
     */
    public function new_message($id, EntityManagerInterface $em): Response
    {

        //$topic = $em->getRepository(Topic::class)->findOneBy(['id' => $id]);

        return $this->render('forum/new_message.html.twig', [
            'id' => $id
        ]);
    }

    /**
     * @Route("new_topic/", name="new_topic", options={"expose"=true})
     */
    public function new_topic(EntityManagerInterface $em): Response
    {

        //$topic = $em->getRepository(Topic::class)->findOneBy(['id' => $id]);

        return $this->render('forum/new_topic.html.twig', [
        ]);
    }


    /**
     * @Route("add_message/{id}", name="add_message", options={"expose"=true})
     */
    public function add_message($id, EntityManagerInterface $em, Request $request): Response
    {
        $username = $request->request->get('username');
        $content = $request->request->get('content');
        $topic = $em->getRepository(Topic::class)->findOneBy(['id' => $id]);

        $message = new Message();
        $message->setUsername($username);
        $message->setContent($content);
        $message->setTopic($topic);
        $message->setDate(new \DateTime(date('d-m-y')));

        $em->persist($message);
        $em->flush();

        return $this->redirectToRoute('view', ['id' => $id]);
    }


    /**
     * @Route("add_topic/", name="add_topic", options={"expose"=true})
     */
    public function add_topic(EntityManagerInterface $em, Request $request): Response
    {
        $username = $request->request->get('username');
        $content = $request->request->get('content');
        $title = $request->request->get('title');

        $topic = new Topic();
        $topic->setUsername($username);
        $topic->setContent($content);
        $topic->setTitle($title);
        $topic->setDate(new \DateTime(date('d-m-y')));

        $em->persist($topic);
        $em->flush();

        /*return $this->render('forum/new_message.html.twig', [
            //'topic' => $topic
        ]);*/

        return $this->redirectToRoute('home');
    }


    /**
     * @Route("search/", name="search", options={"expose"=true})
     */
    public function search(EntityManagerInterface $em, Request $request): Response
    {
        $search = $request->request->get('search');
        $topics = $em->getRepository(Topic::class)->findBy(['title' => $search]);
        return $this->render('forum/search.html.twig', [
            'topics' => $topics
        ]);
    }






}
