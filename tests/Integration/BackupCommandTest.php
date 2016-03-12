<?php

namespace Spatie\Backup\Test\Integration;

use Illuminate\Support\Facades\Artisan;

class BackupCommandTest extends TestCase
{
    public function setUp()
    {
        parent::setUp();
    }

    /** @test */
    public function it_can_backup_only_the_files()
    {
        $resultCode = Artisan::call('backup:run', ['--only-files' => true]);

        $this->assertEquals(0, $resultCode);

        $this->assertFileWithExtensionExistsInDirectoryOnDisk('zip', 'mysite.com', 'local');
    }

    /** @test */
    public function it_will_fail_when_try_to_backup_only_the_files_and_only_the_db()
    {
        $resultCode = Artisan::call('backup:run', [
            '--only-files' => true,
            '--only-db' => true,
        ]);

        $this->assertEquals(-1, $resultCode);

        $this->seeInConsoleOutput('Cannot use only-db and only-files together');
    }

    /** @test */
    public function it_will_fail_when_trying_to_backup_to_an_non_existing_diskname()
    {
        $resultCode = Artisan::call('backup:run', [
            '--backup-only-to' => 'non existing disk',
        ]);

        $this->assertEquals(-1, $resultCode);

        $this->seeInConsoleOutput('There is not backup destination with a disk named');
    }
}
