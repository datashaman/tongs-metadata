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
        $tongs->clean(true);
        $tongs->use(new MetadataPlugin([
            'author' => 'author.json',
        ]));

        $files = $tongs->build();
        $metadata = $tongs->metadata();

        $this->assertEquals(json_decode($tongs->source()->get('author.json'), true), $metadata['author']);
        $this->assertDirEquals($this->fixture('json/expected'), $this->fixture('json/build'));
    }

    public function testYaml()
    {
        $tongs = new Tongs($this->fixture('yaml'));
        $tongs->clean(true);
        $tongs->use(new MetadataPlugin([
            'author' => 'author.yaml',
        ]));

        $files = $tongs->build();
        $metadata = $tongs->metadata();

        $this->assertEquals(Yaml::parse($tongs->source()->get('author.yaml'), Yaml::PARSE_DATETIME), $metadata['author']);
        $this->assertDirEquals($this->fixture('yaml/expected'), $this->fixture('yaml/build'));
    }
}
