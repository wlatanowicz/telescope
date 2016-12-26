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
    const OTHER_DEVICE_DISPLAY = 'Other Device';

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

        $color = SGR::COLOR_BG_BLACK;
        $otherColor = SGR::COLOR_BG_BLACK;

        if (isset($record['context']['color'])) {
            switch ($record['context']['color']) {
                case 'red' : $color = SGR::COLOR_BG_RED; break;
                case 'blue' : $color = SGR::COLOR_BG_BLUE; break;
                case 'green' : $color = SGR::COLOR_BG_GREEN; break;
            }
        }

        if (isset($record['context']['display'])) {
            $record['context']['display'] =
                $this->ansi->color($color)->get()
                . $record['context']['display']
                . $this->ansi->reset()->get();
        } else {
            $record['context']['display'] =
                $this->ansi->color($otherColor)->get()
                . self::OTHER_DEVICE_DISPLAY
                . $this->ansi->reset()->get();
        }
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
