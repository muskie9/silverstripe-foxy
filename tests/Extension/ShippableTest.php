<?php

namespace Dynamic\Foxy\Test\Extension;

use Dynamic\Foxy\Extension\Purchasable;
use Dynamic\Foxy\Test\TestOnly\TestShippableProduct;
use SilverStripe\Core\Injector\Injector;
use SilverStripe\Dev\Debug;
use SilverStripe\Dev\SapphireTest;
use SilverStripe\Forms\FieldList;

class ShippableTest extends SapphireTest
{
    protected static $fixture_file = '../fixtures.yml';

    public function testUpdateCMSFields()
    {
        $object = Injector::inst()->create(TestShippableProduct::class);
        $fields = $object->getCMSFields();
        $this->assertInstanceOf(FieldList::class, $fields);
    }
}
