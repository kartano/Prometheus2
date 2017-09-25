<?php
/**
 * Prom2 Form object
 *
 * @author          Simon Mitchell <kartano@gmail.com>
 *
 * @namespace       Prometheus2\common\forms
 *
 * @version         1.0.0           2017-09-25 2017-09-25 Prototype
 */

namespace Prometheus2\common\forms;
use Prometheus2\common\database AS DB;

/**
 * Class Form
 * @package Prometheus2\common\forms
 *
 * @property string htmlid The HTML ID for this FORM.  This is the ID field of the <form ...> tag.
 */
class Form implements \Iterator, \ArrayAccess, \Countable
{
    protected $settings = [];
    protected $position = 0;
    protected $controls = [];
    protected static $formcount = 1;

    /**
     * Form constructor.
     *
     * @param string $htmlid The unique HTML ID.  A pseudo unique one will be used if none specified.
     */
    public function __construct(string $htmlid = '')
    {
        if ($htmlid === '') {
            $this->htmlid = __CLASS__ . '_' . self::$formcount++;
        } else {
            $this->htmlid = $htmlid;
        }
    }

    /**
     * @param string $key
     *
     * @return mixed|null
     */
    public function __get($key)
    {
        return array_key_exists($key, $this->settings) ? $this->settings[$key] : null;
    }

    /**
     * @param string $key
     * @param mixed  $value
     */
    public function __set($key, $value)
    {
        $this->settings[$key] = $value;
    }

    /**
     * \Iterator interface
     * @return mixed
     */
    public function current(): mixed
    {
        return $this->controls[$this->position];
    }

    /**
     * \Iterator interface
     * @return int
     */
    public function key()
    {
        return $this->position;
    }

    /**
     * \Iterator interface
     */
    public function next()
    {
        ++$this->position;
    }

    /**
     * \Iterator interface
     */
    public function rewind()
    {
        $this->position = 0;
    }

    /**
     * \Iterator interface
     * @return bool
     */
    public function valid(): bool
    {
        return isset($this->controls[$this->position]);
    }

    /**
     * \ArrayAccess interface
     *
     * @param mixed $offset
     * @param mixed $value
     */
    public function offsetSet($offset, $value)
    {
        if (is_null($offset)) {
            $this->controls[] = $value;
        } else {
            $this->controls[$offset] = $value;
        }
    }

    /**
     * \ArrayAccess interface
     *
     * @param mixed $offset
     *
     * @return bool
     */
    public function offsetExists($offset)
    {
        return isset($this->controls[$offset]);
    }

    /**
     * \ArrayAccess inteface.
     *
     * @param mixed $offset
     */
    public function offsetUnset($offset)
    {
        unset($this->controls[$offset]);
    }

    /**
     * \ArrayAccess interface.
     *
     * @param mixed $offset
     *
     * @return mixed|null
     */
    public function offsetGet($offset)
    {
        return isset($this->controls[$offset]) ? $this->controls[$offset] : null;
    }

    /**
     * \Countable interface.
     * @return int
     */
    public function count(): int
    {
        return count($this->controls);
    }

    /**
     * Append a new control to the internal array.  Forces the htmlid for the control to be unique.
     *
     * @param \Prometheus2\common\forms\FormControl $control
     * @param string                                $htmlid
     */
    public function addControl(FormControl $control, string $htmlid)
    {
        if (array_key_exists($htmlid, $this)) {
            throw new \InvalidArgumentException("The html ID for a control was duplicated.  Controls in the form must have unique IDs.");
        }
        $this->controls[$htmlid] = $control;
    }

