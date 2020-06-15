<?php

namespace Dynamic\Foxy\Model;

use Bummzack\SortableFile\Forms\SortableUploadField;
use SilverStripe\Assets\File;
use SilverStripe\Forms\CheckboxField;
use SilverStripe\Forms\CurrencyField;
use SilverStripe\Forms\DropdownField;
use SilverStripe\Forms\FieldGroup;
use SilverStripe\Forms\FieldList;
use SilverStripe\Forms\NumericField;
use SilverStripe\Forms\ReadonlyField;
use SilverStripe\Forms\TextField;
use SilverStripe\ORM\DataObject;
use SilverStripe\ORM\ValidationResult;

/**
 * Class Variation
 * @package Dynamic\Foxy\Model
 */
class Variation extends DataObject
{
    /**
     * @var string
     */
    private static $table_name = 'Variation';

    /**
     * @var string
     */
    private static $singular_name = 'Variation';

    /**
     * @var string
     */
    private static $plural_name = 'Variations';

    /**
     * @var string[]
     */
    private static $db = [
        'Title' => 'Varchar(255)',
        'UseProductContent' => 'Boolean',
        'Content' => 'HTMLText',
        'WeightModifier' => 'Decimal(9,3)',
        'CodeModifier' => 'Text',
        'PriceModifier' => 'Currency',
        'WeightModifierAction' => "Enum('Add,Subtract,Set', null)",
        'CodeModifierAction' => "Enum('Add,Subtract,Set', null)",
        'PriceModifierAction' => "Enum('Add,Subtract,Set', null)",
        'Available' => 'Boolean',
        'Type' => 'Int',
        'OptionModifierKey' => 'Varchar(255)',
        'SortOrder' => 'Int',
    ];

    /**
     * @var string[]
     */
    private static $has_one = [
        'VariationType' => VariationType::class,
    ];

    /**
     * @var array
     */
    private static $many_many = [
        'Images' => File::class,
    ];

    /**
     * @var \string[][]
     */
    private static $many_many_extraFields = [
        'Images' => [
            'SortOrder' => 'Int',
        ],
    ];

    /**
     * @var string[]
     */
    private static $owns = [
        'Images',
    ];

    /**
     * The relation name was established before requests for videos.
     * The relation has subsequently been updated from Image::class to File::class
     * to allow for additional file types such as mp4
     *
     * @var array
     */
    private static $allowed_images_extensions = [
        'gif',
        'jpeg',
        'jpg',
        'png',
        'bmp',
        'ico',
        'mp4',
    ];

    /**
     * @return FieldList
     */
    public function getCMSFields()
    {
        $this->beforeUpdateCMSFields(function (FieldList $fields) {
            $fields->removeByName([
                'Images',
                'WeightModifier',
                'CodeModifier',
                'PriceModifier',
                'WeightModifierAction',
                'CodeModifierAction',
                'PriceModifierAction',
                'Available',
                'Type',
                'OptionModifierKey',
                'SortOrder',
                'ProductID',
            ]);

            $fields->insertBefore(
                'Content',
                CheckboxField::create('Available')
                    ->setTitle('Available for purchase')
            );

            $fields->insertBefore(
                'Content',
                $fields->dataFieldByName('VariationTypeID')
            );

            $fields->insertBefore(
                'Content',
                $fields->dataFieldByName('UseProductContent')
            );

            $content = $fields->dataFieldByName('Content');

            $content->hideUnless('UseProductContent')->isNotChecked()->end();

            if ($this->exists()) {
                $fields->addFieldToTab(
                    'Root.ProductModifications',
                    ReadonlyField::create('OptionModifierKey')
                        ->setTitle(_t('Variation.ModifierKey', 'Modifier Key'))
                );
            }

            $fields->addFieldsToTab(
                'Root.ProductModifications',
                [
                    FieldGroup::create(
                        DropdownField::create(
                            'WeightModifierAction',
                            _t('Variation.WeightModifierAction', 'Weight Modification Type'),
                            [
                                'Add' => _t(
                                    'Variation.WeightAdd',
                                    'Add to Base Weight',
                                    'Add to weight'
                                ),
                                'Subtract' => _t(
                                    'Variation.WeightSubtract',
                                    'Subtract from Base Weight',
                                    'Subtract from weight'
                                ),
                                'Set' => _t('Variation.WeightSet', 'Set as a new Weight'),
                            ]
                        )
                            ->setEmptyString('')
                            ->setDescription(_t(
                                'Variation.WeightDescription',
                                'Does weight modify or replace base weight?'
                            )),
                        NumericField::create("WeightModifier")
                            ->setTitle(_t('Variation.WeightModifier', 'Weight'))
                            ->setScale(3)
                            ->setDescription(_t(
                                'Variation.WeightDescription',
                                'Only supports up to 3 decimal places'
                            ))->displayIf('WeightModifierAction')->isNotEmpty()->end()
                    )->setTitle('Weight Modification'),

                    // Price Modifier Fields
                    //HeaderField::create('PriceHD', _t('Variation.PriceHD', 'Modify Price'), 4),
                    FieldGroup::create(
                        DropdownField::create(
                            'PriceModifierAction',
                            _t('Variation.PriceModifierAction', 'Price Modification Type'),
                            [
                                'Add' => _t(
                                    'Variation.PriceAdd',
                                    'Add to Base Price',
                                    'Add to price'
                                ),
                                'Subtract' => _t(
                                    'Variation.PriceSubtract',
                                    'Subtract from Base Price',
                                    'Subtract from price'
                                ),
                                'Set' => _t('Variation.PriceSet', 'Set as a new Price'),
                            ]
                        )
                            ->setEmptyString('')
                            ->setDescription(_t('Variation.PriceDescription', 'Does price modify or replace base price?')),
                        CurrencyField::create('PriceModifier')
                            ->setTitle(_t('Variation.PriceModifier', 'Price'))
                            ->displayIf('PriceModifierAction')->isNotEmpty()->end()
                    )->setTitle('Price Modifications'),

                    // Code Modifier Fields
                    //HeaderField::create('CodeHD', _t('Variation.CodeHD', 'Modify Code'), 4),
                    FieldGroup::create(
                        DropdownField::create(
                            'CodeModifierAction',
                            _t('Variation.CodeModifierAction', 'Code Modification Type'),
                            [
                                'Add' => _t(
                                    'Variation.CodeAdd',
                                    'Add to Base Code',
                                    'Add to code'
                                ),
                                'Subtract' => _t(
                                    'Variation.CodeSubtract',
                                    'Subtract from Base Code',
                                    'Subtract from code'
                                ),
                                'Set' => _t('Variation.CodeSet', 'Set as a new Code'),
                            ]
                        )
                            ->setEmptyString('')
                            ->setDescription(_t('Variation.CodeDescription', 'Does code modify or replace base code?')),
                        TextField::create('CodeModifier')
                            ->setTitle(_t('Variation.CodeModifier', 'Code'))
                            ->displayIf('CodeModifierAction')->isNotEmpty()->end()
                    )->setTitle('Code Modification'),
                ]
            );

            // Images tab
            $images = SortableUploadField::create('Images')
                ->setSortColumn('SortOrder')
                ->setIsMultiUpload(true)
                ->setAllowedExtensions($this->config()->get('allowed_images_extensions'))
                ->setFolderName('Uploads/Products/Images');

            $fields->addFieldsToTab('Root.Images', [
                $images,
            ]);
        });

        return parent::getCMSFields();
    }

