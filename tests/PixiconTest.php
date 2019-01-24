<?php
use PHPUnit\Framework\TestCase;
use Identicons\Pixicon;
use const Identicons\PLUGIN_SLUG;
class PixiconTest extends TestCase
{
    protected $hash;
    protected $folder;
    protected $file;
    protected function setUp()
    {
        $this->hash = md5(strtolower(trim('user@example.com')));
        $wp_upload_dir = wp_upload_dir();
        $this->folder = sprintf(
            '%s/%s/%s/',
            untrailingslashit($wp_upload_dir['basedir']),
            untrailingslashit(PLUGIN_SLUG),
            untrailingslashit($this->hash)
        );
        $this->file = sprintf(
            '%spixicon.png',
            $this->folder
        );
        if (file_exists($this->file)) {
            unlink($this->file);
        }
        if (file_exists($this->folder)) {
            rmdir($this->folder);
        }
    }
    public function testFileIsCreated()
    {
        $this->assertFileNotExists($this->file);
        $pixicon = new Pixicon($this->hash);
        $pixicon->create();
        $this->assertFileExists($this->file);
    }
    protected function tearDown()
    {
        if (file_exists($this->file)) {
            unlink($this->file);
        }
        if (file_exists($this->folder)) {
            rmdir($this->folder);
        }
    }
}
