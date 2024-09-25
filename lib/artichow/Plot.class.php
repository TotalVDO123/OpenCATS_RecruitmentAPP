<?php
/*
 * This work is hereby released into the Public Domain.
 * To view a copy of the public domain dedication,
 * visit http://creativecommons.org/licenses/publicdomain/ or send a letter to
 * Creative Commons, 559 Nathan Abbott Way, Stanford, California 94305, USA.
 *
 */

require_once __DIR__ . "/Component.class.php";


/**
 * Graph using X and Y axis
 *
 * @package Artichow
 */
abstract class awPlot extends awComponent
{
    /**
     * Values for Y axis
     *
     * @var array
     */
    protected $datay;

    /**
     * Values for X axis
     *
     * @var array
     */
    protected $datax;

    /**
     * Grid properties
     *
     * @var Grid
     */
    public $grid;

    /**
     * X axis
     *
     * @var Axis
     */
    public $xAxis;

    /**
     * Y axis
     *
     * @var Axis
     */
    public $yAxis;

    /**
     * Position of X axis
     *
     * @var int
     */
    protected $xAxisPosition = awPlot::BOTTOM;

    /**
     * Set X axis on zero ?
     *
     * @var bool
     */
    protected $xAxisZero = true;

    /**
     * Set Y axis on zero ?
     *
     * @var bool
     */
    protected $yAxisZero = false;

    /**
     * Position of Y axis
     *
     * @var int
     */
    protected $yAxisPosition = awPlot::LEFT;

    /**
     * Change min value for Y axis
     *
     * @var mixed
     */
    private $yMin = null;

    /**
     * Change max value for Y axis
     *
     * @var mixed
     */
    private $yMax = null;

    /**
     * Change min value for X axis
     *
     * @var mixed
     */
    private $xMin = null;

    /**
     * Change max value for X axis
     *
     * @var mixed
     */
    private $xMax = null;

    /**
     * Left axis
     *
     * @var int
     */
    public const LEFT = 'left';

    /**
     * RIGHT axis
     *
     * @var int
     */
    public const RIGHT = 'right';

    /**
     * Top axis
     *
     * @var int
     */
    public const TOP = 'top';

    /**
     * Bottom axis
     *
     * @var int
     */
    public const BOTTOM = 'bottom';

    /**
     * Both left/right or top/bottom axis
     *
     * @var int
     */
    public const BOTH = 'both';

    /**
     * Build the plot
     */
    public function __construct()
    {
        parent::__construct();

        $this->grid = new awGrid();
        $this->grid->setBackgroundColor(new awWhite());

        $this->padding->add(20, 0, 0, 20);

        $this->xAxis = new awAxis();
        $this->xAxis->addTick('major', new awTick(0, 5));
        $this->xAxis->addTick('minor', new awTick(0, 3));
        $this->xAxis->setTickStyle(awTick::OUT);
        $this->xAxis->label->setFont(new awTuffy(7));

        $this->yAxis = new awAxis();
        $this->yAxis->auto(true);
        $this->yAxis->addTick('major', new awTick(0, 5));
        $this->yAxis->addTick('minor', new awTick(0, 3));
        $this->yAxis->setTickStyle(awTick::OUT);
        $this->yAxis->setNumberByTick('minor', 'major', 3);
        $this->yAxis->label->setFont(new awTuffy(7));
        $this->yAxis->title->setAngle(90);
    }

    /**
     * Get plot values
     *
     * @return array
     */
    public function getValues()
    {
        return $this->datay;
    }

    /**
     * Reduce number of values in the plot
     *
     * @param int $number Reduce number of values to $number
     */
    public function reduce($number)
    {
        $count = count($this->datay);
        $ratio = ceil($count / $number);

        if ($ratio > 1) {
            $tmpy = $this->datay;
            $datay = [];

            $datax = [];
            $cbLabel = $this->xAxis->label->getCallbackFunction();

            for ($i = 0; $i < $count; $i += $ratio) {
                $slice = array_slice($tmpy, $i, $ratio);
                $datay[] = array_sum($slice) / count($slice);

                // Reduce data on X axis if needed
                if ($cbLabel !== null) {
                    $datax[] = $cbLabel($i + round($ratio / 2));
                }
            }

            $this->setValues($datay);

            if ($cbLabel !== null) {
                $this->xAxis->setLabelText($datax);
            }
        }
    }

