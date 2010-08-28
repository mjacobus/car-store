<?php

/**
 * Manages Brands
 *
 * @author marcelo.jacobus
 */
class Admin_Model_ImageUpload extends Admin_Model_Abstract
{

    protected $_tableName = 'Image';

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
     * Get a confirmation messages for deleting a record
     * @var int $id the id of the Brand record
     * @return string
     */
    public function getDelConfirmationMessage($id)
    {
        $record = $this->getById($this->getTablelName(), $id);
        return sprintf('Tem certeza que deseja excluir a imagem "%s (%s)"?',
            $record->filename, $record->description);
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

        if ($form->isValid($values)) {
            if (!$file->receive()) {
                $this->addMessage("Não foi possível fazer o upload do arquivo");
                return false;
            }

            $mi = Model_Image::getInstance();
            $saveTo = $mi->getOriginalPath()
                    . '/' . $form->getValue('filename') . '.png';

            try {
                $savedOn = $file->getFileName();
                WideImage::load($savedOn)->saveToFile($saveTo);
                unlink($savedOn);
            } catch (Exception $e) {
                $this->addMessage("Não foi possível fazer o upload do arquivo");
                $this->addMessage($e->getMessage());

                $traceArray = explode("\n", $e->getTraceAsString());
                foreach ($traceArray as $message) {
                    $this->addMessage($message);
                }
                return false;
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
        throw new Exception("Implementar regras de negocio da imagem");
    }

}