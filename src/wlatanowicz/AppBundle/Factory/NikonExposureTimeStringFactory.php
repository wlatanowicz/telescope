<?php
declare(strict_types=1);

namespace wlatanowicz\AppBundle\Factory;

class NikonExposureTimeStringFactory extends AbstractExposureTimeStringFactory
{
    const BULB = "Bulb";

    static $SPEEDS = [
        "1/8000",
        "1/6400",
        "1/5000",
        "1/4000",
        "1/3200",
        "1/2500",
        "1/2000",
        "1/1600",
        "1/1250",
        "1/1000",
        "1/800",
        "1/640",
        "1/500",
        "1/400",
        "1/320",
        "1/250",
        "1/200",
        "1/160",
        "1/125",
        "1/100",
        "1/80",
        "1/60",
        "1/50",
        "1/40",
        "1/30",
        "1/25",
        "1/20",
        "1/15",
        "1/13",
        "1/10",
        "1/8",
        "1/6",
        "1/5",
        "1/4",
        "1/3",
        "10/25",
        "1/2",
        "10/16",
        "10/13",
        "1",
        "13/10",
        "16/10",
        "2",
        "25/10",
        "3",
        "4",
        "5",
        "6",
        "8",
        "10",
        "13",
        "15",
        "20",
        "25",
        "30",
    ];

}