    /**
     * Count values in the plot
     *
     * @return int
     */
    public function getXAxisNumber()
    {
        [$min, $max] = $this->xAxis->getRange();
        return ($max - $min + 1);
    }

    /**
     * Change X axis
     *
     * @param int $axis
     */
    public function setXAxis($axis)
    {
        $this->xAxisPosition = $axis;
    }

    /**
     * Get X axis
     *
     * @return int
     */
    public function getXAxis()
    {
        return $this->xAxisPosition;
    }

    /**
     * Set X axis on zero
     *
     * @param bool $zero
     */
    public function setXAxisZero($zero)
    {
        $this->xAxisZero = (bool) $zero;
    }

    /**
     * Set Y axis on zero
     *
     * @param bool $zero
     */
    public function setYAxisZero($zero)
    {
        $this->yAxisZero = (bool) $zero;
    }

    /**
     * Change Y axis
     *
     * @param int $axis
     */
    public function setYAxis($axis)
    {
        $this->yAxisPosition = $axis;
    }

    /**
     * Get Y axis
     *
     * @return int
     */
    public function getYAxis()
    {
        return $this->yAxisPosition;
    }

    /**
     * Change min value for Y axis
     * Set NULL for auto selection.
     *
     * @param float $value
     */
    public function setYMin($value)
    {
        $this->yMin = $value;
        $this->yAxis->auto(false);
        $this->updateAxis();
    }

    /**
     * Change max value for Y axis
     * Set NULL for auto selection.
     *
     * @param float $value
     */
    public function setYMax($value)
    {
        $this->yMax = $value;
        $this->yAxis->auto(false);
        $this->updateAxis();
    }

    /**
     * Change min value for X axis
     * Set NULL for auto selection.
     *
     * @param float $value
     */
    public function setXMin($value)
    {
        $this->xMin = $value;
        $this->updateAxis();
    }

    /**
     * Change max value for X axis
     * Set NULL for auto selection.
     *
     * @param float $value
     */
    public function setXMax($value)
    {
        if ($this->xMax < $value) {
            $this->xMax = $value;
        }
        $this->updateAxis();
    }

    /**
     * Get min value for Y axis
     *
     * @return float $value
     */
    public function getYMin()
    {
        if ($this->auto) {
            if (is_null($this->yMin)) {
                $min = array_min($this->datay);
                if ($min > 0) {
                    return 0;
                }
            }
        }
        return is_null($this->yMin) ? array_min($this->datay) : (float) $this->yMin;
    }

    /**
     * Get max value for Y axis
     *
     * @return float $value
     */
    public function getYMax()
    {
        if ($this->auto) {
            if (is_null($this->yMax)) {
                $max = array_max($this->datay);
                if ($max < 0) {
                    return 0;
                }
            }
        }
        return is_null($this->yMax) ? array_max($this->datay) : (float) $this->yMax;
    }

    /**
     * Get min value for X axis
     *
     * @return float $value
     */
    public function getXMin()
    {
        return floor(is_null($this->xMin) ? array_min($this->datax) : $this->xMin);
    }

    /**
     * Get max value for X axis
     *
     * @return float $value
     */
    public function getXMax()
    {
        return (ceil(is_null($this->xMax) ? array_max($this->datax) : (float) $this->xMax)) + ($this->getXCenter() ? 1 : 0);
    }

    /**
     * Get min value with spaces for Y axis
     *
     * @return float $value
     */
    public function getRealYMin()
    {
        $min = $this->getYMin();
        if ($this->space->bottom !== null) {
            $interval = ($this->getYMax() - $min) * $this->space->bottom / 100;
            return $min - $interval;
        } else {
            return is_null($this->yMin) ? $min : (float) $this->yMin;
        }
    }

