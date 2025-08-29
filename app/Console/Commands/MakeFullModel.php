<?php

namespace App\Console\Commands;

use Illuminate\Support\Str;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class MakeFullModel extends Command
{
    protected $signature = 'make:full-model {name}';
    protected $description = 'Create a model along with traits for relations, accessors, and mutators.';

    public function handle()
    {
        $name = $this->argument('name');
        $modelPath = app_path("Models/{$name}");
        $traitsPath = "{$modelPath}";

        // Ensure directories exist
        File::ensureDirectoryExists($modelPath);
        File::ensureDirectoryExists("{$modelPath}/Relations");
        File::ensureDirectoryExists("{$modelPath}/Accessors");
        File::ensureDirectoryExists("{$modelPath}/Mutators");

        // Create Model
        $this->createModel($name, $modelPath);

        // Create Traits
        $this->createTrait($name, 'Relations', $traitsPath);
        $this->createTrait($name, 'Accessors', $traitsPath);
        $this->createTrait($name, 'Mutators', $traitsPath);

        // Create Migration if --m option is provided
        $tableName = Str::plural(Str::snake($name)); // Converts "Ticket" to "tickets"
        $this->call('make:migration', ['name' => "create_{$tableName}_table"]);

        // Create Observer
        $this->call('make:observer', [
            'name' => "Administration/{$name}/{$name}Observer",
            '--model' => "{$name}/{$name}",
        ]);


        $this->info("Full model structure for {$name} created successfully!");
    }

    private function createModel($name, $path)
    {
        $modelTemplate = <<<EOT
        <?php

        namespace App\Models\\{$name};

        use Illuminate\Database\Eloquent\Model;
        use Illuminate\Database\Eloquent\SoftDeletes;
        use App\Models\\{$name}\Mutators\\{$name}Mutators;
        use App\Models\\{$name}\Accessors\\{$name}Accessors;
        use App\Models\\{$name}\Relations\\{$name}Relations;
        use Illuminate\Database\Eloquent\Factories\HasFactory;
        use Illuminate\Database\Eloquent\Attributes\ObservedBy;
        use App\Observers\Administration\\{$name}\\{$name}Observer;

        #[ObservedBy([{$name}Observer::class])]
        class {$name} extends Model
        {
            use HasFactory, SoftDeletes;

            // Relations
            use {$name}Relations;

            // Accessors & Mutators
            use {$name}Accessors, {$name}Mutators;

            protected \$casts = [];

            protected \$fillable = [];
        }
        EOT;

        File::put("{$path}/{$name}.php", $modelTemplate);
    }

    private function createTrait($name, $type, $path)
    {
        $traitTemplate = <<<EOT
        <?php

        namespace App\Models\\{$name}\\{$type};

        trait {$name}{$type}
        {
            //
        }
        EOT;

        File::put("{$path}/{$type}/{$name}{$type}.php", $traitTemplate);
    }
}
