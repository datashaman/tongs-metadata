<?php

namespace Datashaman\Tongs\Plugins;

use Datashaman\Tongs\Plugins\Plugin;
use Datashaman\Tongs\Tongs;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\File;
use Symfony\Component\Yaml\Yaml;

class MetadataPlugin extends Plugin
{
    /**
     * Handle files passed down the pipeline, and call the next plugin in the pipeline.
     *
     * @param Collection $files
     * @param callable $next
     *
     * @return Collection
     */
    public function handle(Collection $files, callable $next): Collection
    {
        $metadata = $this->tongs()->metadata();

        $this
            ->options
            ->each(
                function ($path, $key) use ($files, &$metadata) {
                    $fullPath = "{$this->tongs()->source()}/{$path}";
                    $contents = File::get($fullPath);
                    $extension = File::extension($fullPath);

                    switch ($extension) {
                    case 'json':
                        $data = json_decode($contents, true);
                        break;
                    case 'yaml':
                    case 'yml':
                        $data = Yaml::parse($contents, Yaml::PARSE_DATETIME);
                        break;
                    default:
                        throw new Exception('Unhandle file type: ' . $extension);
                    }

                    $metadata = array_merge(
                        $metadata,
                        [$key => $data]
                    );

                    $files->forget($path);
                }
            );

        $this->tongs()->metadata($metadata);

        return $next($files);
    }
}