    /**
     * Get max value with spaces for Y axis
     *
     * @return float $value
     */
    public function getRealYMax()
    {
        $max = $this->getYMax();
        if ($this->space->top !== null) {
            $interval = ($max - $this->getYMin()) * $this->space->top / 100;
            return $max + $interval;
        } else {
            return is_null($this->yMax) ? $max : (float) $this->yMax;
        }
    }

    public function init(awDrawer $drawer)
    {
        [$x1, $y1, $x2, $y2] = $this->getPosition();

        // Get space informations
        [$leftSpace, $rightSpace, $topSpace, $bottomSpace] = $this->getSpace($x2 - $x1, $y2 - $y1);

        $this->xAxis->setPadding($leftSpace, $rightSpace);

        if ($this->space->bottom > 0 or $this->space->top > 0) {
            [$min, $max] = $this->yAxis->getRange();
            $interval = $max - $min;

            $this->yAxis->setRange(
                $min - $interval * $this->space->bottom / 100,
                $max + $interval * $this->space->top / 100
            );
        }

        // Auto-scaling mode
        $this->yAxis->autoScale();

        // Number of labels is not specified
        if ($this->yAxis->getLabelNumber() === null) {
            $number = round(($y2 - $y1) / 75) + 2;
            $this->yAxis->setLabelNumber($number);
        }

        $this->xAxis->line->setX($x1, $x2);
        $this->yAxis->line->setY($y2, $y1);

        // Set ticks

        $this->xAxis->tick('major')->setNumber($this->getXAxisNumber());
        $this->yAxis->tick('major')->setNumber($this->yAxis->getLabelNumber());


        // Center X axis on zero
        if ($this->xAxisZero) {
            $this->xAxis->setYCenter($this->yAxis, 0);
        }

        // Center Y axis on zero
        if ($this->yAxisZero) {
            $this->yAxis->setXCenter($this->xAxis, 0);
        }

        // Set axis labels
        $labels = [];
        for ($i = 0, $count = $this->getXAxisNumber(); $i < $count; $i++) {
            $labels[] = $i;
        }
        $this->xAxis->label->set($labels);

        parent::init($drawer);

        [$x1, $y1, $x2, $y2] = $this->getPosition();

        [$leftSpace, $rightSpace] = $this->getSpace($x2 - $x1, $y2 - $y1);

        // Create the grid
        $this->createGrid();

        // Draw the grid
        $this->grid->setSpace($leftSpace, $rightSpace, 0, 0);
        $this->grid->draw($drawer, $x1, $y1, $x2, $y2);
    }

    public function drawEnvelope(awDrawer $drawer)
    {
        [$x1, $y1, $x2, $y2] = $this->getPosition();

        if ($this->getXCenter()) {
            $size = $this->xAxis->getDistance(0, 1);
            $this->xAxis->label->move($size / 2, 0);
            $this->xAxis->label->hideLast(true);
        }

        // Draw top axis
        if ($this->xAxisPosition === awPlot::TOP or $this->xAxisPosition === awPlot::BOTH) {
            $top = clone $this->xAxis;
            if ($this->xAxisZero === false) {
                $top->line->setY($y1, $y1);
            }
            $top->label->setAlign(null, awLabel::TOP);
            $top->label->move(0, -3);
            $top->title->move(0, -25);
            $top->draw($drawer);
        }

        // Draw bottom axis
        if ($this->xAxisPosition === awPlot::BOTTOM or $this->xAxisPosition === awPlot::BOTH) {
            $bottom = clone $this->xAxis;
            if ($this->xAxisZero === false) {
                $bottom->line->setY($y2, $y2);
            }
            $bottom->label->setAlign(null, awLabel::BOTTOM);
            $bottom->label->move(0, 3);
            $bottom->reverseTickStyle();
            $bottom->title->move(0, 25);
            $bottom->draw($drawer);
        }

        // Draw left axis
        if ($this->yAxisPosition === awPlot::LEFT or $this->yAxisPosition === awPlot::BOTH) {
            $left = clone $this->yAxis;
            if ($this->yAxisZero === false) {
                $left->line->setX($x1, $x1);
            }
            $left->label->setAlign(awLabel::RIGHT);
            $left->label->move(-6, 0);
            $left->title->move(-25, 0);
            $left->draw($drawer);
        }

        // Draw right axis
        if ($this->yAxisPosition === awPlot::RIGHT or $this->yAxisPosition === awPlot::BOTH) {
            $right = clone $this->yAxis;
            if ($this->yAxisZero === false) {
                $right->line->setX($x2, $x2);
            }
            $right->label->setAlign(awLabel::LEFT);
            $right->label->move(6, 0);
            $right->reverseTickStyle();
            $right->title->move(25, 0);
            $right->draw($drawer);
        }
    }

