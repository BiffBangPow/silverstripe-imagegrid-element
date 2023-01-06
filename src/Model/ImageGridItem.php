<?php

namespace BiffBangPow\Element\Model;

use BiffBangPow\Element\ImageGridElement;
use BiffBangPow\Extension\SortableExtension;
use SilverStripe\AssetAdmin\Forms\UploadField;
use SilverStripe\Assets\File;
use SilverStripe\Assets\Image;
use SilverStripe\CMS\Model\SiteTree;
use SilverStripe\Forms\DropdownField;
use SilverStripe\Forms\TextField;
use SilverStripe\Forms\TreeDropdownField;
use SilverStripe\ORM\DataObject;
use SilverStripe\View\ArrayData;
use UncleCheese\DisplayLogic\Forms\Wrapper;

class ImageGridItem extends DataObject
{
    private static $table_name = 'ImageGridElement_Item';
    private static $extensions = [
        SortableExtension::class
    ];
    private static $db = [
        'LinkType' => 'Varchar',
        'LinkURL' => 'Varchar'
    ];
    private static $has_one = [
        'Image' => Image::class,
        'PageLink' => SiteTree::class,
        'File' => File::class,
        'Element' => ImageGridElement::class
    ];
    private static $owns = [
        'Image',
        'File'
    ];

    private $linkTypes = [
        'sitetree' => 'Page on the site',
        'external' => 'External link',
        'file' => 'Link to a file'
    ];

    private static $summary_fields = [
        'Image.CMSThumbnail' => 'Image',
        'Image.Title' => 'File'
    ];

    public function getCMSFields()
    {
        $fields = parent::getCMSFields();
        $fields->removeByName(['Sort', 'File', 'PageLinkID', 'ElementID', 'LinkURL']);
        $fields->addFieldsToTab('Root.Main', [
            UploadField::create('Image', 'Image')->setAllowedFileCategories('image/supported')
                ->setFolderName('ImageGrid'),
            DropdownField::create('LinkType', 'Link Type', $this->linkTypes)->setEmptyString('None'),
            Wrapper::create(
                TreeDropdownField::create('PageLinkID', 'Link to page', SiteTree::class)
            )->hideUnless("LinkType")->isEqualTo("sitetree")->end(),
            TextField::create('LinkURL', 'External URL')->hideUnless("LinkType")->isEqualTo("external")->end(),
            Wrapper::create(
                UploadField::create('File', 'File to download')->setFolderName('Downloads')
            )->hideUnless("LinkType")->isEqualTo("file")->end()
        ]);
        return $fields;
    }

    public function getLinkData()
    {
        switch ($this->LinkType) {
            case 'external':
                $url = $this->LinkURL;
                $target = 'external';
                break;
            case 'file':
                $url = $this->File()->Link();
                $target = 'download';
                break;
            case 'sitetree':
                $url = $this->PageLink()->Link();
                $target = 'local';
                break;
            case 'none':
            default:
                return false;
        }
        return ArrayData::create([
            'Link' => $url,
            'Target' => $target
        ]);
    }
}
