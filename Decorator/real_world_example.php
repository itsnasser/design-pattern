<?php

/**
 * The Component interface declares a filtering method that must be implemented
 * by all concrete components and decorators.
 */
interface InputFormat
{
    public function formatText(string $text): string;
}


/**
 * The Concrete Component is a core element of decoration. It contains the
 * original text, as is, without any filtering or formatting.
 */
class TextInput implements InputFormat
{

    public function formatText(string $text): string
    {
        return $text;
    }
}

/**
 * The base Decorator class doesn't contain any real filtering or formatting
 * logic. Its main purpose is to implement the basic decoration infrastructure:
 * a field for storing a wrapped component or another decorator and the basic
 * formatting method that delegates the work to the wrapped object. The real
 * formatting job is done by subclasses.
 */
class TextFormat implements InputFormat
{

    protected $inputFormat;
    public function __construct(InputFormat $inputFormat)
    {
        $this->inputFormat = $inputFormat;
    }

    public function formatText(string $text): string
    {
        return $this->inputFormat->formatText($text);
    }
}


/**
 * This Concrete Decorator strips out all HTML tags from the given text.
 */

class PlainTextFilter extends TextFormat
{
    public function formatText(string $text): string
    {
        $text = parent::formatText($text);
        return strip_tags($text);
    }
}


/**
 * This Concrete Decorator strips only dangerous HTML tags and attributes that
 * may lead to an XSS vulnerability.
 */
class DangerousHTMLTagsFilter extends TextFormat
{
    private $dangerousTagPatterns = [
        "|<script.*?>([\s\S]*)?</script>|i",
    ];

    private $dangerousAttributes = [
        "onclick", "onkeypress",
    ];

    public function formatText(string $text): string
    {
        $text = Parent::formatText($text);

        foreach ($this->dangerousTagPatterns as $pattern) {
            $text = preg_replace($pattern, '', $text);
        }

        foreach ($this->dangerousAttributes as $attribute) {
            $text = preg_replace_callback('|<(.*?)>|', function ($matches) use ($attribute) {
                $result = preg_replace("|$attribute=|i", '', $matches[1]);
                return "<" . $result . ">";
            }, $text);
        }

        return $text;
    }
}





function displayCommentAsAWebsite(InputFormat $format, string $text)
{

    echo $format->formatText($text);
}





$dangerousComment = <<<HERE
Hello! Nice blog post!
Please visit my <a href='http://www.iwillhackyou.com'>homepage</a>.
<script src="http://www.iwillhackyou.com/script.js">
  performXSSAttack();
</script>
HERE;


$naiveInput = new TextInput();
$filteredInput = new PlainTextFilter($naiveInput);
$filteredInputDangerous = new DangerousHTMLTagsFilter($filteredInput);
displayCommentAsAWebsite($filteredInputDangerous, $dangerousComment);