    protected function createGrid()
    {
        $max = $this->getRealYMax();
        $min = $this->getRealYMin();

        $number = $this->yAxis->getLabelNumber() - 1;

        if ($number < 1) {
            return;
        }

        // Horizontal lines of the grid

        $h = [];
        for ($i = 0; $i <= $number; $i++) {
            $h[] = $i / $number;
        }

        // Vertical lines

        $major = $this->yAxis->tick('major');
        $interval = $major->getInterval();
        $number = $this->getXAxisNumber() - 1;

        $w = [];

        if ($number > 0) {
            for ($i = 0; $i <= $number; $i++) {
                if ($i % $interval === 0) {
                    $w[] = $i / $number;
                }
            }
        }

        $this->grid->setGrid($w, $h);
    }

    /**
     * Change values of Y axis
     * This method ignores not numeric values
     *
     * @param array $datay
     * @param array $datax
     */
    public function setValues($datay, $datax = null)
    {
        $this->checkArray($datay);

        foreach ($datay as $key => $value) {
            unset($datay[$key]);
            $datay[(int) $key] = $value;
        }

        if ($datax === null) {
            $datax = [];
            for ($i = 0; $i < count($datay); $i++) {
                $datax[] = $i;
            }
        } else {
            foreach ($datax as $key => $value) {
                unset($datax[$key]);
                $datax[(int) $key] = $value;
            }
        }

        $this->checkArray($datax);

        if (count($datay) === count($datax)) {
            // Set values
            $this->datay = $datay;
            $this->datax = $datax;
            // Update axis with the new awvalues
            $this->updateAxis();
        } else {
            trigger_error("Plots must have the same number of X and Y points", E_USER_ERROR);
        }
    }

    /**
     * Return begin and end values
     *
     * @return array
     */
    protected function getLimit()
    {
        $i = 0;
        while (array_key_exists($i, $this->datay) and $this->datay[$i] === null) {
            $i++;
        }
        $start = $i;
        $i = count($this->datay) - 1;
        while (array_key_exists($i, $this->datay) and $this->datay[$i] === null) {
            $i--;
        }
        $stop = $i;

        return [$start, $stop];
    }

    /**
     * Return TRUE if labels must be centered on X axis, FALSE otherwise
     *
     * @return bool
     */
    abstract public function getXCenter();

    private function updateAxis()
    {
        $this->xAxis->setRange(
            $this->getXMin(),
            $this->getXMax()
        );
        $this->yAxis->setRange(
            $this->getRealYMin(),
            $this->getRealYMax()
        );
    }

    private function checkArray(&$array)
    {
        if (is_array($array) === false) {
            trigger_error("You tried to set a value that is not an array", E_USER_ERROR);
        }

        foreach ($array as $key => $value) {
            if (is_numeric($value) === false and is_null($value) === false) {
                trigger_error("Expected numeric values for the plot", E_USER_ERROR);
            }
        }

        if (count($array) < 1) {
            trigger_error("Your plot must have at least 1 value", E_USER_ERROR);
        }
    }
}

registerClass('Plot', true);

class awPlotAxis
{
    /**
     * Left axis
     *
     * @var Axis
     */
    public $left;

    /**
     * Right axis
     *
     * @var Axis
     */
    public $right;

    /**
     * Top axis
     *
     * @var Axis
     */
    public $top;

    /**
     * Bottom axis
     *
     * @var Axis
     */
    public $bottom;

