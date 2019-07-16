<?php
namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\VarDumper\Dumper\CliDumper;
use Symfony\Component\VarDumper\Cloner\VarCloner;

use PHPOffice\PhpSpreadsheet\Reader\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;

use App\Entity\User;
use App\Service\UserService;

class ImportEmployersCommand extends Command {
    private $service;

    /**
     * Functie: __construct
     * Doel:    Constructor van de klasse
     * Taken:   Voert de autowiring voor de klasse uit
     */
    public function __construct(UserService $service) {
        parent::__construct();
        $this->service = $service;
    }

    /**
     * Functie: configure
     * Doel:    configureert het command
     */
    protected function configure() {
        $this
            ->setName('app:employer:import')
            ->setDescription('Imports a .xlsx spreadsheet with data on the '.
                'employers. Employers missing from the database will be '.
                'automatically added.')
            ->setHelp('This command imports the .xslx file with employers given '.
                'as the input argument. Any employers missing in the database '.
                'will be automatically added.')
            ->addArgument('filename', InputArgument::REQUIRED, 'Spreadsheet');
    }

    /**
     * Functie: execute
     * Doel:    importeert het meegegeven excel-bestand, en maakt de users aan die nog niet bestaan
     */
    protected function execute(InputInterface $input, OutputInterface $output) {
        $inputFilename = $input->getArgument('filename');
        $spreadsheet = IOFactory::load($inputFilename);

        $worksheet = $spreadsheet->getActiveSheet();
        $rows = $this->getArrayFromWorksheet($worksheet);

        $result = $this->service->importEmployers($rows);
        $cloner = new VarCloner();
        $dumper = new CliDumper();
        $output->writeln([$dumper->dump($cloner->cloneVar($rows), true), $result]);
    }

    /**
     * Functie: getArrayFromWorksheet
     * Doel:    pakt de informatie uit je worksheet en zet het neer in een array
     */
    private function getArrayFromWorksheet($worksheet) {
        $rows = [];
        $highestRow = $worksheet->getHighestRow();
        
        for ($row = 1; $row <= $highestRow; $row++) {
            $rows[] = [ "username" => 
                    $worksheet->getCellByColumnAndRow(1, $row)->getValue(),
                "email" =>
                    $worksheet->getCellByColumnAndRow(2, $row)->getValue(),
                "salt" =>
                    $worksheet->getCellByColumnAndRow(3, $row)->getValue(),
                "plaats_id" =>
                    $worksheet->getCellByColumnAndRow(4, $row)->getValue(),
                "naam" =>
                    $worksheet->getCellByColumnAndRow(5, $row)->getValue(),
                "telefoonnummer" =>
                    $worksheet->getCellByColumnAndRow(6, $row)->getValue(),
                "adres" =>
                    $worksheet->getCellByColumnAndRow(7, $row)->getValue(),
                "postcode" =>
                    $worksheet->getCellByColumnAndRow(8, $row)->getValue(),
                "omschrijving" =>
                    $worksheet->getCellByColumnAndRow(9, $row)->getValue(),
                "afbeelding" =>
                    $worksheet->getCellByColumnAndRow(10, $row)->getValue(),
                "sector" =>
                    $worksheet->getCellByColumnAndRow(11, $row)->getValue(),
                "contactpersoon" =>
                    $worksheet->getCellByColumnAndRow(12, $row)->getValue() ];
        }

        return $rows;
    }
}