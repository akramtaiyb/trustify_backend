<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Publication;

class RecalculatePublicationScores extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'publications:recalculate-scores';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Reset and recalculate classification scores for all publications';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        // Fetch all publications
        $publications = Publication::all();

        // Loop through each publication and recalculate the score
        foreach ($publications as $publication) {
            $this->info('Recalculating score for publication ID: ' . $publication->id);

            // Reset score
            $publication->classification_score = 0;

            // Call the updateClassificationScore method you created in the model
            $publication->updateClassificationScore();
        }

        $this->info('All publication scores have been recalculated.');

        return 0;
    }
}
