<?php

namespace SAREhub\Commons\Misc;

use PHPUnit\Framework\TestCase;

class ArrayHelperTest extends TestCase
{

    public function testFlatten()
    {
        $input = $arr = [
            'k1' => 'val1',
            'k2' => [
                'subk2' => 'subk2val'
            ],
            'k3' => 'val3',
            'k4' => [
                'subk4' => [
                    'subsubk4' => 'subsubkval4',
                    'subsubk5' => 'subsubkval5',
                ],
                'subk5' => 'subkval5'
            ]
        ];

        $this->assertEquals([
            'k1' => 'val1',
            'k2.subk2' => 'subk2val',
            'k3' => 'val3',
            'k4.subk4.subsubk4' => 'subsubkval4',
            'k4.subk4.subsubk5' => 'subsubkval5',
            'k4.subk5' => 'subkval5'
        ], ArrayHelper::flatten($input));
    }

    public function testGroupBy()
    {
        $records = $this->createRecordsForGroupBy();
        $expected = [
            'a1' => [
                $records[0],
                $records[2]
            ],
            'a2' => [
                $records[1]
            ],
            'a3' => [
                $records[3]
            ]
        ];

        $this->assertEquals($expected, ArrayHelper::groupBy($records, function ($record) {
            return $record['k'];
        }));
    }

    public function testGroupByKey()
    {
        $records = $this->createRecordsForGroupBy();
        $expected = [
            'a1' => [
                $records[0],
                $records[2]
            ],
            'a2' => [
                $records[1]
            ],
            'a3' => [
                $records[3]
            ]
        ];

        $this->assertEquals($expected, ArrayHelper::groupByKey($records, 'k'));
    }

    private function createRecordsForGroupBy()
    {
        return [
            ['k' => 'a1', 'data' => 1],
            ['k' => 'a2', 'data' => 2],
            ['k' => 'a1', 'data' => 3],
            ['k' => 'a3', 'data' => 4]
        ];
    }
}
