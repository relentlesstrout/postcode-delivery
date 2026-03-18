<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\Console\Command\Command as CommandAlias;
use ZipArchive;
use function Laravel\Prompts\progress;

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

    /**
     * @throws ConnectionException
     */
    public function handle()
    {
        $this->info('Fetching postcodes...');

        $storageDisk = 'public';
        $storageDir = 'postcodes';
        $zipStoragePath = "{$storageDir}/ukpostcodes.zip";
        $csvStoragePath = "{$storageDir}/ukpostcodes.csv";

        Storage::disk($storageDisk)->makeDirectory($storageDir);

        $zipAbsPath = Storage::disk($storageDisk)->path($zipStoragePath);
        $csvAbsPath = Storage::disk($storageDisk)->path($csvStoragePath);

        $response = HTTP::sink($zipAbsPath)
            ->get(self::ZIP_URL);

        if (! $response->successful()) {
            $this->error('Postcodes fetch failed. HTTP' . $response->status());
            return 1;
        }

        $this->info('Download Complete');

        $this->info('Opening Zip file...');

        $zip = new ZipArchive();

        if ($zip->open($zipAbsPath) === true) {
            $zip->extractTo(Storage::disk($storageDisk)->path($storageDir));
            $zip->close();
        } else {
            $this->error('Failed to open zip file.');
            return 1;
        }

        $csv = fopen($csvAbsPath, "r");

        $header = fgetcsv($csv); //Skip the headers

        $line_count = 0;

        $this->info('Inserting postcodes into database...');

        $chunk = [];

//        $inserts = progress(
//            label: 'Inserting postcodes into database...',
//            steps: $line_count / 500,
//            callback: function () use ($csv, $chunk) {
//                $this->insertPostcodes($csv, $chunk);
//            }
//        );

        $this->insertPostcodes($csv, $chunk);;

        fclose($csv);

        Storage::disk($storageDisk)->deleteDirectory($storageDir);

        $this->info("Postcodes fetched successfully.  postcodes inserted. ");
        return 0;
    }

    private function insertPostcodes($csv, $chunk) {
        while (($row = fgetcsv($csv)) !== false) {
            [$id, $postcode, $latitude, $longitude] = $row;
            if ($latitude === '' || $longitude === '') {
                continue;
            }
            $chunk[] = [
                'postcode' => preg_replace( '/\W/', '', $postcode), //Remove all non-alphanumeric characters (e.g. '),
                'latitude' => $latitude,
                'longitude' => $longitude,
                'created_at' => now(),
                'updated_at' => now(),
            ];

            if (count($chunk) >= 500) {
                DB::table('postcodes')->insertOrIgnore($chunk);
                $chunk = [];
            }
        }
        if (! empty($chunk)) {
            DB::table('postcodes')->insertOrIgnore($chunk);
        }
    }
}
