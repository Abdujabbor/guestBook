<?php
/**
 * Created by PhpStorm.
 * User: abdujabbor
 * Date: 6/19/18
 * Time: 4:40 PM
 */

class App
{
    private $request;
    private $response;
    private $handlers;

    public function __construct()
    {
        $this->request = new Request();
        $this->response = new Response();
        Session::getInstance();
        $this->handlers = [];
    }

    public function getRequest()
    {
        return $this->request;
    }

    public function getResponse()
    {
        return $this->response;
    }

    public function registerEndpoint($endpoint, $callback)
    {
        if (!isset($this->handlers[$endpoint])) {
            $this->handlers[$endpoint] = $callback;
        }
    }

    /**
     * @throws Exception
     */
    public function run()
    {
        if (empty($this->handlers[$this->getRequest()->getURI()])) {
           $this->response->notFound();
        }

        $this->handlers[$this->getRequest()->getURI()]->call($this, $this->request, $this->response);
    }
}