    /**
     * Build the group of axis
     */
    public function __construct()
    {
        $this->left = new awAxis();
        $this->left->auto(true);
        $this->left->label->setAlign(awLabel::RIGHT);
        $this->left->label->move(-6, 0);
        $this->yAxis($this->left);
        $this->left->setTickStyle(awTick::OUT);
        $this->left->title->move(-25, 0);

        $this->right = new awAxis();
        $this->right->auto(true);
        $this->right->label->setAlign(awLabel::LEFT);
        $this->right->label->move(6, 0);
        $this->yAxis($this->right);
        $this->right->setTickStyle(awTick::IN);
        $this->right->title->move(25, 0);

        $this->top = new awAxis();
        $this->top->label->setAlign(null, awLabel::TOP);
        $this->top->label->move(0, -3);
        $this->xAxis($this->top);
        $this->top->setTickStyle(awTick::OUT);
        $this->top->title->move(0, -25);

        $this->bottom = new awAxis();
        $this->bottom->label->setAlign(null, awLabel::BOTTOM);
        $this->bottom->label->move(0, 3);
        $this->xAxis($this->bottom);
        $this->bottom->setTickStyle(awTick::IN);
        $this->bottom->title->move(0, 25);
    }

    protected function xAxis(awAxis $axis)
    {
        $axis->addTick('major', new awTick(0, 5));
        $axis->addTick('minor', new awTick(0, 3));
        $axis->label->setFont(new awTuffy(7));
    }

    protected function yAxis(awAxis $axis)
    {
        $axis->addTick('major', new awTick(0, 5));
        $axis->addTick('minor', new awTick(0, 3));
        $axis->setNumberByTick('minor', 'major', 3);
        $axis->label->setFont(new awTuffy(7));
        $axis->title->setAngle(90);
    }
}

registerClass('PlotAxis');

/**
 * A graph with axis can contain some groups of components
 *
 * @package Artichow
 */
class awPlotGroup extends awComponentGroup
{
    /**
     * Grid properties
     *
     * @var Grid
     */
    public $grid;

    /**
     * Left, right, top and bottom axis
     *
     * @var PlotAxis
     */
    public $axis;

    /**
     * Set the X axis on zero
     *
     * @var bool
     */
    protected $xAxisZero = true;

    /**
     * Set the Y axis on zero
     *
     * @var bool
     */
    protected $yAxisZero = false;

    /**
     * Real axis used for Y axis
     *
     * @var string
     */
    private $yRealAxis = awPlot::LEFT;

    /**
     * Real axis used for X axis
     *
     * @var string
     */
    private $xRealAxis = awPlot::BOTTOM;

    /**
     * Change min value for Y axis
     *
     * @var mixed
     */
    private $yMin = null;

    /**
     * Change max value for Y axis
     *
     * @var mixed
     */
    private $yMax = null;

    /**
     * Change min value for X axis
     *
     * @var mixed
     */
    private $xMin = null;

    /**
     * Change max value for X axis
     *
     * @var mixed
     */
    private $xMax = null;

    /**
     * Build the PlotGroup
     */
    public function __construct()
    {
        parent::__construct();

        $this->grid = new awGrid();
        $this->grid->setBackgroundColor(new awWhite());

        $this->axis = new awPlotAxis();
    }

    /**
     * Set the X axis on zero or not
     *
     * @param bool $zero
     */
    public function setXAxisZero($zero)
    {
        $this->xAxisZero = (bool) $zero;
    }

    /**
     * Set the Y axis on zero or not
     *
     * @param bool $zero
     */
    public function setYAxisZero($zero)
    {
        $this->yAxisZero = (bool) $zero;
    }

    /**
     * Change min value for Y axis
     * Set NULL for auto selection.
     *
     * @param float $value
     */
    public function setYMin($value)
    {
        $this->axis->left->auto(false);
        $this->axis->right->auto(false);
        $this->yMin = $value;
    }

    /**
     * Change max value for Y axis
     * Set NULL for auto selection.
     *
     * @param float $value
     */
    public function setYMax($value)
    {
        $this->axis->left->auto(false);
        $this->axis->right->auto(false);
        $this->yMax = $value;
    }

