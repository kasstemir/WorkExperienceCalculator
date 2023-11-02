<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use App\Models\WorkExperience;


class ExperienceTest extends TestCase{
    /**
     * @dataProvider experienceProvider
     */
    public function testAddMethod($data, $expectedTotalMonths)
    {
        $exp = new WorkExperience();
        $totalMonths = $exp->calculate($data[0], $data[1]);

        $this->assertSame("Work Experience in Months : $expectedTotalMonths", $totalMonths);
    }

    public static function experienceProvider(){
        return [
            [
                [
                    ["2000-02-15", "2005-11-30", "2007-08-21", "2008-04-13", "2012-03-17", "2017-03-09", "2018-09-15"],
                    ["2003-12-05", "2007-10-15", "2010-07-18", "2012-12-01", "2016-11-24", "2018-02-07", "2019-11-22"]
                ],
                203,
            ],
            [
                [
                    ["2000-01-01", "2004-12-31", "2007-06-01", "2008-01-01", "2021-01-01", "2000-02-02"],
                    ["2013-02-01", "2016-07-01", "2019-05-31", "2021-12-31", "2030-12-31", "2041-09-04"]
                ],
                500,
            ],
            [
                [
                    ["2000-01-01", "2004-12-31", "2007-06-01", "2008-01-01", "2012-01-01"],
                    ["2003-06-01", "2007-05-31", "2010-05-31", "2012-12-31", "2016-12-31"]
                ],
                184,
            ],
            [
                [
                    ["2000-01-01", "2004-12-31", "2007-06-01", "2008-01-01", "2012-01-01"],
                    ["2001-02-01", "2006-07-01", "2009-05-31", "2011-12-31", "2015-12-31"]
                ],

                133,
            ]
        ];
    }
}




