<?php

/**
 * Manages Brands
 *
 * @author marcelo.jacobus
 */
class Admin_Model_ImageUpload extends Admin_Model_Abstract
{

    protected $_tableName = 'Image';

    protected $_ukMapping = array(
        'filename' => array(
            'field' => 'filename',
            'label' => 'Nome do Arquivo',
            'message' => 'Um registro já existe com "{label}" igual a "{value}" '
        ),
        'md5' => array(
            'field' => 'md5',
            'label' => 'Imagem',
            'message' => 'Esta imagem já existe. Por favor troque-a" '
        )
    );

    /**
     * Get the form
     * @param array $params
     * @return Admin_Form_Brand
     */
    public function getForm(array $params = null)
    {
        if ($this->_form == null) {
            $this->_form = new Admin_Form_ImageUpload($params);
        }
        return $this->_form;
    }

    /**
     * Get request for a image
     * @return string
     */
    public function getRequestFormImage($filename,$width = 200, $height = 200)
    {
        $request = Model_Image::getInstance()
                ->setFile($filename . ".png")
                ->setWidth($width)
                ->setHeight($height)
                ->getRequest();

        $request = Zend_Controller_Front::getInstance()->getBaseUrl()
            . '/image' . $request;
        return $request;
    }

    /**
     * Get a confirmation messages for deleting a record
     * @var int $id the id of the Brand record
     * @return string
     */
    public function getDelConfirmationMessage($id)
    {
        $record = $this->getById($this->getTablelName(), $id);
        $this->getRequestFormImage($record->filename);
        $imgTag = '<div><img src="' . $request . '" alt="imagem não encontrada" /></div>';
        return sprintf('Tem certeza que deseja excluir a imagem "%s (%s)"? %s',
            $record->filename, $record->description, $imgTag);
    }

    /**
     * Return a DQL for listing registers
     * @param array $params for querying
     * @return Doctrine_Query
     */
    public function getListingDql(array $params = array())
    {
        $dql = Doctrine_Core::getTable($this->getTablelName())
                ->createQuery();

        if (array_key_exists('search', $params) && $params['search']) {
            $search = $params['search'];
            $dql->addWhere('filename like ?', "%$search%")
                ->orWhere('md5 like ?', "%$search%")
                ->orWhere('description like ?', "%$search%");
        }
        return $dql;
    }

    /**
     * Persist register and save image
     * @param array $values
     * @param int $id
     * @return bool
     */
    public function save(array $values, $id = null)
    {
        $form = $this->getForm();
        $file = $form->file;

        //on update image is not required
        if ($id !== null) {
            $file->setRequired(false);
        }

        if ($form->isValid($values)) {

            if (strlen($file->getValue())) {
                try {
                    if (!$file->receive()) {
                        throw new Exception("Não foi possível fazer o upload do arquivo");
                    }

                    $mi = Model_Image::getInstance();
                    $saveTo = $mi->getOriginalPath()
                        . '/' . $form->getValue('filename') . '.png';

                    $savedOn = $file->getFileName();
                    $tmpFile = $savedOn . ".png";

                    WideImage::load($savedOn)->saveToFile($tmpFile);

                    unlink($savedOn);
                    $md5 = md5_file($tmpFile);
                    $values['md5'] = $md5;
                    rename($tmpFile, $saveTo);
                } catch (Exception $e) {
                    $this->addMessage("Não foi possível fazer o upload do arquivo");

                    if (file_exits($tmpFile)) {
                        unlink($tmpFile);
                    }

                    $this->addMessage($e->getMessage());
                    return false;
                }
            }
        }

        return parent::save($values, $id);
    }

    /**
     * Attempts to delete a record
     * @return bool
     */
    public function deleteRecord($id)
    {
        try {
            $record = $this->getById($this->getTablelName(), $id);
            $record->delete();
            $this->deleteImage($record->filename);
        } catch (Exception $e) {
            //tratar vinculos




            $this->addMessage($this->_crudMessages[self::DELETED_ERROR]);
            return false;
        }
        $this->addMessage($this->_crudMessages[self::DELETE_OK]);
        return true;
    }

    /**
     * Delete image from the filesystem
     * @param string $name
     */
    public function deleteImage($name)
    {
        $pattern = "/^" . $name . "_(\d+)x(\d+)\.png$/i";
        $dir = Model_Image::getInstance()->getResizedPath();
        $dh = opendir($dir);

        while (false !== ($filename = readdir($dh))) {
            if (preg_match($pattern, $filename)) {
                unlink("$dir/$filename");
            }
        }
        $dir = Model_Image::getInstance()->getOriginalPath();
        unlink("$dir/$name.png");
    }

    /**
     * Populates a form
     * @param int $id
     * @throws App_Exception_RegisterNotFound case register wont exist
     * @return Admin_Model_Brand
     */
     public function populateForm($id)
    {
        parent::populateForm($id);
        $form = $this->getForm();
        $filename = $form->getValue('filename');
        $image = $this->getRequestFormImage($filename,400,400);
        $form->image->setImage($image);
        return $this;
    }

}