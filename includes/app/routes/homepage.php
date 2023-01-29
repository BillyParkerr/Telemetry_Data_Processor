<?php

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

// TODO The logger needs to be injected here rather than initialising a new instance.

$app->get('/', function(Request $request, Response $response) use ($app)
{
    $soapClientEEObject = $app->getContainer()->get('soapClientEE');
    $soapClientEEObject->createEESoapConnection('22_2528439', 'Shinobufan123!');
    $messages = $soapClientEEObject->peekMessages(50, "447516729581", '44');
    $sqlHelper = $app->getContainer()->get('sqlHelper');
    $storedMessages = $sqlHelper->getAllStoredMessages();
    return $this->view->render($response, 'homepageform.html.twig', ['messages' => $messages, 'css_path' => CSS_PATH, 'storedMessages' => $storedMessages]);})->setName('homepage');