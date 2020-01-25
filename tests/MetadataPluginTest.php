<?php

declare(strict_types=1);

namespace Datashaman\Tongs\Plugins\Tests;

use Datashaman\Tongs\Tongs;
use Datashaman\Tongs\Plugins\MetadataPlugin;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\File;
use Symfony\Component\Yaml\Yaml;

class MetadataPluginTest extends TestCase
{
    public function testJson()
    {
        $tongs = new Tongs($this->fixture('json'));
        $tongs->use(new MetadataPlugin($tongs, [
            'author' => 'author.json',
        ]));

        $files = $tongs->build();
        $metadata = $tongs->metadata();

        $fullPath = "{$tongs->source()}/author.json";

        $this->assertEquals(json_decode(File::get($fullPath), true), $metadata['author']);
        $this->assertDirEquals($this->fixture('json/expected'), $this->fixture('json/build'));
    }

    public function testYaml()
    {
        $tongs = new Tongs($this->fixture('yaml'));
        $tongs->use(new MetadataPlugin($tongs, [
            'author' => 'author.yaml',
        ]));

        $files = $tongs->build();
        $metadata = $tongs->metadata();

        $fullPath = "{$tongs->source()}/author.yaml";

        $this->assertEquals(Yaml::parse(File::get($fullPath), Yaml::PARSE_DATETIME), $metadata['author']);
        $this->assertDirEquals($this->fixture('yaml/expected'), $this->fixture('yaml/build'));
    }
}