    /**
     * Factory method to create a new Form object based on a given result set.
     *
     * @param \mysqli_result $result The result set from which to determine what fields we need to use.
     * @param string         $form_htmlid The unique ID for the associated form.
     *
     * @return \Prometheus2\common\forms\Form The newly generated and populated Form object.
     */
    public static function buildFromResult(\mysqli_result $result, string $form_htmlid): Form
    {
        $form = new Form($form_htmlid);
        foreach ($result->fetch_fields() as $field) {
            try {
                // SM:  Calculated fields will not have a specified DB.
                $calculated_field=false;
                if ($field->db=='') {
                    $comment = $field->name;
                    $calculated_field=true;
                    $htmlid = "CALC_FIELD_{$field->name}";
                } else {
                    $comment = DB\PromDB::getFieldComment($field->table, $field->name);
                    $htmlid = "{$field->table}_{$field->name}";
                }
            } catch (\InvalidArgumentException $exception) {
                throw $exception;
            }
            $control = null;
            switch ($field->type) {
                case MYSQLI_TYPE_DECIMAL:
                case MYSQLI_TYPE_NEWDECIMAL:
                case MYSQLI_TYPE_FLOAT:
                case MYSQLI_TYPE_DOUBLE:
                case MYSQLI_TYPE_TINY:
                case MYSQLI_TYPE_SHORT:
                case MYSQLI_TYPE_LONG:
                case MYSQLI_TYPE_LONGLONG:
                case MYSQLI_TYPE_INT24:
                case MYSQLI_TYPE_YEAR:
                    $control = new TextControl($htmlid, $comment, $field);
                    break;
                case MYSQLI_TYPE_ENUM:
                    $enums = DB\PromDB::getFieldEnums($field->table, $field->name);
                    $control = new DropdownControl($htmlid, $comment, $field);
                    $counter = 0;
                    foreach ($enums as $enum) {
                        $option = new OptionControl($htmlid . '_' . $counter++, $enum, $field);
                        $option->value = $enum;
                        $control[$enum] = $option;
                    }
                    break;
                case MYSQLI_TYPE_BIT:
                    $control = new CheckboxControl($htmlid, $comment, $field);
                    break;
                case MYSQLI_TYPE_DATE:
                case MYSQLI_TYPE_NEWDATE:
                    // SM:  TO DO - Date control.
                    break;
                case MYSQLI_TYPE_VAR_STRING:
                case MYSQLI_TYPE_STRING:
                case MYSQLI_TYPE_CHAR:
                    if (!$calculated_field) {
                        $length = DB\PromDB::getFieldLength($field->table, $field->name);
                    } else {
                        $length = 0;
                    }
                    if ($length >= 256) {
                        $control = new TextArea($htmlid, $comment, $field);
                    } else {
                        $control = new TextControl($htmlid, $comment, $field);
                    }
                    $control->maxlength = $length !== null ? $length : 0;
                    break;
                case MYSQLI_TYPE_TINY_BLOB:
                case MYSQLI_TYPE_MEDIUM_BLOB:
                case MYSQLI_TYPE_LONG_BLOB:
                case MYSQLI_TYPE_BLOB:
                    // SM:  This is likely to be an image or other binary file.
                    break;
                case MYSQLI_TYPE_TIMESTAMP:
                case MYSQLI_TYPE_TIME:
                    break;
                case MYSQLI_TYPE_DATETIME:
                    break;
                case MYSQLI_TYPE_INTERVAL:
                    break;
                case MYSQLI_TYPE_SET:
                    break;
                case MYSQLI_TYPE_GEOMETRY:
                    break;
            }
            if ($control !== null) {
                if ($calculated_field) {
                    $control->readonly=true;
                }
                $form->addControl($control, $htmlid);
            }
        }
        return $form;
    }
}

/**
 * Class FormControl
 * @package Prometheus2\common\forms
 * @property string placeholder The placeholder text.
 * @property string htmlid  The control's unique htmlid.
 * @property string caption The caption for this control.
 * @property object field   The field object (I.E: the field from a resultset) for this control.
 */
abstract class FormControl
{
    protected $settings = [];

    /**
     * FormControl constructor.
     *
     * @param string $htmlid
     * @param string $caption
     * @param \stdClass $field
     */
    public function __construct(string $htmlid, string $caption, \stdClass $field)
    {
        $this->htmlid = $htmlid;
        $this->caption = $caption;
        $this->field = $field;
    }

    /**
     * @param string $key
     *
     * @return mixed|null
     */
    public function __get($key)
    {
        return array_key_exists($key, $this->settings) ? $this->settings[$key] : null;
    }

    /**
     * @param string $key
     * @param mixed  $value
     */
    public function __set($key, $value)
    {
        $this->settings[$key] = $value;
    }

    /**
     * Render label.
     */
    public function renderLabel(): void
    {
        //
    }

    /**
     * Render control.
     */
    public function renderControl(): void
    {
        //
    }
    // TO DO:  Factory method???
}

/**
 * Class TextControl
 * @package Prometheus2\common\forms
 * @property int  maxlength The maximum length for this field.
 * @property bool password  True if this is a password field.
 * @property bool readonly  True if this is a readonly field.
 */
class TextControl extends FormControl
{
    /**
     * Render label.
     */
    public function renderLabel(): void
    {
        ?>
        <label for="<?= $this->htmlid; ?>"><?= $this->caption; ?></label>
        <?php
    }

