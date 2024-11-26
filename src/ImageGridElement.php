<?php

namespace BiffBangPow\Element;

use BiffBangPow\Element\Control\ImageGridElementController;
use BiffBangPow\Element\Model\ImageGridItem;
use DNADesign\Elemental\Models\BaseElement;
use SilverStripe\Forms\DropdownField;
use SilverStripe\Forms\GridField\GridField;
use SilverStripe\Forms\GridField\GridFieldConfig_RecordEditor;
use SilverStripe\Forms\GridField\GridFieldPaginator;
use SilverStripe\Forms\HeaderField;
use SilverStripe\Forms\HTMLEditor\HTMLEditorField;
use Symbiote\GridFieldExtensions\GridFieldConfigurablePaginator;
use Symbiote\GridFieldExtensions\GridFieldOrderableRows;
use UndefinedOffset\SortableGridField\Forms\GridFieldSortableRows;

class ImageGridElement extends BaseElement
{
    private static $table_name = 'ImageGridElement';
    private static $db = [
        'Content' => 'HTMLText',
        'ColsMobile' => 'Int',
        'ColsTablet' => 'Int',
        'ColsDesktop' => 'Int',
        'ColsLarge' => 'Int'
    ];
    private static $has_many = [
        'Items' => ImageGridItem::class
    ];

    private static $inline_editable = false;
    private static $cascade_duplicates = [
        'Items'
    ];

    private static $controller_class = ImageGridElementController::class;

    const COLUMN_OPTIONS = [
        1 => 1,
        2 => 2,
        3 => 3,
        4 => 4,
        5 => 5,
        6 => 6,
        7 => 7,
        8 => 8,
        9 => 9,
        10 => 10,
        11 => 11,
        12 => 12
    ];

    public function getType()
    {
        return _t(__CLASS__ . '.BlockType', 'Image Grid');
    }


    public function getCMSFields()
    {
        $paginator = GridFieldConfigurablePaginator::create(50, [20, 50, 100]);
        $paginator->setItemsPerPage(20);

        $fields = parent::getCMSFields();
        $fields->removeByName(['Items', 'ColsMobile', 'ColsTablet', 'ColsDesktop', 'ColsLarge']);
        $fields->addFieldsToTab('Root.Main', [
            HTMLEditorField::create('Content')->setDescription('Shown above the images')->setRows(10),
            $gridField = GridField::create('Items', 'Images', $this->Items(),
                GridFieldConfig_RecordEditor::create()
                    ->removeComponentsByType(GridFieldPaginator::class)
                    ->addComponents([
                        new GridFieldOrderableRows(),
                        $paginator
                    ]))
        ]);

        $fields->addFieldsToTab('Root.Settings', [
            HeaderField::create('Number of columns to show:'),
            DropdownField::create('ColsMobile', 'Mobile columns', self::COLUMN_OPTIONS),
            DropdownField::create('ColsTablet', 'Tablet columns', self::COLUMN_OPTIONS),
            DropdownField::create('ColsDesktop', 'Desktop columns', self::COLUMN_OPTIONS),
            DropdownField::create('ColsLarge', 'Large screen columns', self::COLUMN_OPTIONS)
        ]);

        return $fields;
    }

    public function getSimpleClassName()
    {
        return 'bbp-image-grid';
    }

}
