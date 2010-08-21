<?php

class ImageController extends Zend_Controller_Action
{

    /**
     *
     * @var Model_Image
     */
    protected $model;

    public function init()
    {
        $this->model = Model_Image::getInstance();
    }

    /**
     * Get the content of a image
     */
    public function indexAction()
    {
        $request = $this->getRequest();
        $model = $this->model;
        $model->setFile($request->getParam('file'))
            ->setWidth($request->getParam('width', 150))
            ->setHeight($request->getParam('height', 150))
            ->setToken($request->getParam('token'));

        $content = $model->getFileContent();
        $contentType = $model->getFileContentType();
        header("Content-type: $contentType");
        echo $content;
    }

    public function preDispatch()
    {
        $this->_helper->layout()->disableLayout();
        $this->_helper->viewRenderer->setNoRender(true);
    }

}

