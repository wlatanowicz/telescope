<?php
declare(strict_types = 1);

namespace wlatanowicz\AppBundle\Helper;

use Bramus\Ansi\Ansi;
use Bramus\Ansi\ControlSequences\EscapeSequences\Enums\SGR;
use Bramus\Monolog\Formatter\ColoredLineFormatter;
use Monolog\Formatter\FormatterInterface;
use Monolog\Formatter\LineFormatter;

class LogFormatter extends LineFormatter
{
    const OTHER_DISPLAY = 'Other';

    /**
     * @var Ansi
     */
    private $ansi;

    /**
     * @var FormatterInterface
     */
    private $nestedFormatter;

    /**
     * LogFormatter constructor.
     * @param Ansi $ansi
     * @param FormatterInterface $nestedFormatter
     */
    public function __construct(
        string $format,
        Ansi $ansi,
        FormatterInterface $nestedFormatter
    ) {
        $this->ansi = $ansi;
        $this->nestedFormatter = $nestedFormatter;
        parent::__construct($format);
    }

    public function format(array $record)
    {
        $formattedRecord = $this->nestedFormatter->format($record);

        $bgcolor = SGR::COLOR_BG_WHITE;
        $fgcolor = SGR::COLOR_FG_BLACK;

        if (isset($record['context']['color'])) {
            switch ($record['context']['color']) {
                case 'red' : $bgcolor = SGR::COLOR_BG_RED; break;
                case 'blue' : $bgcolor = SGR::COLOR_BG_BLUE; break;
                case 'green' : $bgcolor = SGR::COLOR_BG_GREEN; break;
                case 'yellow' : $bgcolor = SGR::COLOR_BG_YELLOW; break;
            }
        }

        if (!isset($record['context']['display'])) {
            $record['context']['display'] = self::OTHER_DISPLAY;
        }

        $record['context']['display'] =
            $this->ansi->color($bgcolor)->color($fgcolor)->get()
            . $record['context']['display']
            . $this->ansi->reset()->get();

        return parent::format($record).$formattedRecord;
    }

    public function formatBatch(array $records)
    {
        $formattedRecords = [];
        foreach ($records as $record) {
            $formattedRecords[] = $this->format($record);
        }
        return $formattedRecords;
    }

}