    /**
     * Change min value for X axis
     * Set NULL for auto selection.
     *
     * @param float $value
     */
    public function setXMin($value)
    {
        $this->xMin = $value;
    }

    /**
     * Change max value for X axis
     * Set NULL for auto selection.
     *
     * @param float $value
     */
    public function setXMax($value)
    {
        $this->xMax = $value;
    }

    /**
     * Get min value for X axis
     *
     * @return float $value
     */
    public function getXMin()
    {
        return $this->getX('min');
    }

    /**
     * Get max value for X axis
     *
     * @return float $value
     */
    public function getXMax()
    {
        return $this->getX('max');
    }

    private function getX($type)
    {
        switch ($type) {
            case 'max':
                if ($this->xMax !== null) {
                    return $this->xMax;
                }
                break;
            case 'min':
                if ($this->xMin !== null) {
                    return $this->xMin;
                }
                break;
        }

        $value = null;
        $get = 'getX' . ucfirst((string) $type);

        for ($i = 0; $i < count($this->components); $i++) {
            $component = $this->components[$i];

            if ($value === null) {
                $value = $component->$get();
            } else {
                $value = $type($value, $component->$get());
            }
        }

        return $value;
    }

    /**
     * Get min value with spaces for Y axis
     *
     * @param string $axis Axis name
     * @return float $value
     */
    public function getRealYMin($axis = null)
    {
        if ($axis === null) {
            return null;
        }

        $min = $this->getRealY('min', $axis);
        $max = $this->getRealY('max', $axis);

        if ($this->space->bottom !== null) {
            $interval = ($min - $max) * $this->space->bottom / 100;
            return $min + $interval;
        } else {
            return $min;
        }
    }

    /**
     * Get max value with spaces for Y axis
     *
     * @param string $axis Axis name
     * @return float $value
     */
    public function getRealYMax($axis = null)
    {
        if ($axis === null) {
            return null;
        }

        $min = $this->getRealY('min', $axis);
        $max = $this->getRealY('max', $axis);

        if ($this->space->top !== null) {
            $interval = ($max - $min) * $this->space->top / 100;
            return $max + $interval;
        } else {
            return $max;
        }
    }

    private function getRealY($type, $axis)
    {
        switch ($type) {
            case 'max':
                if ($this->yMax !== null) {
                    return $this->yMax;
                }
                break;
            case 'min':
                if ($this->yMin !== null) {
                    return $this->yMin;
                }
                break;
        }

        $value = null;
        $get = 'getY' . ucfirst((string) $type);

        for ($i = 0; $i < count($this->components); $i++) {
            $component = $this->components[$i];

            $test = match ($axis) {
                awPlot::LEFT, awPlot::RIGHT => $component->getYAxis() === $axis,
                default => false,
            };

            if ($test) {
                if ($value === null) {
                    $value = $component->$get();
                } else {
                    $value = $type($value, $component->$get());
                }
            }
        }

        return $value;
    }

