<?php

namespace ACFBentveld\ASeeder\Commands;

use Illuminate\Console\Command;
use Schema;
use DB;

class MakeSeed extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:aseed';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command to make new seed files';

    /**
     * To migrate all tables or not
     *
     * @var bool
     */
    protected $allTables = false;

    /**
     * Relative path to the folder
     *
     * @var string
     */
    protected $path = "database/seeds/";

    /**
     * Truncate the table before inserting
     */
    protected $truncate = true;

    /**
     * Seeded files
     *
     * @var string
     */
    protected $seeds = "";

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->stubFiles = __DIR__ . '/../Stubs/';
    }


    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->path = $this->ask("Where do you want to store the seed files?", $this->path);
        if(!is_dir(base_path().'/'.$this->path)){
            $this->error(base_path().'/'.$this->path.' is not a valid path!');
            $this->info('all done!');
            return false;
        }
        if ($this->confirm("Create seeds for all tables?")) {
            $this->allTables = true;
        }
        if (!$this->confirm("Truncate before seeding?")) {
            $this->truncate = false;
        }
        
        $this->loopTables();
        $this->appendToDatabaseFile();
        $this->info('all done!');
    }

    /**
     * Loop the tables. Add to the seed array
     *
     */
    private function loopTables()
    {
        $tables = $this->getTables();
        foreach($tables as $table){
            $this->table = $table;
            $this->tableName = studly_case($table);
            $this->parseRows();
            $stub = $this->parse(\File::get($this->stubFiles . '/seed.stub'));
            \File::put(base_path().'/'.$this->path.'/'.$this->tableName.'TableSeeder.php', $stub);
            $this->seeds .= "\t\t\t".$this->tableName."TableSeeder::class,\n";
        }
    }

    /**
     * Create or update the DatabaseSeeder file
     *
     * @return void
     */
    private function appendToDatabaseFile()
    {
        if(!\File::exists(base_path().'/'.$this->path.'/DatabaseSeeder.php')){
           return $this->createDatabaseFile();
        }
        $file = \File::get($this->stubFiles . '/DatabaseSeeder.stub');
        $parsed  = $this->get_string_between($file, 'public function run()', '}');
        $newLine = "$parsed\n\t\t\$this->call([\n";
        $newLine .= $this->seeds;
        $newLine .= "\t\t]);";
        $lines = $this->replace_string_between($file, $newLine, $parsed);
        \File::put(base_path().'/'.$this->path.'/DatabaseSeeder.php', $lines);
    }

    /**
     * Create the DatabaseSeeder file
     *
     */
    private function createDatabaseFile()
    {
        $file = \File::get($this->stubFiles . '/DatabaseSeeder.stub');
        $replaced = str_replace("%SEEDS%", $this->seeds, $file);
        \File::put(base_path().'/'.$this->path.'/DatabaseSeeder.php', $replaced);
    }

    /**
     * Parse the rows
     * 
     */
    private function parseRows()
    {
        $items = DB::table($this->table)->get();
        $this->rows = "";
        foreach($items as $index => $row){
            $this->rows .= "\t\t\t$index => array(  \n";
            foreach($row as $key => $value){
                $newValue = $this->createValue($value);
                $this->rows .= "\t\t\t\t'$key' => $newValue, \n";
            }
            $this->rows .= "\t\t\t), \n";
        }
    }

    /**
     * Create value input for seed
     *
     * @param mixed $value
     * @return string
     */
    private function createValue($value)
    {
        if(is_numeric($value) && !substr( $value, 0, 1 ) == 0){
            return $value;
        } else if(is_null($value)) {
            return 'null';
        }

        return "'".addslashes($value)."'";
    }

    /**
     * Parse the stub
     *
     * @param $content
     *
     * @return mixed
     * @author Amando Vledder <amando@acfbentveld.nl>
     */
    private function parse($content)
    {
        $output =  ($this->truncate)?str_replace('%TRUNCATE%', "\DB::table('%TABLE%')->truncate();", $content):str_replace('%TRUNCATE%', "", $content);
        $output1 = str_replace('%TABLE%', $this->table, $output);
        $output2 = str_replace('%TABLENAME%', $this->tableName, $output1);
        $output3 = str_replace('%ROWS%', $this->rows, $output2);

        return $output3;
    }


    /**
     * Get all tables to make migration of
     *
     * @return array
     */
    private function getTables()
    {
        $run = [];
        $tables = DB::select('SHOW TABLES');
        foreach($tables as $table){
            $tableName = 'Tables_in_'.env('DB_DATABASE');
            if ($this->allTables){
                $run[] = $table->{$tableName};
            }else{
                if ($this->confirm("Create seed for ".$table->{$tableName})) {
                    $run[] = $table->{$tableName};
                }
            }
        }
        
        return $run;
    }

    /**
     * Extract from stub file
     *
     * @param string $string
     * @param type $start
     * @param type $end
     * @return string
     */
    public function get_string_between($string, $start, $end){
        $string = ' ' . $string;
        $ini = strpos($string, $start);
        if ($ini == 0) return '';
        $ini += strlen($start);
        $len = strpos($string, $end, $ini) - $ini;
        return substr($string, $ini, $len);
    }

    /**
     * Extract from stub file
     *
     * @param string $string
     * @param type $start
     * @param type $end
     * @return string
     */
    public function replace_string_between($string, $with, $replace){
        $content = str_replace($replace, $with, $string);
        return $content;
    }

}