    /**
     * Render control.
     */
    public function renderControl(): void
    {
        $type = $this->password ? "password" : "text";
        $readonly = $this->readonly ? "readonly" : "";
        $tabindex = $this->readonly ? "-1" : "0";
        ?>
        <input type="<?= $type; ?>" id="<?= $this->htmlid; ?>" name="<?= $this->name; ?>"
               maxlength="<?= $this->maxlength; ?>" readonly="<?= $readonly; ?>" tabindex="<?= $tabindex; ?>"
               placeholder="<?= $this->placeholder; ?>"/>
        <?php
    }
}

/**
 * Class CheckboxControl
 * @package Prometheus2\common\forms
 * @property bool checked TRUE if the default setting is checked.
 */
class CheckboxControl extends FormControl
{
    /**
     * Render label.
     */
    public function renderLabel(): void
    {
        ?>
        <label for="<?= $this->htmlid; ?>"><?= $this->caption; ?></label>
        <?php
    }

    public function renderControl(): void
    {
        $readonly = $this->readonly ? "readonly" : "";
        $tabindex = $this->readonly ? "-1" : "0";
        $checked = $this->checked ? "checked" : "";
        ?>
        <label for="<?= $this->htmlid; ?>"><?= $this->caption; ?></label>
        <input type="checkbox" id="<?= $this->htmlid; ?>" name="<?= $this->name; ?>" value="<?= $this->value; ?>"
               readonly="<?= $readonly; ?>" tabindex="<?= $tabindex; ?>" checked="<?= $checked; ?>"/>
        <?php
    }
}

/**
 * Class RadioControls
 * @package Prometheus2\common\forms
 */
class RadioControls extends FormControl implements \Iterator, \ArrayAccess, \Countable
{
    protected $position = 0;
    protected $controls = [];

    /**
     * Render the label.  For a collection of radio buttons, this is the legend, which is displayed during RENDER as it
     * needs to be enclosed within its own fieldset.
     */
    public function renderLabel(): void
    {
        //
    }

    public function renderControl(): void
    {
        ?>
        <fieldset>
            <legend><?= $this->caption; ?></legend>
            <?php
            foreach ($this as $radiocontrol) {
                $radiocontrol->renderLable();
                $radiocontrol->renderControl();
                ?>
                <br/>
                <?php
            }
            ?>
        </fieldset>
        <?php
    }

    /**
     * \Iterator interface
     */
    public function rewind()
    {
        $this->position = 0;
    }

    /**
     * \Iterator interface
     * @return mixed
     */
    public function current()
    {
        return $this->controls[$this->position];
    }

    /**
     * \Iterator interface
     * @return int
     */
    public function key()
    {
        return $this->position;
    }

    /**
     * \Iterator interface
     */
    public function next()
    {
        ++$this->position;
    }

    /**
     * \Iterator interface
     * @return bool
     */
    public function valid()
    {
        return isset($this->controls[$this->position]);
    }

    /**
     * \ArrayAccess interface
     *
     * @param mixed $offset
     * @param mixed $value
     */
    public function offsetSet($offset, $value)
    {
        if (is_null($offset)) {
            $this->controls[] = $value;
        } else {
            $this->controls[$offset] = $value;
        }
    }

    /**
     * \ArrayAccess interface
     *
     * @param mixed $offset
     *
     * @return bool
     */
    public function offsetExists($offset)
    {
        return isset($this->controls[$offset]);
    }

    /**
     * \ArrayAccess interface
     *
     * @param mixed $offset
     */
    public function offsetUnset($offset)
    {
        unset($this->controls[$offset]);
    }

    /**
     * \ArrayAccess interface
     *
     * @param mixed $offset
     *
     * @return mixed|null
     */
    public function offsetGet($offset)
    {
        return isset($this->controls[$offset]) ? $this->controls[$offset] : null;
    }

    /**
     * \Countable interface
     * @return int
     */
    public function count()
    {
        return count($this->controls);
    }
}

/**
 * Class OptionControl
 * @package Prometheus2\common\forms
 * @property string value   The value for this option.
 * @property bool   checked True if this radio control should be selected.
 */
class RadioControl extends FormControl
{
    public function renderLabel(): void
    {
        ?>
        <label for="<?= $this->htmlid; ?>"><?= $this->caption; ?></label>
        <?php
    }

    public function renderControl(): void
    {
        $checked = $this->checked ? "checked" : "";
        ?>
        <input type="radio" name="<?= $this->name; ?>" value="<?= $this->value; ?>" checked="<?= $checked; ?>"> />
        <?php
    }
}

/**
 * Class DropdownControl
 * @package Prometheus2\common\forms
 * @property bool multiple True if we allow multi-select on this control.
 * @property bool readonly True if this control is readonly.
 */