    public function init(awDrawer $drawer)
    {
        [$x1, $y1, $x2, $y2] = $this->getPosition();

        // Get PlotGroup space
        [$leftSpace, $rightSpace, $topSpace, $bottomSpace] = $this->getSpace($x2 - $x1, $y2 - $y1);

        // Count values in the group
        $values = $this->getXAxisNumber();

        // Init the PlotGroup
        $this->axis->top->line->setX($x1, $x2);
        $this->axis->bottom->line->setX($x1, $x2);
        $this->axis->left->line->setY($y2, $y1);
        $this->axis->right->line->setY($y2, $y1);

        $this->axis->top->setPadding($leftSpace, $rightSpace);
        $this->axis->bottom->setPadding($leftSpace, $rightSpace);

        $xMin = $this->getXMin();
        $xMax = $this->getXMax();

        $this->axis->top->setRange($xMin, $xMax);
        $this->axis->bottom->setRange($xMin, $xMax);

        for ($i = 0; $i < count($this->components); $i++) {
            $component = $this->components[$i];

            $component->auto($this->auto);

            // Copy space to the component

            $component->setSpace($this->space->left, $this->space->right, $this->space->top, $this->space->bottom);

            $component->xAxis->setPadding($leftSpace, $rightSpace);
            $component->xAxis->line->setX($x1, $x2);

            $component->yAxis->line->setY($y2, $y1);
        }

        // Set Y axis range
        foreach (['left', 'right'] as $axis) {
            if ($this->isAxisUsed($axis)) {
                $min = $this->getRealYMin($axis);
                $max = $this->getRealYMax($axis);

                $interval = $max - $min;

                $this->axis->{$axis}->setRange(
                    $min - $interval * $this->space->bottom / 100,
                    $max + $interval * $this->space->top / 100
                );

                // Auto-scaling mode
                $this->axis->{$axis}->autoScale();
            }
        }

        if ($this->axis->left->getLabelNumber() === null) {
            $number = round(($y2 - $y1) / 75) + 2;
            $this->axis->left->setLabelNumber($number);
        }

        if ($this->axis->right->getLabelNumber() === null) {
            $number = round(($y2 - $y1) / 75) + 2;
            $this->axis->right->setLabelNumber($number);
        }

        // Center labels on X axis if needed
        $test = [
            awPlot::TOP => false,
            awPlot::BOTTOM => false,
        ];

        for ($i = 0; $i < count($this->components); $i++) {
            $component = $this->components[$i];


            if ($component->getValues() !== null) {
                $axis = $component->getXAxis();

                if ($test[$axis] === false) {
                    // Center labels for bar plots
                    if ($component->getXCenter()) {
                        $size = $this->axis->{$axis}->getDistance(0, 1);
                        $this->axis->{$axis}->label->move($size / 2, 0);
                        $this->axis->{$axis}->label->hideLast(true);
                        $test[$axis] = true;
                    }
                }
            }
        }

        // Set axis labels
        $labels = [];
        for ($i = $xMin; $i <= $xMax; $i++) {
            $labels[] = $i;
        }
        if ($this->axis->top->label->count() === 0) {
            $this->axis->top->label->set($labels);
        }
        if ($this->axis->bottom->label->count() === 0) {
            $this->axis->bottom->label->set($labels);
        }

        // Set ticks

        $this->axis->top->tick('major')->setNumber($values);
        $this->axis->bottom->tick('major')->setNumber($values);
        $this->axis->left->tick('major')->setNumber($this->axis->left->getLabelNumber());
        $this->axis->right->tick('major')->setNumber($this->axis->right->getLabelNumber());


        // Set X axis on zero
        if ($this->xAxisZero) {
            $axis = $this->selectYAxis();
            $this->axis->bottom->setYCenter($axis, 0);
            $this->axis->top->setYCenter($axis, 0);
        }

        // Set Y axis on zero
        if ($this->yAxisZero) {
            $axis = $this->selectXAxis();
            $this->axis->left->setXCenter($axis, 1);
            $this->axis->right->setXCenter($axis, 1);
        }

        parent::init($drawer);

        [$leftSpace, $rightSpace, $topSpace, $bottomSpace] = $this->getSpace($x2 - $x1, $y2 - $y1);

        // Create the grid
        $this->createGrid();

        // Draw the grid
        $this->grid->setSpace($leftSpace, $rightSpace, 0, 0);
        $this->grid->draw($drawer, $x1, $y1, $x2, $y2);
    }

    public function drawComponent(awDrawer $drawer, $x1, $y1, $x2, $y2, $aliasing)
    {
        $xMin = $this->getXMin();
        $xMax = $this->getXMax();

        $maxLeft = $this->getRealYMax(awPlot::LEFT);
        $maxRight = $this->getRealYMax(awPlot::RIGHT);

        $minLeft = $this->getRealYMin(awPlot::LEFT);
        $minRight = $this->getRealYMin(awPlot::RIGHT);

        foreach ($this->components as $component) {
            $min = $component->getYMin();
            $max = $component->getYMax();

            // Set component minimum and maximum
            if ($component->getYAxis() === awPlot::LEFT) {
                [$min, $max] = $this->axis->left->getRange();

                $component->setYMin($min);
                $component->setYMax($max);
            } else {
                [$min, $max] = $this->axis->right->getRange();

                $component->setYMin($min);
                $component->setYMax($max);
            }

            $component->setXAxisZero($this->xAxisZero);
            $component->setYAxisZero($this->yAxisZero);

            $component->xAxis->setRange($xMin, $xMax);

            $component->drawComponent(
                $drawer,
                $x1,
                $y1,
                $x2,
                $y2,
                $aliasing
            );

            $component->setYMin($min);
            $component->setYMax($max);
        }
    }

