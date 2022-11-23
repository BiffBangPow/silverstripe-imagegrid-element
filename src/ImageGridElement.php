<?php

namespace BiffBangPow\Element;

use BiffBangPow\Element\Model\ImageGridItem;
use DNADesign\Elemental\Models\BaseElement;
use SilverStripe\Forms\GridField\GridField;
use SilverStripe\Forms\GridField\GridFieldConfig_RecordEditor;
use SilverStripe\Forms\HTMLEditor\HTMLEditorField;
use Symbiote\GridFieldExtensions\GridFieldOrderableRows;
use UndefinedOffset\SortableGridField\Forms\GridFieldSortableRows;

class ImageGridElement extends BaseElement
{
    private static $table_name = 'ImageGridElement';
    private static $db = [
        'Content' => 'HTMLText'
    ];
    private static $has_many = [
        'Items' => ImageGridItem::class
    ];

    private static $inline_editable = false;
    private static $cascade_duplicates = [
        'Items'
    ];

    public function getType()
    {
        return _t(__CLASS__ . '.BlockType', 'Image Grid');
    }


    public function getCMSFields()
    {
        $fields = parent::getCMSFields();
        $fields->removeByName(['Items']);
        $fields->addFieldsToTab('Root.Main', [
            HTMLEditorField::create('Content')->setDescription('Shown above the images')->setRows(10),
            GridField::create('Items', 'Images', $this->Items(), GridFieldConfig_RecordEditor::create()
                ->addComponent(new GridFieldOrderableRows()))
        ]);

        return $fields;
    }

    public function getSimpleClassName()
    {
        return 'bbp-image-grid';
    }

}
