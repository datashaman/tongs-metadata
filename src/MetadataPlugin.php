<?php

namespace Datashaman\Tongs\Plugins;

use Datashaman\Tongs\Plugins\Plugin;
use Datashaman\Tongs\Tongs;
use Illuminate\Support\Facades\File;
use Symfony\Component\Yaml\Yaml;

class MetadataPlugin extends Plugin
{
    /**
     * Handle files passed down the pipeline, and call the next plugin in the pipeline.
     *
     * @param array $files
     * @param callable $next
     *
     * @return array
     */
    public function handle(array $files, callable $next): array
    {
        $metadata = $this->tongs()->metadata();

        foreach ($this->options as $key => $path) {
            $contents = $this->tongs()->source()->get($path);
            $extension = File::extension($path);

            switch ($extension) {
            case 'json':
                $data = json_decode($contents, true);
                break;
            case 'yaml':
            case 'yml':
                $data = Yaml::parse($contents, Yaml::PARSE_DATETIME);
                break;
            default:
                throw new Exception('Unhandled file type: ' . $extension);
            }

            $metadata = array_merge(
                $metadata,
                [$key => $data]
            );

            unset($files[$path]);
        }

        $this->tongs()->metadata($metadata);

        return $next($files);
    }
}
