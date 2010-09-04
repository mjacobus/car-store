<?php

/**
 * Base_Car
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property integer $id
 * @property string $model
 * @property string $color
 * @property integer $brand_id
 * @property integer $status_id
 * @property float $price
 * @property boolean $showPrice
 * @property integer $year
 * @property integer $modelYear
 * @property integer $priority
 * @property string $licensePlate
 * @property CarStatus $Status
 * @property Brand $Brand
 * @property Doctrine_Collection $Image
 * @property Doctrine_Collection $CarFeature
 * 
 * @package    ##PACKAGE##
 * @subpackage ##SUBPACKAGE##
 * @author     ##NAME## <##EMAIL##>
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class Base_Car extends Doctrine_Record
{
    public function setTableDefinition()
    {
        $this->setTableName('car');
        $this->hasColumn('id', 'integer', null, array(
             'type' => 'integer',
             'primary' => true,
             'unsigned' => true,
             'autoincrement' => true,
             ));
        $this->hasColumn('model', 'string', 255, array(
             'type' => 'string',
             'unique' => true,
             'notnull' => true,
             'length' => '255',
             ));
        $this->hasColumn('color', 'string', 255, array(
             'type' => 'string',
             'notnull' => true,
             'length' => '255',
             ));
        $this->hasColumn('brand_id', 'integer', null, array(
             'type' => 'integer',
             'notnull' => true,
             'unsigned' => true,
             ));
        $this->hasColumn('status_id', 'integer', null, array(
             'type' => 'integer',
             'notnull' => true,
             'unsigned' => true,
             ));
        $this->hasColumn('price', 'float', null, array(
             'type' => 'float',
             'default' => 0,
             ));
        $this->hasColumn('showPrice', 'boolean', null, array(
             'type' => 'boolean',
             'default' => false,
             'notnull' => true,
             ));
        $this->hasColumn('year', 'integer', null, array(
             'type' => 'integer',
             'notnull' => true,
             ));
        $this->hasColumn('modelYear', 'integer', null, array(
             'type' => 'integer',
             'notnull' => true,
             ));
        $this->hasColumn('priority', 'integer', null, array(
             'type' => 'integer',
             'default' => 0,
             'notnull' => true,
             ));
        $this->hasColumn('licensePlate', 'string', 8, array(
             'type' => 'string',
             'unique' => true,
             'notnull' => true,
             'length' => '8',
             ));

        $this->option('type', 'INNODB');
        $this->option('collate', 'utf8_general_ci');
        $this->option('charset', 'utf8');
    }

    public function setUp()
    {
        parent::setUp();
        $this->hasOne('CarStatus as Status', array(
             'local' => 'status_id',
             'foreign' => 'id'));

        $this->hasOne('Brand', array(
             'local' => 'brand_id',
             'foreign' => 'id'));

        $this->hasMany('CarImage as Image', array(
             'local' => 'id',
             'foreign' => 'car_id',
             'onDelete' => 'NULLIFY'));

        $this->hasMany('CarFeature', array(
             'local' => 'id',
             'foreign' => 'car_id'));

        $timestampable0 = new Doctrine_Template_Timestampable();
        $this->actAs($timestampable0);
    }
}