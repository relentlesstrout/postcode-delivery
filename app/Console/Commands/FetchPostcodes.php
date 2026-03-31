<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use ZipArchive;

class FetchPostcodes extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:fetch-postcodes';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetches all UK Postcodes';

    /**
     * Execute the console command.
     */
    private const string ZIP_URL = 'https://data.freemaptools.com/download/full-uk-postcodes/ukpostcodes.zip';

    private const int CHUNK_SIZE = 500;
    private const int CHUNK_SIZE_COUNT = 8192;

    /**
     * @throws ConnectionException
     */
    public function handle(): int
    {
        $this->info('Fetching postcodes...');

        $storageDisk = 'public';
        $storageDir = 'postcodes';
        $zipStoragePath = "{$storageDir}/ukpostcodes.zip";
        $csvStoragePath = "{$storageDir}/ukpostcodes.csv";

        Storage::disk($storageDisk)->makeDirectory($storageDir);

        $zipAbsPath = Storage::disk($storageDisk)->path($zipStoragePath);
        $csvAbsPath = Storage::disk($storageDisk)->path($csvStoragePath);

        $response = Http::sink($zipAbsPath)
            ->get(self::ZIP_URL);

        if (! $response->successful()) {
            $this->error('Postcodes fetch failed. HTTP'.$response->status());

            return 1;
        }

        $this->info('Download Complete');

        $this->info('Opening Zip file...');

        $zip = new ZipArchive;

        if ($zip->open($zipAbsPath) === true) {
            $zip->extractTo(Storage::disk($storageDisk)->path($storageDir));
            $zip->close();
        } else {
            $this->error('Failed to open zip file.');

            return 1;
        }

        $csv = fopen($csvAbsPath, 'r');

        fgetcsv($csv); // Skip the headers

        $lineCount = $this->countLines($csv);

        $this->info('Inserting postcodes into database...');

        $progressBar = $this->output->createProgressBar($lineCount);
        $progressBar->start();

        rewind($csv);
        fgetcsv($csv); // Skip the headers

        $postcodes_count = $this->insertPostcodes($csv, $progressBar);

        fclose($csv);

        Storage::disk($storageDisk)->deleteDirectory($storageDir);

        $progressBar->finish();

        $this->info(" Postcodes fetched successfully. '$lineCount' postcodes processed. '$postcodes_count' postcodes inserted into database.");

        return 0;
    }

    private function insertPostcodes($csv, $progressBar): int
    {
        $postcode_count = 0;

        while (($row = fgetcsv($csv)) !== false) {
            [$id, $postcode, $latitude, $longitude] = $row;
            if ($latitude === '' || $longitude === '') {
                continue;
            }
            $chunk[] = [
                'postcode' => preg_replace('/\W/', '', $postcode), // Remove all non-alphanumeric characters (e.g. '),
                'latitude' => $latitude,
                'longitude' => $longitude,
                'created_at' => now(),
                'updated_at' => now(),
            ];

            if (count($chunk) >= self::CHUNK_SIZE) {
                DB::table('postcodes')->insertOrIgnore($chunk);
                $postcode_count += count($chunk);
                $chunk = [];
                $progressBar->advance(self::CHUNK_SIZE);
            }
        }
        if (! empty($chunk)) {
            DB::table('postcodes')->insertOrIgnore($chunk);
            $postcode_count += count($chunk);
            $progressBar->advance(count($chunk));
        }
        return $postcode_count;
    }

    public function countLines($csv): int
    {
        $lineCount = 0;

        while (! feof($csv)) {
            $lineCount += substr_count(fread($csv, self::CHUNK_SIZE_COUNT), "\n");
        }

        return $lineCount;
    }
}
