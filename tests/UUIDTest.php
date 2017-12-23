<?php

namespace projectmentor\UUID\Test;

use UUID;
class UUIDTest extends TestCase
{
    /**
     * @test
     * @group uuid_v4
     * @return void
     */
    public function test_v4()
    {
        $result = UUID::v4();
        $regex = '/[A-F0-9]{8}\-[A-F0-9]{4}\-4[A-F0-9]{3}\-(8|9|A|B)[A-F0-9]{3}\-[A-F0-9]{12}/';
        $this->assertEquals(1, preg_match($regex, $result, $m));
    }
}
