<?php

namespace Dynamic\Foxy\Test\Model;

use Dynamic\Foxy\Model\FoxyCategory;
use Dynamic\Foxy\Model\OptionType;
use SilverStripe\Core\Injector\Injector;
use SilverStripe\Dev\SapphireTest;
use SilverStripe\Forms\FieldList;
use SilverStripe\Security\Member;

class OptionTypeTest extends SapphireTest
{
    /**
     * @var string
     */
    protected static $fixture_file = '../fixtures.yml';

    /**
     *
     */
    public function testGetCMSFields()
    {
        $object = Injector::inst()->create(OptionType::class);
        $fields = $object->getCMSFields();
        $this->assertInstanceOf(FieldList::class, $fields);

        $object->write();
        $fields = $object->getCMSFields();
        $this->assertInstanceOf(FieldList::class, $fields);
    }

    /**
     *
     */
    public function testCanCreate()
    {
        /** @var OptionType $object */
        $object = singleton(OptionType::class);
        /** @var \SilverStripe\Security\Member $admin */
        $admin = $this->objFromFixture(Member::class, 'admin');
        /** @var \SilverStripe\Security\Member $siteOwner */
        $siteOwner = $this->objFromFixture(Member::class, 'site-owner');
        /** @var \SilverStripe\Security\Member $default */
        $default = $this->objFromFixture(Member::class, 'default');

        $this->assertFalse($object->canCreate($default));
        $this->assertTrue($object->canCreate($admin));
        $this->assertTrue($object->canCreate($siteOwner));
    }

    /**
     *
     */
    public function testCanEdit()
    {
        /** @var OptionType $object */
        $object = singleton(OptionType::class);
        /** @var \SilverStripe\Security\Member $admin */
        $admin = $this->objFromFixture(Member::class, 'admin');
        /** @var \SilverStripe\Security\Member $siteOwner */
        $siteOwner = $this->objFromFixture(Member::class, 'site-owner');
        /** @var \SilverStripe\Security\Member $default */
        $default = $this->objFromFixture(Member::class, 'default');

        $this->assertFalse($object->canEdit($default));
        $this->assertTrue($object->canEdit($admin));
        $this->assertTrue($object->canEdit($siteOwner));
    }

    /**
     *
     */
    public function testCanDelete()
    {
        /** @var OptionType $object */
        $object = singleton(OptionType::class);
        /** @var \SilverStripe\Security\Member $admin */
        $admin = $this->objFromFixture(Member::class, 'admin');
        /** @var \SilverStripe\Security\Member $siteOwner */
        $siteOwner = $this->objFromFixture(Member::class, 'site-owner');
        /** @var \SilverStripe\Security\Member $default */
        $default = $this->objFromFixture(Member::class, 'default');

        $this->assertFalse($object->canDelete($default));
        $this->assertTrue($object->canDelete($admin));
        $this->assertTrue($object->canDelete($siteOwner));
    }
}
