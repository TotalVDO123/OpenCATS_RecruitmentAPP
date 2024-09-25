<?php
/*
 * This work is hereby released into the Public Domain.
 * To view a copy of the public domain dedication,
 * visit http://creativecommons.org/licenses/publicdomain/ or send a letter to
 * Creative Commons, 559 Nathan Abbott Way, Stanford, California 94305, USA.
 *
 */

require_once __DIR__ . "/Plot.class.php";

/**
 * ScatterPlot
 *
 * @package Artichow
 */
class awScatterPlot extends awPlot implements awLegendable
{
    /**
     * Add marks to the scatter plot
     *
     * @var Mark
     */
    public $mark;

    /**
     * Labels on the plot
     *
     * @var Label
     */
    public $label;

    /**
     * Link points ?
     *
     * @var bool
     */
    protected $link = false;

    /**
     * Display impulses
     *
     * @var bool
     */
    protected $impulse = null;

    /**
     * Link NULL points ?
     *
     * @var bool
     */
    protected $linkNull = false;

    /**
     * Line color
     *
     * @var Color
     */
    protected $lineColor;

    /**
     * Line type
     *
     * @var int
     */
    protected $lineStyle = awLine::SOLID;

    /**
     * Line thickness
     *
     * @var int
     */
    protected $lineThickness = 1;

    /**
     * Construct a new awScatterPlot
     *
     * @param array $datay Numeric values for Y axis
     * @param array $datax Numeric values for X axis
     */
    public function __construct($datay, $datax = null)
    {
        parent::__construct();

        // Defaults marks
        $this->mark = new awMark();
        $this->mark->setType(awMark::CIRCLE);
        $this->mark->setSize(7);
        $this->mark->border->show();

        $this->label = new awLabel();

        $this->setValues($datay, $datax);
        $this->setColor(new awBlack());
    }

    /**
     * Display plot as impulses
     */
    public function setImpulse($color)
    {
        $this->impulse = $color;
    }

    /**
     * Link scatter plot points
     *
     * @param bool $link
     * @param awColor $color Line color (default to black)
     */
    public function link($link, $color = null)
    {
        $this->link = (bool) $link;
        if ($color instanceof awColor) {
            $this->setColor($color);
        }
    }

    /**
     * Ignore null values for Y data and continue linking
     *
     * @param bool $link
     */
    public function linkNull($link)
    {
        $this->linkNull = (bool) $link;
    }

    /**
     * Change line color
     */
    public function setColor(awColor $color)
    {
        $this->lineColor = $color;
    }

    /**
     * Change line style
     *
     * @param int $style
     */
    public function setStyle($style)
    {
        $this->lineStyle = (int) $style;
    }

    /**
     * Change line tickness
     *
     * @param int $tickness
     */
    public function setThickness($tickness)
    {
        $this->lineThickness = (int) $tickness;
    }

    /**
     * Get the line thickness
     *
     * @return int
     */
    public function getLegendLineThickness()
    {
        return $this->lineThickness;
    }

    /**
     * Get the line type
     *
     * @return int
     */
    public function getLegendLineStyle()
    {
        return $this->lineStyle;
    }

    /**
     * Get the color of line
     *
     * @return Color
     */
    public function getLegendLineColor()
    {
        return $this->lineColor;
    }

    /**
     * Get the background color or gradient of an element of the component
     *
     * @return Color, Gradient
     */
    public function getLegendBackground()
    {
        return null;
    }

    /**
     * Get a mark object
     *
     * @return Mark
     */
    public function getLegendMark()
    {
        return $this->mark;
    }

    public function drawComponent(awDrawer $drawer, $x1, $y1, $x2, $y2, $aliasing)
    {
        $count = count($this->datay);

        // Get start and stop values
        [$start, $stop] = $this->getLimit();

        // Build the polygon
        $polygon = new awPolygon();

        for ($key = 0; $key < $count; $key++) {
            $x = $this->datax[$key];
            $y = $this->datay[$key];

            if ($y !== null) {
                $p = awAxis::toPosition($this->xAxis, $this->yAxis, new awPoint($x, $y));
                $polygon->set($key, $p);
            } elseif ($this->linkNull === false) {
                $polygon->set($key, null);
            }
        }

        // Link points if needed
        if ($this->link) {
            $prev = null;

            foreach ($polygon->all() as $point) {
                if ($prev !== null and $point !== null) {
                    $drawer->line(
                        $this->lineColor,
                        new awLine(
                            $prev,
                            $point,
                            $this->lineStyle,
                            $this->lineThickness
                        )
                    );
                }
                $prev = $point;
            }

            $this->lineColor->free();
        }

        // Draw impulses
        if ($this->impulse instanceof awColor) {
            foreach ($polygon->all() as $key => $point) {
                if ($point !== null) {
                    $zero = awAxis::toPosition(
                        $this->xAxis,
                        $this->yAxis,
                        new awPoint($key, 0)
                    );

                    $drawer->line(
                        $this->impulse,
                        new awLine(
                            $zero,
                            $point,
                            awLine::SOLID,
                            1
                        )
                    );
                }
            }
        }

        // Draw marks and labels
        foreach ($polygon->all() as $key => $point) {
            $this->mark->draw($drawer, $point);
            $this->label->draw($drawer, $point, $key);
        }
    }

    protected function xAxisPoint($position)
    {
        $y = $this->xAxisZero ? 0 : $this->getRealYMin();
        return awAxis::toPosition($this->xAxis, $this->yAxis, new awPoint($position, $y));
    }

    public function getXCenter()
    {
        return false;
    }
}

registerClass('ScatterPlot');