    public function drawEnvelope(awDrawer $drawer)
    {
        [$x1, $y1, $x2, $y2] = $this->getPosition();

        // Hide unused axis
        foreach ([awPlot::LEFT, awPlot::RIGHT, awPlot::TOP, awPlot::BOTTOM] as $axis) {
            if ($this->isAxisUsed($axis) === false) {
                $this->axis->{$axis}->hide(true);
            }
        }

        // Draw top axis
        $top = $this->axis->top;
        if ($this->xAxisZero === false) {
            $top->line->setY($y1, $y1);
        }
        $top->draw($drawer);

        // Draw bottom axis
        $bottom = $this->axis->bottom;
        if ($this->xAxisZero === false) {
            $bottom->line->setY($y2, $y2);
        }
        $bottom->draw($drawer);

        // Draw left axis
        $left = $this->axis->left;
        if ($this->yAxisZero === false) {
            $left->line->setX($x1, $x1);
        }
        $left->draw($drawer);

        // Draw right axis
        $right = $this->axis->right;
        if ($this->yAxisZero === false) {
            $right->line->setX($x2, $x2);
        }
        $right->draw($drawer);
    }

    /**
     * Is the specified axis used ?
     *
     * @param string $axis Axis name
     * @return bool
     */
    protected function isAxisUsed($axis)
    {
        for ($i = 0; $i < count($this->components); $i++) {
            $component = $this->components[$i];

            switch ($axis) {
                case awPlot::LEFT:
                case awPlot::RIGHT:
                    if ($component->getYAxis() === $axis) {
                        return true;
                    }
                    break;

                case awPlot::TOP:
                case awPlot::BOTTOM:
                    if ($component->getXAxis() === $axis) {
                        return true;
                    }
                    break;
            }
        }

        return false;
    }

    protected function createGrid()
    {
        $max = $this->getRealYMax(awPlot::LEFT);
        $min = $this->getRealYMin(awPlot::RIGHT);

        // Select axis (left if possible, right otherwise)
        $axis = $this->selectYAxis();

        $number = $axis->getLabelNumber() - 1;

        if ($number < 1) {
            return;
        }

        // Horizontal lines of grid

        $h = [];
        for ($i = 0; $i <= $number; $i++) {
            $h[] = $i / $number;
        }

        // Vertical lines

        $major = $axis->tick('major');
        $interval = $major->getInterval();
        $number = $this->getXAxisNumber() - 1;

        $w = [];

        if ($number > 0) {
            for ($i = 0; $i <= $number; $i++) {
                if ($i % $interval === 0) {
                    $w[] = $i / $number;
                }
            }
        }

        $this->grid->setGrid($w, $h);
    }

    protected function selectYAxis()
    {
        // Select axis (left if possible, right otherwise)
        if ($this->isAxisUsed(awPlot::LEFT)) {
            $axis = $this->axis->left;
        } else {
            $axis = $this->axis->right;
        }

        return $axis;
    }

    protected function selectXAxis()
    {
        // Select axis (bottom if possible, top otherwise)
        if ($this->isAxisUsed(awPlot::BOTTOM)) {
            $axis = $this->axis->bottom;
        } else {
            $axis = $this->axis->top;
        }

        return $axis;
    }

    protected function getXAxisNumber()
    {
        $offset = $this->components[0];
        $max = $offset->getXAxisNumber();
        for ($i = 1; $i < count($this->components); $i++) {
            $offset = $this->components[$i];
            $max = max($max, $offset->getXAxisNumber());
        }
        return $max;
    }
}

registerClass('PlotGroup');