class DropdownControl extends FormControl implements \Iterator, \ArrayAccess, \Countable
{
    protected $position = 0;
    protected $controls = [];

    /**
     * Render the label.
     */
    public function renderLabel(): void
    {
        ?>
        <label for="<?= $this->htmlid; ?>"><?= $this->caption; ?></label>
        <?php
    }

    public function renderControl(): void
    {
        $multiple = $this->multiple ? "multiple" : "";
        $readonly = $this->readonly ? "readonly" : "";
        $tabindex = $this->readonly ? "-1" : "0";
        ?>
        <select name="<?= $this->name; ?>" id="<?= $this->htmlid; ?>" multiple="<?= $multiple; ?>"
                tabindex="<?= $tabindex; ?>" readonly="<?= $readonly; ?>">
            <?php
            foreach ($this as $option) {
                $option->renderLabel();
                $option->renderControl();
            }
            ?>
        </select>
        <?php
    }

    /**
     * \Iterator interface
     */
    public function rewind()
    {
        $this->position = 0;
    }

    /**
     * \Iterator interface
     * @return mixed
     */
    public function current()
    {
        return $this->controls[$this->position];
    }

    /**
     * \Iterator interface
     * @return int
     */
    public function key()
    {
        return $this->position;
    }

    /**
     * \Iterator interface
     */
    public function next()
    {
        ++$this->position;
    }

    /**
     * \Iterator interface
     * @return bool
     */
    public function valid()
    {
        return isset($this->controls[$this->position]);
    }

    /**
     * \ArrayAccess interface
     *
     * @param mixed $offset
     * @param mixed $value
     */
    public function offsetSet($offset, $value)
    {
        if (is_null($offset)) {
            $this->controls[] = $value;
        } else {
            $this->controls[$offset] = $value;
        }
    }

    /**
     * \ArrayAccess interface
     *
     * @param mixed $offset
     *
     * @return bool
     */
    public function offsetExists($offset)
    {
        return isset($this->controls[$offset]);
    }

    /**
     * \ArrayAccess interface
     *
     * @param mixed $offset
     */
    public function offsetUnset($offset)
    {
        unset($this->controls[$offset]);
    }

    /**
     * \ArrayAccess interface
     *
     * @param mixed $offset
     *
     * @return mixed|null
     */
    public function offsetGet($offset)
    {
        return isset($this->controls[$offset]) ? $this->controls[$offset] : null;
    }

    /**
     * \Countable interface
     * @return int
     */
    public function count()
    {
        return count($this->controls);
    }
}

/**
 * Class OptionControl
 * @package Prometheus2\common\forms
 * @property string value    The value for this option.
 * @property bool   selected True if this radio control should be selected.
 */
class OptionControl extends FormControl
{
    public function renderLabel(): void
    {
        //
    }

    public function renderControl(): void
    {
        $selected = $this->selected ? "selected" : "";
        ?>
        <option value="<?= $this->value; ?>" selected="<?= $selected; ?>"><?= $this->caption; ?></option>
        <?php
    }
}

/**
 * Class TextArea
 * @package Prometheus2\common\forms
 * @property int maxlength The maximum length for this control.
 * @property int rows Row count for the text area.
 * @property int cols Column count for the text area.
 */
class TextArea extends TextControl
{
    /**
     * TextArea constructor.
     *
     * @param string $htmlid
     * @param string $caption
     * @param object $field
     * @param int    $rows
     * @param int    $cols
     */
    public function __construct(string $htmlid, string $caption, object $field, int $rows = 4, int $cols = 50)
    {
        parent::__construct($htmlid, $caption, $field);
        $this->rows = $rows;
        $this->cols = $cols;
    }

    /**
     * Render label.
     */
    public function renderLabel(): void
    {
        ?>
        <label for="<?= $this->htmlid; ?>"><?= $this->caption; ?></label>
        <?php
    }

    /**
     * Render control.
     */
    public function renderControl(): void
    {
        $type = $this->password ? "password" : "text";
        $readonly = $this->readonly ? "readonly" : "";
        $tabindex = $this->readonly ? "-1" : "0";
        ?>
        <textarea id="<?= $this->htmlid; ?>" rows="<?= $this->rows; ?>" cols="<?= $this->cols; ?>"
                  name="<?= $this->name; ?>" maxlength="<?= $this->maxlength; ?>" readonly="<?= $readonly; ?>"
                  tabindex="<?= $tabindex; ?>" placeholder="<?= $this->placeholder; ?>"></textarea>
        <?php
    }
}