    /**
     *
     */
    public function onBeforeWrite()
    {
        parent::onBeforeWrite();

        $modifierKeyField = 'OptionModifierKey';
        $this->{$modifierKeyField} = $this->getGeneratedValue();

        $codeModifierField = 'CodeModifier';
        switch ($this->CodeModifierAction) {
            case 'Subtract':
            case 'Add':
                if ($this->config()->get('trimAllWhitespace') == false) {
                    // trim the right of the code - some companies use spaces to denote options
                    $trimmed = rtrim($this->{$codeModifierField});
                    // replace duplicate spaces
                    $this->{$codeModifierField} = preg_replace('/\s+/', ' ', $trimmed);
                    break;
                }
            /* falls through */
            case 'Set':
                $trimmed = trim($this->{$codeModifierField});
                $this->{$codeModifierField} = preg_replace('/\s+/', ' ', $trimmed);
                break;
        }
    }

    /**
     * @return ValidationResult
     */
    public function validate()
    {
        $validate = parent::validate();

        if (!$this->Title) {
            $validate->addFieldError('Title', 'A title is required');
        }

        if (!$this->VariationTypeID) {
            $validate->addFieldError('VariationTypeID', 'A variation type is required');
        }

        return $validate;
    }

    /**
     * @return string
     */
    public function getGeneratedValue()
    {
        $modPrice = ($this->PriceModifier) ? (string)$this->PriceModifier : '0';
        $modPriceWithSymbol = self::getOptionModifierActionSymbol($this->PriceModifierAction) . $modPrice;
        $modWeight = ($this->WeightModifier) ? (string)$this->WeightModifier : '0';
        $modWeight = self::getOptionModifierActionSymbol($this->WeightModifierAction) . $modWeight;
        $modCode = self::getOptionModifierActionSymbol($this->CodeModifierAction) . $this->CodeModifier;

        return $this->Title . '{p' . $modPriceWithSymbol . '|w' . $modWeight . '|c' . $modCode . '}';
    }

    /**
     * @param $oma
     * @param bool $returnWithOnlyPlusMinus
     *
     * @return string
     */
    public static function getOptionModifierActionSymbol($oma, $returnWithOnlyPlusMinus = false)
    {
        switch ($oma) {
            case 'Subtract':
                $symbol = '-';
                break;
            case 'Set':
                $symbol = ($returnWithOnlyPlusMinus) ? '' : ':';
                break;
            default:
                $symbol = '+';
        }

        return $symbol;
    }

    /**
     * @return string
     */
    protected function getWeightModifierWithSymbol()
    {
        return $this->getOptionModifierActionSymbol($this->WeightModifierAction) . $this->WeightModifier;
    }

    /**
     * @return string
     */
    protected function getPriceModifierWithSymbol()
    {
        return $this->getOptionModifierActionSymbol($this->PriceModifierAction) . $this->PriceModifier;
    }

    /**
     * @return string
     */
    protected function getCodeModifierWithSymbol()
    {
        return $this->getOptionModifierActionSymbol($this->CodeModifierAction) . $this->CodeModifier;
    }
}
