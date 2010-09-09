<?php

class IndexController extends Zend_Controller_Action
{

    public function indexAction()
    {

        echo "<pre>";
        echo "Image:\n";

        $images = Doctrine_Query::create()->from('Image')->orderBy('filename ASC')->execute();
        foreach($images as $image) {
            $id = $this->getIdentifier($image->filename);
            $logos[] = $id;
            echo "  $id:\n";
            foreach($image->toArray() as $field => $value) {
                if (!preg_match('/id|logo|_at/',$field))
                echo "    $field: $value\n";
            }
        }

        echo "Brand:\n";
        $brands = Doctrine_Query::create()->from('Brand')->orderBy('name ASC')->execute();
        foreach($brands as $brand) {
            $id = $this->getIdentifier(Util_String::toUrl($brand->name));
            echo "  $id:\n";
            foreach($brand->toArray() as $field => $value) {
                if (!preg_match('/id|logo|_at/',$field))
                echo "    $field: $value\n";
            }
            $logo = "Logotipo$id";
            if (!in_array($logo,$logos)) {
                $missing[] = $logo;
            }
            echo "    Logo: $logo\n";
        }


        print_r($missing);



      
    }

    public function getIdentifier($string) {
        $string = Util_String::spacesTo(ucwords(str_replace('-', ' ', $string)),'');
        return $string;
    }


}

