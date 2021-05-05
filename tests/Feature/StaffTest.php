<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class StaffTest extends TestCase
{
   public function testGetAllStaffData()
   {
       // functions accordonig to return 
       $allstaff = $this->json('GET','/api/admin/staff/all');
    //    dd($allstaff);
       $allstaff->assertStatus(200);
   }
}
