<?php

use App\Services\DateRefiner;
use Carbon\Carbon;

class DateRefinerTest extends \PHPUnit_Framework_TestCase
{

    /** @var Carbon $aMonday*/
    protected $aMonday;

    public function setUp()
    {
        parent::setUp();
        $this->aMonday = Carbon::createFromDate(2017, 1, 2);
    }

    public function testPassNull()
    {
        $refine = null;
        $date = $this->aMonday;

        $this->assertTrue(DateRefiner::datePassRefine($date, $refine));
    }

    public function testPassBlankArray()
    {
        $refine = [];
        $date = $this->aMonday;

        $this->assertTrue(DateRefiner::datePassRefine($date, $refine));
    }

    public function testPassAll()
    {
        $refine = ['all'];
        $date = $this->aMonday;

        $this->assertTrue(DateRefiner::datePassRefine($date, $refine));
    }

    public function testPassMondays()
    {
        $refine = ['mondays'];
        $date = $this->aMonday;

        $this->assertTrue(DateRefiner::datePassRefine($date, $refine));
    }

    public function testDontPassMondays()
    {
        $refine = ['mondays'];
        $date = $this->aMonday->addDay();

        $this->assertFalse(DateRefiner::datePassRefine($date, $refine));
    }

    public function testPassMondaysOrFridays()
    {
        $refine = ['mondays', 'fridays'];
        $date = $this->aMonday;

        $this->assertTrue(DateRefiner::datePassRefine($date, $refine));
    }

    public function testPassWeekdays()
    {
        $refine = ['weekdays'];
        $date = $this->aMonday;

        $this->assertTrue(DateRefiner::datePassRefine($date, $refine));
    }

    public function testPassFridaysOrAll()
    {
        $refine = ['fridays', 'all'];
        $date = $this->aMonday;

        $this->assertTrue(DateRefiner::datePassRefine($date, $refine));
    }
}
